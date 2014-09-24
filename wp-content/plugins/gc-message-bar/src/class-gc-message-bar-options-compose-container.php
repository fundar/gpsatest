<?php
if(class_exists("Gc_Message_Bar_Options_Compose_Container_Renderer")){
	return;
}
class Gc_Message_Bar_Options_Compose_Container_Renderer extends Gc_MessageBar_Options_Group_Container_Renderer{
    public function __construct($group_descriptor,$items,$namespace){
	   parent::__construct($group_descriptor,$items,$namespace);
    }

    public function render($gci) {
        $this->on_before_render();
        $this->custom_render($this->items);
        $this->on_after_render();
    }

	public function group_header_render(){ 
        $group_id = $this->group_descriptor->get_id();
        ?>
        <a name="<?php echo $group_id; ?>"></a>
        <section class="adminblock" id="<?php echo $group_id;?>">
            <section class="blockheader">
                <a href="#" onClick="return Gc.Option_Group_On_Click(this,'<?php echo $group_id;?>');" class="opener <?php echo $this->state_option->get_value();?>" id="<?php echo $group_id.'_a';?>">
                    <span></span><h2><?php echo $this->group_descriptor->get_title();?></h2>
                    <b><?php echo _('Show / hide panel');?></b>
                    <div class="clear"></div>
                </a>
            </section>
            <section class="blockcnt" id="<?php echo $group_id."_body";?>" <?php if($this->state_option->get_value() == "close"){ echo 'style="display:none;"';}?>>
        <?php
	}

   	public function custom_render($items){
        ?>
        <div class="composemessage">
            <div class="label">
                <label><?php echo $items['message_text']->get_text(); ?> <i>/ <?php echo $items['message_text']->get_description(); ?></i></label>
            </div>
            <div class="edit">
                <div class="msgtxt"><input type="text" id="<?php echo $items['message_text']->get_name(); ?>" name="post[<?php echo $items['message_text']->get_name(); ?>]" value="<?php echo $items['message_text']->get_value(); ?>" /></div>
                <div class="msgtxt_counter" id="<?php echo $items['message_text']->get_name(); ?>_counter">0</div>
                <div class="clear"></div>
            </div>
            <div class="leftcol">
                <div class="label">
                    <label><?php echo $items['action_url']->get_text(); ?> <i>/ <?php echo $items['action_url']->get_description(); ?></i></label>
                </div>
                <div class="edit">
                    <div class="urltxt"><input type="text" id="<?php echo $items['action_url']->get_name(); ?>" name="post[<?php echo $items['action_url']->get_name(); ?>]" value="<?php echo $items['action_url']->get_value(); ?>" /></div>
                    <div class="clear"></div>
                </div>
                <div class="editoption">
                    <div class="optiem">
                        <div class="chkbx"><input type="checkbox" class="chkbxdef" id="<?php echo $items['action_target']->get_name(); ?>" name="post[<?php echo $items['action_target']->get_name(); ?>]" <?php if ($items['action_target']->is_checked()) { echo "checked "; } ?> value="1"/></div>
                        <label><?php echo $items['action_target']->get_text(); ?></label>
                        <div class="clear"></div>
                    </div>
                    <div class="optiem">
                        <div class="chkbx"><input type="checkbox" class="chkbxdef"  id="<?php echo $items['action_nofollow']->get_name(); ?>" name="post[<?php echo $items['action_nofollow']->get_name(); ?>]" <?php if ($items['action_nofollow']->is_checked()) { echo "checked "; } ?> value="1"/></div>
                        <label><?php echo $items['action_nofollow']->get_text(); ?></label>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="rightcol">
                <div class="label">
                    <label><?php echo $items['action_button_text']->get_text(); ?> <i>/ <?php echo $items['action_button_text']->get_description(); ?></i></label>
                </div>
                <div class="edit">
                    <div class="btntxt"><input type="text" id="<?php echo $items['action_button_text']->get_name(); ?>" name="post[<?php echo $items['action_button_text']->get_name(); ?>]" value="<?php echo $items['action_button_text']->get_value(); ?>" /></div>
                    <div class="btntxt_counter" id="<?php echo $items['action_button_text']->get_name(); ?>_counter">0</div>
                    <div class="clear"></div>
                </div>
                <div class="desc"></div>
            </div>
            <div class="clear"></div>
            <div class="alldesc"><?php echo _('You may use these HTML tags: &lt;b&gt;<b>bold</b>&lt;/b&gt;, &lt;i&gt;<i>italic</i>&lt;/i&gt;, &lt;u&gt;<u>underline</u>&lt;/u&gt;, &lt;s&gt;<s>strike</s>&lt;/s&gt;<br/>In case the Message Text or Action Button Text field is empty, the Message or the Action Button will not appear'); ?></div>
        </div>
        <script type="text/javascript">
           jQuery(document).ready(function(){
                Gc.Input_Type_Text_Character_Counter('<?php echo $items['message_text']->get_name(); ?>','<?php echo $items['message_text']->get_name(); ?>_counter');
                Gc.Input_Type_Text_Character_Counter('<?php echo $items['action_button_text']->get_name(); ?>','<?php echo $items['action_button_text']->get_name(); ?>_counter');
                jQuery("#<?php echo $items['message_text']->get_name(); ?>").trigger("keyup");
                jQuery("#<?php echo $items['action_button_text']->get_name(); ?>").trigger("keyup");
            });
        </script>
        <?php
   	}

    public function render_item($key,$description,$counter) { }

}

