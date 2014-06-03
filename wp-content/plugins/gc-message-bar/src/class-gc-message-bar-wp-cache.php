<?php
if(class_exists("Gc_Message_Bar_Wp_Cache")){
	return;
}
class Gc_Message_Bar_Wp_Cache{
    protected $connfiguration;
    public function __construct($namespace, $controller){
        $this->configuration = Gc_MessageBar_Service_Locator::get("config");
	}
    protected $w3_total_cache_key = "w3-total-cache/w3-total-cache.php";
	public function is_w3_total_cache(){
        return is_plugin_active($this->w3_total_cache_key);
    }
    public function w3_total_cache_flush(){
        if(false === $this->is_w3_total_cache()){
            return false;
        }
        if(function_exists("w3tc_pgcache_flush")){
            w3tc_pgcache_flush();            
        }
        if(function_exists("w3tc_dbcache_flush")){
            w3tc_dbcache_flush();
        }
        if(function_exists("w3tc_objectcache_flush")){
            w3tc_objectcache_flush();

        }
        if(function_exists("w3tc_minify_flush")){
            w3tc_minify_flush();
        }
        return true;
    }

    protected $wp_super_cache_key = "wp-super-cache/wp-cache.php";
    public function is_wp_super_cache(){
        return is_plugin_active($this->wp_super_cache_key);

    }
    public function wp_super_cache_flush(){

        if(false === $this->is_wp_super_cache()){
            return false;
        }

        if(function_exists("wp_cache_clear_cache")){
            wp_cache_clear_cache();            
        }
        return true;

    }
}
