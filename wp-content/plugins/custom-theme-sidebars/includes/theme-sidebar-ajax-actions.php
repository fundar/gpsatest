<?php 
/**
 * Theme Sidebar AJAX Functions
 *
 * This file contains all of the AJAX functionality
 * that is used by this plugin.
 * 
 * @package 	WordPress
 * @subpackage 	Custom_Theme_Sidebars
 * @author 		Sunny Johal - Titanium Themes
 * @copyright 	Copyright (c) 2014, Titanium Themes
 * @version 	1.4
 * 
 */

/**
 * Add Item/Page Attachement to a Custom Sidebar - Ajax Function
 *
 * Checks WordPress nonce and upon successful validation
 * creates the html markup for a new sidebar item and 
 * returns it to the client.
 *
 * @link 	http://codex.wordpress.org/Function_Reference/check_ajax_referer 		check_ajax_referer()
 * @link 	http://codex.wordpress.org/Function_Reference/current_user_can 			current_user_can()
 * @link 	http://codex.wordpress.org/Function_Reference/wp_die 					wp_die()
 * @link 	http://codex.wordpress.org/Function_Reference/get_post 					get_post()
 * @link 	http://codex.wordpress.org/Function_Reference/apply_filters 			apply_filters()
 * @link 	http://codex.wordpress.org/Function_Reference/add_action 				add_action()
 *
 * @uses  class Master_Walker_Sidebar_Edit 		defined in includes/classes/class-master-walker-sidebar-edit.php
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_ajax_add_sidebar_item() {

	// Check admin nonce for security
	check_ajax_referer( 'master_add_sidebar_item', 'master_sidebar_settings_column_nonce' );

	// Make sure user has the required access level
	if ( ! current_user_can( 'edit_theme_options' ) )
		wp_die( -1 );

	// Get nav menu file 
	require_once ABSPATH . 'wp-admin/includes/nav-menu.php';

	// Variables to store output
	$output          = '';
	$menu_items_data = array();

	// Get sidebar item data
	foreach ( (array) $_POST['menu-item'] as $menu_item_data ) {
		
		if (
			! empty( $menu_item_data['menu-item-type'] ) &&
			'custom' != $menu_item_data['menu-item-type'] &&
			! empty( $menu_item_data['menu-item-object-id'] )
		) {
			switch( $menu_item_data['menu-item-type'] ) {
				case 'post_type' :
					$_object = get_post( $menu_item_data['menu-item-object-id'] );
					break;

				case 'taxonomy' :
					$_object = get_term( $menu_item_data['menu-item-object-id'], $menu_item_data['menu-item-object'] );
					break;
				
			}
			
			if ( $menu_item_data['menu-item-type'] == 'post_type' || $menu_item_data['menu-item-type'] == 'taxonomy') {
				$_menu_items = array_map( 'wp_setup_nav_menu_item', array( $_object ) );
				$_menu_item  = array_shift( $_menu_items );
				
				// Restore the missing menu item properties
				$menu_item_data['menu-item-description'] = $_menu_item->description;
			}			
		
		}

		$menu_items_data[] = $menu_item_data;
	}

	$item_ids = wp_save_nav_menu_items( 0, $menu_items_data );
	
	if ( is_wp_error( $item_ids ) ) {
		wp_die( 0 );
	}
		
	$menu_items = array();

	foreach ( (array) $item_ids as $menu_item_id ) {
		$menu_obj = get_post( $menu_item_id );
		if ( ! empty( $menu_obj->ID ) ) {
			$menu_obj        = wp_setup_nav_menu_item( $menu_obj );
			$menu_obj->label = $menu_obj->title; // don't show "(pending)" in ajax-added items
			$menu_items[]    = $menu_obj;
		}
	}

	$walker_class_name = apply_filters( 'master_edit_sidebar_walker', 'Master_Walker_Sidebar_Edit', $_POST['menu'] );

	if ( ! class_exists( $walker_class_name ) )
		wp_die( 0 );

	if ( ! empty( $menu_items ) ) {
		$args = array(
			'after'       => '',
			'before'      => '',
			'link_after'  => '',
			'link_before' => '',
			'walker'      => new $walker_class_name
		);

		$output .= walk_nav_menu_tree( $menu_items, 0, (object) $args );
		echo $output;
	}
	
	wp_die();
}
add_action( 'wp_ajax_master_add_sidebar_item', 'master_ajax_add_sidebar_item' );

/**
 * Create Sidebar Instance - Ajax Function
 * 
 * Checks WordPress nonce and upon successful validation
 * creates a new sidebar instance. This function then 
 * constructs a new ajax response and sends it back to the
 * client.
 *
 * @link http://codex.wordpress.org/Function_Reference/check_ajax_referer 		check_ajax_referer()
 * @link http://codex.wordpress.org/Function_Reference/current_user_can 		current_user_can()
 * @link http://codex.wordpress.org/Function_Reference/get_post_meta 			get_post_meta()
 * @link http://codex.wordpress.org/Function_Reference/wp_die 					wp_die()
 * @link http://codex.wordpress.org/Function_Reference/WP_Ajax_Response 		WP_Ajax_Response
 * @link http://codex.wordpress.org/Function_Reference/add_action 				add_action()
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_ajax_create_sidebar_instance() {
	
	// Check admin nonce for security
	check_ajax_referer( 'master_edit_sidebar_instance', 'master_sidebar_edit_sidebar_instance_nonce' );

	// Make sure user has the required access level
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( -1 );
	}

	// Get Sidebar Name
	if( isset( $_POST['sidebar_name'] ) ) {
		$sidebar_name =  $_POST['sidebar_name'];
	} else {
		$sidebar_name = __( 'Custom Sidebar', 'theme-translate' );
	}

	// Create the new sidebar and get the associated ID
	$new_sidebar = master_update_sidebar_instance( '0', '0', $sidebar_name );
	$new_sidebar_id = get_post_meta( $new_sidebar, 'sidebar_id', true );

	// Create array to hold additional xml data
	$supplimental_data = array(
		'new_sidebar_id'     => $new_sidebar_id
	);

	$data = array(
		'what'         => 'new_sidebar',
		'id'           => 1,
		'data'         => '',
		'supplemental' => $supplimental_data
	);

	
	// Create a new WP_Ajax_Response obj and send the request
	$x = new WP_Ajax_Response( $data );
	$x->send();

	wp_die();

}
add_action( 'wp_ajax_master_create_sidebar_instance', 'master_ajax_create_sidebar_instance' );

/**
 * Update Sidebar Instance - Ajax Function
 *
 * Checks WordPress nonce and upon successful validation
 * either updates a sidebar instance if it exists or 
 * creates a new sidebar instance if it doesn't exist.
 * 
 * @link http://codex.wordpress.org/Function_Reference/check_ajax_referer 		check_ajax_referer()
 * @link http://codex.wordpress.org/Function_Reference/current_user_can 		current_user_can()
 * @link http://codex.wordpress.org/Function_Reference/wp_die 					wp_die()
 * @link http://codex.wordpress.org/Function_Reference/WP_Ajax_Response 		WP_Ajax_Response
 * @link http://codex.wordpress.org/Function_Reference/add_action 				add_action()
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_ajax_update_sidebar_instance() {
	
	// Check admin nonce for security
	check_ajax_referer( 'master_edit_sidebar_instance', 'master_sidebar_edit_sidebar_instance_nonce' );

	// Make sure user has the required access level
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( -1 );
	}

	// Get sidebar attributes	
	$sidebar_id      = isset( $_POST[ 'sidebarId' ] ) ? (string) $_POST[ 'sidebarId' ] : (string) '0';
	$replacement_id  = isset( $_POST[ 'replacementId' ] ) ? (string) $_POST[ 'replacementId' ] : (string) '0';
	$sidebar_name    = isset( $_POST[ 'sidebarName' ] ) ? (string) $_POST[ 'sidebarName' ] : __( 'Custom Sidebar', 'theme-translate' );
	$description     = isset( $_POST[ 'description' ] ) ? (string) $_POST[ 'description' ] : '';
	$attachment_data = array();

	if ( isset( $_POST[ 'sidebar-items' ] ) ) {

		// Build the sidebar attachment data array
		foreach ( (array) $_POST[ 'sidebar-items' ] as $sidebar_item_data ) {

			// Array index position should have been set on the admin screen
			$i = (int) $sidebar_item_data[ 'menu-item-position' ];
			$attachment_data[$i] = $sidebar_item_data;
		}
	}

	// Update sidebar or create a new one if it doesn't exist
	$sidebar = master_update_sidebar_instance( $sidebar_id, $replacement_id, $sidebar_name, $description, $attachment_data );

	// Create array to hold additional xml data
	$supplimental_data = array(
		'sidebar_name'     => get_the_title( $sidebar )
	);

	$data = array(
		'what'         => 'sidebar',
		'id'           => 1,
		'data'         => '',
		'supplemental' => $supplimental_data
	);

	// Create a new WP_Ajax_Response obj and send the request
	$x = new WP_Ajax_Response( $data );
	$x->send();

	wp_die();

}
add_action( 'wp_ajax_master_update_sidebar_instance', 'master_ajax_update_sidebar_instance' );

/**
 * Delete Sidebar Instance - Ajax Function
 * 
 * Checks WordPress nonce and upon successful validation
 * it deletes the sidebar instance from the database.
 *
 * @link http://codex.wordpress.org/Function_Reference/check_ajax_referer 		check_ajax_referer()
 * @link http://codex.wordpress.org/Function_Reference/current_user_can 		current_user_can()
 * @link http://codex.wordpress.org/Function_Reference/wp_die 					wp_die()
 * @link http://codex.wordpress.org/Function_Reference/add_action 				add_action()
 * 
 * @uses master_delete_sidebar_instance() defined in includes/theme-sidebar-functions.php
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_ajax_delete_sidebar_instance() {

	// Check admin nonce for security
	check_ajax_referer( 'master_delete_sidebar_instance', 'master_sidebar_delete_sidebar_instance_nonce' );

	// Make sure user has the required access level
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( -1 );
	}
		
	if( isset( $_POST['sidebarId'] ) ) {
		master_delete_sidebar_instance( $_POST['sidebarId'] );
	}

	wp_die();

}
add_action( 'wp_ajax_master_delete_sidebar_instance', 'master_ajax_delete_sidebar_instance' );

/**
 * Delete All Sidebar Instances - Ajax Function
 * 
 * Checks WordPress nonce and upon successful validation
 * it deletes all sidebar instances from the database.
 *
 * @link http://codex.wordpress.org/Function_Reference/check_ajax_referer 		check_ajax_referer()
 * @link http://codex.wordpress.org/Function_Reference/current_user_can 		current_user_can()
 * @link http://codex.wordpress.org/Function_Reference/wp_die 					wp_die()
 * @link http://codex.wordpress.org/Function_Reference/add_action 				add_action()
 * 
 * @uses master_delete_all_sidebar_instances() defined in includes/theme-sidebar-functions.php
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_ajax_delete_all_sidebar_instances() {
	
	// Check admin nonce for security
	check_ajax_referer( 'master_delete_sidebar_instance', 'master_sidebar_delete_sidebar_instance_nonce' );

	// Make sure user has the required access level
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( -1 );
	}

	master_delete_all_sidebar_instances();
	
	wp_die();
}
add_action( 'wp_ajax_master_delete_all_sidebar_instances', 'master_ajax_delete_all_sidebar_instances' );


/**
 * Edit Sidebar Replacement - Ajax Function
 * 
 * Provides a quick way to only change the sidebar
 * replacement of a custom sidebar on the Manage
 * Sidebar Replacements Screen.
 *
 * @link http://codex.wordpress.org/Function_Reference/check_ajax_referer 		check_ajax_referer()
 * @link http://codex.wordpress.org/Function_Reference/current_user_can 		current_user_can()
 * @link http://codex.wordpress.org/Function_Reference/update_post_meta 		update_post_meta()
 * @link http://codex.wordpress.org/Function_Reference/wp_die 					wp_die()
 * @link http://codex.wordpress.org/Function_Reference/add_action 				add_action()
 *
 * @uses master_get_sidebar_instance() defined in includes/theme-sidebar-functions.php
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_ajax_edit_sidebar_replacement() {
	
	// Check admin nonce for security
	check_ajax_referer( 'master_edit_sidebar_instance', 'master_sidebar_edit_sidebar_instance_nonce' );

	// Make sure user has the required access level
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( -1 );
	}

	// Update sidebar replacement id
	if( isset( $_POST['sidebarId'] ) && isset( $_POST['replacementId'] ) ) {
		
		$sidebar_instance = master_get_sidebar_instance( $_POST['sidebarId'] );
		$replacement_id   = $_POST['replacementId'];

		if ( $sidebar_instance ) {
			update_post_meta( $sidebar_instance->ID, 'sidebar_replacement_id', $replacement_id );
		}
	}

	wp_die();
}
add_action( 'wp_ajax_master_edit_sidebar_replacement', 'master_ajax_edit_sidebar_replacement' );

/**
 * Quick Search 
 *
 * AJAX function that performs a search query based on
 * the user input that has been posted and returns a 
 * search results response.
 *
 * @link http://codex.wordpress.org/Function_Reference/current_user_can 		current_user_can()
 * @link http://codex.wordpress.org/Function_Reference/wp_die 					wp_die()
 * @link http://codex.wordpress.org/Function_Reference/add_action 				add_action()
 *
 * @uses master_sidebar_quick_search() defined in includes/theme-sidebar-functions.php
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_ajax_sidebar_quick_search() {
	
	// Make sure user has the required access level
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( -1 );
	}

	master_sidebar_quick_search( $_POST );
	wp_die();
}
add_action( 'wp_ajax_master_sidebar_quick_search', 'master_ajax_sidebar_quick_search' );

/**
 * AJAX Metabox Pagination
 *
 * Gets metabox information passed via AJAX and generates
 * the approriate metabox markup to replace on the clients
 * browser. Allows the user to paginate through each metabox
 * without refreshing the page. This function echos back the
 * html markup to the client admin page.
 * 
 * @link http://codex.wordpress.org/Function_Reference/current_user_can 		current_user_can()
 * @link http://codex.wordpress.org/Function_Reference/wp_die 					wp_die()
 * @link http://codex.wordpress.org/Function_Reference/get_post_types 			get_post_types()
 * @link http://codex.wordpress.org/Function_Reference/get_taxonomies 			get_taxonomies()
 * @link http://codex.wordpress.org/Function_Reference/add_action 				add_action()
 *
 * @uses master_sidebar_quick_search() defined in includes/theme-sidebar-functions.php
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_ajax_sidebar_get_metabox() {
	
	// Make sure user has the required access level
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( -1 );
	}


	if ( isset( $_POST['item-type'] ) && 'post_type' == $_POST['item-type'] ) {

		$type     = 'posttype';
		$callback = 'master_sidebar_item_post_type_meta_box';
		$items    = (array) get_post_types( array( 'show_in_nav_menus' => true ), 'object' );

	} elseif ( isset( $_POST['item-type'] ) && 'taxonomy' == $_POST['item-type'] ) {

		$type     = 'taxonomy';
		$callback = isset( $_POST['custom-item-type'] ) ? 'master_sidebar_render_category_posts_metabox' : 'master_sidebar_item_taxonomy_meta_box';
		$items    = (array) get_taxonomies( array( 'show_ui' => true ), 'object' );

	} elseif ( isset( $_POST['item-type'] ) && 'author_archive' == $_POST['item-type'] ) {
		$type     = 'author_archive';
		$callback = 'master_sidebar_render_author_meta_box';

		ob_start();
		call_user_func_array($callback, array(
			null,
			array(
				'callback' => $callback,
			)
		));		
		$markup = ob_get_clean();

		$replace_id = 'master-author-archive';

		// Return JSON data
		echo json_encode(array(
			'replace-id' => $replace_id,
			'markup'     => $markup,
		));

	}


	if ( ! empty( $_POST['item-object'] ) && isset( $items[$_POST['item-object']] ) ) {

		$item = apply_filters( 'master_sidebar_meta_box_object', $items[ $_POST['item-object'] ] );
		ob_start();
		call_user_func_array($callback, array(
			null,
			array(
				'id'       => 'master-add-' . $item->name,
				'title'    => $item->labels->name,
				'callback' => $callback,
				'args'     => $item,
			)
		));

		$markup = ob_get_clean();

		// Generate Replacement ID
		$replace_id = $type . '-' . $item->name;

		// Add suffix if custom posts in category metabox
		if ( isset( $_POST['custom-item-type'] ) ) {
			if ( 'category_posts' == $_POST['custom-item-type'] ) {
				$replace_id .= '-custom-category';
			}
		}

		// Return JSON data
		echo json_encode(array(
			'replace-id' => $replace_id,
			'markup'     => $markup,
		));
	}	

	// Die
	wp_die();
}
add_action( 'wp_ajax_master_sidebar_get_metabox', 'master_ajax_sidebar_get_metabox' );

