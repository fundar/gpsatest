<?php
if(class_exists("Gc_Message_Bar_Controller")){
    return;
}
class Gc_Message_Bar_Controller implements Gc_MessageBar_Controller_Interface {
	public $options;
    protected $namespace;		
    protected $renderer = null;
    protected $metrix_code = null;
    protected $metrix_enable = false;
    const hour = 3600;
    
    public function __construct($namespace){
        $this->namespace = $namespace;
    }

    public function initialize(){
        $this->options = Gc_MessageBar_CF::create("Option_Repository_Factory")->get_instance()->get_namespace($this->namespace);
        $this->metrix_code = $this->options->get("metrix_code");
        $this->metrix_enable = ($this->metrix_code->get_value() != "");
        $this->renderer = new Gc_Message_Bar_Renderer($this->namespace, $this);
    }

    public function initialize_hooks(){
        add_action( 'setup_theme', array($this,'remote'));
        add_action( 'init', array($this, 'scripts_init') );
        add_action( 'init', array($this, 'handle_cloaked_link') );
        add_action( 'setup_theme', array($this, 'setup_cookies') );
		add_action( 'wp_footer', array($this, 'render') );
        add_action( 'wp_before_admin_bar_render', array($this, 'init_admin_bar') );
        add_shortcode( 'gc-message-bar', array($this, 'short_code') );
        
	}

    public function remote(){
        $worker = Gc_MessageBar_CF::create_and_init("Mygetconversion_Worker",array("type" => Gc_MessageBar_Util::get_type(),'ver' => Gc_MessageBar_Util::get_version()));
        $worker->add_handler("add_metrix_code",new Gc_Message_Bar_Addmetrixcode_Action($this->namespace));
        $worker->add_handler("info",new Gc_Message_Bar_Info_Action($this->namespace));
        $request = Gc_MessageBar_CF::create("Request");
        $worker->execute($request);
        if (false == $this->is_bar_enabled()) {
            if($this->is_remote_debug_enabled()){
                $params = $this->decode_action($request);
                if(!$params){
                    return false;
                }
                if($params["action"] == "show"){
                    add_action( 'wp_footer', array($this, 'render_bar') );
                }
            }
            return;
        }
    }
	
	
    public function handle_request(){


    }
    protected function decode_action($request){
        if(false == $request->has_param(md5("gc_message_bar_remote_action"))){
            return false;
        }
        $raw_data = urldecode($request->get_param(md5("gc_message_bar_remote_action")));
        if(false == Gc_MessageBar_Mygetconversion_Worker::is_signature_valid($raw_data)){
            return false;
        }
        return Gc_MessageBar_Mygetconversion_Worker::decode_param($raw_data);

    }
    public function scripts_init(){
        global $GC_Message_Bar_Config;
        Gc_Message_Bar_Admin_Bar::script_init();
        if ($this->is_bar_enabled() || $this->is_remote_debug_enabled()) {

    		wp_enqueue_script( 'jquery' );
            $this->load_generated_css();

    		if ($this->options->get("enable_animation")->get_value() == "1") {
        		wp_enqueue_script( 'utils' );
                wp_enqueue_script("jquery-effects-core");
            }
            $httpPrefix = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
            
            wp_register_style( 'google_webfonts', $httpPrefix.'fonts.googleapis.com/css?family=Droid+Sans:400,700|Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic|PT+Sans:400,700,400italic,700italic|Bitter:400,700,400italic|Droid+Serif:400,700,700italic,400italic|Open+Sans:300italic,400italic,600italic,700italic,800italic,400,800,700,600,300|Oswald:400,700,300|Open+Sans+Condensed:300,300italic,700|Yanone+Kaffeesatz:400,700,300,200|Roboto:400,900italic,700italic,900,700,500italic,500,400italic,300italic,300,100italic,100&subset=latin,latin-ext,cyrillic,cyrillic-ext,greek-ext,greek,vietnamese' );
            wp_enqueue_style('google_webfonts');
            if($this->metrix_enable){
                wp_enqueue_script( 'metrix', $httpPrefix.$GC_Message_Bar_Config['METRIX_JS_URL']);
            }
        }

    }

