<?php
if(class_exists("Gc_MessageBar_Option")){
    return;
}
class Gc_MessageBar_Option implements Gc_MessageBar_Option_Interface, 
                           Gc_MessageBar_Option_Meta_Data_Interface,
                           Gc_MessageBar_Option_Type_Detection_Interface, 
                           Gc_MessageBar_Configurable_interface {

    protected $store_engine = "option_store";
    protected $namespace = "";
    protected $value = null;
    protected $default ="";
    protected $formatting = false;
    protected $text = "";
    protected $type = "text";
    protected $id = "";
    protected $group ="";
    protected $description = "";
    protected $visible = true;
    protected $remote_edit_enable = false;
    protected $options = array();
    protected $params = array();
    protected $permanent = true;
    protected $renderer = "";
    protected $instance = null;
    protected $loaded = false;

    public function __construct(){    }

    /***
    Gc_MessageBar_Configurable_Interface
    ***/

    public function configure(array $options){
        $namespace = isset($options["namespace"]) ? $options["namespace"] : "";
        $this->load_from_array($options,$namespace);
    }

    public function load_from_array(array $params,$namespace = ""){
        $this->namespace = $namespace;
        if(isset($params["permanent"])){
            $this->permanent = $params["permanent"];
        }
        $value = null;
        if(isset($params["id"])){
            $this->id = $params["id"];
            if($this->is_permanent()){
                if(isset($params["value"])){
                    $value = $params["value"];
                }else{
                    $store = $this->get_option_store();
                    $value = $store->get_option($this->get_full_option_name($this->id));

                }
                $this->loaded = true;
            }
            
        }

        if(isset($params["type"])){
            $this->type = $params["type"];
        }

        if(isset($params["group"])){
            $this->group = $params["group"];
        }

        if(isset($params["default"])){
            $this->default = $params["default"];
        }
        
        if(isset($params["formatting"])){
            $this->formatting = $params["formatting"];
        }

        if(isset($params["text"])){
            $this->text = $params["text"];
        }

        if(isset($params["options"])){
            $this->options = $params["options"];
        }

        if(isset($params["description"])){
            $this->description = $params["description"];
        }
    
        if(isset($params["params"])){
            $this->params = $params["params"];
        }

        if(isset($params["visible"])){
            $this->visible = $params["visible"];
        }

        if(isset($params["renderer"])){
            $this->renderer = $params["renderer"];
        }

        if(isset($params["remote_edit_enable"])){
            $this->remote_edit_enable = $params["remote_edit_enable"];
        }
        $this->value = !isset($value) ? $this->default : $value;
        return $this;


    }

    public function get_name(){
        return $this->get_full_option_name($this->id);
    }

    public function get_unique_name(){
        return $this->get_full_option_name($this->id);
    }

    public function get_id(){
        return $this->id;
    }


    public function get_group(){
        return $this->group;
    }

    public function get_default(){
        return $this->default;
    }

    public function get_value(){
        return isset($this->value) ? stripslashes($this->value) : $this->default;
    }
    
    public function is_formatting_enabled(){
        return isset($this->formatting) ? $this->formatting : false;
    }

    public function set_value($value){
        $this->value = $value;
    }

    public function set_checked($value){
        if($value){
            $this->value = "1";
        } else {
            $this->value = "2";

        }
    }
    
    
    public function get_type(){
        return $this->type;
    }

    public function get_text(){
        return $this->text;
    }

    public function get_description(){
        return $this->description;
    }

    public function get_options(){
        return $this->options;
    }

    /***
    Gc_MessageBar_Option_Type_Detection_Interface
    ***/

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
    
    /***
        Gc_MessageBar_Option_Meta_Data_Interface
     ***/
    public function is_visible(){
        return ($this->visible);
    }

    public function is_loaded(){
        return $this->loaded;
    }

    public function is_remote_edit_enable(){
        return ($this->remote_edit_enable);
    }

    public function set_visible($visible){
        $this->visible = $visible;
    }

    public function set_store_engine($engine){
        $this->store_engine = $engine;
    }

    public function get_store_engine(){
        return $this->store_engine;
    }

    public function set_remote_edit_enable($enable){
        $this->remote_edit_enable = $enable;
    }

    public function set_instance($key){
        $this->instance = $key;
    }

    public function get_instance(){
        return $this->instance;
    }

    public function get_full_option_name($name){
        return $this->namespace.$name;
    }
    
/*    
    public function is_appear() {
        return strstr($this->id, 'appear_here_');
    }
*/
    
    public function save(){
        $store = $this->get_option_store();
        $store->save_option($this->get_full_option_name($this->id),$this->get_value(),$this->get_instance());
    }

    public function load(){
        if($this->is_loaded()){
            return $this;
        }
        $store = $this->get_option_store();

        $this->value = $store->get_option($this->get_full_option_name($this->id),$this->default,$this->get_instance());
        $this->loaded = true;
        return $this;
    }

    protected function get_option_store(){
        if(Gc_MessageBar_Service_Locator::has($this->store_engine)){
            return Gc_MessageBar_Service_Locator::get($this->store_engine);
        };

        return Gc_MessageBar_CF::create("Option_Store");
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
   
    public function has_renderer(){
        return !empty($this->renderer);
    }

    public function get_renderer(){
        if(!$this->has_renderer()){
            return $this->default_renderer;
        }
        return $this->renderer;
    }

    public function is_permanent(){
        return $this->permanent;
    }

    public function get_namespace(){
        return $this->namespace;
    }
}