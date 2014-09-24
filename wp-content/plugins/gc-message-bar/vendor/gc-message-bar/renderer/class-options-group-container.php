<?php
if(class_exists("Gc_MessageBar_Options_Group_Container_Renderer")){
    return;
}
class Gc_MessageBar_Options_Group_Container_Renderer extends Gc_MessageBar_Container_Renderer{
    protected $state_option;

    public function __construct($group_descriptor,$items,$namespace){
        $this->items = $items;
        $this->group_descriptor = $group_descriptor;
        $this->namespace = $namespace;
        $this->initialize_state_option();
        $this->initialize_callback();
    }

    protected function initialize_state_option(){
        $this->state_option =  Gc_MessageBar_CF::create_and_init("Option",array("namespace" => $this->namespace , "id" => $this->group_descriptor->get_id()."_group" , "default" => "open"));
        $this->state_option->load();      
    }

    protected function initialize_callback(){
        $this->add_callback("before_render",array($this,"group_header_render"));
        $this->add_callback("after_render",array($this,"group_footer_render"));
        $this->add_callback("after_item_render",array($this,"after_item_render"));

    }
    public function group_header_render(){
        $group_id = $this->group_descriptor->get_id();
        ?>
    <a name="<?php echo $group_id; ?>"></a>
        <section class="adminblock" id="<?php echo $group_id;?>">
            <section class="blockheader">
                <a href="#" onClick="return Gc.Option_Group_On_Click(this,'<?php echo $group_id;?>');" class="opener <?php echo $this->state_option->get_value();?>" id="<?php echo $group_id.'_a';?>">
                    <span></span><h2><?php echo _($this->group_descriptor->get_title());?></h2>
                    <b><?php echo _('Show / hide panel');?></b>
                    <div class="clear"></div>
                </a>
            </section>
            <section class="blockcnt" id="<?php echo $group_id."_body";?>" <?php if($this->state_option->get_value() == "close"){ echo 'style="display:none;"';}?>>
        <?php
    }

    public function group_footer_render(){ 
    $group_id = $this->group_descriptor->get_id();
        ?>
            </section>
            <section class="blockfooter" id="<?php echo $group_id."_footer";?>" <?php if($this->state_option->get_value() == "close"){ echo 'style="display:none;"';}?>>
                <input type="submit" value="<?php echo _('Save');?>" onClick="return Gc.Save_Button_On_Click('<?php echo $group_id;?>');" class="savebutton" name="<?php echo $this->namespace;?>submit"/>
            </section>
        </section>
        <?php
    }

   public function render_item($key,$description,$counter){
      if($description->has_renderer()){
        $renderer_name = $description->get_renderer();
        $cnt = $renderer = Gc_MessageBar_CF::create($renderer_name);
        $cnt->render($description);
        return;
    }

      switch($description->get_type()){
               case "text":
                   $this->render_type_default($description);
               break;
               case "textarea":
                   $this->render_type_textarea($description);
               break;
               case "number":
                   $this->render_type_number($description);
               break;
               case "checkbox":
                   $this->render_type_checkbox($description);
               break;
               case "radio":
                   $this->render_type_radio($description);
               break;
               case "select":
                   $this->render_type_select($description);
               break;
               case "onoff":
                   $this->render_type_onoff($description);
               break;
               case "darklight":
                   $this->render_type_darklight($description);
               break;
               case "color":
                   $this->render_type_color($description);
               break;
               default:
                   $this->render_type_default($description);
           }
       }

       public function render_type_default($description){
         $renderer = Gc_MessageBar_CF::create("Text_Renderer");
       $renderer->render($description);
       }

       public function render_type_number($description){
       $renderer = Gc_MessageBar_CF::create("Number_Renderer");
       $renderer->render($description);
       }

       public function render_type_textarea($description){
       $renderer = Gc_MessageBar_CF::create("Textarea_Renderer");
       $renderer->render($description);
       }

       public function render_type_checkbox($description){
        $renderer = Gc_MessageBar_CF::create("Checkbox_Renderer");
        $renderer->render($description);
    }

    public function render_type_radio($description){
      $renderer = Gc_MessageBar_CF::create("Radio_Renderer");
        $renderer->render($description);
    }

       public function render_type_select($description){
        $renderer = Gc_MessageBar_CF::create("Select_Renderer");
        $renderer->render($description);
    }

    public function render_type_color($description){
        $renderer = Gc_MessageBar_CF::create("Color_Renderer");
        $renderer->render($description);
    }
       public function render_type_onoff($description){
        $renderer = Gc_MessageBar_CF::create("Button_Onoff_Renderer");
        $renderer->render($description);
       }
       public function render_type_darklight($description){
        $renderer = Gc_MessageBar_CF::create("Button_Darkligth_Renderer");
        $renderer->render($description);
       }

    public function render_type_fonttype_select($description){
        $renderer = Gc_MessageBar_CF::create("Fonttype_Select_Rnderer");
        $renderer->render($description);
    }

    
       public function render_type_slider($description){
           $renderer = Gc_MessageBar_CF::create("Slider_Renderer");
        $renderer->render($description);
       }


    public function after_item_render($key,$description,$counter){
        if($this->group_descriptor->has_sub_group($description->get_id())) {
            $container = $this->group_descriptor->get_sub_group($description->get_id());
            if($container->has_renderer()) {
                $renderer_name = $container->get_renderer();
            } else {
                $renderer_name = Gc_MessageBar_CF::get_class_name("Options_Subgroup_Container_Renderer");
            }
            $repository = Gc_MessageBar_CF::create("Option_Repository_Factory")->get_instance();
            $options = $repository->get_namespace($this->namespace);
            $container->set_parent_option($description);
            $cnt = new $renderer_name($container,$options->filter_options_by_group($container->get_option_group()),$this->namespace);
            $cnt->set_event_manager($this->event_manager);
            $cnt->set_event_prefix(GC_MESSAGE_BAR_NAME);
            $cnt->init_event_handler();
            $cnt->render(array());
        }
    }

}