<?php
if(class_exists("Gc_MessageBar_Options_Subgroup_Container_Renderer")){
    return;
}
class Gc_MessageBar_Options_Subgroup_Container_Renderer extends Gc_MessageBar_Container_Renderer{
  protected $state_option;

    public function __construct($group_descriptor,$items,$namespace){
    $this->items = $items;
    $this->group_descriptor = $group_descriptor;
    $this->namespace = $namespace;
    $this->state_option =  Gc_MessageBar_CF::create_and_init("Option",array("namespace" => $this->namespace , "id" => $this->group_descriptor->get_id()."_group" , "default" => "open"));
    $this->state_option->load();
    $this->add_callback("before_render",array($this,"group_header_render"));
    $this->add_callback("after_render",array($this,"group_footer_render"));
    }

  public function get_state_depend_on_parent_option(){
      if($this->group_descriptor->get_state_depend_on_parent_option() == "hidden"){
        return "hideitemgroup";
      }
      return "";
  }

  public function get_css_action_from_parent_option_state(){
    return json_encode($this->group_descriptor->get_parent_option_state());
  }
    public function group_header_render(){ 
        ?>
        <div class="itemgroup <?php echo $this->group_descriptor->get_param("css_class");?> <?php echo $this->get_state_depend_on_parent_option();?>" id="<?php echo $this->group_descriptor->get_id(); ?>">
            <h3><?php echo _($this->group_descriptor->get_title());?></h3>
    <?php
    }

    public function group_footer_render(){ 
        ?>
        </div>
        <?php if($this->group_descriptor->has_parent_option_state()): ?>
        <script type="text/javascript">
          jQuery(document).ready(function(){
            <?php if($this->group_descriptor->has_options_visibility()): ?>
            jQuery('#<?php echo $this->group_descriptor->get_parent_option()->get_name();?>_input').change(function(value) {
              var visibility = {
              <?php 
              $tmp = array();
              foreach($this->group_descriptor->get_options_visibility() as $key => $visibility){
                    $block = $key.":{";
                    $tmpblock = array();
                    foreach($visibility as $id => $value){
                      $tmpblock[] = "$id:'$value'";
                    }
                    $block .= implode(',',$tmpblock);
                    $block .="}";
                    $tmp[] = $block;
              };
              echo implode(',',$tmp); ?>
                };
              var input = value.currentTarget;
              var item_id = '<?php echo $this->group_descriptor->get_id();?>';
              var item_group = jQuery('#'+item_id);
              var actual = visibility[input.value];
              if (actual === undefined) {
                item_group.addClass("hideitemgroup");
              } else {
                item_group.removeClass("hideitemgroup");
              }
              for(var element in actual){
                var domElement = jQuery('#<?php echo $this->namespace;?>'+element+'_cnt');
                if(domElement === undefined){
                  continue;
                }
                if(actual[element] === "show"){
                  domElement.show();
                }
                if(actual[element] === "hidden"){
                  domElement.hide();
                }
              }
            });
            <?php else: ?>
            jQuery('#<?php echo $this->group_descriptor->get_parent_option()->get_name();?>_input').change(function(value) {
              var input = value.currentTarget;
              var item_id = '<?php echo $this->group_descriptor->get_id();?>';
              var item_group = jQuery('#'+item_id);

              var action = <?php echo $this->get_css_action_from_parent_option_state();?>;
              if(action[input.value] != undefined && action[input.value] == "hidden"){
                  item_group.addClass("hideitemgroup");
              }else{
                  item_group.removeClass("hideitemgroup");

              }
            });
            <?php endif; ?>
          });
        </script>          
        <?php endif; ?>

        <?php
    }

       public function render_item($key,$description,$counter){
           if($this->group_descriptor->has_options_visibility()){
              if(!$this->group_descriptor->is_option_visible($description)){
                $description->set_visible(false);
              }
           }
           
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
    
       public function render_type_slider($description){
        $renderer = Gc_MessageBar_CF::create("Slider_Renderer");
        $renderer->render($description);
    }

}

