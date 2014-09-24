<?php
if(class_exists('Gc_MessageBar_Cache')) {
    return;
} 
class Gc_MessageBar_Cache 
    implements 
    Gc_MessageBar_Configurable_Interface
{
    protected $cache_dir = "";
    public function configure(array $options){
        $this->configure_field($options,"cache_dir");
    }

    protected function configure_field(array $options,$field_name){
        if(isset($options[$field_name])){
            $this->$field_name = $options[$field_name];
        }
    }

    public function is_file_exists($file_name){
        $file = $this->get_cache_file_path($file_name);
        return @file_exists($file);
    }



    public function write_file($file_name,$content){
        $file = $this->get_cache_file_path($file_name);
        if(false == @file_put_contents($file, $content)){
            return false;
        }
        return true;

    }

    public function read_file($file_name){
        $file = $this->get_cache_file_path($file_name);
        return @file_get_contents($file);

    }

    public function get_cache_file_path($file_name){
        if(empty($this->cache_dir)){
            $file = Gc_MessageBar_Util::get_realpath().DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR.$file_name;
        } else{
            $file = ABSPATH.trim($this->cache_dir,DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$file_name;

        }
        return $file;

    }
    public function get_cache_file_url($file_name){
        if(empty($this->cache_dir)){
            $url = Gc_MessageBar_Util::get_url()."/"."cache"."/".$file_name;
        } else{
            $url = site_url()."/".trim($this->cache_dir,DIRECTORY_SEPARATOR)."/".$file_name;

        }
        return $url;
    }

}