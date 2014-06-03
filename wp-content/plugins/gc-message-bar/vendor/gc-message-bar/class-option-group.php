<?php
if(class_exists('Gc_MessageBar_Option_Group')) {
    return;
}
class Gc_MessageBar_Option_Group{
    protected $title = "";
    protected $id = "";
    protected $params = array();
    protected $extra_params = array();
    protected $sub_groups = array();
    protected $default_renderer = "Options_Group_Container_Renderer";
    protected $renderer = "";
    protected $option_group = "";
    protected $parent_option;
    protected $parent_option_state = array();
    protected $options_visibility = array();

    public function __construct($data = array()){
        $this->load_from_array($data);
    }

    public function configure($data = array()){
        $this->load_from_array($data);
    }

    public function load_from_array($data = array()){
        if(isset($data["title"])){
            $this->set_title($data["title"]);
        }
        if(isset($data["extra_param"])){
            $this->set_extra_param($data["extra_param"]);
        }

        if(isset($data["id"])){
            $this->set_id($data["id"]);
        }

        if(isset($data["params"])){
            $this->params = $data["params"];
        }

        if(isset($data["option_group"])){
            $this->option_group = $data["option_group"];
        }

        if(isset($data["renderer"])){
            $this->renderer = $data["renderer"];
        }

        if(isset($data["sub_groups"])){
            $this->load_sub_groups_from_array($data["sub_groups"]);
        }

        if(isset($data["parent_option_state"])){
            $this->parent_option_state = $data["parent_option_state"];
        }

        if(isset($data["options_visibility"])){
            $this->options_visibility = $data["options_visibility"];
        }

    }

    protected function load_sub_groups_from_array($sub_groups){
        foreach($sub_groups as $key => $descriptor){
            $this->sub_groups[$key] = Gc_MessageBar_CF::create_and_init("Option_Group", $descriptor);
        }
    }

    public function get_title(){
        return $this->title;
    }

    public function set_title($title){
        $this->title = $title;
    }

    public function get_id(){
        return $this->id;
    }

    public function set_id($id){
        $this->id = $id;
    }

    public function get_option_group(){
        return $this->option_group;
    }
    public function get_extra_param(){
        return $this->extra_param;
    }

    public function set_extra_param($extra_param){
        $this->extra_param = $extra_param;
    }


    public function get_param($name,$default=""){

        if(!$this->has_param($name)){
            return $default;
        }
        return $this->params[$name];

    }

    public function has_param($name){
        return isset($this->params[$name]);

    }

    public function get_sub_groups(){
        return $this->sub_groups;
    }

    public function has_sub_group($name){
        return isset($this->sub_groups[$name]);
    }

    public function get_sub_group($name){

        if(!$this->has_sub_group($name)){
            return null;
        }
        return $this->sub_groups[$name];

    }

    public function has_renderer(){
        return !empty($this->renderer);
    }

    public function has_options_visibility(){
        return count($this->options_visibility);
    }

    public function is_option_visible($option){
        if(!$this->has_options_visibility()){
            return true;
        }
        $parent_value = $this->parent_option->get_value();
        if(!isset($this->options_visibility[$parent_value])){
            return false;
        }
        $visibility = $this->options_visibility[$parent_value];
        $id = $option->get_id();
        if(!isset($visibility[$id])){
            return false;
        }

        if($visibility[$id] == "show"){
            return true;
        }
        return false;

    }

    public function get_options_visibility(){
        return $this->options_visibility;
    }


    public function get_renderer(){
        if(!$this->has_renderer()){
            return $this->default_renderer;
        }
        return $this->renderer;
    }

    public function has_parent_option(){
        return isset($this->parent_option);
    }

    public function has_parent_option_state(){
        return count($this->parent_option_state);
    }


    public function set_parent_option($option){
        $this->parent_option = $option;
    }

    public function get_parent_option(){
        return $this->parent_option;
    }

    public function get_state_depend_on_parent_option(){
        if(!$this->has_parent_option()){
            return "";
        }
        $parent_value = $this->parent_option->get_value();
        if(!isset($this->parent_option_state[$parent_value])){
            return "";
        }

        return $this->parent_option_state[$parent_value];

    }

    public function get_parent_option_state(){
        return $this->parent_option_state;
    }
}