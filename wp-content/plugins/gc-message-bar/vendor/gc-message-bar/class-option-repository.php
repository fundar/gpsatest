<?php
if(class_exists('Gc_MessageBar_Option_Repository')) {
    return;
}
class Gc_MessageBar_Option_Repository{

    protected $namespace = array();

    public function add_namespace($namespace){
        if(isset($this->namespace[$namespace])){
            return;
        }
        $this->namespace[$namespace] = array();
    }

    public function add_configuration($namespace,$configs){
        $res = $this->create_namespace_instance();
        $res->configure(array(
            "namespace"     => $namespace,
            "options"       => $configs,
            "option_store"  => "option_store"
        ));
        $this->namespace[$namespace] = $res;
    }

    public function get_namespace($namespace,$id = false){
        if(isset($this->namespace[$namespace])){
            return $this->namespace[$namespace];
        }

        $res = $this->create_namespace_instance();
        $res->configure(array(
            "namespace" => $namespace
        ));
        return $res;
    }

    protected function create_namespace_instance(){
        return Gc_MessageBar_CF::create("Option_Namespace");

    }
}