<?php
if(class_exists("Gc_MessageBar_Abstract_Renderer")){
    return;
}
abstract class Gc_MessageBar_Abstract_Renderer{
    protected $namespace;
    protected $controller;
    protected $event_manager = null;
    protected $eventprefix = '';
    
    abstract public function render($params);
    
    public function __construct($namespace, $controller){
        $this->namespace = $namespace;
        $this->controller = $controller;
    }
    
    protected function get_option_value($name){
        return $this->get_option($name)->get_value();
    }

    protected function get_option($name){
        $options = $this->get_option_namespace();
        return $options->get($name)->load();
    }

    protected function get_repository_factory(){
        return Gc_MessageBar_CF::create("Option_Repository_Factory");
    }

    protected function get_option_namespace(){
        return $this->get_repository_factory()
                        ->get_instance()
                        ->get_namespace($this->namespace);

    }

    public function set_event_manager($event_manager){
      $this->event_manager = $event_manager;
    }

    public function set_event_prefix($prefix){
      $this->eventprefix = $prefix;
    }

    public function get_event_manager(){
      if($this->event_manager == null){
        $this->event_manager = Gc_MessageBar_CF::create_and_init("Event_Manager",array("global" => true, "namespace"=> "get_conversion"));
      } 
      return $this->event_manager;
      
    }

    public function get_namespace(){
        return $this->namespace;
    }

    public function set_namespace($namespace){
        $this->namespace = $namespace;
    }


}