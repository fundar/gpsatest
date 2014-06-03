<?php
if(class_exists('Gc_MessageBar_Multi_Option_Repository')) {
    return;
}
class Gc_MessageBar_Multi_Option_Repository extends Gc_MessageBar_Option_Repository{

    protected $instance = array();
    protected $store = null;

    public function add_configuration($namespace,$configs){
        parent::add_configuration($namespace, $configs);
        $this->instance[$namespace] = array();
        $this->init_instances();
    }

    protected function init_instances(){

        $store = $this->get_store();
        $data = $store->get_all();
        if(is_array($data)){
            $this->instance = $data;
        }
        /*
        foreach($data as $ns_name => $ns){
            $this->instance[$ns_name] = array();
            foreach ($ns as $key => $instance) {
                $this->instance[$ns_name][$key] = $this->get_configured_namespace($ns_name,$instance);
            }
            
        }*/
    }

    public function findBy($option_name,$value){
        $ret = array();
        foreach($this->instance as $instance){
            //if($instance->)
        }
    }

    public function new_namespace_instance($ns){
        if(!isset($this->instance[$ns->get_namespace()])){
            $this->instance[$ns->get_namespace()] = array();
        }
        $id = count($this->instance);
        if($id >0){
            $id--;
        }
        $ns->set_instance($id);
        $this->instance[$ns->get_namespace()][$id] = $ns;
    }

    public function get_namespace($namespace,$id = null){
        if(!isset($this->instance[$namespace])){
            $res = $this->create_namespace_instance();
            $res->configure(array(
                "namespace" => $namespace
            ));
            return $res;
        }
        if(!isset($id)){
            return $this->namespace[$namespace];
        }

        if(isset($this->instance[$namespace][$id])){
            return $this->instance[$namespace][$id];
        } else {
            return false;
        }



    }

    protected function get_configured_namespace($namespace,$data,$instance = null){
        $ns = $this->create_namespace_instance();
        $ns->configure(array(
            'namespace' => $namespace,
            'options'   => $data,
            "option_store"  => "multi_option_store",
            "instance" => $instance
            ));
        return $ns;
    }


    public function update($id,$instance){
        $this->instance[$id] = $instance;
        $this->save();
    }
    protected function get_store(){
        if(isset($this->store)){
            return $this->store;
        }
        if(!Gc_MessageBar_Service_Locator::has("multi_option_store")){
            throw new Exception("No option store engine");
        }
        $this->store = Gc_MessageBar_Service_Locator::get("multi_option_store");
        return $this->store;
    }

    public function get_instance_count($namespace){
        if(!isset($this->instance[$namespace])){
            return 0;
        }
        return count($this->instance[$namespace]);
    }


    public function save(){
        try{
            $store = $this->get_store();
            $store->save($this);
        }catch(Exception $ex){
            
        }

    }

    public function has($id){
        if(array_key_exists($id, $this->instance)){
            return true;
        }
        return false;
    }

    public function get($id){
        if(!$this->has($id)){
            return false;
        }
        return $this->instance[$id];
    }


    protected function create_namespace_instance(){
        return Gc_MessageBar_CF::create("Option_Namespace");

    }
}