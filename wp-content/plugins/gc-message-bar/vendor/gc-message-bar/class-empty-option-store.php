<?php
if(class_exists('Gc_MessageBar_Empty_Option_Store')) {
    return;
}

class Gc_MessageBar_Empty_Option_Store implements Gc_MessageBar_Option_Store_interface{
    public function __construct(){

    }

    public function save_option($option_name, $newValue,$deprecated=' ', $autoload='no') {
    }

    public function add_option($option_name,$optionValue, $deprecated=' ', $autoload='no'){
    }
    
    public function update_option($option_name,$optionValue){
    }

    public function get_option($option_name,$default = null){
    }
    public function commit(){

    }

}