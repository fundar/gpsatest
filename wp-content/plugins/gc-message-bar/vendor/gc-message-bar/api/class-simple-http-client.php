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
        $opts = array('https' =>
          array(
            'timeout' => 1
          )
        );
        $context  = stream_context_create($opts);        
        $response = @file_get_contents($url,false,$context);
        return $response;

    }
    protected function get_url(){
        $url = rtrim($this->endpoint_url,'/').'/'.trim($this->action,'/');
        if(count($this->parameters)){
            $url .= '?'.http_build_query($this->parameters);
        }
        return $url;
    }
}
