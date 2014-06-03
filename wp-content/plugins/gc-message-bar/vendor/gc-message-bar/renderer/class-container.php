<?php
if(class_exists("Gc_MessageBar_Container_Renderer")){
    return;
}
abstract class Gc_MessageBar_Container_Renderer extends Gc_MessageBar_Abstract_Renderer{
    protected $items = array();
    protected $item_render_counter = 0;
    protected $group_descriptor = "";
    protected $callbacks = array(
        "on_before_render" => array(),
        "on_after_render"  => array(),
        "on_before_item_render" => array(),
        "on_after_item_render"  => array(),
    );
    public function add_callback($name,$cb){
        $cb_name = "on_".$name;
        if(!isset($this->callbacks[$cb_name])){
            return;
        }
        $this->callbacks[$cb_name][] = $cb;
    }
    public function init_event_handler(){
      $em = $this->get_event_manager();
      $event_space = $this->eventprefix.'.'.$this->group_descriptor->get_id();
      $em->listen($event_space.".render_option",array($this,"on_render_option"));

    }
    public function on_render_option($event){
       $key = $event->get_param("key");
       $desc = $event->get_param("descriptor");
       $this->on_before_item_render($key,$desc,$this->item_render_counter);
       $this->render_item($key,$desc,$this->item_render_counter);
       $this->on_after_item_render($key,$desc,$this->item_render_counter);
    }
     public function render($gci) {
         $this->on_before_render();
         $this->item_render_counter = 0;
         $items = $this->items;
         $em = $this->get_event_manager();
         if(!is_array($this->items)){
            $items = $this->items->get_parameters();
         }
         foreach($items as $key => $desc){
             $event = new Gc_MessageBar_Event(array('key' => $key,'descriptor' => $desc,'group_decriptor' => $this->group_descriptor, 'namespace' => $this->namespace));
             $em->dispatch($this->eventprefix.'.'.$this->group_descriptor->get_id().".render_option",$event,true);
             $this->item_render_counter++;
         }

         $this->on_after_render();

     }

   abstract public function render_item($key,$description,$counter);

   public function on_before_render(){
       foreach ($this->callbacks['on_before_render'] as $cb) {
           call_user_func($cb);
       }
   }

       public function on_after_render(){
           foreach ($this->callbacks['on_after_render'] as $cb) {
               call_user_func($cb);
           }
       }


       public function on_before_item_render($key,$description,$counter){
           foreach ($this->callbacks['on_before_item_render'] as $cb) {
               call_user_func($cb,$key,$description,$counter);
           }
       }

       public function on_after_item_render($key,$description,$counter){
           foreach ($this->callbacks['on_after_item_render'] as $cb) {
               call_user_func($cb,$key,$description,$counter);
           }
       }


}