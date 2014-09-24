<?php
if(class_exists("Gc_Message_Bar_Style_Renderer")){
	return;
}
class Gc_Message_Bar_Style_Renderer 
extends 
	Gc_MessageBar_Abstract_Renderer
implements 
	Gc_MessageBar_Configurable_Interface
{
    protected $echo = true;
    protected $minify = false;
    protected $dev_mode = false;
    public function __construct($namespace){
        parent::__construct($namespace,null);
            

	}

	protected function get_options(){
		return Gc_MessageBar_CF::create("Option_Repository_Factory")->get_instance()->get_namespace($this->namespace);
	}
	
    public function configure(array $options){
        $this->configure_field($options,"echo");
        $this->configure_field($options,"minify");
        $this->configure_field($options,"dev_mode");
    }

    protected function configure_field(array $options,$field_name){
        if(isset($options[$field_name])){
            $this->$field_name = $options[$field_name];
        }
    }

	public function render($params ) {
	    $options = $this->get_options();
	    $config = Gc_MessageBar_Service_Locator::get(GC_MESSAGE_BAR_SL_CONFIG);
	    $css_content = "";
        if($dev_mode){
            $css_content .= "/*".PHP_EOL.__FILE__.PHP_EOL."*/".PHP_EOL;            
        }
	    $styleZindexDefault = '99998';
	    $styleZindex = $options->get('z_index')->get_value();
	    $event_manager = Gc_MessageBar_Service_Locator::get(GC_MESSAGE_BAR_SL_EVENT_MANAGER);
		$css_content = 
"	    
#gc_message_bar div,
#gc_message_bar span,
#gc_message_bar b,
#gc_message_bar i,
#gc_message_bar form,
#gc_message_bar label {
    line-height: 1;
    margin: 0;
    padding: 0;
    border: 0;
    outline: 0;
    font-size: 100%;
    vertical-align: baseline;
    background: transparent
}
#gc_message_bar a {
    margin: 0;
    padding: 0;
    font-size: 100%;
    vertical-align: baseline;
    background: transparent;
}
#gc_message_bar input,
#gc_message_bar select {
    vertical-align: middle;
}


/* CONTENT */
#gc_message_bar {
	white-space:nowrap;
    z-index: ".
    ( ( preg_match('/^([-]?[0-9]+)|(auto)/', $styleZindex) && ( intval($styleZindex) !== 0 ) ) ? $styleZindex : $styleZindexDefault ).";".
"
    position: fixed;
    left: 0px;
    width: 100%;
    height: 40px;
    line-height: 40px;
";
    if($options->get("bar_shadow")->get_value() == 1){
        $css_content .= $this->generate_box_shadow("0px 2px 4px","0,0,0,0.2");
	}
    $css_content .= $this->generate_gradient('background_color');
    $css_content .= 
"    
}
";
if($options->get("bar_shadow")->get_value() == 1){
$css_content .= 
"
.gc_message_bar_bottom {
".
$this->generate_box_shadow("0px -2px 4px","0,0,0,0.2","!important").
"
}
";
}
$css_content .= 
"
.gc_message_bar_top.add_adminbar {
    top: -17px;
}

.gc_message_bar_top_open.add_adminbar {
    top: 28px;
}

.gc_message_bar_top {
    top: -45px;
}

.gc_message_bar_top_open {
    top: 0px;
}

.gc_message_bar_bottom {
    bottom: -45px;
}

.gc_message_bar_bottom_open {
    bottom: 0px;
}


#gc_message_bar #gc_message_bar_message {
    margin: 0 10px;
    vertical-align: top;
    line-height: 30px;
    height: 30px;
    display: inline-block;
    color: ".$options->get('message_color')->get_value().";
".
$this->generate_shadow('message_shadow').
"
	font-family: ".$options->get('message_font')->get_value().";
	font-size: ".$options->get('message_font_size')->get_value().";
}

