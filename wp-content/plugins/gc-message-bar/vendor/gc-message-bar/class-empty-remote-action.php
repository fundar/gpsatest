<?php
if(class_exists("Gc_MessageBar_Remote_Action_Empty")){
    return;
}

class Gc_MessageBar_Remote_Action_Empty implements Gc_MessageBar_Remote_Action_Interface {
    public function execute($request){
        return array("error" => true,"msg" => "cmd not found", "code" => 101);
    }
}