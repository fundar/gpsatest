<?php
if(class_exists("Gc_MessageBar_Color_Renderer")){
    return;
}

class Gc_MessageBar_Color_Renderer extends Gc_MessageBar_Abstract_Renderer{
       public function __construct(){

       }

       public function render($description) {
           ?>
           <div class="item color <?php /* echo $this->get_descriptor_param("css_class"); */ ?>" id="<?php echo $description->get_unique_name(); ?>_cnt" <?php if(!$description->is_visible()){ echo 'style="display:none"';}?>>
            <div class="label">
                <label><?php echo $description->get_text(); ?></label>
            </div>
            <div class="edit">
                <input type="text" id="<?php echo $description->get_unique_name(); ?>" name="post[<?php echo $description->get_unique_name(); ?>]" class="def" value="<?php echo $description->get_value(); ?>" data-default-color="<?php echo $description->get_value(); ?>" />
            </div>
            <?php if ($description->get_description()) { ?>
            <div class="desc">
                <label><?php echo $description->get_description(); ?></label>
            </div>
            <?php } ?>
            <div class="clear"></div>
        </div>
        <script>
        jQuery(document).ready(function($){
            $('#<?php echo $description->get_unique_name(); ?>').wpColorPicker();
        });
        </script>
        <?php
       }

}

