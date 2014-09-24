<?php
if(class_exists("Gc_MessageBar_Metrix_Code_Renderer")){
    return;
}

class Gc_MessageBar_Metrix_Code_Renderer extends Gc_MessageBar_Abstract_Renderer{
    protected $configuration;
    public function __construct(){
        $this->configuration = Gc_MessageBar_Service_Locator::get("config");
    }

    public function render($description) {
    ?>
        <div class="item metrixcode <?php /* echo $this->get_descriptor_param("css_class"); */ ?>" id="<?php echo $description->get_name(); ?>_cnt">
            <div class="label">
                <label>MY.GetConversion Connect:</label>
            </div>
            <div class="edit">
            <?php if($description->get_value()): ?>
                <div class="connectlabel">Connected</div> 
                <div class="disconnectlink"><a href="javascript:void(0);" class="disconnect" title="Disconnect">Disconnect</a></div>
                <div class="clear"></div>
            <?php else: ?>
                <div class="connectbutton"><a href="javascript:void(0);" id="connect-to-mygc">Connect to MY.GetConversion</a></div>
                <div class="clear"></div>
            <?php endif; ?>
            </div>
            <div class="clear"></div>
        <?php if($description->get_value()): ?>
            <div class="label2">
                <label><?php echo $description->get_text(); ?></label>
            </div>
            <div class="metrixcodelabel">
                <?php echo $description->get_value(); ?>
            </div>
            <?php
            /*
            <input type="text" id="<?php echo $description->get_name(); ?>" name="post[<?php echo $description->get_name(); ?>]" class="def" value="<?php echo $description->get_value(); ?>" />
            */
            ?>
            <?php if ($description->get_description()) { ?>
            <div class="desc">
                <label><?php echo $description->get_description(); ?></label>
            </div>
            <?php } ?>
            <div class="clear"></div>
        <?php else: ?>    
            <div class="desc ">
                <label>Required FREE MY.GetConversion account. <a href="<?php echo $this->configuration['MYGC'];?>/signup/plugin" target="_blank">Don't have MY.GetConversion account yet?</a></label>
            </div>
            <div class="clear"></div>
        <?php endif; ?>
        </div>
        <script>
        jQuery(document).ready(function(){
            jQuery('#connect-to-mygc').click(function(){
                jQuery('body').popBox({
                    easing : 'easeInOutExpo',
                    content : 'iframe',
                    contentUrl : 'admin-ajax.php?action=gc-message-bar-mygc-signin',
                    useBeforeUnload : false
                });
            });
        });
        </script>
    <?php
    }

}

