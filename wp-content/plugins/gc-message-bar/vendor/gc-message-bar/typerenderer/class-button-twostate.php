<?php

if(class_exists("Gc_MessageBar_Button_Twostate_Renderer")){
    return;
}

class Gc_MessageBar_Button_Twostate_Renderer extends Gc_MessageBar_Abstract_Renderer{
       protected $type = "";
    protected $states = array();
    protected $text = array();
    protected $onclik_handler ="";

       public function render($description) {
      $on_click_handler = '';
      if(!empty($this->onclik_handler)){
        $on_click_handler = 'onClick="return '.$this->onclik_handler.'(this,\''.$description->get_unique_name().'\');"';;
      }
      
      ?>
        <div class="item <?php echo $this->type; /* . " " . $this->get_descriptor_param("css_class"); */ ?>" id="<?php echo $description->get_unique_name(); ?>_cnt" <?php if(!$description->is_visible()){ echo 'style="display:none"';}?>>
            <div class="label">
                <label><?php echo $description->get_text(); ?></label>
            </div>
            <div class="edit">
            <?php foreach($this->states as $id => $state){
                if ($description->get_value() == $id) { 
                    echo '<a href="#" class="'.$state.'" '.$on_click_handler.' id="'.$description->get_unique_name().'_a">'.$this->text[$id].'</a>'.PHP_EOL;
                    echo '<input type="hidden" name="post['.$description->get_unique_name().']" value="'.$id.'" id="'.$description->get_unique_name().'_input"/>'; 
                }
            } ?>
            </div> 
            <?php if ($description->get_description()) { ?>
            <div class="desc">
                <label><?php echo $description->get_description(); ?></label>
            </div>
            <?php } ?>            
            <div class="clear"></div>
        </div>
        <?php 
       }

}

