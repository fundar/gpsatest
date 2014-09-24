<?php
if(class_exists("Gc_MessageBar_GroupFilter_Group_Renderer")){
    return;
}

class Gc_MessageBar_GroupFilter_Group_Renderer extends Gc_MessageBar_Abstract_Renderer{
	public $list = array();
	public $groups = array();
    public function __construct(){
		if(!is_plugin_active('groups'.DIRECTORY_SEPARATOR .'groups.php')) {
			return;
		}
    }
	
	public function get_editable_groups() {
		global $wpdb;
		$result = false;
		$group_table = _groups_get_tablename( 'group' );
		$groups = $wpdb->get_col("SELECT name FROM $group_table");
		return $groups;
	}
	
	public function is_checked($name) {
		return array_key_exists($name, $this->list);
	}
	protected function init_checkbox_data($description){
		$this->list = unserialize(htmlspecialchars_decode($description->get_value()));
		if(!$this->list or !is_array($this->list)){
			$this->list = array();
		}				
	}
	public function render($description) {
		global $GC_Message_Bar_Config;
		if(!is_plugin_active('groups'.DIRECTORY_SEPARATOR .'groups.php')) {
			?>
			<div class="noplugin">
				<b>GROUPS PLUGIN IS NOT AVAILABLE!</b><br/>
				This function requires Groups plugin. You can dowload it from here: <a href="<?php echo $GC_Message_Bar_Config['GCSERVICES']; ?>/gc-message-bar/groups" target="_blank">Groups</a>
			</div>
			<?php
			return false;
		}
		$this->init_checkbox_data($description);
		$this->groups = $this->get_editable_groups();
	  ?>
		<div class="item <?php echo $this->type; /* . " " . $this->get_descriptor_param("css_class"); */ ?>" id="<?php echo $description->get_name(); ?>_cnt" <?php if(!$description->is_visible()){ echo 'style="display:none"';}?>>
			<?php foreach($this->groups as $name): ?>
				<div class="label">
					<label><?php echo $name; ?>:</label>
				</div>
				<div class="edit">
					<input type="checkbox" value="1" name="post[<?php echo $description->get_name();?>][<?php echo strtolower($name); ?>]" class="chkbxdef" <?php echo ($this->is_checked(strtolower($name)) ? "checked" : ""); ?> />
				</div>         
				<div class="clear"></div>
			<?php endforeach; ?>
		</div>
		<?php 
	   }

}

