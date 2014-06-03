<?php
if(class_exists('Gc_MessageBar_Option_Namespace')) {
    return;
}

class Gc_MessageBar_Option_Namespace implements Gc_MessageBar_Configurable_Interface{
    protected $namespace = "gc_";
    protected $option_store = "option_store";
    protected $options;
    protected $instance = null;
    public function __construct(){
    }

    public function configure(array $options){
        if(isset($options['namespace'])){
            $this->namespace = $options['namespace'];
        }
        if(isset($options['option_store'])){
            $this->option_store = $options['option_store'];
        }

        if(isset($options['instance'])){
            $this->instance = $options['instance'];
        }

        if(isset($options['options'])){
            $this->load_options_from_array($options['options']);
        }
    }

    public function load_options_from_array($config){
        foreach ($config as $key => $value) {
            $option = $this->get_new_option();
            $option->set_store_engine($this->option_store);
            $option->load_from_array($value,$this->namespace);
            $option->set_instance($this->get_instance());
            $option->load();
            $this->options[$key] = $option;
        }
    }


    public function set_instance($key){
        $this->instance = $key;
        foreach($this->options as $option){
            $option->set_instance($key);
        }
    }

    public function get_instance(){
        return $this->instance;
    }


    public function get_option_store(){
        return $this->option_store;
    }

    public function get_namespace(){
        return $this->namespace;
    }

    public function get($key){
        if(isset($this->options[$key])){
            return $this->options[$key]->load();
        }
        $option = $this->get_new_option();
        $option->configure(array("id" => $key));
        return $option;
    }

    public function get_options(){
        return $this->options;
    }

    public function filter_options_by_type($type_name,$visible = true){
        return $this->filter("get_type", $type_name,$visible);
    }


    public function filter_options_by_group($group_name,$visible = true){
        return $this->filter("get_group", $group_name,$visible);
    }

    public function filter($filter_name,$filter_value,$visible = true){
        $filtered_options = array();
        $options = $this->get_options();
        foreach ($options as $key => $opt) {
            if($opt->$filter_name() == $filter_value and $opt->is_visible() == $visible){
                $filtered_options[$key] = $opt;
            }
        }
        return $filtered_options;
    }

    protected function get_new_option(){
        return Gc_MessageBar_CF::create("Option");

    }
    
}

