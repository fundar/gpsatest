<?php 

	if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
		exit();
	
	
	global $wpdb;
	if (function_exists('is_multisite') && is_multisite()) 
	{
		
		// check if it is a network activation - if so, run the activation function for each blog id
		if (isset($_GET['networkwide']) && ($_GET['networkwide'] == 1)) 
		{
	        
			$old_blog = $wpdb->blogid;
			
			// Get all blog ids
			$blogids = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs"));
			foreach ($blogids as $blog_id) {

				switch_to_blog($blog_id);
				$carousels_table = $wpdb->prefix . 'touchcarousels';
				$wpdb->query( "DROP TABLE $carousels_table" );
		
			}
			
			switch_to_blog($old_blog);
			return;

		}	
	
	} 
		
	$carousels_table = $wpdb->prefix . 'touchcarousels';
	$wpdb->query( "DROP TABLE $carousels_table" );
	

?>