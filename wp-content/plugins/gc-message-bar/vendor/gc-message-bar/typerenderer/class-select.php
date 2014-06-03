<?php
if(class_exists("Gc_MessageBar_Select_Renderer")){
    return;
}

class Gc_MessageBar_Select_Renderer extends Gc_MessageBar_Abstract_Renderer{
    public function __construct(){

    }

    public function render($description) {
        ?>
        <div class="item definput <?php /* echo $this->get_descriptor_param("css_class"); */ ?>" id="<?php echo $description->get_unique_name(); ?>_cnt" <?php if(!$description->is_visible()){ echo 'style="display:none"';}?>>
            <div class="label">
                <label><?php echo $description->get_text(); ?></label>
            </div>
            <div class="edit chkbx">                
                <select id="<?php echo $description->get_unique_name(); ?>_input" name="post[<?php echo $description->get_unique_name(); ?>]" class="def">
                    <?php foreach($description->get_options() as $value => $text) : ?>
                    <option <?php if($description->get_value() == $value) echo "selected=\"selected\""; ?> value="<?php echo $value;?>"><?php echo __($text) ?></option>
                    <?php endforeach; ?>
                </select>
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
