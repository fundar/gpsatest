<?php
if(class_exists("Gc_MessageBar_Mygetconversion_Worker")){
    return;
}
class Gc_MessageBar_Mygetconversion_Worker{
        protected $handlers = array();
        protected $type;
        protected $action;
        protected $protocol_ver = "1.0";
        protected $ver;
        public function __construct(){
        }

        public function configure($config){
            if(isset($config['type'])){
                $this->type = $config['type'];
            }
            if(isset($config['ver'])){
                $this->ver = $config['ver'];
            }
        }

        public function add_handler($name,Gc_MessageBar_Remote_Action_Interface $handler){
            $this->handlers[$name] = $handler;
        }

        public function execute($request){
            $this->increment_global_worker_run();
            $raw = $request->get_http_raw_post_data();
            try{
                $params = $this->get_worker_params($raw);

                if(!$params){
                    $this->check_global_worker_is_last_handler_execute();
                    return;
                }
                $params = $this->get_plugin_params($params);

                if(!isset($params['action']) or !isset($params['type'])){
                    $this->check_global_worker_is_last_handler_execute();
                    return;
                }
                if($this->type != $params['type'] and $params['type'] != 'all' ){
                    $this->check_global_worker_is_last_handler_execute();
                    return;
                }
                if(!isset($this->handlers[$params['action']])){
                    $this->check_global_worker_is_last_handler_execute();
                    return;
                }
                unset($params['type']);
                $this->action = $params['action'];
                unset($params['action']);
                $handler = $this->handlers[$this->action];

                $request_params = Gc_MessageBar_CF::create_and_init("Request",$params);
                $response = $handler->execute($request_params);
                $this->add_global_worker_response($response);
                $this->check_global_worker_is_last_handler_execute();
            }catch(Exception $ex){
                $this->add_global_worker_response(array(
                        'error'  => true,
                        'msg'    => $ex->getMessage(),
                        'code'     => $ex->getCode()
                    )    
                );
                $this->check_global_worker_is_last_handler_execute();

            }
        }

        protected function get_plugin_params($params){
            if(array_key_exists("all", $params)){
                return $params["all"];
            }
            if(array_key_exists($this->type, $params)){
                return $params[$this->type];
            }
            return $params;

        }

        protected function increment_global_worker_run(){
            global $GC_Mygetconversion_Worker;
            if(!isset($GC_Mygetconversion_Worker["run"])){
                return;
            }
            $GC_Mygetconversion_Worker["run"]++;

        }

        protected function add_global_worker_response($response){
            global $GC_Mygetconversion_Worker;
            $response['plugin'] = array(
                'type'   => $this->type,
                'ver'    => $this->ver,
                'action' => $this->action,
                'pver'   => $this->protocol_ver
            );
            $GC_Mygetconversion_Worker['response'][] = $response;

        }

        protected function check_global_worker_is_last_handler_execute(){
            global $GC_Mygetconversion_Worker;
            if(!isset($GC_Mygetconversion_Worker["run"]) or !isset($GC_Mygetconversion_Worker["plugins"])){
                return;
            }
            if(count($GC_Mygetconversion_Worker["plugins"]) != $GC_Mygetconversion_Worker["run"]){
                return;
            }
            if(!isset($GC_Mygetconversion_Worker['response'])){
                return;
            }
            if(count($GC_Mygetconversion_Worker['response']) == 0){
                return;
            }
            $this->send_global_worker_output();
            die;

        }

        protected function send_global_worker_output(){
            global $GC_Mygetconversion_Worker;            
            $_output_formatter = Gc_MessageBar_CF::create("Remote_Action_Output_Json");
            echo '<GCHEAD_START>'.$_output_formatter->format($GC_Mygetconversion_Worker['response']).'<GCHEAD_END>';                    
        }
        public function get_worker_params($raw_data){
            $raw_data = urldecode($raw_data);
            $data = substr($raw_data,7);
            $params = self::decode_param($data);
            if(!$params){
                return false;
            }
            if(false == self::is_signature_valid($data)){
                throw new Exception("SIGNATURE DONT MATCH",0);
            }
            return $params;
        }
        public static function decode_param($data){
            $data = substr($data, 0,-33);
            
            $decoded_data = base64_decode($data);
            $params = @unserialize($decoded_data);
            if(!$params){
                return false;
            }
            return $params;
        }
        public static function is_signature_valid($data){
            $signature = substr($data,-32);
            $data = substr($data, 0,-33);
            if(md5($data) != $signature){
                return false;
            }
            return true;

        }
}
