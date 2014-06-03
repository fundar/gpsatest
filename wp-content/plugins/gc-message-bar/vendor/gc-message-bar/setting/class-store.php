<?php
if(class_exists('Gc_MessageBar_Setting_Store')) {
    return;
}

class Gc_MessageBar_Setting_Store 
implements Gc_MessageBar_Option_Store_interface{
    protected $option_name = "instance_data";
    protected $option_store_name = "single_option_store";
    public function __construct(){

    }

    public function save_option($option_name, $option_value,$deprecated=' ', $autoload='no') {
        $store = $this->get_store();
        $store->save_option($option_name,$this->code_data($option_value));
    }

    public function add_option($option_name,$option_value, $deprecated=' ', $autoload='no'){
        return $this->save_option($option_name,$option_value);
    }
    
    public function update_option($option_name,$option_value){
        return $this->save_option($option_name,$option_value);
    }

    protected function code_data($options){
        return serialize($options);
    }

    protected function decode_data($options){
        return unserialize($options);
    }
    public function get_store(){
        $store = Gc_MessageBar_Service_Locator::get($this->option_store_name);
        if(is_null($store)){
            $store = Gc_MessageBar_CF::create('Option_Store');
        }
        return $store;        
    }
    public function get_option($option_name,$default = null){
        $store = $this->get_store();
        $data = $store->get_option($option_name);
        if(!$data){
            return $default;
        }
        if(is_string($data)){
            return $this->decode_data($data);
        }
        return $data;
    }
    public function commit(){

    }
}