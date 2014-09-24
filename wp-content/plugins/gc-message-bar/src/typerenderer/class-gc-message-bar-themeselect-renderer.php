<?php
if(class_exists("Gc_MessageBar_Themeselect_Renderer")){
    return;
}

class Gc_MessageBar_Themeselect_Renderer extends Gc_MessageBar_Abstract_Renderer{
    protected $themes_repo;
    protected $all_options;
    protected $option;

    public function __construct(){

    }

    public function render($description) {
        $this->all_options = Gc_MessageBar_CF::create("Option_Repository_Factory")->get_instance()->get_namespace($description->get_namespace());
        $this->themes_repo = Gc_MessageBar_CF::create("Theme_Repository_Factory")->get_instance();
        $this->option = $description;
        ?>
        <div class="item definput <?php /* echo $this->get_descriptor_param("css_class"); */ ?>">
            <div class="label">
                <label><?php echo _('Predefined Color Sets'); ?>:</label>
            </div>
            <div class="edit">                
                <select id="<?php echo $description->get_name();?>_input" class="def" >
                    <option selected="selected" value="none"><?php echo __('Select a predefined set') ?></option>
                    <?php 
                    $themes = $this->themes_repo->get_all();
                    foreach ($themes as $id => $theme) : ?>
                        <option style="padding: 5px; color: <?php echo $theme->get_param('option_text_color'); ?>; font-weight: bold; background-color:<?php echo $theme->get_param('option_background_color'); ?>;" value="<?php echo $id;?>"><?php echo __($theme->get_name());  ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="desc">
                <label><?php echo _('Select from the predefined color sets with a click'); ?></label>
            </div>
            <div class="clear"></div>
        </div>
        <?php
        $this->render_js();
    }

