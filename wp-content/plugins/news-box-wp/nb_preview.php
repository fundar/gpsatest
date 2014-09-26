<?php
// overwrite the page content to display the box

add_filter('the_content', 'nb_manage_preview' );
function nb_manage_preview($the_content) {
	$target_page = (int)get_option('nb_preview_pag');
	$curr_page_id = (int)get_the_ID();
	
	if($target_page == $curr_page_id && is_user_logged_in() && isset($_REQUEST['nb_preview'])) {
			
		$content = do_shortcode('[newsbox id="'.(int)$_REQUEST['nb_preview'].'"]');
		return $content;
	}	
	
	else {return $the_content;}
}

?>