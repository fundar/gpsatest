<?php
if(class_exists("Gc_MessageBar_Remote_Action_Setoption")){
    return;
}

class Gc_MessageBar_Remote_Action_Setoption  implements Gc_MessageBar_Remote_Action_Interface {
    protected $_request;
    protected $_option_name = "";
    protected $_option;
    public function __construct($option_name,$option){
        $this->set_option($option);
        $this->set_option_name($option_name);
    }
    public function execute($request){
        $this->_request = $request;
        if(!$this->check_required_param("value")){
            return $this->get_required_param_missing_error("value");
        }
        if($this->get_option_name() == ""){
            return $this->get_required_param_missing_error($this->get_option_name());
        }
        $this->_option->set_value($this->_request->get_param("value"));
        $this->_option->save();
        return array("success"=>true);
    }

    public function set_option_name($name){
        $this->_option_name = $name;
    }

    public function get_option_name(){
        return $this->_option_name;
    }

    public function set_option($option){
        $this->_option = $option;
    }

    protected function check_required_param($name){
        return $this->_request->has_param($name);
    }

    protected function get_required_param_missing_error($name){
        return array("error" => true,"msg" => "missing param: ".$name, "code" => 201);
    }
}