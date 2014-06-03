<?php
/**
 * 
 * eventon addons class
 *
 * @author 		AJDE
 * @category 	Admin
 * @package 	EventON/Classes
 * @version     0.1
 */
 
class evo_addons{
	
	// Add Addon to the list
	public function add_to_eventon_addons_list($args){	
		
		$eventon_addons_opt = get_option('eventon_addons');
		
		
		$eventon_addons_ar[$args['slug']]=$args;
		if(is_array($eventon_addons_opt)){
			$eventon_addons_new_ar = array_merge($eventon_addons_opt, $eventon_addons_ar );
		}else{
			$eventon_addons_new_ar = $eventon_addons_ar;
		}
		
		update_option('eventon_addons',$eventon_addons_new_ar);
		
		
	}

	public function remove_from_eventon_addon_list($slug){
		$eventon_addons_opt = get_option('eventon_addons');
			
		if(is_array($eventon_addons_opt) && array_key_exists($slug, $eventon_addons_opt)){
			foreach($eventon_addons_opt as $addon_name=>$addon_ar){
				
				if($addon_name==$slug){
					unset($eventon_addons_opt[$addon_name]);
				}
			}
		}
		update_option('eventon_addons',$eventon_addons_opt);
	}

	/**
	 * update a field for addon
	 */
	public function eventon_update_addon_field($addon_name, $field_name, $new_value){
		$eventon_addons_opt = get_option('eventon_addons');
		
		$newarray = array();
		
		// the array that contain addon details in array
		$addon_array = $eventon_addons_opt[$addon_name];
		
		if(is_array($addon_array)){
			foreach($addon_array as $field=>$val){
				if($field==$field_name){ 
					$val=$new_value;
				}
				$newarray[$field]=$val;
			}
			$new_ar[$addon_name] = $newarray;
			
			$merged=array_merge($eventon_addons_opt,$new_ar);
			
			
			update_option('eventon_addons',$merged);
		}
	}

	
}
?>