<?php
if(class_exists("Gc_MessageBar_RoleFilter_Group_Renderer")){
    return;
}

class Gc_MessageBar_RoleFilter_Group_Renderer extends Gc_MessageBar_Abstract_Renderer{
	public $list = array();
    public function __construct(){

    }
	
	public function get_editable_roles() {
		global $wp_roles;

		$all_roles = $wp_roles->roles;
		$editable_roles = apply_filters('editable_roles', $all_roles);
		unset($editable_roles['administrator']);
		return $editable_roles;
	}
	
	public function is_checked($name) {
		return in_array($name, $this->list);
	}
	protected function init_checkbox_data($description){
		$this->list = unserialize(htmlspecialchars_decode($description->get_value()));
		if(!$this->list){
			$this->list = array();
		}
		$this->list = array_keys($this->list);

	}
	public function render($description) {
		$this->init_checkbox_data($description);
	  ?>
		<div class="item <?php echo $this->type; /* . " " . $this->get_descriptor_param("css_class"); */ ?>" id="<?php echo $description->get_name(); ?>_cnt" <?php if(!$description->is_visible()){ echo 'style="display:none"';}?>>
				<div class="label">
					<label>Administrator:</label>
				</div>
				<div class="edit">
					<input type="checkbox" value="1" name="post[<?php echo $description->get_name();?>][administrator]" class="chkbxdef" disabled="disabled" checked="checked" onClick="return false;" />
					<input type="hidden" value="1" name="post[<?php echo $description->get_name();?>][administrator]" />
				</div>         
				<div class="clear"></div>
			<?php foreach($this->get_editable_roles() as $role_key => $role): ?>
				<div class="label">
					<label><?php echo $role['name']; ?>:</label>
				</div>
				<div class="edit">
					<input type="checkbox" value="1" name="post[<?php echo $description->get_name();?>][<?php echo $role_key; ?>]" class="chkbxdef" <?php echo ($this->is_checked($role_key) ? "checked" : ""); ?> />
				</div>         
				<div class="clear"></div>
			<?php endforeach; ?>
		</div>
		<?php 
	   }

}

