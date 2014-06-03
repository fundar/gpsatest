<?php
if(class_exists("Gc_MessageBar_Remote_Action_Output_Json")){
    return;
}

class Gc_MessageBar_Remote_Action_Output_Json implements Gc_MessageBar_Remote_Action_Output_Interface{
    public function format($data){
        return json_encode($data);
    }
}