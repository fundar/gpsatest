<?php
if(class_exists("Gc_MessageBar_Request")){
    return;
}

class Gc_MessageBar_Request{
    public function __construct(){
        $this->data = $_REQUEST;
    }

    public function configure($config){
        if(!count($config)){
            return;
        }
        $this->data =$config;
    }
    public function get_param($name,$default = ""){
        if($this->has_param($name)){
            return $this->data[$name];
        }else{
            return $default;
        }
    }
    public function has_param($name){
        return isset($this->data[$name]);
    }

    public function get_http_raw_post_data(){
        if (!isset($HTTP_RAW_POST_DATA)) {
            $HTTP_RAW_POST_DATA = file_get_contents('php://input');
        }
        return $HTTP_RAW_POST_DATA;
    }

}