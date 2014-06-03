<?php

/**
 * FILE: vibeimport.php 
 * Author: Mr.Vibe 
 * Credits: www.VibeThemes.com
 * Project: WPLMS
 */

function vibe_import($file){

if ( (is_plugin_active('vibe-wordpress-importer/wordpress-importer.php')) ) {    
    
    
require_once ABSPATH . 'wp-admin/includes/import.php';
$file_path = VIBE_PATH. "/sampledata/$file.xml";

	if( !class_exists('WP_Import') )
		require_once (ABSPATH . 'wp-content/plugins/vibe-wordpress-importer/wordpress-importer.php');
			if( class_exists('WP_Import') )
	{
     
		if( file_exists($file_path) )
		{

			$WP_Import = new WP_Import();

			if ( ! function_exists ( 'wp_insert_category' ) )
				include ( ABSPATH . 'wp-admin/includes/taxonomy.php' );
			if ( ! function_exists ( 'post_exists' ) )
				include ( ABSPATH . 'wp-admin/includes/post.php' );
			if ( ! function_exists ( 'comment_exists' ) )
				include ( ABSPATH . 'wp-admin/includes/comment.php' );

			//ob_start();

				$WP_Import->fetch_attachments = true;
				$WP_Import->allow_fetch_attachments();

				$WP_Import->import( $file_path );

			//ob_end_clean();
                         _e('Import Complete !','vibe');   
		}
		else
		{

			echo __("Unable to locate Sample Data file.", 'vibe') ;

		}

	}
	else
	{

		echo __("Couldn't install the test demo data as we were unable to use the WP_Import class.", THEME_DOMAIN);

	}
	}else{
               if ( (is_plugin_active('wordpress-importer/wordpress-importer.php')) ) {    
                   _e("Please deactivate & delete WordPress importer plugin and install Vibe WordPress Importer Plugin.", 'vibe') ;
               }else
		 _e("Please install Vibe WordPress Importer Plugin.", 'vibe') ;
		}
	
	
}
?>