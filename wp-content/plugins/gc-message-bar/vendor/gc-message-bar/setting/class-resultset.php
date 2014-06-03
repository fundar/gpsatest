<?php
if(class_exists('Gc_MessageBar_Setting_Resultset')) {
    return;
}

class Gc_MessageBar_Setting_Resultset
implements
    Gc_MessageBar_Configurable_interface,
    Gc_MessageBar_Serializable_Interface
{
    protected $parameters = array();
    public function __construct(){

    }

    /* #REGION Gc_MessageBar_Configurable_interface*/
    public function configure(array $options){
        $this->load_from_array($options);
    }
    /* #ENDREGION */

    /* #REGION Gc_MessageBar_Serializable_Interface*/
    public function load_from_array($parameters){
        foreach ($parameters as $key => $value) {
            $this->configure_internal_parameter($key,$value);
        }
        if(isset($parameters["parameters"])){
               $this->configure_parameters($parameters["parameters"]);

        }

    }

    protected function configure_internal_parameter($key,$value){
        switch($key){
            case "namespace":
            case "instance":
            case "group":
                $this->$key = $value;
            break;
        }
    }

    protected function configure_parameters($parameters){
        foreach ($parameters as $key => $param) {
            if($this->is_instance()){
                if(is_array($param)){
                    $param['instance'] = $this->get_instance();    
                }else{
                    $param->set_instance($this->get_instance());
                }
                
            }
            if(is_array($param)){
                $param['namespace'] = $this->get_namespace();    
            } else{
                $param->set_namespace($this->get_namespace());
            }
            if(is_array($param)){
                $this->parameters[$key] = Gc_MessageBar_CF::create_and_init("Setting_Parameter", $param);
            }else{
                $this->parameters[$key] = $param;
            }
        }
    }

    public function export_to_array($with_value = false){
        $res = array();
        $res["namespace"] = $this->get_namespace();
        if($this->is_group()){
            $res["group"] = $this->get_group();
        }
        if($this->is_instance()){
            $res["instance"] = $this->get_instance();
        }
        foreach($this->parameters as $key => $param){
            $res["parameters"][$key] = $param->export_to_array($with_value);
        }
        return $res;
    }
    public function export_data_to_array(){
        $res = array();
        $res["namespace"] = $this->get_namespace();
        if($this->is_group()){
            $res["group"] = $this->get_group();
        }
        if($this->is_instance()){
            $res["instance"] = $this->get_instance();
        }
        foreach($this->parameters as $key => $param){
            $res["parameters"][$key] = $param->export_data_to_array();
        }
        return $res;
    }
    /* #ENDREGION */

    public function get_parameter($name = null){
        if(is_null($name)){
            return false;
        }
        if(!isset($this->parameters[$name])){
            return false;
        }
        return $this->parameters[$name];
    }

    public function get($name = null){
        return $this->get_parameter($name);
    }

    public function get_parameters(){
        return $this->parameters;
    }

    /* #REGION $namespace */
    protected $namespace ="";
    public function set_namespace($namespace){
        $this->namespace = $namespace;
        $options = $this->get_parameters();

        foreach ($options as $key => $opt) {
            $opt->set_namespace($this->namespace);
        }

    }
    public function get_namespace(){
        return $this->namespace;
    }
    public function get_unique_name(){
        return $this->get_namespace().$this->get_id();
    }
    /* #ENDREGION */

    /* #REGION $instance */
    protected $instance = null;
    public function set_instance($instance){
        $this->instance = $instance;
        foreach ($this->parameters as $key => $value) {
            $value->set_instance($instance);
        }
    }
    public function get_instance(){
        return $this->instance;
    }
    public function is_instance(){
        return isset($this->instance);
    }
    /* #ENDREGION */

    /* #REGION $group */
    protected $group = null;
    public function set_group($group){
        $this->group = $group;
    }
    public function get_group(){
        return $this->group;
    }
    public function is_group(){
        return isset($this->group);
    }
    /* #ENDREGION */

    public function filter_by_type($type_name,$visible = true){
        $res =  $this->filter("get_type", $type_name,$visible);
        return $res;
    }


    public function filter_by_group($group_name,$visible = true){
        $res =  $this->filter("get_group", $group_name,$visible);
        $res->set_group($group_name);
        return $res;
    }

    public function filter($filter_name,$filter_value,$visible = true){

        $filtered_options = array();
        $options = $this->get_parameters();

        foreach ($options as $key => $opt) {
            if($opt->$filter_name() == $filter_value and $opt->is_visible() == $visible){
//                $filtered_options[$key] = $opt->export_to_array(true);
                $filtered_options[$key] = $opt;
            }
        }
        $config = $this->export_to_array();
        $config['parameters'] = $filtered_options;
        return Gc_MessageBar_CF::create_and_init("Setting_Resultset", $config);
    }

}