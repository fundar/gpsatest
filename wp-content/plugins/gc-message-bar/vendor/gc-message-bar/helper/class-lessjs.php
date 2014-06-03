<?php
if(!class_exists("Gc_MessageBar_Lessjs_Helper")){
    return;
}


class Gc_MessageBar_Lessjs_Helper{
    protected $less_file;
    protected $debug_mode = false;
    protected $plugin_name = "";
    protected $less_file_name = "";

    public function __construct(){


    }

    public function configure($data){
        if(isset($data['less_file'])){
            $this->less_file = $data['less_file'];
        }
        if(isset($data['plugin_name'])){
            $this->plugin_name = $data['plugin_name'];
        }
    }

    public function set_debug_mode($mode = true){
        $this->debug_mode = $mode;
    }

    public function initialize(){
        if($this->debug_mode){
            add_filter( 'style_loader_tag', array($this, 'filter_for_less') );
            add_action( 'admin_enqueue_scripts', array($this, 'debug_for_less'), 1100 );
            $this->less_file_name = $this->less_file.".less";
        } else{
            $this->less_file_name = $this->less_file.".css";    
        }
        
        add_action( 'admin_enqueue_scripts', array($this,'register_style_files'));
        //do_action( 'admin_enqueue_scripts', $less_file_name);
        // wp_register_style( 'less',  $less_file_name);
        // wp_enqueue_style ( 'less' );
        /* End of LESS item */
    }
    
    public function register_style_files() {
        wp_register_style( 'gc-less',  $this->less_file_name);
        wp_enqueue_style ( 'gc-less' );
    }

        /* LESS item */
    public function filter_for_less($tag){
        return str_replace("rel='stylesheet' id='gc-less-css'", "rel='stylesheet/less' id='gc-less-css'", $tag);
    }
    public function debug_for_less(){
        wp_enqueue_script( 'gc-less', plugins_url($this->plugin_name.'/js/less-1.5.min.js'));
        echo '<script type="text/javascript"> localStorage.clear(); less = { env: "development" }; </script>';
    }
    /* End of LESS item */


}