#gc_message_bar div {
    margin: 0 10px;
    text-decoration: none;
}
#gc_message_bar #gc_message_bar_button_a {
    display: inline-block;
    height: 30px;
    line-height: 30px;
    background: none !important;
}
#gc_message_bar #gc_message_bar_button #gc_message_bar_buttontext {
    width: 100%;
    height: 28px;
    line-height: 28px;
    display: inline-block;
	font-family: ".$options->get('action_button_font')->get_value().";
	font-size: ".$options->get('action_button_font_size')->get_value().";
}

#gc_message_bar #gc_message_bar_button {
    display: inline-block;
    text-align: center;
    height: 28px;
    line-height: 28px;
    padding: 0 10px;
    cursor: pointer;
    color: ".$options->get('action_button_text_color')->get_value().";";
    if ($options->get('action_button_shadow')->get_value() == '1') {
        $css_content .= $this->generate_box_shadow("2px 2px 3px","0,0,0,0.3");    	
    }
$css_content .="    
    -webkit-border-radius: ".$options->get('action_button_corner_radius')->get_value()."px;
    -moz-border-radius: ".$options->get('action_button_corner_radius')->get_value()."px;
    border-radius: ".$options->get('action_button_corner_radius')->get_value()."px;
    border: 1px solid ".$options->get('action_button_border_color')->get_value().";
".$this->generate_gradient('action_button_color')."
".$this->generate_shadow('button_shadow')."
}

#gc_message_bar #gc_message_bar_button:hover {
    color: ".$options->get('action_button_hover_text_color')->get_value().";
    border: 1px solid ".$options->get('action_button_hover_border_color')->get_value().";
".$this->generate_gradient('action_button_hover')."
".$this->generate_shadow('button_hover_shadow')."
}

#gc_message_bar #gc_message_bar_wrapper {
    margin: 5px 50px;
	height: 30px;
    line-height: 30px;
}
#gc_message_bar #gc_message_bar_content {
    position: relative;
	height: 30px;
    width: 100%;
}
/* CLASSES FOR THE 8 POSITIONS */
.gc_message_bar_contentSetting1 { text-align: left; }
.gc_message_bar_buttonSetting1 { }
.gc_message_bar_messageSetting1 { }

.gc_message_bar_contentSetting2 { text-align: center; }
.gc_message_bar_buttonSetting2 { }
.gc_message_bar_messageSetting2 { }

.gc_message_bar_contentSetting3 { text-align: right; }
.gc_message_bar_buttonSetting3 { }
.gc_message_bar_messageSetting3 { }

.gc_message_bar_contentSetting4 { text-align: left; }
.gc_message_bar_buttonSetting4 { float: right; }
.gc_message_bar_messageSetting4 { }

.gc_message_bar_contentSetting5 { text-align: left; }
.gc_message_bar_buttonSetting5 { }
.gc_message_bar_messageSetting5 { }

.gc_message_bar_contentSetting6 { text-align: center; }
.gc_message_bar_buttonSetting6 { }
.gc_message_bar_messageSetting6 { }

.gc_message_bar_contentSetting7 { text-align: right; }
.gc_message_bar_buttonSetting7 { }
.gc_message_bar_messageSetting7 { }

.gc_message_bar_contentSetting8 { text-align: right; }
.gc_message_bar_buttonSetting8 { float: left; }
.gc_message_bar_messageSetting8 { }



/* ADMIN */

#general-elements.gc_message_bar_admin input[type=\"text\"],
#general-elements.gc_message_bar_admin input[type=\"number\"],
#general-elements.gc_message_bar_admin select {
    width: 100%;
}
#gc_message_bar_admin .mainmessage {
    margin-bottom:40px;
}
#gc_message_bar_admin .form-table {
    margin-top:20px;
}
#gc_message_bar_admin .submit input {
    width: auto;
}

