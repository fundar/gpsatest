<?php
// implement tinymce button

add_action('admin_init', 'nb_action_admin_init');	
function nb_action_admin_init() {
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
		return;

	if ( get_user_option('rich_editing') == 'true') {
		add_filter( 'mce_external_plugins', 'nb_filter_mce_plugin');
		add_filter( 'mce_buttons', 'nb_filter_mce_button');
	}
}
	
function nb_filter_mce_button( $buttons ) {
	array_push( $buttons, '|', 'nb_btn');
	return $buttons;
}

function nb_filter_mce_plugin( $plugins ) {
	if( (float)substr(get_bloginfo('version'), 0, 3) < 3.9) {
		$plugins['NewsBox'] = NB_URL . '/js/tinymce_btn_oldwp.js';
	} else {
		$plugins['NewsBox'] = NB_URL . '/js/tinymce_btn.js';	
	}
	return $plugins;
}




add_action('admin_footer', 'nb_editor_btn_content');
function nb_editor_btn_content() {
	global $current_screen;
	if(strpos($_SERVER['REQUEST_URI'], 'post.php') || strpos($_SERVER['REQUEST_URI'], 'post-new.php')) :
	?>
    
    <div id="nb-shortcode-form" style="display:none;">
    	<p>
            <label><?php _e('Select a box', 'nb_ml') ?></label><br/>
            <select name="nb_sc_type" id="nb_box_id" class="lcweb-chosen" data-placeholder="<?php _e('Select a box', 'nb_ml') ?> .." style="width: 90%;" autocomplete="off">
			  <?php
              $items = get_terms('nb_boxes', 'hide_empty=0');
			  foreach($items as $box) {
				echo '<option value="'.$box->term_id.'">'.$box->name.'</option>';  
			  }
              ?>
            </select>
        </p>
        <p>
        	<input type="button" id="nb-shortcode-submit" class="button-primary" value="<?php _e('Insert', 'nb_ml') ?>" name="submit" />
        </p>	
    </div>    
    <script src="<?php echo NB_URL; ?>/js/chosen/chosen.jquery.min.js" type="text/javascript"></script>
    
    <?php
	endif;
	return true;
}

