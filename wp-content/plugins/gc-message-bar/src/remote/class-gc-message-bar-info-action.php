<?php
if(class_exists("Gc_Message_Bar_Info_Action")){
	return;
}
class Gc_Message_Bar_Info_Action implements Gc_MessageBar_Remote_Action_Interface {
	public function __construct($namespace){
	}
	public function execute($request){ 
		return array("success" => true,"ver" => Gc_MessageBar_Util::get_version());
	}
}


