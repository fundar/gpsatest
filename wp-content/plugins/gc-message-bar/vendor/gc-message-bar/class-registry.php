<?php
if(class_exists("Gc_MessageBar_Registry")){
    return;
}

class Gc_MessageBar_Registry
implements
    Gc_MessageBar_Configurable_Interface
{
    protected $global = false;
    protected $namespace = "gc_registry";
    protected static $store = array();
    public function __construct(){
    }

    public function configure(array $options){
        if(isset($options["global"])){
            $this->global = $options["global"];
        }
        if(isset($options["namespace"])){
            $this->namespace = $options["namespace"];
        }
        if($this->global){
            if(!isset($GLOBALS[$this->namespace])){
                $GLOBALS[$this->namespace] = array();
            }
        }
    }
    public function is_global(){
        return $this->global;
    }
    public function set($name,$value){
        if($this->global){
            $this->set_global($name,$value);
        } else {
            $this->set_local($name,$value);
        }
    }
    public function get($name,$default = null){
        if($this->global){
            return $this->get_global($name,$default);
        } else {
            return $this->get_local($name,$default);
        }

    }
    public function get_all(){
        if($this->global){
            return $GLOBALS[$this->namespace];
        } else{
            return self::$store;
        }
    }
    protected function set_global($name,$value){
        $GLOBALS[$this->namespace][$name] = $value;
    }
    protected function set_local($name,$value){
        self::$store[$this->namespace][$name] = $value;
    }
    protected function get_global($name,$default = null){
        if(isset($GLOBALS[$this->namespace][$name])){
            return $GLOBALS[$this->namespace][$name];
        } else{
            return $default;
        }
    }
    protected function get_local($name,$default = null){
        if(isset(self::$store[$this->namespace][$name])){
            return self::$store[$this->namespace][$name];
        } else{
            return $default;
        }
    }

}