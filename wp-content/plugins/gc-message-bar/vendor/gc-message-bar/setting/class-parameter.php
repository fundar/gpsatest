<?php
if(class_exists("Gc_MessageBar_Setting_Parameter")){
    return;
}
class Gc_MessageBar_Setting_Parameter 
extends 
    Gc_MessageBar_Setting_Parameter_Base
implements 
    Gc_MessageBar_Configurable_interface,
    Gc_MessageBar_Storable_Interface,
    Gc_MessageBar_Serializable_Interface,
    Gc_MessageBar_Option_Type_Detection_Interface
{

    /* #REGION Gc_MessageBar_Configurable_interface*/
    public function configure(array $options){
        //$namespace = isset($options["namespace"]) ? $options["namespace"] : "";
        $this->load_from_array($options);
    }
    /* #ENDREGION */

    /* #REGION Gc_MessageBar_Storable_Interface */
    public function save(){

    }
    public function load(){

    }

    protected $store_engine = "settings_store";
    public function set_store_engine($engine){
        $this->store_engine = $engine;
    }
    public function get_store_engine(){
        return $this->store_engine;
    }
    public function get_store_engine_instance(){
        if(Gc_MessageBar_Service_Locator::has($this->store_engine)){
            return Gc_MessageBar_Service_Locator::get($this->store_engine);
        };

        return Gc_MessageBar_CF::create("Option_Store");
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
            case "id":
            case "namespace":
            case "type":
            case "group":
            case "default":
            case "formattable":
            case "title":
            case "options":
            case "description":
            case "visible":
            case "permanent":
            case "renderer":
            case "testable":
            case "instance":
                $this->$key = $value;
            break;
            case "formatting":
                $this->configure_internal_parameter("formattable",$value);
            break;
            case "text":
                $this->configure_internal_parameter("title",$value);
            break;
            case "value":
                if(is_array($value) and array_key_exists(self::DEF_VARIANT_NAME, $value)){
                    $this->value = $value;
                } else {
                    $this->set_value($value);
                }
            break;
        }
    }

    public function export_to_array($with_value = false){
        $res = array();
        $res["id"] = $this->get_id();
        $res["namespace"] = $this->get_namespace();
        $res["type"] = $this->get_type();
        $res["group"] = $this->get_group();
        $res["default"] = $this->get_default();
        $res["formattable"] = $this->is_formattable();
        $res["title"] = $this->get_title();
        $res["options"] = $this->get_options();
        $res["description"] = $this->get_description();
        $res["visible"] = $this->is_visible();
        $res["permanent"] = $this->is_permanent();
        $res["renderer"] = $this->get_renderer();
        $res["testable"] = $this->is_testable();
        $res["variant"] = $this->get_variant();
        $res["instance"] = $this->get_instance();
        if($with_value){
            $res["value"] = $this->value;
        }
        return $res;
    }
    public function export_data_to_array(){
        $res = array();
        $res["value"] = $this->value;
        return $res;
    }
    /* #ENDREGION */


    /* #REGION Gc_MessageBar_Option_Type_Detection_Interface*/

    public function is_text(){
         return ($this->type == 'text');
    }

    public function is_text_area(){
        return ($this->type  == 'textarea');
    }

    public function is_checkbox(){
        return ($this->type  == 'checkbox');
    }
    public function is_checked(){
        return ($this->value == "1");
    } 
    public function is_radio(){
        return ($this->type  == 'radio');
    }

    public function is_color(){
        return ($this->type  == 'color');
    }


    public function is_select(){
         return ($this->type == 'select');
    }

    public function is_number(){
         return ($this->type == 'number');
    }
    /* #ENDREGION */


    public function debug(){
        $msg =  "----------------".PHP_EOL;
        $msg .= print_r($this->export_to_array());
        $msg .= "----------------".PHP_EOL;
        return $msg;
    }

}
