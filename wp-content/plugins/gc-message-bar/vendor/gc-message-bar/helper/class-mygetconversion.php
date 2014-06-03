<?php
if(!class_exists("Gc_MessageBar_Mygetconversion_Helper")){
    return;
}


class Gc_MessageBar_Mygetconversion_Helper{
    protected $request;
    protected $api_url;
    protected $domain_url;

    public function configure($data){
        if(isset($data['request'])){
            $this->request = $data['request'];
        }

        if(isset($data['api_url'])){
            $this->api_url = $data['api_url'];
        }

        if(isset($data['domain_url'])){
            $this->domain_url = $data['domain_url'];
        }


    }

    public function handle_activate(){
        $this->handle_request("activate");
    }


    public function handle_deactivate(){
        $this->handle_request("deactivate");
    }

    protected function handle_request($type){
        if(!$this->request->has_param('metrixCode')){
            return;
        }
        $metrix_code = $this->request->get_param('metrixCode');
        $domain_url = $this->domain_url;
        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'header'=>"Referer: $domain_url\r\n"
            )
        );
        try{
            $context = stream_context_create($opts);
            $url = $this->api_url.'/plugin/'.$type.'?metrix_code='.$metrix_code;
            $result = file_get_contents($this->api_url.'/plugin/'.$type.'?metrix_code='.$metrix_code, false, $context);
            $data = json_decode($result);
            die(json_encode($data));
        } catch (Exception $e){
            die('{"success":"false","msg":"Failed to get data at this moment."}'); 
        }

    }
}