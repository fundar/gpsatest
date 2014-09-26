<?php
// QUICK NEWS METABOXES

// register
function nb_register_metaboxes() {
	add_meta_box('mg_item_opt_box', __('Additional field', 'nb_ml'), 'nb_news_opt_box', 'nb_news', 'normal', 'default');
}
add_action('admin_init', 'nb_register_metaboxes');


//////////////////////////
// YOUTUBE / VIMEO + LINK

function nb_news_opt_box() {
	include_once(NB_DIR . '/functions.php');
	global $post;	
	?>
    <div class="lcwp_mainbox_meta">
      <table class="widefat lcwp_table lcwp_metabox_table">
        <tr>
          <td class="lcwp_field_td">
              <p style="margin-bottom: 5px;"><label><?php _e('News Link', 'nb_ml') ?></label></p>
			  <?php $val = trim((string)get_post_meta($post->ID, 'nb_link', true)); ?>
              <input type="text" name="nb_link" value="<?php echo nb_sanitize_input($val) ?>" style="width: 90%; max-width: 900px;" /><br/>
              <span class="info" style="padding-top: 7px;"><?php _e('Must be a valid URL', 'nb_ml') ?></span>
          </td>     
        </tr>
        <tr>
          <td class="lcwp_field_td">
              <p style="margin-bottom: 5px;"><label><?php _e('Attached media', 'nb_ml') ?></label></p>
			  <?php $val = trim((string)get_post_meta($post->ID, 'nb_media', true)); ?>
              <input type="text" name="nb_media" value="<?php echo nb_sanitize_input($val) ?>" style="width: 90%; max-width: 900px;" /><br/>
              <span class="info" style="padding-top: 7px;"><?php _e('Can be a <strong>youtube or vimeo</strong> video URL as well as a <strong>soundcloud embed</strong> URL', 'nb_ml') ?></span>
          </td>     
        </tr>
      </table>  
    </div>
    
    <?php // ////////////////////// ?>
    
    <?php // FIX ADMIN MENU FOR post-new.php PAGE ?>
    <script type="text/javascript">
	var curr_url = location.href;
	if(curr_url.indexOf('post-new.php') != -1) {
		var $parent = jQuery('#toplevel_page_nb_menu');
		$parent.find('li, a').removeClass('current');
		$parent.find('li:nth-child(3), li:nth-child(3) a').addClass('current');
	}
	</script>
    
    <?php // security nonce ?>
    <input type="hidden" name="nb_nonce" value="<?php echo wp_create_nonce('lcwp') ?>" />   
    <?php	
	return true;	
}



//////////////////////////
// SAVING METABOXES

function nb_news_meta_save($post_id) {
	if(isset($_POST['nb_nonce'])) {
		// authentication checks
		if (!wp_verify_nonce($_POST['nb_nonce'], 'lcwp')) return $post_id;

		// check user permissions
		if (!current_user_can('edit_post', $post_id)) return $post_id;
		
		$link = (isset($_POST['nb_link'])) ? trim($_POST['nb_link']) : '';
		update_post_meta($post_id, 'nb_link', $link);
		
		$media = (isset($_POST['nb_media'])) ? trim($_POST['nb_media']) : '';
		update_post_meta($post_id, 'nb_media', $media);
	}

    return $post_id;
}
add_action('save_post', 'nb_news_meta_save');

