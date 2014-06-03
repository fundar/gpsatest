<?php
if(class_exists("Gc_MessageBar_Button_Darklight_Renderer")){
    return;
}

class Gc_MessageBar_Button_Darkligth_Renderer extends Gc_MessageBar_Button_Twostate_Renderer{
    public function __construct(){
      $this->type ="darklight";
      $this->states = array(1 => "light",2 => "dark");
      $this->text = array(1=> '<span>Light</span><b></b><div class="clear"></div>',2=>'<b></b><span>Dark</span><div class="clear"></div>');
      $this->onclik_handler = "Gc.Darklight_Button_On_Click";
    }
}