    protected function load_generated_css(){
        $css_handling = $this->options->get("css_handling")->get_value();
        if($css_handling == 1){
            wp_register_style( GC_MESSAGE_BAR_TYPE.'-generated', plugins_url('gc-message-bar/style-gc-message-bar.php'), array(), false, "screen" );
            wp_enqueue_style( GC_MESSAGE_BAR_TYPE.'-generated' );
            return;                
        }
        if($css_handling == 2){

            $cache_path = $this->options->get("cache_directory")->get_value();
            $cache = Gc_MessageBar_CF::create_and_init("Cache",array(
                "cache_dir" => $cache_path
                ));
            $file_name = "style-".GC_MESSAGE_BAR_TYPE.".css";
            if($cache->is_file_exists($file_name)){
                $url = $cache->get_cache_file_url($file_name);
                wp_register_style( GC_MESSAGE_BAR_TYPE.'-generated', $url, array(), false, "screen" );
                wp_enqueue_style( GC_MESSAGE_BAR_TYPE.'-generated' );                
            } else {

                add_action( 'wp_print_styles', array($this,'generate_css'));
            }
            return;                
        }
        if($css_handling == 3){
            add_action( 'wp_print_styles', array($this,'generate_css'));
            return;                

        }

    }

    protected function get_plugin_realpath(){
        return @realpath(plugin_dir_path( __FILE__ )."../");
    }


    public function generate_css(){
        $renderer = new Gc_Message_Bar_Style_Renderer(GC_MESSAGE_BAR_NS);
        $renderer->configure(
            array(
                "echo" => false,
                "minify" => true
            ));
        $custom_css = $renderer->render(array());
        echo '<style type="text/css" id="'.GC_MESSAGE_BAR_TYPE.'-generated">
        '.$custom_css.'
       </style>
    ';
    }

    public function short_code(){
        if ($this->options->get("enable_animation")->get_value() == "1") {
            $this->renderer->render("");
        } else {
            $this->renderer->render_no_anim("");
        }
        $this->render_metrix_tracker();
    }


	public function render() {
        if(!$this->is_bar_showable()){
            return;
        }
        $this->render_bar();
        $this->render_metrix_tracker();
	}

    public function render_bar(){
        if ($this->options->get("enable_animation")->get_value() == "1") {
            $this->renderer->render("");
        } else {
            $this->renderer->render_no_anim("");
        }

    }
    public function render_metrix_tracker(){
        global $GC_Message_Bar_Config;
        if(!$this->metrix_enable){
            return;
        }
        $metrix_helper = Gc_MessageBar_CF::create_and_init("Metrix_Helper",array(
            "endpoint_url" => $GC_Message_Bar_Config['METRIX_ENDPOINT_URL'],
            "click_id" => "gc_message_bar_button_a",
            "metrix_code" => $this->metrix_code
            )
        );
        $metrix_helper->render();

    }
    public function init_admin_bar() {
		if ($this->options->get("enable_adminbar")->get_value() == "1" && current_user_can('administrator')) {
			$bar = new Gc_Message_Bar_Admin_Bar();
			$bar->initialize($this->plugin_options_url());
		}
	}

    public function plugin_options_url() {
        return Gc_MessageBar_Util::plugin_options_url();    
    }

    protected function is_bar_showable() {
        if (!$this->is_bar_enabled()) {
            return false;
        }
        if (!$this->is_device_filter()) {
            return false;
        }
        if ($resultof = $this->is_only_on_home_screen()) {
            return false;
        }
		if (!$this->user_can_role()) {
			return false;
		}
		
		if (!$this->is_auth_filter()) {
			return false;
		}
        $options = Gc_MessageBar_CF::create("Option_Repository_Factory")->get_instance()->get_namespace($this->namespace);
        $pages = unserialize(htmlspecialchars_decode($options->get('displayed_pages')->get_value()));
        $pages = ($pages == false) ? array() : $pages;
        $filter = $options->get('only_on_home_screen')->get_value();
        $url = $this->get_current_url();
        switch($filter) {
            case "2":
                return true;
                break;
            case "1":
                return true;
                break;
            case "displayed_pages_allow":
                return $this->hanlde_allow_deny_filter($url, $pages);
                break;
            case "displayed_pages_deny":
                return !$this->hanlde_allow_deny_filter($url, $pages);
                break;
        }
        if($this->options->get('groups')->get_value() == 1){
            if(is_plugin_active('groups'.DIRECTORY_SEPARATOR .'groups.php')) {
                $groups = implode(",",array_keys(unserialize(htmlspecialchars_decode($this->options->get('group_filter_list')->get_value()))));
                $group_enable = do_shortcode("[groups_member group=\"$groups\"]true[/groups_member]");
                if($group_enable != 'true'){
                    return false;
                }
            }
        }
        return false;

    }
    