#gc_message_bar_admin textarea {
    min-width: 100%;
    max-width: 100%;
    resize:vertical;
}
#gc_message_bar_admin .clear {
    clear:both;
}
#gc_message_bar_message_text {
    width: 350px;
    height: 50px;
}
#gc_message_bar_admin .wrap {
    float: left;
}
#gc_message_bar_admin #gc_ad {
    float: right;
    margin: 15px;
}
#gc_message_bar_admin #gc_ad #gc_message_bar_banners {
    width: 270px; 
    height: 800px;    
}
#updateSettings{
    /*width: 700px;*/
}
#gc_message_bar_admin .submit {
    border-bottom:1px solid #eee;
    margin:15px 0 30px 0;
    padding:0 0 30px 230px;
}
#gc_message_bar_admin .submit.bottom {
    margin-bottom: 10px !important;
}
#gc_message_bar_admin .copy {
    margin-bottom: 30px;
}
#gc_message_bar_admin .gcmblink {
    font-weight: bold;
}
#gc_message_bar_admin .gc_icon32 {
    margin:0 8px 0 0px
}
#gc_message_bar_admin h2 {
    line-height:36px;
    height:36px;
    margin-bottom:0;
    font-size:24px;
    font-weight:normal !important;
}
#gc_message_bar_admin h3 {
    font-size:20px;
    font-weight:normal !important;
    margin-bottom:10px;
}
#gc_message_bar_open {
	z-index: 10000;
    position: fixed;
    top: -120px; /* -80px */
    left: 30px;
    height: 80px;
    ".$this->generate_gradient('background_color')."
    -webkit-border-bottom-right-radius: 5px;
    -webkit-border-bottom-left-radius: 5px;
    -moz-border-radius-bottomright: 5px;
    -moz-border-radius-bottomleft: 5px;
    border-bottom-right-radius: 5px;
    border-bottom-left-radius: 5px;
}
#gc_message_bar_open.adminbar.showopentop {
    top: -12px !important;
}
#gc_message_bar_open.showopentop {
    top: -40px !important;
}
#gc_message_bar_open.showopenbottom {
    bottom: -40px !important;
}
#gc_message_bar_open.right {
    left: auto;
    right: 30px;
}
#gc_message_bar_open.bottom {
    top: auto;
    bottom: -120px;
    -webkit-border-bottom-right-radius: 0px;
    -webkit-border-bottom-left-radius: 0px;
    -moz-border-radius-bottomright: 0px;
    -moz-border-radius-bottomleft: 0px;
    border-bottom-right-radius: 0px;
    border-bottom-left-radius: 0px;
    -webkit-border-top-right-radius: 5px;
    -webkit-border-top-left-radius: 5px;
    -moz-border-radius-topright: 5px;
    -moz-border-radius-topleft: 5px;
    border-top-right-radius: 5px;
    border-top-left-radius: 5px;
}
#gc_message_bar_open.top.adminbar { 
    top: -92px;
}
#gc_message_bar_open .icon {
	display: block;
    height: 25px;
    width: 22px;
    background-image: url('".plugins_url()."/gc-message-bar/images/arrow-light-down.png');
    background-repeat: no-repeat;
    background-position: center;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
	padding-right: 3px;
    opacity: 0.5;
    cursor: pointer;
	margin-left: 3px;
	margin-right: 3px;
}
#gc_message_bar_open.top .icon {
    margin-top: 47px;
}
#gc_message_bar_open.bottom .icon {
    margin-top: 8px;
}
#gc_message_bar_open.dark .icon {
    background-image: url('".plugins_url()."/gc-message-bar/images/arrow-dark-down.png');
}
#gc_message_bar_open.bottom .icon {
    background-image: url('".plugins_url()."/gc-message-bar/images/arrow-light-up.png');
}
#gc_message_bar_open.dark.bottom .icon {
    background-image: url('".plugins_url()."/gc-message-bar/images/arrow-dark-up.png');
}
#gc_message_bar_open .icon:hover {
    background-color: rgba(0,0,0,0.5);
    opacity: 0.7;
}
#gc_message_bar_open.dark .icon:hover {
    background-color: rgba(255,255,255,0.5);
}

