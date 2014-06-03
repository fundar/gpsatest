<?php
if(class_exists('Gc_MessageBar_Setting_Repository')) {
    return;
}

class Gc_MessageBar_Setting_Repository
implements
    Gc_MessageBar_Configurable_interface,
    Gc_MessageBar_Serializable_Interface
{
    protected $single_params = null;
    protected $single_params_config = null;
    protected $multi_params  = array();
    protected $multi_params_config = array();
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

    }

    protected function configure_internal_parameter($key,$value){
        switch($key){
            case "namespace":
            case "single_params_config":
            case "multi_params_config":
                $this->$key = $value;
            break;
            case "single_params":
                $this->single_params = $this->create_resultset($this->get_single_params_config(),$value);
            break;
            case "multi_params":
                $this->multi_params = $this->initialize_multi_params($value);
            break;
        }
    }


    public function export_to_array($with_value = false){
        $res = array();
        $res["namespace"] = $this->get_namespace();
        $res["single_params_config"] = $this->single_params_config;
        $res["single_params"] = $this->single_params;
        $res["multi_params_config"] = $this->multi_params_config;
        $res["multi_params"] = $this->multi_params;
        return $res;
    }
    /* #ENDREGION */

    public function get_single_params(){
        if(!isset($this->single_params)){
            $this->single_params = $this->create_resultset($this->get_single_params_config(),array());
        }
        return $this->single_params;
    }

    protected function initialize_multi_params($params){
        $result = array();
        foreach ($params as $key => $value) {
            $result[$key] = $this->create_resultset($this->get_multi_params_config(),$value,$key);
        }
        return $result;
    }
    protected function create_resultset($config,$value,$instance = null){
        $result_params = Gc_MessageBar_CF::create_and_init(
            "Setting_Resultset", 
            array(
                'namespace'  => $this->get_namespace(),
                'parameters' => $config,
                'instance'   => $instance
            )
        );            
        $params = $result_params->get_parameters();
        foreach ($params as $key => $param) {
            $param->set_namespace($this->get_namespace());
            
            if(isset($instance)){
                $param->set_instance($instance);
            }
            if(!isset($value['parameters'])){
                continue;
            }
            if(!is_array($value['parameters'])){
                continue;
            }
            if(!isset($value['parameters'][$key])){
                continue;
            }
            if(!isset($value['parameters'][$key]['value'])){
                continue;
            }
            if(is_array($value['parameters'][$key]['value'])){
                $param->set_raw_value($value['parameters'][$key]['value']);    
            } else{
                $param->set_value($value['parameters'][$key]['value']);    
            }
            
        }
        return $result_params;

    }

    public function set_single_params($params){
        $this->single_params = $params;
    }
    public function get_single_params_config(){
        return $this->single_params_config;
    }


    public function get_multi_params($id = null){
        $result = array();
        if(is_null($id)){
            return $this->multi_params;
        }
        if(!isset($this->multi_params[$id])){
            return $result;
        }
//        $result[] = $this->create_resultset($this->get_multi_params_config(),$this->multi_params[$id],$id);
        $result[] = $this->multi_params[$id];
        return $result;
    }

    public function set_multi_params($params){
        $this->multi_params = $params;
    }

    public function get_new_multi_params(){
        return Gc_MessageBar_CF::create_and_init(
            "Setting_Resultset", 
            array(
                'namespace'  => $this->get_namespace(),
                'parameters' => $this->get_multi_params_config()
            )
        ); 
    }
    public function get_multi_params_config(){
        return $this->multi_params_config;
    }
    public function add_multi_params($result_set,$id = false){
        if(!$id){
            $id = count($this->multi_params);
        }
        $result_set->set_instance($id);
        $this->multi_params[$id] = $result_set;
        
    }

    /* #REGION Gc_MessageBar_Serializable_Interface*/
    public function save(){
        $store = $this->get_store_instance();
        $data = $this->export_data_to_array();
        $store->save_option(GC_MESSAGE_BAR_TYPE,$data);
    }

    public function load(){
        $store = $this->get_store_instance();
        $this->configure($store->get_option(GC_MESSAGE_BAR_TYPE,array()));

    }
    protected function get_store_instance(){
        if(defined(GC_MESSAGE_BAR_SL_SETTING_STORE)){
            return Gc_MessageBar_Service_Locator::get(GC_MESSAGE_BAR_SL_SETTING_STORE);            
        }
        return Gc_MessageBar_CF::create("Setting_Store");
    }
    /* #ENDREGION */
    /* #REGION $namespace */
    protected $namespace ="";
    public function set_namespace($namespace){
        $this->namespace = $namespace;
    }
    public function get_namespace(){
        return $this->namespace;
    }

    public function export_data_to_array(){
        $multi_params = array();
        foreach ($this->multi_params as $key => $multi) {
            $multi_params[$key] = $multi->export_data_to_array(true);
        }
        return array(
            "namespace" => $this->get_namespace(),
            "multi_params" => $multi_params,
            "single_params" => $this->single_params->export_data_to_array(true)
        );

    }

}