<?php
if(class_exists("Gc_MessageBar_Setting_Parameter")){
    return;
}
class Gc_MessageBar_Event
implements 
    Gc_MessageBar_Configurable_Interface
{
    protected $handled = false;
    protected $params = array();
    protected $result = "";
    protected $stack = array();
    public function __construct(array $params = array()){
        $this->params = $params;
    }
    public function configure(array $options){
        $this->params = $options;
    }
    public function get_params(){
        return $this->params;
    }
    public function get_param($name,$default = null){
        if(isset($this->params[$name])){
            return $this->params[$name];
        }
        return $default;
    }
    public function get_result(){
        return $this->result;
    }
    public function set_result($result){
        $this->result = $result;
    }
    public function set_handled($handled = true){
        $this->handled = $handled;
    }
    public function is_handled(){
        return $this->handled;
    }
    public function get_stack_trace(){
        return $this->stack;
    }
    public function add_to_stack($data){
        $this->stack[] = $data;
    }
}
class Gc_MessageBar_Event_Manager 
implements
    Gc_MessageBar_Configurable_Interface
{
    protected $namespace = "gc_eventmanager";
    protected $global = true;
    protected $store = null;
    public function configure(array $options){
        $this->configure_option("namespace",$options);
        $this->configure_option("global",$options);
        $this->store = new Gc_MessageBar_Registry();
        $this->store->configure(array("namespace" => $this->namespace,"global" => $this->global));
    } 
    protected function configure_option($name,array $options){
        if(isset($options[$name])){
            $this->$name = $options[$name];
        }

    }
    public function get_namespace(){
        return $this->namespace;
    }
    public function is_global(){
        return $this->global;
    }

    public function listen($event, $callback){
        $listeners = $this->store->get($event,array());
        $listeners[] = $callback;
        $this->store->set($event,$listeners);
    }

    public function get_listeners($event){
        return $this->store->get($event,array());
    }
    public function get_all_listeners(){
        return $this->store->get_all();   
    }

    public function print_all_listeners($print = true){
        $tmp = $this->store->get_all();
        $res = array();
        foreach ($tmp as $event_name => $listeners) {
            $listeners_info = array();
            foreach ($listeners as $listener) {
                $listeners_info[] = $this->get_listener_info($listener);
            }
            $res[$event_name] = $listeners_info;
        }
        if($print){
            var_dump($res);
        }
        return $res;
    }
 
    public function dispatch($event, Gc_MessageBar_Event $event_data,$before_after_event_call = false){
        if($before_after_event_call){
            $event_name = $event.':before';
            $listeners = $this->store->get($event_name,array());
            foreach ($listeners as $listener){
            $this->add_to_stack($listener,$event_name,$event_data);
                call_user_func($listener, $event_data);
                if($event_data->is_handled()){
                    break;
                }
            }           
        }
        $listeners = $this->store->get($event,array());
        foreach ($listeners as $listener){
            //var_dump($listener);
            $this->add_to_stack($listener,$event,$event_data);
            call_user_func($listener, $event_data);
            if($event_data->is_handled()){
                return $event_data->get_result();
            }
        }
        if($before_after_event_call){
            $event_name = $event.':after';
            $listeners = $this->store->get($event_name,array());
            foreach ($listeners as $listener){
                $this->add_to_stack($listener,$event_name,$event_data);
                call_user_func($listener, $event_data);
                if($event_data->is_handled()){
                    break;
                }
            }           
        }

        return $event_data->get_result();
    }
    protected function add_to_stack($listener,$event,$event_data){
        $event_data->add_to_stack(
            array(
                "event" => $event, 
                "listener" => $this->get_listener_info($listener)
                )
            );
            

    }
    protected function get_listener_info($listener){
        return array(
                    "class" => get_class($listener[0]),
                    "method" => $listener[1]
                    );
    }
}