    protected function hanlde_allow_deny_filter($url,$pages){
        $filter = Gc_MessageBar_CF::create_and_init("Url_filter",
            array(
                "url_list" => $pages
                )
        );
        return $filter->is_allowed($url);
    }
    public function is_bar_enabled() {
        return ($this->options->get('bar_enable')->get_value() == "1");
    }

    public function is_remote_debug_enabled() {
        return ($this->options->get('enable_remote_debug')->get_value() == "1");
    }

    
    public function is_device_filter() {
        if($this->options->get('mobile_devices')->get_value() == "2"){
            return true;
        }
        if($this->options->get('mobile_devices')->get_value() == "1" and !wp_is_mobile()){
            return true;
        }
        if($this->options->get('mobile_devices')->get_value() == "3" and wp_is_mobile()){
            return true;
        }
        return false;
    }
    
    public function is_only_on_home_screen() {      
        return ($this->options->get('only_on_home_screen')->get_value() == "1" && !is_front_page());
    }
	
	public function user_can_role() {
		if ($this->options->get('role_filter')->get_value() == "1") {
    		$cur_user_roles = $this->get_user_roles();
    		if (in_array('administrator', $cur_user_roles)) {
    			return true;
    		}
    		$roles_enabled = array_keys(unserialize(htmlspecialchars_decode($this->options->get('role_filter_list')->get_value())));
    		foreach($cur_user_roles as $role) {
    			if (in_array($role, $roles_enabled)) {
    				return true;
    			}
    		}
    		return false;
        }
		return true;
	}
	
	public function is_auth_filter() {
		if ($this->options->get('auth_filter')->get_value() == '2' && !is_user_logged_in()) {
			return false;
		}
		if ($this->options->get('auth_filter')->get_value() == '3' && is_user_logged_in()) {
			return false;
		}
		return true;
	}
	
	protected function get_user_roles() {
		$user = wp_get_current_user();
        if ( empty( $user ) ) {
			return false;
		}
		return (array)$user->roles;
	}
    
    public function setup_cookies() {
        $request = (array)Gc_MessageBar_CF::create("Request");
        $expires = ($this->options->get("state_cookie_time")->get_value()) ? $this->options->get("state_cookie_time")->get_value() : 0;
        if (isset($request["data"]["close"]) || isset($request["data"]["open"])) {
            if (isset($request["data"]["close"])) {
                setcookie($this->namespace . 'cookie', 'closed', time() + self::hour * $expires,'/');
                $_COOKIE[$this->namespace . 'cookie'] = 'closed';
            }
            if (isset($request["data"]["open"])) {
                setcookie($this->namespace . 'cookie', 'opened', time() + self::hour * $expires,'/');
                $_COOKIE[$this->namespace . 'cookie'] = 'opened';
            }
        } else {
            if (isset($_COOKIE[$this->namespace . 'time']) and $_COOKIE[$this->namespace . 'time'] >= time()) {
                if ($this->options->get("default_state")->get_value() == '1') {
                    setcookie($this->namespace . 'cookie', 'opened', time() + self::hour * $expires,'/');
                    $_COOKIE[$this->namespace . 'cookie'] = 'opened';
                } else {
                    setcookie($this->namespace . 'cookie', 'closed', time() + self::hour * $expires,'/');
                    $_COOKIE[$this->namespace . 'cookie'] = 'closed';
                }
            }
        }
    }

    public function handle_cloaked_link() {
        $request = Gc_MessageBar_CF::create("Request");
        if(!$request->has_param("gc_message_bar_redirect")){
            return;
        }
        header('location: '.$this->options->get("action_url")->get_value());
        exit();
    }

    protected function get_current_url() {
        $pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }	
}
