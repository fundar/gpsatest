<?php
if(class_exists("Gc_MessageBar_Mygetconversion_API")){
    return;
}
class 
    Gc_MessageBar_Mygetconversion_API 
extends 
    Gc_MessageBar_API {

    public function activate($username,$password,$domain,$type){

        $baseParams = array("username" => $username,"password"=>$password,"type" => $type,"domain" => $domain);

        $response = $this->call("/plugin/activate", $baseParams);
        if(!isset($response)){
            return $this->create_error("INTERNAL_ERROR");
        }
        if($response->type == "error"){
            return $this->create_error("AUTH_ERROR");
        }
        return $this->create_success($response->data->metrix_code);
    }
    
    public function activate_product($username,$password,$domain,$type){
        $baseParams = array("username" => $username,"password"=>$password,"type" => $type,"domain" => $domain);

        $response = $this->call("/product/activate", $baseParams);

        if(!isset($response)){
            return $this->create_error("INTERNAL_ERROR");
        }
        if($response->type == "error" and $response->data->name == 404){
            return $this->create_error("NO_LICENSE_ERROR");            
        }
        if($response->type == "error"){
            return $this->create_error("AUTH_ERROR");
        }
        return $this->create_success($response->data->key);
    }

    public function check_product($key,$domain,$type){
        $baseParams = array("key" => $key,"type" => $type,"domain" => $domain);
        $response = $this->call("/product/checkactivation", $baseParams);
        if(!isset($response)){
            return $this->create_error("INTERNAL_ERROR");
        }
        if($response->type == "error" and $response->data->name == 404){
            return $this->create_error("NO_LICENSE_ERROR");            
        }
        if($response->type == "error"){
            return $this->create_error("SERVER_ERROR");            
        }
        return $this->create_success("OK");
    }

    public function create_error($msg){
        return $this->create_message("error",$msg);
    }

    public function create_success($msg){
        return $this->create_message("success",$msg);
    }

    public function create_message($type,$msg){
        return array(
            "type" => $type,
            "data" => $msg
        );
    }



    

}