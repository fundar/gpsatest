<?php
if(class_exists("Gc_MessageBar_Slider_Renderer")){
    return;
}

class Gc_MessageBar_Slider_Renderer extends Gc_MessageBar_Abstract_Renderer{
    public function __construct(){

    }

    public function render($description) {
        ?>
        <div class="item slider <?php /* echo $this->get_descriptor_param("css_class"); */ ?>" id="<?php echo $description->get_unique_name(); ?>_cnt" <?php if(!$description->is_visible()){ echo 'style="display:none"';}?>>
            <div class="label">
                <label><?php echo $description->get_text(); ?></label>
            </div>
            <div class="edit">
                <div class="slider">
                    <div class="value">25px</div>
                    <div class="zone">
                        <div class="controller"></div>
                        <div class="bar"><div class="inside"></div></div>
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
