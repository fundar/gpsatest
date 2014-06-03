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
        $worker->execute(Gc_MessageBar_CF::create("Request"));
    }
	
	
    public function handle_request(){

    }
    
    public function scripts_init(){
        global $GC_Message_Bar_Config;
		wp_enqueue_script( 'jquery' );
		wp_register_style( 'generated', plugins_url('gc-message-bar/style-gc-message-bar.php'), array(), false, "screen" );
		wp_enqueue_style( 'generated' );
		if ($this->options->get("enable_animation")->get_value() == "1") {
    		wp_enqueue_script( 'utils' );
            wp_enqueue_script("jquery-effects-core");
        }
        $httpPrefix = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
        
        wp_register_style( 'google_webfonts', $httpPrefix.'fonts.googleapis.com/css?family=Droid+Sans:400,700|Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic|PT+Sans:400,700,400italic,700italic|Bitter:400,700,400italic|Droid+Serif:400,700,700italic,400italic|Open+Sans:300italic,400italic,600italic,700italic,800italic,400,800,700,600,300|Oswald:400,700,300|Open+Sans+Condensed:300,300italic,700|Yanone+Kaffeesatz:400,700,300,200|Roboto:400,900italic,700italic,900,700,500italic,500,400italic,300italic,300,100italic,100&subset=latin,latin-ext,cyrillic,cyrillic-ext,greek-ext,greek,vietnamese' );
        wp_enqueue_style('google_webfonts');

        Gc_Message_Bar_Admin_Bar::script_init();
        if($this->metrix_enable){
            wp_enqueue_script( 'metrix', $httpPrefix.$GC_Message_Bar_Config['METRIX_JS_URL']);
        }

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
		if ($this->options->get("enable_animation")->get_value() == "1") {
			$this->renderer->render("");
		} else {
			$this->renderer->render_no_anim("");
		}
        $this->render_metrix_tracker();
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
        $url_pieces = parse_url($url );
        $url2 = '';
        if(isset($url_pieces['host'])){
            if(substr($url_pieces['host'], 0,4) == 'www.'){
                $url_pieces['host'] = substr($url_pieces['host'], 5);
            } else{
                $url_pieces['host'] = 'www.'.$url_pieces['host'];
            }
            $url2 = $this->build_url($url_pieces);
        }
        if(in_array($url, $pages)){
            return true;
        }
        return in_array($url, $pages);
    }
    protected function build_url($url_pieces){
        $url2 = '';
        if(isset($url_pieces['scheme'])){
            $url2 .= $url_pieces['scheme'];
        }else{
            $url2 .= 'http';
        }
        $url2 .= "://";
        if(isset($url_pieces['host'])){
            $url2 .= $url_pieces['host'];
        }else{
            $url2 .= '';
        }
        if(isset($url_pieces['path'])){
            $url2 .= $url_pieces['path'];
        }else{
            $url2 .= '';
        }
        if(isset($url_pieces['query'])){
            $url2 .= '?'.$url_pieces['query'];
        }else{
            $url2 .= '';
        }
        if(isset($url_pieces['fragment'])){
            $url2 .= $url_pieces['fragment'];
        }else{
            $url2 .= '';
        }
        return $url2;
    }
    public function is_bar_enabled() {
        return ($this->options->get('bar_enable')->get_value() == "1");
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
