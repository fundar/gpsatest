<?php
	/*
	Plugin Name: Logooos Wordpress plugin (shared on wplocker.com)
	Plugin URI: http://codecanyon.net/user/husamrayan
	Description: Logooos Wordpress plugin.
	Version: 1.8.2
	Author: husamrayan
	*/
	
	
	/*==========================================================================
		enqueue
	==========================================================================*/
	
	function logooos_theme_enqueue() {
	
		wp_register_style( 'logooos-style', plugins_url('css/logos.css', __FILE__) );
		wp_enqueue_style( 'logooos-style' );
		
		wp_enqueue_script('jquery');
		
		wp_register_script( 'logooos_carouFredSel', plugins_url('js/jquery.carouFredSel-6.2.1.js', __FILE__) );
		wp_enqueue_script( 'logooos_carouFredSel' );
		
		wp_register_script( 'logooos_script', plugins_url('js/logos.js', __FILE__) );
		wp_enqueue_script( 'logooos_script' );
		
	}
	
	add_action( 'wp_enqueue_scripts', 'logooos_theme_enqueue' );
	
	function logooos_admin_enqueue() {
		
		wp_register_style( 'logooos-style', plugins_url('css/logos.css', __FILE__) );
		wp_enqueue_style( 'logooos-style' );
		
		wp_register_style( 'logooos-admin-style', plugins_url('css/admin.css', __FILE__) );
		wp_enqueue_style( 'logooos-admin-style' );
		
		wp_register_script( 'logooos_carouFredSel', plugins_url('js/jquery.carouFredSel-6.2.1.js', __FILE__) );
		wp_enqueue_script( 'logooos_carouFredSel' );
		
		wp_register_script( 'logooos-generate-shortcode', plugins_url('js/generate_shortcode.js', __FILE__) );
		wp_enqueue_script( 'logooos-generate-shortcode' );
		
		global $wp_version;
		
		 //If the WordPress version is greater than or equal to 3.5, then load the new WordPress color picker.
		if ($wp_version >= 3.5){
			//Both the necessary css and javascript have been registered already by WordPress, so all we have to do is load them with their handle.
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );

		}
		//If the WordPress version is less than 3.5 load the older farbtasic color picker.
		else {
			//As with wp-color-picker the necessary css and javascript have been registered already by WordPress, so all we have to do is load them with their handle.
			wp_enqueue_style( 'farbtastic' );
			wp_enqueue_script( 'farbtastic' );
		}
		
	}
	
	add_action( 'admin_enqueue_scripts', 'logooos_admin_enqueue' );

	
	
	/*==========================================================================
		Register logooo Post Type
	============================================================================*/
	
	include('inc/logooo_custom_post.php');
	
	/*==========================================================================
		Shortcode
	============================================================================*/
	
	include('inc/shortcode.php');
	
	
	/*==========================================================================
		Admin Menu
	============================================================================*/
	
	add_action('admin_menu', 'register_logooo_custom_submenu_page');

	function register_logooo_custom_submenu_page() {
		
		// Generate Shortcode Page
		add_submenu_page( 'edit.php?post_type=logooo', 'Generate shortcode', 'Generate shortcode', 'manage_options', 'logooos_generate_shortcode', 'logooos_generate_shortcode_callback' );
		
		// Restore old data
		$args =	array ( 'post_type' => 'myclients', 'posts_per_page' => -1, 'post_status' => 'any');
		$clients_query = new WP_Query( $args );
		
		if($clients_query->post_count > 0 && get_option('logooos_data_restored')=='') {
			add_submenu_page( 'edit.php?post_type=logooo', 'Restore Old Data', 'Restore Old Data', 'manage_options', 'logooos_restore_old_data', 'logooos_restore_old_data_callback' );
		}
		
	}
	
	// Generate Shortcode Page
	function logooos_generate_shortcode_callback() {
		
		include('inc/generate_shortcode/generate_shortcode.php');

	}
	
	// Restore old data
	function logooos_restore_old_data_callback() {
		
		include('inc/restore_old_data.php');

	}
	
	
	/*==========================================================================
		Shortcode Widget
	============================================================================*/
	
	include('inc/widget.php');
	


?>