<?php
if(class_exists("Gc_MessageBar_Engine_Options_Group_Container_Renderer")){
    return;
}
class Gc_MessageBar_Options_Engine_Group_Container_Renderer extends Gc_MessageBar_Container_Renderer{
    protected $state_option;

    public function __construct($group_descriptor,$items,$namespace){
        $this->items = $items;
        $this->group_descriptor = $group_descriptor;
        $this->namespace = $namespace;
        $this->state_option =  Gc_MessageBar_CF::create_and_init(
          "Option",
          array(
            "namespace" => $this->namespace , 
            "id" => $this->group_descriptor->get_id()."_group" , 
            "default" => "close")
        );
        $this->state_option->load();

        $this->add_callback("before_render",array($this,"group_header_render"));
        $this->add_callback("after_render",array($this,"group_footer_render"));
        $this->add_callback("after_item_render",array($this,"after_item_render"));
    }

    public function group_header_render(){
        $group_id = $this->group_descriptor->get_id();
        ?>
        <a name="<?php echo $group_id; ?>"></a>
        <section class="enginepanel" id="<?php echo $group_id;?>" <?php if($this->state_option->get_value() == "close"){ echo 'style="display:none;"';}?>>
            <header>
                <a href="#" onClick="return Gc.Option_Group_On_Click(this,'<?php echo $group_id;?>');" class="opener <?php echo $this->state_option->get_value();?>" id="<?php echo $group_id.'_a';?>">
                    <span></span><h2><?php echo _($this->group_descriptor->get_title());?></h2>
                    <b><?php echo _('Hide panel');?></b>
                    <div class="clear"></div>
                </a>
            </header>
            <section class="engine_settings" id="<?php echo $group_id."_body";?>">
        <?php
    }

    public function group_footer_render(){ 
    $group_id = $this->group_descriptor->get_id();
        ?>
            </section>
            <footer id="engine_settings_footer">
                <input type="submit" value="<?php echo _('Save');?>" onClick="return Gc.Save_Button_On_Click('<?php echo $group_id;?>');" class="savebutton" name="<?php echo $this->namespace;?>submit"/>
            </footer>
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
               case "onoff":
                   $this->render_type_onoff($description);
               break;
               default:
                   $this->render_type_default($description);
           }
       }

       public function render_type_default($description){
            $renderer = Gc_MessageBar_CF::create("Text_Renderer");
            $renderer->render($description);
       }

       public function render_type_onoff($description){
        $renderer = Gc_MessageBar_CF::create("Button_Onoff_Renderer");
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
            $cnt->render(array());
        }
    }

}