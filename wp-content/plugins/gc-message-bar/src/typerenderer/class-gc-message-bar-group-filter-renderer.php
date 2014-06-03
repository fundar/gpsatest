<?php

if(class_exists("Gc_MessageBar_GroupFilter_Renderer")){
    return;
}

class Gc_MessageBar_GroupFilter_Renderer extends Gc_MessageBar_Abstract_Renderer{
    protected $type = "";
    protected $states = array();
    protected $text = array();
    protected $onclik_handler ="";

    public function __construct(){
        $this->type ="onoff";
        $this->states = array(1 => "on",2 => "off");
        $this->text = array(1=> '<span>ON</span><b></b><div class="clear"></div>',2=>'<b></b><span>OFF</span><div class="clear"></div>');
        $this->onclik_handler = "Gc.Onoff_Button_On_Click";
    }
    public function render($description) {
        global $GC_Message_Bar_Config;
        $on_click_handler = '';
        if(!empty($this->onclik_handler)){
            $on_click_handler = 'onClick="return '.$this->onclik_handler.'(this,\''.$description->get_name().'\');"';;
        }
        ?>
        <div class="item <?php echo $this->type; /* . " " . $this->get_descriptor_param("css_class"); */ ?>" id="<?php echo $description->get_name(); ?>_cnt" <?php if(!$description->is_visible()){ echo 'style="display:none"';}?>>
            <div class="label">
                <label><?php echo $description->get_text(); ?></label>
            </div>
            <div class="edit">
            <?php foreach($this->states as $id => $state){
                if ($description->get_value() == $id) { 
                    echo '<a href="#" class="'.$state.'" '.$on_click_handler.' id="'.$description->get_name().'_a">'.$this->text[$id].'</a>'.PHP_EOL;
                    echo '<input type="hidden" name="post['.$description->get_name().']" value="'.$id.'" id="'.$description->get_name().'_input"/>'; 
                }
            } ?>
                <div class="ad">
                    <div class="wooblock">
                        <a href="<?php echo $GC_Message_Bar_Config['GCSERVICES']; ?>/gc-message-bar/woothemes/woocommerce" target="_blank">
                            <i>Compatible with</i>
                            <b></b>
                            <div class="clear"></div>
                        </a>
                    </div>
                    <div class="woolinkblock">
                        <a href="<?php echo $GC_Message_Bar_Config['GCSERVICES']; ?>/gc-message-bar/woothemes/groups-for-woocommerce" target="_blank">How to start with Groups</a>
                    </div>
                </div>
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

