<?php
if(class_exists("Gc_MessageBar_API")){
    return;
}

class Gc_MessageBar_API {
    protected $api_url = "";
    protected $api_key = false;
    protected $signing = false;
    protected $curl_enable = false;
    public function __construct(){
        if(function_exists("curl_exec")){
            $this->curl_enable = true;
        }

        $this->init();
    }

    public function init(){
        // PLACE YOUR CONSTRUCTOR CODE HERE BY OVERRIDE THIS METHOD
    }

    public function configure($parameters){
        if(isset($parameters['api_url'])){
            $this->api_url = $parameters['api_url'];
        }
        if(isset($parameters['api_key'])){
            $this->api_key = $parameters['api_key'];
        }
        if(isset($parameters['signing'])){
            $this->signing = $parameters['signing'];
        }


    }

    public function is_api_key(){
        if(!$this->api_key){
            return false;
        }
        return true;
    }

    public function is_signing(){
        if(!$this->signing){
            return false;
        }
        return true;
    }


    public function get_api_key(){
        return $this->api_key;
    }

    public function call($cmd,$parameters){
        $client = $this->get_client();
        $client->set_endpoint($this->api_url);
        $client->set_action($cmd);

        // IF WE HAVE API KEY
        if($this->is_api_key()){
            $parameters['apikey'] = $this->get_api_key();
        }

        // IF WE SIGNING ENABLED
        if($this->is_signing()){
            if(isset($this->signing['enabled']) && $this->signing['enabled'] === true ){
                $hash_string = $this->create_hash_string($parameters);
                $parameters[$this->signing['parameter']['name']] = $hash_string;
            }
        }
    
        $client->set_parameters($parameters);
        $response = $client->get_response();
        $resp = json_decode($response);
        return $resp;
    }

    public function get_client(){
        return Gc_MessageBar_CF::create("Simple_HTTP_Client");
    }

    public function create_hash_string(array $params){
        ksort($params);
        $parameter_string = "";
        $parameter_string = http_build_query($params);
        if(function_exists('hash_hmac')){
            $signature = hash_hmac($this->signing['cipher']['algorithm'],$parameter_string,$this->signing['cipher']['secret']);
        }else{
            $signature = $this->hmac_sha1($this->signing['cipher']['secret'], $parameter_string);
        }
        return $signature;
    }

    private function hmac_sha1($key, $data){

        // Adjust key to exactly 64 bytes
        if (strlen($key) > 64) {
            $key = str_pad(sha1($key, true), 64, chr(0));
        }
        if (strlen($key) < 64) {
            $key = str_pad($key, 64, chr(0));
        }

        // Outter and Inner pad
        $opad = str_repeat(chr(0x5C), 64);
        $ipad = str_repeat(chr(0x36), 64);

        // Xor key with opad & ipad
        for ($i = 0; $i < strlen($key); $i++) {
            $opad[$i] = $opad[$i] ^ $key[$i];
            $ipad[$i] = $ipad[$i] ^ $key[$i];
        }

        return sha1($opad.sha1($ipad.$data, true));
    }

}
