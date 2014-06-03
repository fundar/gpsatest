<?php
if(class_exists('Gc_MessageBar_Instance_Option_Store')) {
    return;
}

class Gc_MessageBar_Instance_Option_Store implements Gc_MessageBar_Option_Store_interface{
    protected $option_nme = "instance_data";
    protected $option_store_name = "option_store";
    protected $data = array();
    public function __construct(){

    }

    public function save_option($option_name, $newValue,$deprecated=' ', $autoload='no') {
        if ( $this->get_option( $option_name ) != $newValue ) {
            $this->update_option( $option_name, $newValue );
        } else {
            $deprecated = ' ';
            $autoload = 'no';
            $this->add_option( $option_name, $newValue);
        }
    }
    public function commit(){

    }

    public function add_option($option_name,$optionValue, $deprecated=' ', $autoload='no'){
    }
    
    public function update_option($option_name,$optionValue){
    }

    public function get_all(){
        $store = Gc_MessageBar_Service_Locator::get($this->option_store_name);

        return $this->decode_data($store->get_option($this->option_nme));

    }

    public function save($options){
        $store = Gc_MessageBar_Service_Locator::get($this->option_store_name);
        $store->save_option($this->option_nme,$this->code_data($options));
    }

    protected function code_data($option){
        return serialize($options);
    }

    protected function decode_data($options){
        return unserialize($options);
    }

    public function get_option($option_name,$default = null,$instance = null){

        var_dump(func_get_args());
        $store = Gc_MessageBar_Service_Locator::get($this->option_store_name);
        
        var_dump($store);
        //die;
        $data = $this->decode_data($store->get_option($this->option_nme));
/*
        if(!function_exists("get_option")){
            return;
        }
        return esc_attr(get_option($option_name,$default));
        */
       return "aaaa";
    }

}