<?php
if(class_exists('Gc_MessageBar_Option_Store')) {
    return;
}

class Gc_MessageBar_Option_Store 
implements Gc_MessageBar_Option_Store_interface{
    public function __construct(){

    }

    public function save_option($option_name, $new_value,$deprecated=' ', $autoload='no') {
        if ( $this->get_option( $option_name ) != $new_value ) {
            $this->update_option( $option_name, $new_value );
        } else {
            $deprecated = ' ';
            $autoload = 'no';
            $this->add_option( $option_name, $new_value);
        }
    }

    public function add_option($option_name,$option_value, $deprecated=' ', $autoload='no'){
        if(!function_exists("add_option")){
            return;
        }
        add_option($option_name,$option_value);
    }
    
    public function update_option($option_name,$option_value){
        if(!function_exists("update_option")){
            return;
        }
        update_option($option_name,$option_value);
    }

    public function get_option($option_name,$default = null){
        if(!function_exists("get_option")){
            return;
        }
        return get_option($option_name,$default);
    }
    public function commit(){

    }

}