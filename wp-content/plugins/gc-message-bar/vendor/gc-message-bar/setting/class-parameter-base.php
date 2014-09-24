<?php
if(class_exists("Gc_MessageBar_Setting_Parameter_Base")){
    return;
}
class Gc_MessageBar_Setting_Parameter_Base 
implements
        Gc_MessageBar_Testable_Interface    
{
    /* #REGION $id */
    protected $id ="";
    public function set_id($id){
        $this->id = $id;
    }
    public function get_id(){
        return $this->id;
    }

    /* #REGION $namespace */
    protected $namespace ="";
    public function set_namespace($namespace){
        $this->namespace = $namespace;
    }
    public function get_namespace(){
        return $this->namespace;
    }
    public function get_unique_name(){
        return $this->get_namespace().$this->get_id();
    }
    public function get_name(){
        trigger_error("DEPRECATED: call get_unique_name()", E_USER_ERROR);
    }
    /* #ENDREGION */

    /* #REGION $type */
    protected $type ="text";
    public function set_type($type){
        $this->type = $type;
    }
    public function get_type(){
        return $this->type;
    }
    /* #ENDREGION */

    /* #REGION $group */
    protected $group ="";
    public function set_group($group){
        $this->group = $group;
    }
    public function get_group(){
        return $this->group;
    }
    /* #ENDREGION */

    /* #REGION $default */
    protected $default ="";
    public function set_default($default){
        $this->default = $default;
    }
    public function get_default(){
        return $this->default;
    }
    /* #ENDREGION */

    /* #REGION $formattable */
    protected $formattable = false;
    public function set_formattable($formattable){
        $this->formattable = $formattable;
    }
    public function is_formattable(){
        return $this->formattable;
    }
    /* @DEPRECATED */
    public function is_formatting_enabled(){
        return $this->formattable;
    }
    /* #ENDREGION */

    /* #REGION $title */
    protected $title ="";
    public function set_title($title){
        $this->title = $title;
    }
    public function get_title(){
        return $this->title;
    }
    /* @DEPRECATED */
    public function get_text(){
        return $this->title;
    }
    /* #ENDREGION */

    /* #REGION $instance */
    protected $instance = null;
    public function set_instance($instance){
        $this->instance = $instance;
    }
    public function get_instance(){
        return $this->instance;
    }
    public function is_instance(){
        return isset($this->instance);
    }
    /* #ENDREGION */


    /* #REGION $options */
    protected $options = array();
    public function set_options($options){
        $this->options = $options;
    }
    public function get_options(){
        return $this->options;
    }
    /* #ENDREGION */

    /* #REGION $description */
    protected $description ="";
    public function set_description($description){
        $this->description = $description;
    }
    public function get_description(){
        return $this->description;
    }
    /* #ENDREGION */

    /* #REGION $visible */
    protected $visible = false;
    public function set_visible($visible){
        $this->visible = $visible;
    }
    public function is_visible(){
        return $this->visible;
    }
    /* #ENDREGION */

    /* #REGION $permanent */
    protected $permanent = true;
    public function set_permanent($permanent){
        $this->permanent = $permanent;
    }
    public function is_permanent(){
        return $this->permanent;
    }
    /* #ENDREGION */

    /* #REGION $renderer */
    protected $renderer = "";
    public function set_renderer($renderer){
        $this->renderer = $renderer;
    }
    public function get_renderer(){
        return $this->renderer;
    }
    public function has_renderer(){
        return (empty($this->renderer) ? false : true);
    }
    /* #ENDREGION */

    /* #REGION $testable */
    protected $testable = true;
    public function set_testable($testable){
        $this->testable = $testable;
    }
    public function is_testable(){
        return $this->testable;
    }
    /* #ENDREGION */


    /* #REGION $value */
    protected $value = array(
        self::DEF_VARIANT_NAME => null
    );
    public function set_value($value){
        $this->value[$this->get_variant()] = $value;
    }
    public function get_value(){
        return $this->get_variant_value($this->get_variant());
    }
    public function set_raw_value(array $value){
        $this->value = $value;
    }
    public function get_raw_value(){
        return $this->value;
    }
    public function set_checked($value){
        if($value){
            $this->set_value("1");
        } else {
            $this->set_value("2");

        }
    }

    /* #ENDREGION */

    /* #REGION Gc_MessageBar_Testable_Interface */
    /* #REGION $variant */
    const DEF_VARIANT_NAME = "default";
    protected $variant = self::DEF_VARIANT_NAME;
    public function set_variant($variant){
        $this->variant = $variant;
    }
    public function get_variant(){
        return $this->variant;

    }
    /* #ENDREGION */
    public function count_actual_variant(){
        return count($this->value) - 1;
    }
    public function is_under_testing(){
        return (count($this->value) > 1 ? true : false);
    }
    public function get_control_value(){
        return $this->get_variant_value(self::DEF_VARIANT_NAME);
    }
    public function get_variant_value($variant){
        if(!isset($this->value[$variant])){
            return $this->get_default();
        }

        $value = $this->value[$variant];
        if(is_array($value)){
            return $value;
        }
        return isset($value) ? stripslashes($value) : $this->get_default();

    }

    /* #ENDREGION */
}
