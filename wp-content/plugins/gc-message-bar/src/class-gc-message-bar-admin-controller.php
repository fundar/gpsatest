<?php
if(class_exists("Gc_Message_Bar_Admin_Controller")){
    return;
}
class Gc_Message_Bar_Admin_Controller implements Gc_MessageBar_Controller_Interface {
    protected $namespace;
    protected $options;
    protected $this_plugin = 'gc-message-bar/main.php';
    protected $configuration = array();
    protected $event_manager = null;

    public function __construct($namespace){
        $this->namespace = $namespace;
        $this->configuration = Gc_MessageBar_Service_Locator::get(GC_MESSAGE_BAR_SL_CONFIG);
        $this->event_manager = Gc_MessageBar_Service_Locator::get(GC_MESSAGE_BAR_SL_EVENT_MANAGER);

    }
    public function initialize(){
        $this->event_manager->listen(GC_MESSAGE_BAR_NAME.".admin_init",array($this,"on_admin_init"));
        $this->event_manager->listen(GC_MESSAGE_BAR_NAME.".handle_request",array($this,"on_handle_request"));
        $this->event_manager->listen(GC_MESSAGE_BAR_NAME.".handle_request:after",array($this,"on_post_handle_request"));
        $this->event_manager->listen(GC_MESSAGE_BAR_NAME.".handle_service_request:after",array($this,"on_post_handle_request"));
        $this->options = Gc_MessageBar_CF::create("Option_Repository_Factory")->get_instance()->get_namespace($this->namespace);
    }
    
