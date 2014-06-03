<?php
if(class_exists("Gc_MessageBar_Theme")){
    return;
}

class Gc_MessageBar_Theme{
    protected $name;
    protected $params = array();
    protected $options = array();

    public function get_name(){
        return $this->name;
    }

    public function load_from_array(array $params){
        foreach ($params as $key => $value) {
            if($key == "id"){
                $this->id = $value;
                continue;
            }
            if($key == "name"){
                $this->name = $value;
                continue;
            }
            if($key == "options"){
                $this->options = $value;
                continue;
            }
            $this->params[$key] = $value;
        }

        return $this;


    }

    public function get_param($name){
        if(!isset($this->params[$name])){
            return false;
        }
        return $this->params[$name];
    }

    public function get_options(){
        return $this->options;
    }


}