    protected function render_js(){
        ?>
        <script type="text/javascript">
                var GC = {};
                GC.MessageBar = {
                    sets : {<?php $this->render_js_theme_sets(); ?>},
                    current : {<?php $this->render_js_current_theme(); ?>},
                    shadows : function() {
                        
                        if (jQuery("#<?php echo $this->option->get_namespace(); ?>text_shadow_input:checked").val() === "1") {
                            jQuery("#message_shadow_row").show();
                            jQuery("#button_shadow_row").show();
                            jQuery("#button_hover_shadow_row").show();
                        } else {
                            jQuery("#message_shadow_row").hide();
                            jQuery("#button_shadow_row").hide();
                            jQuery("#button_hover_shadow_row").hide();
                        }
                        jQuery("#<?php echo $this->option->get_namespace(); ?>text_shadow_input").change(function() {
                            if (jQuery("#<?php echo $this->option->get_namespace(); ?>text_shadow_input").is(':checked')) {
                                jQuery("#message_shadow_row").show();
                                jQuery("#button_shadow_row").show();
                                jQuery("#button_hover_shadow_row").show();
                            } else {
                                jQuery("#message_shadow_row").hide();
                                jQuery("#button_shadow_row").hide();
                                jQuery("#button_hover_shadow_row").hide();
                            }
                        });
                    },
                    changeColors : function (set) {
                        <?php 
                        $i=0;
                        $colors = $this->all_options->filter_options_by_type("color");
                        foreach($colors as $key => $color) { ?>
                            jQuery('#<?php echo $color->get_name(); ?>').wpColorPicker('color',set['<?php echo strtolower($key); ?>']);
                        <?php } ?>

                            if(jQuery("#<?php echo $this->option->get_namespace(); ?>gc_mailpoet_ex_input_background_color").length > 0){
                                jQuery('#<?php echo $this->option->get_namespace(); ?>gc_mailpoet_ex_input_background_color').wpColorPicker('color',set['<?php echo strtolower("gc_mailpoet_ex_input_background_color"); ?>']);
                            }
                            if(jQuery("#<?php echo $this->option->get_namespace(); ?>gc_mailpoet_ex_input_border_color").length > 0){
                                jQuery('#<?php echo $this->option->get_namespace(); ?>gc_mailpoet_ex_input_border_color').wpColorPicker('color',set['<?php echo strtolower("gc_mailpoet_ex_input_border_color"); ?>']);
                            }
                            if(jQuery("#<?php echo $this->option->get_namespace(); ?>gc_mailpoet_ex_input_text_color").length > 0){
                                jQuery('#<?php echo $this->option->get_namespace(); ?>gc_mailpoet_ex_input_text_color').wpColorPicker('color',set['<?php echo strtolower("gc_mailpoet_ex_input_text_color"); ?>']);
                            }
                            if(jQuery("#<?php echo $this->option->get_namespace(); ?>gc_mailpoet_ex_input_placeholder_color").length > 0){
                                jQuery('#<?php echo $this->option->get_namespace(); ?>gc_mailpoet_ex_input_placeholder_color').wpColorPicker('color',set['<?php echo strtolower("gc_mailpoet_ex_input_placeholder_color"); ?>']);
                            }


                            if(jQuery("#<?php echo $this->option->get_namespace(); ?>gc_mailpoet_ex_pro_input_background_color").length > 0){
                                jQuery('#<?php echo $this->option->get_namespace(); ?>gc_mailpoet_ex_pro_input_background_color').wpColorPicker('color',set['<?php echo strtolower("gc_mailpoet_ex_pro_input_background_color"); ?>']);
                            }
                            if(jQuery("#<?php echo $this->option->get_namespace(); ?>gc_mailpoet_ex_pro_input_border_color").length > 0){
                                jQuery('#<?php echo $this->option->get_namespace(); ?>gc_mailpoet_ex_pro_input_border_color').wpColorPicker('color',set['<?php echo strtolower("gc_mailpoet_ex_pro_input_border_color"); ?>']);
                            }
                            if(jQuery("#<?php echo $this->option->get_namespace(); ?>gc_mailpoet_ex_pro_input_text_color").length > 0){
                                jQuery('#<?php echo $this->option->get_namespace(); ?>gc_mailpoet_ex_pro_input_text_color').wpColorPicker('color',set['<?php echo strtolower("gc_mailpoet_ex_pro_input_text_color"); ?>']);
                            }
                            if(jQuery("#<?php echo $this->option->get_namespace(); ?>gc_mailpoet_ex_pro_input_placeholder_color").length > 0){
                                jQuery('#<?php echo $this->option->get_namespace(); ?>gc_mailpoet_ex_pro_input_placeholder_color').wpColorPicker('color',set['<?php echo strtolower("gc_mailpoet_ex_pro_input_placeholder_color"); ?>']);
                            }
                            if(jQuery("#<?php echo $this->option->get_namespace(); ?>gc_mailpoet_ex_pro_error_message_color").length > 0){
                                jQuery('#<?php echo $this->option->get_namespace(); ?>gc_mailpoet_ex_pro_error_message_color').wpColorPicker('color',set['<?php echo strtolower("gc_mailpoet_ex_pro_error_message_color"); ?>']);
                            }
                            if(jQuery("#<?php echo $this->option->get_namespace(); ?>gc_mailpoet_ex_pro_error_message_border_color").length > 0){
                                jQuery('#<?php echo $this->option->get_namespace(); ?>gc_mailpoet_ex_pro_error_message_border_color').wpColorPicker('color',set['<?php echo strtolower("gc_mailpoet_ex_pro_error_message_border_color"); ?>']);
                            }
                            if(jQuery("#<?php echo $this->option->get_namespace(); ?>gc_mailpoet_ex_pro_error_message_text_color").length > 0){
                                jQuery('#<?php echo $this->option->get_namespace(); ?>gc_mailpoet_ex_pro_error_message_text_color').wpColorPicker('color',set['<?php echo strtolower("gc_mailpoet_ex_pro_error_message_text_color"); ?>']);
                            }


                            if(jQuery("#<?php echo $this->option->get_namespace(); ?>text_shadow_input").val() !== set['text_shadow']){
                                jQuery("#<?php echo $this->option->get_namespace(); ?>text_shadow_a").trigger("click");
                            }


                            if(jQuery("#<?php echo $this->option->get_namespace(); ?>message_shadow_input").val() !== set['message_shadow']){
                                jQuery("#<?php echo $this->option->get_namespace(); ?>message_shadow_a").trigger("click");
                            }


                            if(jQuery("#<?php echo $this->option->get_namespace(); ?>button_shadow_input").val() !== set['button_shadow']){
                                jQuery("#<?php echo $this->option->get_namespace(); ?>button_shadow_a").trigger("click");
                            }

                            if(jQuery("#<?php echo $this->option->get_namespace(); ?>button_hover_shadow_input").val() !== set['button_hover_shadow']){
                                jQuery("#<?php echo $this->option->get_namespace(); ?>button_hover_shadow_a").trigger("click");
                            }

                            if(jQuery("#<?php echo $this->option->get_namespace(); ?>close_icon_color_input").val() !== set['close_icon_color']){
                                jQuery("#<?php echo $this->option->get_namespace(); ?>close_icon_color_a").trigger("click");
                            }

                            jQuery("#<?php echo $this->option->get_namespace(); ?>action_button_corner_radius").val(set['action_button_corner_radius']);
                            jQuery("#<?php echo $this->option->get_namespace(); ?>box_corner_radius").val(set['box_corner_radius']);
                            GC.MessageBar.shadows();
                    }

                }
                jQuery(document).ready(function(){
                    jQuery("#<?php echo $this->option->get_name(); ?>_input").change(function() {
                        GC.MessageBar.changeColors(GC.MessageBar.sets[jQuery("#<?php echo $this->option->get_name(); ?>_input option:selected").val()]);
                    });
                });
        </script>
        <?php
    }

    protected function render_js_theme_sets() {
        $themes = $this->themes_repo->get_all();
        $res = array();
        foreach ($themes as $id => $theme) {
            
            $options = $theme->get_options();
            $data = array();
            foreach ($options as $key => $value) {
                $data[] = $key.":'".$value."'";
            }
            $res[] = $id .":{".implode(',',$data)."}".PHP_EOL;
        }
        echo implode(',',$res);
    }

    protected function render_js_current_theme() {
        $themes = $this->themes_repo->get_all();
        if(!count($themes)){
            return;
        }
        $theme = array_pop($themes);
        $res = array();
            
        $option_names = $theme->get_options();

        $data = array();
        foreach ($option_names as $key => $value) {
            $data[] = $key.":'".$this->all_options->get($key)->get_value()."'";
        }
        echo implode(',',$data).PHP_EOL;
    }


}