    public function initialize_hooks(){
        register_activation_hook( Gc_MessageBar_Util::get_base_file(), array($this,'activate_plugin') );

        if(Gc_MessageBar_Util::is_plugin_page()){
            add_action( 'init', array($this, 'scripts_init') );
            $this->initialize_less_js();
            $metrix_code = $this->options->get("metrix_code");
            $metrix_enable = ($metrix_code->get_value() != "");
            if(!$metrix_enable){
                add_action( 'admin_notices', array($this, 'mygc_url_admin_notice') );             
            }

        } 
        else {
            add_action( 'init', array($this, 'otherscreens_scripts_init') );
        }


        add_action( 'wp_ajax_gc-message-bar-group', array($this,'group') );
        add_action( 'init', array($this, 'adminbar_init') );
	    add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'add_sub_menu') );
        add_action( 'wp_ajax_nopriv_gc-message-bar', array($this,'info'));  
        add_action( 'wp_ajax_nopriv_gc-message-bar-remote', array($this,'remote') );
        add_action( 'wp_ajax_gc-message-bar-remote', array($this,'remote') );
        add_action( 'wp_ajax_nopriv_gc-message-bar', array($this,'info') );
        add_action( 'wp_ajax_gc-message-bar-mygc-signin', array($this,'mygc_signin') );
		
        $this->initialize_getconversion_remote();            

		
		
        add_filter( 'plugin_action_links', array($this, 'add_action_link'), 10, 2 );
		
		add_action( 'wp_before_admin_bar_render', array($this, 'init_admin_bar') );

        add_action( 'wp_ajax_gc-message-bar-add-page', array($this, 'add_page') );
        add_action( 'wp_ajax_gc-message-bar-remove-page', array($this, 'remove_page') );
        add_action( 'add_meta_boxes', array($this, 'spec_pages' ));

	}

    public function activate_plugin(){
        $remote = $this->options->get("enable_remote_configuration");
        $remote->set_value(1);
        $remote->save();
        $trigger = $this->options->get("trigger");
        $value = $trigger->get_value();
        $value = str_replace("_row","",$value);
        $trigger->set_value($value);
        $trigger->save();

    }

    public function spec_pages() {
        $filter = $this->options->get("only_on_home_screen")->get_value();
        if ($filter == "1" or $filter == "2") {
            return;
        }
        $screens = array( 'post', 'page' );
        foreach ($screens as $screen) {
            add_meta_box(
                'gc_message_bar_allow_or_deny',
                __( 'GC Message Bar Allow/Deny' ),
                array($this,'pageswitcher'),
                $screen
            );
        }
    }
    
    public function pageswitcher() {
        wp_enqueue_script( 'gc_message_bar_admin', plugins_url('gc-message-bar/js/gc-admin.js') );
        wp_localize_script( 'gc_message_bar_admin', 'WP', array(
            'base_url' => admin_url("admin-ajax.php"),
            'group_ajax_url' => 'gc-message-bar-group',
            'disconnect_action' => 'gc-message-bar'
        ) );
        $filter = $this->options->get("only_on_home_screen")->get_value();
        $pages = unserialize(htmlspecialchars_decode($this->options->get("displayed_pages")->get_value()));
       
        $post = get_post();
        list($permalink, $post_name) = get_sample_permalink($post->ID);
        $permalink = str_replace("%postname%",$post_name,$permalink);
        $permalink = str_replace("%pagename%",$post_name,$permalink);
        
        if ($filter == "displayed_pages_allow") {
            if (is_array($pages) and in_array($permalink, $pages)) {
                $state = "on";
                $val = "ON";
                $id = "1";
            } else {
                $state = "off";
                $val = "OFF";
                $id = "2";
            }
        } else if ($filter == "displayed_pages_deny") {
            if (is_array($pages) and in_array($permalink, $pages)) {
                $state = "off";
                $val = "OFF";
                $id = "2";
            } else {
                $state = "on";
                $val = "ON";
                $id = "1";
            }
        }
        $renderer = new Gc_Message_Bar_Admin_Renderer($this->namespace,$this,$this->options);
        $renderer->render_pageswitcher($state, $val, $id, $permalink, $filter);

    }
    
    public function add_page() {
        $request = Gc_MessageBar_CF::create("Request");
        $data = $request->get_param('data');
        $type = $request->get_param('filtertype',"");

        if (preg_match("%\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))%s", $data)) {
            $opt = $this->options->get("displayed_pages");
            $pages = unserialize(htmlspecialchars_decode($opt->get_value()));
            if(!is_array($pages)){
                $pages = array();
            }
            
            if (in_array($data, $pages)) {
                die(json_encode(array("success" => false, "reason" => 'This URL is already exists.')));
            }
            if($type == "beginswith"){
                $data .= "*";
            }
            $pages[] = $data;

            $opt->set_value(serialize($pages));
            $opt->save();
            $last_element = end($pages); //pointer set to end
            $last_key = key($pages);     //get the key, where the pointer is
            $this->clear_cache_plugins();
            die(json_encode(array("success" => true, "result" => $this->create_specified_pages_element($last_key, $last_element), "serialized" => htmlspecialchars(serialize($pages)))));
        } else {
            die(json_encode(array("success" => false, "reason" => 'You can only add URLs.')));
        }
    }
    
    public function remove_page() {
        $request = Gc_MessageBar_CF::create("Request");
        $data = $request->get_param('data');
        $opt = $this->options->get("displayed_pages");
        $pages = unserialize(htmlspecialchars_decode($opt->get_value()));
        $key = array_search($data, $pages);
        unset($pages[$key]);
        $opt->set_value(serialize($pages));
        $opt->save();
        $this->clear_cache_plugins();
        
        die(json_encode(array("success" => true, "serialized" => htmlspecialchars(serialize($pages)))));
    }
    
    protected function create_specified_pages_element($key, $element) {
        $val = $element;
        $url = $val;
        if(substr($val,-1) == "*"){
            $label = "Begins with";
            
            $val = substr($val,0,-1);

        } else{
            $label = "Equals to";
        }
        $element = $val;        
        if (strlen($val)>70) {
            $element = "...";
            $element .= substr($val, strlen($val)-70); 
        }
        return $retstr = "<div class=\"pageitem\" id=\"gc-message-bar-specified-page-".$key."\">\n<div class=\"specified-close-page\"></div>\n<input type=\"hidden\" id=\"gc-message-bar-specified-page-key-$key\" value=\"$url\"/>\n<div class=\"specified-label\">".$label."</div><div class=\"filter-specified-page\">".$element."</div>\n<div class=\"clear\"></div>\n</div>";
    }

    public function adminbar_init(){
        Gc_Message_Bar_Admin_Bar::script_init();
    }

    protected function initialize_getconversion_remote(){
		add_action( 'wp_ajax_gc-message-bar-activate', array($this,'activate') );
		add_action( 'wp_ajax_gc-message-bar-deactivate', array($this,'deactivate') );

    }

    public function activate(){
        $helper = Gc_MessageBar_CF::create_and_init("Mygetconversion_Helper",array(
            "domain_url" => $this->get_domain_url(),
            "api_url" => $this->configuration['GCAPI'],
            "request" => Gc_MessageBar_CF::create("Request")
        ));
        $helper->handle_activate();
    }

    public function deactivate(){

        $helper = Gc_MessageBar_CF::create_and_init("Mygetconversion_Helper",array(
            "domain_url" => $this->get_domain_url(),
            "api_url" => $this->configuration['GCAPI'],
            "request" => Gc_MessageBar_CF::create("Request")
        ));
        $helper->handle_deactivate();
    }


	
	protected function initialize_less_js(){

		$less_helper = Gc_MessageBar_CF::create_and_init("Lessjs_Helper",array(
			"less_file" => plugins_url('gc-message-bar/css/style-gc-message-bar-admin'),
			"plugin_name" => "gc-message-bar"
			) );

		if($this->configuration['GCTYPE'] == "DEV"){
			$less_helper->set_debug_mode();
		}

		$less_helper->initialize();
		

	}

    public function info(){
        die('{"ver":"'.Gc_MessageBar_Util::get_version().'"}');  
	}
	
	public function remote(){
		$remote_handler = new Gc_Message_Bar_Remote_Handler($this->namespace);
		$remote_handler->execute(Gc_MessageBar_CF::create("Request"));
		exit();
	}
	
    public function mygc_signin(){
        $renderer = new Gc_Message_Bar_MyGC_Signin_Renderer($this->namespace,$this);

        $request = Gc_MessageBar_CF::create("Request");
        if(!$request->has_param('email') or !$request->has_param('passw')){
            $renderer->render(array('section'=>'signin'));
            die();    
        }
        $email = $request->get_param('email');
        $password = $request->get_param('passw');
        if(empty($email) or empty($password)){
            $renderer->render(array('section'=>'signin','failed'=>true,'error_type'=>'EMPTY_FORM'));
            die();          
        }
           
        $api = Gc_MessageBar_CF::create_and_init("Mygetconversion_API",
            array(
                "api_url" => $this->configuration['GCAPI'],
                "api_key" => $this->configuration['MYGC_APIKEY'],
                "signing" => $this->configuration['SIGNING']
            )
        );
        $result = $api->activate($email,$password,get_bloginfo('url'),Gc_MessageBar_Util::get_type());
        if($result['type'] === 'success'){
            $option_metrix_code = $this->options->get('metrix_code');
            $option_metrix_code->set_value($result['data']);
            $option_metrix_code->save();
            $renderer->render(array('section'=>'success','metrix_code'=>$result['data'],'redirect_to'=>Gc_MessageBar_Util::get_name()));
        }else{
            $renderer->render(array('section'=>'signin','failed'=>true,'error_type'=>$result['data']));    
        }
        die();
    }
	
	private function get_domain_url() {
		$page_url = 'http';
		if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on") {$page_url .= "s";}
			$page_url .= "://";
		if (isset($_SERVER["SERVER_PORT"]) and $_SERVER["SERVER_PORT"] != "80") {
			$page_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
		} else {
			$page_url .= $_SERVER["SERVER_NAME"];
		}
		return $page_url;
	}
	
	public function group(){
		$request = Gc_MessageBar_CF::create("Request");
		if(!$request->has_param('id')){
			return;
		}
		if(!$request->has_param('group')){
			return;
		}
		$option = Gc_MessageBar_CF::create_and_init("Option",array("namespace" => $this->namespace,"id" => $request->get_param('id')."_group"));
		$option->set_value($request->get_param('group'));
		$option->save();
        $this->event_manager->dispatch(GC_MESSAGE_BAR_NAME.".change_group_state",new Gc_MessageBar_Event(array("request" => $request)));
		return;
	}

    public function admin_init() {
        $this->event_manager->dispatch(GC_MESSAGE_BAR_NAME.".admin_init",new Gc_MessageBar_Event());
        add_settings_section( 'style_settings', 'Style Settings', array($this, 'style_sub_message'), 'style_options');
        add_settings_section( 'general_settings', 'General Settings', array($this, 'general_sub_message'), 'general_options');
    }

    public function on_admin_init($event){
    }

    public function add_sub_menu() {
        add_submenu_page( 'plugins.php', 'GC Message Bar Options', 'GC Message Bar','manage_options', 'gc_message_bar', array($this, 'render'));
    }

    public function render() {
        $this->init_values();
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        $renderer = new Gc_Message_Bar_Admin_Renderer($this->namespace,$this,$this->options);
		$renderer->render("");
    }

    public function plugin_options_url() {
            return Gc_MessageBar_Util::plugin_options_url();    
    }


    public function add_action_link($links, $file) {
        if ( $file == $this->this_plugin ) {
            $settings_link = '<a href="' . $this->plugin_options_url() . '">' . __( 'Settings' ) . '</a>';
            array_unshift( $links, $settings_link );
        }
        return $links;
    }
	
	public function init_values() {
        $installedOption = $this->options->get("installed");
        if ($installedOption->get_value() == "true") {
            return;
        }
        $options = $this->get_options();
        foreach ($options as $key => $opt) {
            $opt->save();
        }
        $installedOption->set_value("true");
        $installedOption->save();       
    }

    public function get_options(){
        return $this->options->get_options();

    }    

    protected function clear_cache_plugins(){
        $cache = Gc_MessageBar_CF::create_and_init("Wp_Cache",
            array(
                "config" => Gc_MessageBar_Service_Locator::get("config")
            )
        );
        $cache->w3_total_cache_flush();
        $cache->wp_super_cache_flush();

    }



    public function style_sub_message() {
        __( 'Options for styling the GC Message Bar' );
    }
    
    public function general_sub_message() {
        __( 'General options for the GC Message Bar' );
    }
	
	public function scripts_init(){
        $this->init_fonts();

        wp_enqueue_script( 'jquery' );

        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );


        wp_enqueue_script( 'gc_message_bar_admin', plugins_url('gc-message-bar/js/gc-admin.js') );
        wp_localize_script( 'gc_message_bar_admin', 'WP', array(
            'base_url' => admin_url("admin-ajax.php"),
            'group_ajax_url' => 'gc-message-bar-group',
            'disconnect_action' => 'gc-message-bar'
        ) );

        wp_register_script( 'jquerypopbox', plugins_url('gc-message-bar/js/jquery.popBox.js') );
        wp_enqueue_script( 'jquerypopbox' );

        wp_register_style( 'jquerypopboxstyle', plugins_url('gc-message-bar/css/popBox.css') );
        wp_enqueue_style( 'jquerypopboxstyle' );

        $this->init_fonts_selector_scripts();
	}

    protected function init_fonts_selector_scripts(){
        wp_enqueue_script( 'jqueryselectbox',plugins_url('gc-message-bar/js/jquery.selectbox.js' ));
        wp_enqueue_style( 'jqueryselectbox-css', plugins_url('gc-message-bar/css/jquery.selectbox.css') );

    }

    public function  otherscreens_scripts_init() {
        $this->init_fonts();
        wp_enqueue_script( 'jquery' );
        wp_register_style( 'gc_message_bar_admin_other',  plugins_url('gc-message-bar/css/style-gc-message-bar-admin-other.css') );
        wp_enqueue_style( 'gc_message_bar_admin_other' );
    }


    protected function init_fonts(){
        $urlPrefix = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
        
        wp_register_style( 'gc_font_roboto', $urlPrefix.'fonts.googleapis.com/css?family=Roboto:400,700,300');
        wp_register_style( 'gc_font_roboto_condensed', $urlPrefix.'fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300');
        wp_enqueue_style( 'gc_font_roboto' );
        wp_enqueue_style( 'gc_font_roboto_condensed' );
        
    }

	public function handle_request(){
        $request = Gc_MessageBar_CF::create("Request");

        if (!$request->has_param($this->namespace.'submit') and !$request->has_param($this->namespace.'engine') or !$request->has_param('post')) {
            return;
        }
        
        if($this->handle_service_request($request)){
            return;
        }

        if(!$request->has_param('post')){
            return;
        }
        $data = $request->get_param('post');
        if(false == is_array($data)){
            $data = array();
        }

		if (isset($data[$this->namespace . 'role_filter_list'])) {
			$data[$this->namespace . 'role_filter_list'] = htmlspecialchars(@serialize($data[$this->namespace . 'role_filter_list']));
		} else {
			$data[$this->namespace . 'role_filter_list'] = serialize(array());
		}
		if (isset($data[$this->namespace . 'group_filter_list'])) {
			$data[$this->namespace . 'group_filter_list'] = htmlspecialchars(@serialize($data[$this->namespace . 'group_filter_list']));
		} else {
			$data[$this->namespace . 'group_filter_list'] = serialize(array());
		}
        $event = new Gc_MessageBar_Event(array("data" => $data,"namespace" => $this->namespace));
        $this->event_manager->dispatch(GC_MESSAGE_BAR_NAME.".handle_request",$event,true);



	}

    public function on_handle_request($event){
        $data = $event->get_param("data",array());
        $options = $this->get_options();

        foreach($options as $option) {
            if(!$option->is_visible()){
                continue;
            }
            if (isset($data[$option->get_name()])) {
                $option->set_value($data[$option->get_name()]);
            } elseif($option->is_checkbox()) {
                $option->set_checked(false);
            }else{
                continue;
            }
            $option->save();
        }
    }
    public function on_post_handle_request($event){

        $this->generate_css($event);
        $this->clear_cache_plugins();
        $event->set_handled();

    }

    public function generate_css($event){
        $data = $event->get_param("data",array());
        $css_handling = $this->options->get("css_handling")->get_value();
        if($css_handling != 2){
            return;
        }
        $config = Gc_MessageBar_Service_Locator::get(GC_MESSAGE_BAR_SL_CONFIG);
        $dev_mode = false;
        if(isset($config["GCTYPE"]) and strtolower($config["GCTYPE"]) == "dev"){
            $dev_mode = true;
        }

        $renderer = new Gc_Message_Bar_Style_Renderer(GC_MESSAGE_BAR_NS);
        $renderer->configure(
            array(
                "echo" => false,
                "minify" => false,
                "dev_mode" => $dev_mode
            ));
        $custom_css = $renderer->render(array());
        $this->write_css_to_cache($custom_css);

    }


    public function css_cache_generation_error_admin_notices(){
        echo "<div class='updated'><p>Error writing css cache</p></div>";
    }

    public function write_css_to_cache($content){
        $cache_path = $this->options->get("cache_directory")->get_value();
        $cache = Gc_MessageBar_CF::create_and_init("Cache",array(
            "cache_dir" => $cache_path
            ));
        if(false == $cache->write_file("style-".GC_MESSAGE_BAR_TYPE.".css",$content)){
            //Error writing css file
            add_action('admin_notices', array($this,'css_cache_generation_error_admin_notices'));    
            return;
        }

    }

    protected function handle_service_request($request){
        if (!$request->has_param($this->namespace.'engine')) {
            return false;
        }

        if(!$request->has_param('post')){
            return false;
        }
        $options =  $this->options->filter_options_by_group("internal_engine");

        $data = $request->get_param('post');
        foreach($options as $option) {
            if(!$option->is_visible()){
                continue;
            }
            if (isset($data[$option->get_name()])) {
                $option->set_value($data[$option->get_name()]);
            } elseif($option->is_checkbox()) {
                $option->set_checked(false);
            }else{
                continue;
            }
            $option->save();
        }
        $event = new Gc_MessageBar_Event(array("data" => $data,"namespace" => $this->namespace));
        $this->event_manager->dispatch(GC_MESSAGE_BAR_NAME.".handle_service_request",$event,true);

        return true;
    }

	public function init_admin_bar() {
		if ($this->options->get("enable_adminbar")->get_value() == "1" && current_user_can('administrator') && is_admin()) {
			$bar = new Gc_Message_Bar_Admin_Bar();
			$bar->initialize($this->plugin_options_url());
		} 
	}


    public function mygc_url_admin_notice() {
        $metrix_code = $this->options->get('metrix_code')->get_value();

        if(  !empty($metrix_code) ){
            return;
        }
        $connection_url = trim(site_url());
        echo '<div class="update-nag gcurl-nag">';
        printf(__('Your site URL for MY.GetConversion is'));
        echo ': <b>'.$connection_url.'</b>';
        echo "</div>";
    }
	
	function get_editable_roles() {
		global $wp_roles;

		$all_roles = $wp_roles->roles;
		$editable_roles = apply_filters('editable_roles', $all_roles);

		return $editable_roles;
	}

}
