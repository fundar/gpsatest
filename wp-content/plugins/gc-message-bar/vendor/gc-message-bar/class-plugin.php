<?php
if(class_exists("Gc_MessageBar_Plugin")){
    return;
}

class Gc_MessageBar_Plugin{
    protected $admin_controlers = array();
    protected $controlers = array();
    public function __construct(){

    }

    public function add_admin_controller($controller){
        if(!$this->is_type_controller($controller)){
            return;
        }
        $this->admin_controlers[] = $controller;    
        return $this;
    }

    public function add_controller($controller){
        if(!$this->is_type_controller($controller)){
            return;
        }
        $this->controlers[] = $controller;    
        return $this;
    }


    public function run(){
        if(is_admin()){
            $this->run_admin();
        } else {
            $this->run_public();
        }

    }

    public function run_admin(){
        $controllers = $this->admin_controlers;
        $this->initialize($controllers);
        $this->initialize_hooks($controllers);
        $this->handle_request($controllers);
    }

    public function run_public(){
        $controllers = $this->controlers;
        $this->initialize($controllers);
        $this->initialize_hooks($controllers);
        $this->handle_request($controllers);
    }

    public function initialize($controllers){
        foreach ($controllers as $controller) {
            $controller->initialize();
        }
    }

    public function initialize_hooks($controllers){
        foreach ($controllers as $controller) {
            $controller->initialize_hooks();
        }
    }

    public function handle_request($controllers){
        foreach ($controllers as $controller) {
            $controller->handle_request();
        }
    }

    protected function is_type_controller($controller){
        if(!method_exists($controller, "initialize")) {
            return false;
        }
        if(!method_exists($controller, "initialize_hooks")) {
            return false;
        }
        if(!method_exists($controller, "handle_request")) {
            return false;
        }
        return true;
    }


}