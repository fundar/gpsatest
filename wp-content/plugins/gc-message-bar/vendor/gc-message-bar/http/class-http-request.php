<?php
if(class_exists("Gc_MessageBar_Http_Request")){
    return;
}
interface Gc_MessageBar_Http_Request_Interface{
    function get($url,$parameters,$timeout = 0);

}
class Gc_MessageBar_Http_Request
implements Gc_MessageBar_Http_Request_Interface
{
    protected $timeout = 1;
    protected $request_implementation = null;
    public function __construct(){
        $this->init_implementation();
    }

    public function init_implementation(){
        if(function_exists('curl_init')) {
            $this->request_implementation = Gc_MessageBar_CF::create("Http_Request_Curl_Impl");
            return;
        }
        if(ini_get('allow_url_fopen')){
            $this->request_implementation = Gc_MessageBar_CF::create("Http_Request_Filegetcontents_Impl");
            return;
        }
        if(function_exists('http_get')){
            $this->request_implementation = Gc_MessageBar_CF::create("Http_Request_Httpget_Impl");
            return;
        }

    }

    public function is_implementation_available(){
        return isset($this->request_implementation);
    }
    public function get($url,$parameters,$timeout = 0){
        
        if(!isset($this->request_implementation)){
            /*echo'<div class="clear"></div>
            <div class="update-nag"><strong>';
            _e('Your server doesn\'t support remote exchanges.');
            echo'</strong><br/>';
            _e('Contact your administrator to modify that, it should be configurable.');
            echo'<br/><ul>
            <li>';
            _e('CURL library - DISABLED');
            echo'</li>
            <li>';
            _e('allow_url_fopen - DISABLED');
            echo'</li>
            <li>';
            _e('PECL pecl_http >= 0.1.0 - DISABLED');
            echo'</li></ul></div>';*/
            //throw new Exception();
            return;
        }
        if($timeout == 0){
            $timeout = $this->timeout;
        }
        $response = $this->request_implementation->get($url,$parameters,$timeout);
       
        return $response;
    }
}

class 
    Gc_MessageBar_Http_Request_Filegetcontents_Impl
implements 
    Gc_MessageBar_Http_Request_Interface{
    public function get($url,$parameters,$timeout = 0){
        ini_set('default_socket_timeout',(int)$timeout);
        if(count($this->parameters)){
            $url .= '?'.http_build_query($parameters);
        }
        return @file_get_contents($url);

    }

}
class 
    Gc_MessageBar_Http_Request_Curl_Impl
implements 
    Gc_MessageBar_Http_Request_Interface{
    public function get($url,$parameters,$timeout = 0){
        if(count($this->parameters)){
            $url .= '?'.http_build_query($parameters);
        }
        $ch = curl_init( $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
        $result = curl_exec( $ch );
        curl_close( $ch );
        return $result;

    }

}
class 
    Gc_MessageBar_Http_Request_Httpget_Impl
implements 
    Gc_MessageBar_Http_Request_Interface{
    public function get($url,$parameters,$timeout = 0){
        if(count($this->parameters)){
            $url .= '?'.http_build_query($parameters);
        }
        $response = http_get($url, array('timeout'=>(int)$timeout,'verifypeer ' => false),$info);
        $response = http_parse_message($response)->body;
        return $response;
    }

}