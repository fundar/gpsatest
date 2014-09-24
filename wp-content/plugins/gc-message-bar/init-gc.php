<?php
require_once(GC_MESSAGE_BAR_ABS_LIB_PATH."init.php");
Gc_MessageBar_CF::set_prefix("Gc_MessageBar");

global $GC_Message_Bar_Config;
Gc_MessageBar_Service_Locator::set(GC_MESSAGE_BAR_SL_CONFIG,$GC_Message_Bar_Config);
global $GC_Mygetconversion_Worker;
if(!isset($GC_Mygetconversion_Worker)){
	$GC_Mygetconversion_Worker = array("run" => 0,"plugins" => array());
}
$GC_Mygetconversion_Worker["plugins"][GC_MESSAGE_BAR_TYPE] = true;
$event_manager = Gc_MessageBar_CF::create_and_init("Event_Manager",array("global" => true, "namespace"=> "get_conversion"));
Gc_MessageBar_Service_Locator::set(GC_MESSAGE_BAR_SL_EVENT_MANAGER,$event_manager);

