<?php
if(class_exists("Gc_MessageBar_Button_Onoff_Renderer")){
    return;
}

class Gc_MessageBar_Button_Onoff_Renderer extends Gc_MessageBar_Button_Twostate_Renderer{
    public function __construct(){
      $this->type ="onoff";
      $this->states = array(1 => "on",2 => "off");
      $this->text = array(1=> '<span>ON</span><b></b><div class="clear"></div>',2=>'<b></b><span>OFF</span><div class="clear"></div>');
      $this->onclik_handler = "Gc.Onoff_Button_On_Click";
    }
}

