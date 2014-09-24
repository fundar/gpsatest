<?php
if(class_exists("Gc_MessageBar_Simple_HTTP_Client")){
    return;
}
class Gc_MessageBar_Simple_HTTP_Client implements Gc_MessageBar_Http_Client_Interface{
    protected $endpoint_url = "";
    protected $action = "";
    protected $parameters = array();
    public function set_endpoint($url){
        $this->endpoint_url = $url;
    }
    public function set_action($cmd){
        $this->action = $cmd;
    }
    public function set_parameters($parameters){
        $this->parameters = $parameters;
    }
    public function get_response(){
        $url = $this->get_url();
        $request = Gc_MessageBar_CF::create("Http_Request");
        $response = $request->get($url,$this->parameters);
        return $response;

    }
    protected function get_url($parameters = array()){
        $url = rtrim($this->endpoint_url,'/').'/'.trim($this->action,'/');
        if(count($this->parameters)){
            $url .= '?'.http_build_query($this->parameters);
        }
        return $url;
    }

}