#gc_message_bar_close {
    position: absolute;
    top: 50%;
    margin-top: -20px !important;
    left: -40px;
    height: 40px;
    width: 31px;
}
#gc_message_bar_close.right {
    left: auto;
    right: -40px;
}
#gc_message_bar_close.bottom {
}
#gc_message_bar_close.top.adminbar { 
}
#gc_message_bar_close .icon {
    display: block;
    height: 25px;
    width: 25px;
    margin-top: 7px;
    margin-left: 3px;
    background-image: url('".plugins_url()."/gc-message-bar/images/arrow-light-up.png');
    background-repeat: no-repeat;
    background-position: center;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    opacity: 0.5;
    cursor: pointer;
}
#gc_message_bar_close.dark .icon {
    background-image: url('".plugins_url()."/gc-message-bar/images/arrow-dark-up.png');
}
#gc_message_bar_close.bottom .icon {
    background-image: url('".plugins_url()."/gc-message-bar/images/arrow-light-down.png');
}
#gc_message_bar_close.dark.bottom .icon {
    background-image: url('".plugins_url()."/gc-message-bar/images/arrow-dark-down.png');
}
#gc_message_bar_close .icon:hover {
    background-color: rgba(0,0,0,0.5);
    opacity: 0.7;
}
#gc_message_bar_close.dark .icon:hover {
    background-color: rgba(255,255,255,0.5);
}
";
        $event = new Gc_MessageBar_Event(
        array(
            "options" => $options,
            "params"  => array(
                "echo" => $this->echo,
                "minify" => $this->minify
                )
            )
        );
        if($this->echo){
            echo $css_content;
    		$event_manager->dispatch(GC_MESSAGE_BAR_NAME.".render_style",$event);
        }else{
            $event_manager->dispatch(GC_MESSAGE_BAR_NAME.".render_style",$event);
            $result = $event->get_result();
            $css_content .= $result;
            if($this->minify){
                return $this->minify($css_content);
            }
            return $css_content;

        }

	}

    public function minify($css_content){
// Remove comments
$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css_content);
 
// Remove space after colons
$buffer = str_replace(': ', ':', $buffer);
 
// Remove whitespace
$buffer = str_replace(array("\r\n", "\r", "\n", "\t"), '', $buffer);

$buffer = preg_replace( '/\s+/', ' ', $buffer );
return $buffer;
    }

    public function generate_shadow($layout) {
        $options = $this->get_options();
        $layout = $options->get($layout)->get_value();
        if ( $options->get('text_shadow')->get_value() == "1") {
            switch($layout) {
                case "1":
                    return 
"    text-shadow: 1px 1px 1px rgba(255,255,255,.3);";                
                case "2":
                    return 
"    text-shadow: 1px 1px 1px rgba(0,0,0,.3);";
            }
        }
        return null;
    }
    
    public function generate_gradient($layout) {
        $options = $this->get_options();
            return 
"    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='" .$options->get($layout.'2')->get_value(). "', endColorstr='" .$options->get($layout)->get_value(). "',GradientType=0);
    background: " .$options->get($layout)->get_value(). ";
    background: -moz-linear-gradient(top, " .$options->get($layout.'2')->get_value(). " 0%, ". $options->get($layout)->get_value(). " 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, " .$options->get($layout.'2')->get_value(). "), color-stop(100%, ".$options->get($layout)->get_value()."));
    background: -o-linear-gradient(top, " .$options->get($layout.'2')->get_value(). " 0%, " .$options->get($layout)->get_value(). " 100%);
    background: -ms-linear-gradient(top, " .$options->get($layout.'2')->get_value(). " 0%, " .$options->get($layout)->get_value(). " 100%);
    background: linear-gradient(to bottom, " .$options->get($layout.'2')->get_value(). " 0%, " .$options->get($layout)->get_value(). " 100%);
    background: -webkit-linear-gradient(top, " .$options->get($layout.'2')->get_value(). " 0%, " .$options->get($layout)->get_value(). " 100%);";
    }

    public function generate_box_shadow($pixels,$color,$important = ""){
        $content = 
    "    -moz-box-shadow: ".$pixesls."  rgba(".$color.") ".$important.";
    -webkit-box-shadow: ".$pixesls."  rgba(".$color.") ".$important.";
    box-shadow: ".$pixesls."  rgba(".$color.") ".$important.";".PHP_EOL;
        if($this->dev_mode){
            $content = "    /* ".__FUNCTION__." start */".PHP_EOL.
            $content."    /* ".__FUNCTION__." end */".PHP_EOL;
        }
        return $content;

    }

}