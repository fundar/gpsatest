<?php
if(file_exists(ABSPATH.'wp-admin/includes/plugin.php')){
    require_once(ABSPATH.'wp-admin/includes/plugin.php');
}
if(class_exists("Gc_MessageBar_Util")){
    return;
}

class Gc_MessageBar_Util {

    public static $base_file = "";
    public static $name = "";
    public static $type = "";
    public static function initialize($base_file,$name,$type){
        self::$base_file = $base_file;
        self::$name = $name;
        self::$type = $type;
    }

    public static function plugin_options_url() {
        return admin_url( 'plugins.php?page='.self::$name );
    }
    
    public static function get_base_file(){
        return self::$base_file;
    }

    public static function get_type() {
        return self::$type;
    }

    public static function get_name() {
        return self::$name;
    }


    public static function get_version() {

        $plugin_folder = get_plugins( );
        $plugin_file = self::$type.DIRECTORY_SEPARATOR.wp_basename( ( self::$base_file ) );
        return $plugin_folder[$plugin_file]['Version'];
    }

    public static function get_plugin_file() {

        $plugin_folder = get_plugins( );
        $plugin_file = self::$type.DIRECTORY_SEPARATOR.wp_basename( ( self::$base_file ) );
        return $plugin_file;
    }

    public static function is_plugin_page(){
        $request = Gc_MessageBar_CF::create("Request");
        if(!$request->has_param('page')){
            return false;
        }
        if($request->get_param('page') == self::$name){
            return true;
        }
        return false;
    }
    
    public static function get_realpath(){
        $file = WP_CONTENT_DIR.DIRECTORY_SEPARATOR.'plugins'.DIRECTORY_SEPARATOR.self::get_plugin_file();
        return @realpath(dirname($file));
    }

    public static function get_url(){
        $file = plugins_url()."/".self::$type;
        return $file;
    }


}
