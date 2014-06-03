<?php 
/**
 * Theme Sidebar Functions
 *
 * This file is responsible for creating custom sidebar
 * instances and returning information about them.
 * 
 * @package 	WordPress
 * @subpackage 	Custom_Theme_Sidebars
 * @author 		Sunny Johal - Titanium Themes
 * @copyright 	Copyright (c) 2014, Titanium Themes
 * @version 	1.4
 * 
 */

/**
 * CUSTOM SIDEBAR POSTTYPE FUNCTIONS
 * =================================
 */

/**
 * Register Custom Sidebar Posttype
 * 
 * Register the sidebar posttype in the same fashion that
 * WordPress registers nav-menus internally. This will be used
 * to store any sidebar instances. Created when the 'init' action
 * is fired.
 *
 * @link 	http://codex.wordpress.org/Function_Reference/register_post_type 	register_post_type()
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_register_custom_sidebar_post_type() {
    register_post_type( 'sidebar_instance', array(
        'labels' => array(
            'name'          => __( 'Custom Sidebar Instances', 'theme-translate' ),
            'singular_name' => __( 'Custom Sidebar Instance',  'theme-translate' )
        ),
        'public'           => false,
        'hierarchical'     => false,
        'rewrite'          => false,
        'delete_with_user' => false,
        'query_var'        => false 
    ) );
}
add_action( 'init', 'master_register_custom_sidebar_post_type', 0 ); // highest priority

/**
 * Add Custom Sidebar Instance
 * 
 * Create a post for the 'sidebar_instance' posttype which 
 * will use the custom post meta WordPress functionality to store 
 * all of the necessary attributes for each custom sidebar. 
 * Note: The sidebar_id is different to the actual post id for each 
 * sidebar instance.
 *
 * @link http://codex.wordpress.org/Function_Reference/WP_Query             WP_Query()
 * @link http://codex.wordpress.org/Function_Reference/wp_insert_post 		wp_insert_post()
 * @link http://codex.wordpress.org/Function_Reference/update_post_meta 	update_post_meta()
 * @link http://codex.wordpress.org/Function_Reference/get_post_meta        get_post_meta()
 * @link http://codex.wordpress.org/Function_Reference/the_post             the_post()
 * @link http://codex.wordpress.org/Function_Reference/get_the_ID           get_the_ID()
 *
 * @uses global $wp_registered_sidebars
 * 
 * @param  string $post_title     The name for this custom sidebar item.
 * @param  string $replacement_id The ID for the default theme sidebar we wish to replace.
 * @param  string $description    The description text for this custom sidebar.
 * 
 * @return $post  The ID of the post if the post is successfully added to the database or 0 on failure.
 *
 * @since 1.0
 * @version 1.4
 *
 */
function master_add_sidebar_instance( $post_title, $replacement_id, $description = '', $sidebar_attachment_data = array() ) {
	
	global $wp_registered_sidebars;

	// Generate ID and make sure its unique
	$sidebar_count  = rand( 1, 100 );
	$sidebar_id     = 'custom-sidebar-' . $sidebar_count;

	// Generate an array of existing sidebar ids and names
	$existing_sidebar_ids   = array();
	$existing_sidebar_names = array();
	$sidebar_id_exists      = true;
	$sidebar_name_exists    = true;
	
	$params = array(
		'post_type'      => 'sidebar_instance',
		'posts_per_page' => -1
	);
	$query = new WP_Query( $params );

	while( $query->have_posts() ) {
		$query->the_post();
		$existing_sidebar_ids[]   = get_post_meta( get_the_ID(), 'sidebar_id', true );
		$existing_sidebar_names[] = get_the_title();
	}
	
	// Make sure the ID doesn't already exist
	while ( $sidebar_id_exists ) {
		if ( in_array( $sidebar_id, $existing_sidebar_ids ) ) {
			$sidebar_count++;
			$sidebar_id = "custom-sidebar-{$sidebar_count}";
		} else {
			$sidebar_id_exists = false;
		}
	}

	// Strip any unallowed characters from the post title
	$post_title = str_replace( array( '#', "'", '"', '&' ), '', $post_title	);

	// Give the post a title if it is an empty string
	if ( '' == $post_title ) {
		$post_title = __( 'Sidebar', 'theme-translate' );
	}

	// Make sure the name doesn't already exist
	$name_count    = 1;
	$original_name = $post_title; 	

	while ( $sidebar_name_exists ) {
		if ( in_array( $post_title, $existing_sidebar_names ) ) {
			$name_count++;
			$post_title = "{$original_name} {$name_count}";
		} else {
			$sidebar_name_exists = false;
		}		
	}

	// Remove the save_post action to prevent capabilities error
	// as wp_insert_post triggers this action when called
	$hook_name = 'save_post';
	global $wp_filter;
	$save_post_functions = $wp_filter[$hook_name];
	$wp_filter[$hook_name] = array();

	$postarr = array(
		'post_type'   => 'sidebar_instance',
		'post_title'  => $post_title,
		'post_status' => 'publish' 
	); 
	$post = wp_insert_post( $postarr );

	// Update the post meta to hold the custom sidebar properties
	update_post_meta( $post, 'sidebar_id', 	$sidebar_id );
	update_post_meta( $post, 'sidebar_replacement_id', $replacement_id );
	update_post_meta( $post, 'sidebar_description', sanitize_text_field( $description ) );
	update_post_meta( $post, 'sidebar_attachments', $sidebar_attachment_data );

	// Restore all save post functions
	$wp_filter[$hook_name] = $save_post_functions;

	return $post;
}

/**
 * Get Sidebar Instance
 *
 * Takes the sidebar id as a parameter and returns the
 * post object if it's 'sidebar_id' meta value matches 
 * the sidebar id passed in the parameter. Returns false
 * if no matches have been found.
 *
 * @link http://codex.wordpress.org/Function_Reference/WP_Query             WP_Query()
 * @link http://codex.wordpress.org/Function_Reference/have_posts           have_posts()
 * @link http://codex.wordpress.org/Function_Reference/the_post             the_post()
 * @link http://codex.wordpress.org/Function_Reference/get_post             get_post()
 * @link http://codex.wordpress.org/Function_Reference/get_the_ID           get_the_ID()
 * 
 * @param  string $sidebar_id The ID of the sidebar we wish to check
 * @return post object if found otherwise false
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_get_sidebar_instance( $sidebar_id ) {

	$params = array(
		'post_type'  => 'sidebar_instance',
		'meta_key'   => 'sidebar_id',
		'meta_value' => $sidebar_id
	);
	$query = new WP_Query( $params );

	if( $query->have_posts() ) {
		$query->the_post();
		return get_post( get_the_ID() );
	} else {
		return false;
	}
}

/**
 * Sidebar Instance Name Exists
 *
 * Takes the sidebar name to check and the sidebar_id to 
 * exclude and returns true if there are any other sidebar
 * instances that have this name. (Boolean Function)
 *
 * @link http://codex.wordpress.org/Function_Reference/WP_Query             WP_Query()
 * @link http://codex.wordpress.org/Function_Reference/have_posts           have_posts()
 * @link http://codex.wordpress.org/Function_Reference/the_post             the_post()
 * @link http://codex.wordpress.org/Function_Reference/get_the_ID           get_the_ID()
 * @link http://codex.wordpress.org/Function_Reference/get_the_title        get_the_title()
 * 
 * @param  string $sidebar_name           The sidebar name we wish to check
 * @param  string $sidebar_exclusion_id   The sidebar id to exclude in the search
 * @return boolean - true if there is another sidebar instance that has $sidebar_name
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_sidebar_name_exists( $sidebar_name, $sidebar_exclusion_id ) {

	$sidebar_name_exists = false;

	$params = array(
		'post_type'      => 'sidebar_instance',
		'posts_per_page' => -1
	);
	$query = new WP_Query( $params );

	// Check if the sidebar name exists
	while ( $query->have_posts() ) {

		$query->the_post();
		$sidebar_id = get_post_meta( get_the_ID(), 'sidebar_id', true );

		if ( $sidebar_id ) {
			if ( $sidebar_id != $sidebar_exclusion_id ) {
				if ( $sidebar_name == get_the_title() ) {
					$sidebar_name_exists = true;
				}
			}
		}
	}

	wp_reset_postdata();

	return $sidebar_name_exists;
}

/**
 * Get All Sidebar Instance Posts
 *
 * Returns all of the sidebar-instance posttypes objects
 * in alphabetical order by default. This function will return 
 * false if there are no 'sidebar_instance' posts in the 
 * database.
 *
 * @link http://codex.wordpress.org/Function_Reference/WP_Query             WP_Query()
 * @link http://codex.wordpress.org/Function_Reference/have_posts           have_posts()
 * 
 * @return array $query if post exists and 
 *         boolean if there are no posts.
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_get_all_sidebar_instances( $orderby = 'title', $order = 'ASC' ) {

	$params = array(
		'post_type'      => 'sidebar_instance',
		'posts_per_page' => -1,
		'orderby'        => $orderby,
		'order'          => $order
	);
	
	$query = new WP_Query( $params );

	if( $query->have_posts() ) {
		return $query;
	} else {
		return false;
	}
}

/**
 * Update Sidebar Instance
 *
 * Updates an existing sidebar instance with the values 
 * passed into the parameter. If a sidebar instance is
 * not found a new sidebar instance would be created.
 *
 * @link http://codex.wordpress.org/Function_Reference/WP_Query             WP_Query()
 * @link http://codex.wordpress.org/Function_Reference/wp_insert_post 		wp_insert_post()
 * @link http://codex.wordpress.org/Function_Reference/update_post_meta 	update_post_meta()
 * @link http://codex.wordpress.org/Function_Reference/get_post_meta        get_post_meta()
 * @link http://codex.wordpress.org/Function_Reference/the_post             the_post()
 * @link http://codex.wordpress.org/Function_Reference/get_the_ID           get_the_ID()
 * 
 * @param  string $sidebar_id     The ID for the sidebar we wish to update. Note: This is NOT the post id but the sidebar_id meta value.
 * @param  string $replacement_id The ID for the default theme sidebar we wish to replace.
 * @param  string $post_title     The name for this custom sidebar item.
 * @param  string $description    The description text for this custom sidebar.
 * 
 * @return string $post_id The post ID of the updated/created post.
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_update_sidebar_instance( $sidebar_id, $replacement_id, $post_title, $description = '', $sidebar_attachment_data = array() ) {

	$params = array(
		'post_type'  => 'sidebar_instance',
		'meta_key'   => 'sidebar_id',
		'meta_value' => $sidebar_id
	);

	$query = new WP_Query( $params );
	
	/*
	 * Remove the save_post action to prevent any capabilities 
	 * error as wp_insert_post triggers this action when called
	 */
	$hook_name = 'save_post';
	global $wp_filter;
	$save_post_functions     = $wp_filter[ $hook_name ];
	$wp_filter[ $hook_name ] = array();

	// Strip any unallowed characters from the post title
	$post_title = str_replace( array( '#', "'", '"', '&' ), '', $post_title	);

	// Give the post a title if it is an empty string
	if ( '' == $post_title ) {
		$post_title = __( 'Sidebar', 'theme-translate' );
	}

	if( $query->found_posts > 0 ) {
		$query->the_post();
		$post_id = get_the_ID();

		// Make sure no other sidebar has the same name
		if ( master_sidebar_name_exists( $post_title, $sidebar_id ) ) {
			
			$sidebar_name_exists = true;
			$name_count          = 1;
			$original_name       = $post_title;

			while ( $sidebar_name_exists ) {
				
				$post_title = "{$original_name} {$name_count}";
				
				if ( master_sidebar_name_exists( $post_title, $sidebar_id ) ) {
					$name_count++;
				} else {
					$sidebar_name_exists = false;
				}
			}
		}

		// Update the post object
		$post_arr = array(
			'ID'         => $post_id,
			'post_title' => $post_title
		);
		wp_update_post( $post_arr );

	} else {
		$new_post = master_add_sidebar_instance( $post_title, $replacement_id, sanitize_text_field( $description ) );
		$post_id = $new_post;
	}
	
	// Reset the query globals
	wp_reset_postdata();

	/*
	 * Update other post meta properties to hold
	 * the custom sidebar properties.
	 */	
	update_post_meta( $post_id, 'sidebar_replacement_id', $replacement_id );
	update_post_meta( $post_id, 'sidebar_description', sanitize_text_field( $description ) );
	update_post_meta( $post_id, 'sidebar_attachments', $sidebar_attachment_data );

	/*
	 * Restore the save_post action so any functions
	 * that are hooked to it will execute as intended.
	 */	
	$wp_filter[ $hook_name ] = $save_post_functions;	

	return $post_id;
}

/**
 * Delete Custom Sidebar Instance
 *
 * Looks for a custom sidebar instance with the id that is 
 * passed as a string in the parameter and deletes it.
 * Returns false if no matches have been found. 
 *
 * @link http://codex.wordpress.org/Function_Reference/WP_Query              WP_Query()
 * @link http://codex.wordpress.org/Function_Reference/wp_reset_postdata     wp_reset_postdata()
 * 
 * @param  string  $sidebar_id    The id of the sidebar we want to delete. Note: This is NOT the post id but the sidebar_id meta value.
 * 
 * @return boolean $deleted       True if the sidebar has been located and deleted, false otherwise.
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_delete_sidebar_instance( $sidebar_id ) {
	
	$params = array(
			'post_type'      => 'sidebar_instance',
			'posts_per_page' => -1,
			'meta_key'       => 'sidebar_id',
			'meta_value'     => $sidebar_id
		);
	$query   = new WP_Query( $params );
	$deleted = false;

	// If no posts are found set deleted to true as it doesn't exist
	if ( 0 == $query->found_posts ) {
		$deleted = true;
	}

	// Delete the post if it exists
	while ( $query->have_posts() ) {
		$query->the_post();
		wp_delete_post( get_the_ID(), true );
		$deleted = true;
	}

	// Reset postdata as we have used the_post()
	wp_reset_postdata();

	return $deleted;
}

/**
 * Delete All Custom Sidebar Instances
 * 
 * A function used to delete all posts in the 'sidebar_item'
 * custom posttype, which will remove all custom sidebars
 * generated by the user.
 *
 * @link http://codex.wordpress.org/Function_Reference/WP_Query             WP_Query()
 * @link http://codex.wordpress.org/Function_Reference/the_post             the_post()
 * @link http://codex.wordpress.org/Function_Reference/get_the_ID           get_the_ID()
 * @link http://codex.wordpress.org/Function_Reference/wp_delete_post 		wp_delete_post()
 * @link http://codex.wordpress.org/Function_Reference/wp_reset_postdata    wp_reset_postdata()
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_delete_all_sidebar_instances() {
	$params = array(
			'post_type'      => 'sidebar_instance',
			'posts_per_page' => -1
		);

	$query  = new WP_Query($params);

	while ( $query->have_posts() ) {
		$query->the_post();
		wp_delete_post( get_the_ID(), true );
	}

	// Reset postdata as we have used the_post()
	wp_reset_postdata();	
}
// master_delete_all_sidebar_instances();

/**
 * CUSTOM SIDEBAR DYNAMIC FUNCTIONS
 * ================================
 */

/**
 * Register All Custom Sidebars
 *
 * Gets all sidebar instances and registers them with 
 * WordPress using the built in register_sidebar() function.
 * This function has been updated to compensate for themes
 * that are not coded correctly.
 *
 * @link http://codex.wordpress.org/Function_Reference/WP_Query             WP_Query()
 * @link http://codex.wordpress.org/Function_Reference/get_post_meta        get_post_meta()
 * @link http://codex.wordpress.org/Function_Reference/get_the_ID           get_the_ID()
 * @link http://codex.wordpress.org/Function_Reference/get_the_title        get_the_title()
 * @link http://codex.wordpress.org/Function_Reference/the_post             the_post()
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar     register_sidebar()
 * @link http://codex.wordpress.org/Function_Reference/wp_reset_postdata    wp_reset_postdata()
 *
 * @uses global $wp_registered_sidebars
 * 
 * @since  1.0
 * @version 1.4
 * 
 */
function master_register_custom_sidebars() {

	global $wp_registered_sidebars;
	global $post;

	$main_post = $post;

	$params = array(
			'post_type'      => 'sidebar_instance',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC'
		);

	$query   = new WP_Query( $params );

	while ( $query->have_posts() ) {
		
		$query->the_post();
		$id                  = get_post_meta( get_the_ID(), 'sidebar_id', true );
		$original_sidebar_id = get_post_meta( get_the_ID(), 'sidebar_replacement_id', true );
		$description         = get_post_meta( get_the_ID(), 'sidebar_description', true );

		if ( isset( $wp_registered_sidebars[ $original_sidebar_id ] ) ) {

			$original_sidebar = $wp_registered_sidebars[ $original_sidebar_id ];

			$sidebar  = array(
				'custom_sidebar' => 'true',
				'name'           => get_the_title(),
				'id'             => $id,
				'description'    => $description,
				'class'          => $original_sidebar['class'],
				'before_widget'  => $original_sidebar['before_widget'],
				'after_widget'   => $original_sidebar['after_widget'],
				'before_title'   => $original_sidebar['before_title'],
				'after_title'    => $original_sidebar['after_title']				
			);

			register_sidebar( $sidebar );
		}		
	}

	$post = $main_post;

	// Reset postdata as we have used the_post()
	wp_reset_postdata();	
}
add_action( 'init', 'master_register_custom_sidebars' );

/**
 * Return All Registered Sidebars
 *
 * Gets all sidebars that are currently registered
 * with WordPress.
 *
 * @uses global $wp_registered_sidebars
 * @return array $wp_registered_sidebars
 *
 * @since 1.0
 * @version  1.4
 * 
 */
function master_get_all_registered_sidebars() {
	global $wp_registered_sidebars;
	return $wp_registered_sidebars;
}

/**
 * Return All Default Sidebars
 * 
 * Gets all registered sidebars and only returns the 
 * default sidebars that have been registered with the
 * theme.
 *
 * @uses   master_get_all_registered_sidebars 	defined in includes/theme-sidebar-functions.php
 * @return array $default_sidebars An array of default sidebar objects
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_get_theme_default_sidebars() {
	$default_sidebars = array();

	foreach( master_get_all_registered_sidebars() as $sidebar ) {
		if( ! array_key_exists('custom_sidebar', $sidebar ) ) {
			$default_sidebars[] = $sidebar;
		}
	}

	return $default_sidebars;
}

/**
 * Return All Default Sidebars
 * 
 * Gets all registered sidebars and only returns the 
 * default sidebars that have been registered with the
 * theme.
 *
 * @uses   master_get_all_registered_sidebars 	defined in includes/theme-sidebar-functions.php
 * @return array $default_sidebars An array of default sidebar objects
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_get_theme_custom_sidebars() {
	$custom_sidebars = array();

	foreach( master_get_all_registered_sidebars() as $sidebar ) {
		if( array_key_exists('custom_sidebar', $sidebar ) ) {
			$custom_sidebars[] = $sidebar;
		}
	}

	return $custom_sidebars;	
}

/**
 * Get Ordered Custom Sidebars
 *
 * Versatile function that allows you to pass in a sort parameter and
 * and array to sort. This function will return an array of sorted 
 * sidebar options. By default (if no parameters are given) this function
 * will return an ordered array of custom sidebars that are ordered by
 * name.
 *
 * @uses master_get_theme_custom_sidebars() defined in includes/theme-sidebar-functions.php 
 * @param  string $sort_by Name by default
 * 
 * @return array $array An array of sidebar objects to sort
 *
 * @since 1.0
 * @version  1.4
 */
function master_get_ordered_theme_custom_sidebars( $sort_by = 'name', $array = null ) {

	$registered_sidebars = $array == null ? master_get_theme_custom_sidebars() : $array;	
	$ordered_sidebars = array();

	if ( ! empty( $registered_sidebars ) ) {
		foreach ( $registered_sidebars as $sidebar ) {
			$ordered_sidebars[] = $sidebar[$sort_by];
		}
		array_multisort($ordered_sidebars, SORT_ASC, $registered_sidebars);
		return $registered_sidebars;
	}

	return false;
}

/**
 * Get A Single Custom Sidebar
 *
 * Gets all registered sidebars and only returns the 
 * custom sidebars that have been generated by the
 * custom sidebar generator.
 * 
 * @uses   master_get_theme_custom_sidebars 	defined in includes/theme-sidebar-functions.php
 * @return array $custom_sidebars An array of custom sidebar objects
 *
 * @since 1.0
 * @version  1.4
 * 
 */
function master_get_theme_custom_sidebar( $sidebar_id ) {
	
	$custom_sidebars = master_get_theme_custom_sidebars();

	if( ! empty( $custom_sidebars ) ) {
		foreach ($custom_sidebars as $sidebar ) {
			if ( $sidebar_id == $sidebar['id'] ) {
				return $sidebar;
			}
		}
	}

	return false;
}

/**
 * Unregister A Custom Sidebar
 *
 * Finds a custom sidebar if it exists and uses the native 
 * WordPress function to unregister the sidebar.
 *
 * @link http://codex.wordpress.org/Function_Reference/unregister_sidebar 	unregister_sidebar()
 * 
 * @uses   master_get_theme_custom_sidebar 	defined in includes/theme-sidebar-functions.php
 * @param  $sidebar_id ID of the sidebar to delete
 * @return boolean	true if successfully deleted, false otherwise
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_unregister_theme_custom_sidebar( $sidebar_id ) {
	$custom_sidebar = master_get_theme_custom_sidebar( $sidebar_id );

	if ( $custom_sidebar ) {
		unregister_sidebar( $sidebar_id );
		return true;
	}

	return false;
}

/**
 * CUSTOM SIDEBAR FRONTEND FUNCTIONS
 * =================================
 *
 * The functions listed below are used in the
 * frontend of the website to allow the default
 * theme sidebars to be overriden.
 *
 * Filters: 'dynamic_sidebar_params'
 * Actions: 'dynamic_sidebar'
 *
 * @since 1.0
 * @version 1.4
 * 
 */

/**
 * Dynamic Sidebar Parameter Override
 *
 * This function is called when 'dynamic_sidebar_params' filter
 * is applied. In this function we override the theme default
 * sidebar ID if a replacement exists. By assigning the replacements
 * to a global variable we are able to replace more than one sidebar
 * on the same page.
 *
 * @link http://codex.wordpress.org/Function_Reference/is_admin    		is_admin()
 * @link http://codex.wordpress.org/Function_Reference/add_filter    	add_filter()
 *
 * @uses global $wp_registered_sidebars
 * @uses global $wp_registered_widgets
 * @uses global $sidebar_id_list - Holds any replacements.
 *
 * @return array $params - Widget area parameters
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_dynamic_sidebar_params_override( $params ) {

	global $wp_registered_sidebars, $wp_registered_widgets, $sidebar_id_list;

	if ( ! is_admin() ) {

		$current_sidebar_id = $params[0]['id'];

		if ( ! isset( $sidebar_id_list[ $current_sidebar_id ] ) ) {
			$sidebar_id_list[ $current_sidebar_id ] = true;
		} 
	}

	return $params;
}
add_filter( 'dynamic_sidebar_params', 'master_dynamic_sidebar_params_override', 0 );

/**
 * Override Sidebar Widget
 *
 * Checks for replacement and swaps widgets when necessary. This 
 * function is called when the 'sidebars_widgets' filter is applied.
 * By only swapping the widgets at runtime we are able to ensure
 * that our custom sidebar adopts the intended styles defined by
 * the active wordpress theme.
 *
 * @link http://codex.wordpress.org/Function_Reference/is_admin    		is_admin()
 * @link http://codex.wordpress.org/Function_Reference/add_filter    	add_filter()
 *
 * @uses global $wp_registered_sidebars
 * @uses global $wp_registered_widgets
 * @uses global $post
 * 
 * @param  array $sidebars_widgets
 * @return array $sidebars_widgets 
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_frontend_sidebar_override( $sidebars_widgets ) {

	global $wp_registered_sidebars, $wp_registered_widgets, $post;

	// Make sure we are not in the admin area
	if( ! is_admin() ) {

		foreach ( $sidebars_widgets as $sidebar_id => $widgets ) {

			$replacement_id = master_get_sidebar_replacement( $sidebar_id );

			if ( $replacement_id ) {
				if ( isset( $sidebars_widgets[ $replacement_id ] ) ) {
					$sidebars_widgets[ $sidebar_id ] =  $sidebars_widgets[ $replacement_id ];
				} else {
					$sidebars_widgets[ $sidebar_id ] = array();
				}
			}
		}		
	}
	
	return $sidebars_widgets;
}
add_filter( 'sidebars_widgets', 'master_frontend_sidebar_override', 10 );

/**
 * Get the Replacement Custom Sidebar
 *
 * First this function determines what kind of page/post etc
 * that the user is currently on. Once this has been established
 * this function attempts to find the best sidebar replacement
 * if it exists.
 *
 * Note: If two different sidebars have the same post/taxonomy
 * assigned to it then the latest sidebar will be applied only
 * (in alphabetical order).
 *
 * @link http://codex.wordpress.org/Function_Reference/get_the_ID				get_the_ID()
 * @link http://codex.wordpress.org/Function_Reference/wp_get_theme 			wp_get_theme()
 * @link http://codex.wordpress.org/Function_Reference/get_page_templates 		get_page_templates()
 * @link http://codex.wordpress.org/Function_Reference/WP_Query             	WP_Query()
 * @link http://codex.wordpress.org/Function_Reference/have_posts             	have_posts()
 * @link http://codex.wordpress.org/Function_Reference/the_post             	the_post()
 * @link http://codex.wordpress.org/Function_Reference/get_post_meta          	get_post_meta()
 * @link http://codex.wordpress.org/Function_Reference/is_404          			is_404()
 * @link http://codex.wordpress.org/Function_Reference/is_home          		is_home()
 * @link http://codex.wordpress.org/Function_Reference/is_search          		is_search()
 * @link http://codex.wordpress.org/Function_Reference/is_author          		is_author()
 * @link http://codex.wordpress.org/Function_Reference/is_date          		is_date()
 * @link http://codex.wordpress.org/Function_Reference/is_page          		is_page()
 * @link http://codex.wordpress.org/Function_Reference/is_single          		is_single()
 * @link http://codex.wordpress.org/Function_Reference/is_tax          			is_tax()
 * @link http://codex.wordpress.org/Function_Reference/get_post_type          	get_post_type()
 * @link http://codex.wordpress.org/Function_Reference/get_queried_object       get_queried_object()
 * @link http://codex.wordpress.org/Function_Reference/wp_reset_postdata		wp_reset_postdata()
 * 
 * 
 * @return string $replacement_id 	The replacement id if it exists or false if no replacement is found.
 *
 * @since  1.0
 * @version 1.4
 * 
 */
function master_get_sidebar_replacement( $sidebar_id ) {
	
	// Get the current post, query and database variables
	global $post;
	global $wp_query;
	global $wpdb;

	// Check if a post object exists before testing for conditions
	if ( $post ) {

		// Local variables
		$post_id            = get_the_ID();
		$replacement_id     = '';
		$replacement_exists = false;
		
		// Used to determine sidebar overrides for multiple matched conditions
		// Initialised using a large int value intentionally
		$sidebar_importance = 9999; 

		// Get Page Template Information
		if ( is_page() ) {
			$has_page_template  = false;
			$page_template_name = '';
			$page_templates     = wp_get_theme()->get_page_templates();

			foreach ( $page_templates as $template_filename => $template_name ) {
	       		if ( is_page_template( $template_filename ) ) {
	       			$has_page_template  = true;
	       			$page_template_name = $template_name;
	       		}
	    	}

		}

		// Get all sidebar instances that replace the default sidebar passed in the parameter
		// Get custom sidebars in name order
		$params = array(
			'post_type'      => 'sidebar_instance',
			'meta_key'       => 'sidebar_replacement_id',
			'meta_value'     => $sidebar_id,
			'orderby'        => 'title',
			'order'          => 'DESC',
			'posts_per_page' => -1,
		);

		$query = new WP_Query( $params );

		/*
		 * Loop through each custom sidebar:
		 * Determine the best type of sidebar to fetch for this page
		 * and attempt to find the best sidebar replacement.
		 */ 
		while ( $query->have_posts() ) : $query->the_post();

			$possible_id         = get_post_meta( get_the_ID(), 'sidebar_id', true );
			$sidebar_attachments = get_post_meta( get_the_ID(), 'sidebar_attachments', true );

			foreach ( $sidebar_attachments as $attachment ) {
				/*
				 * Template Hierachy Conditional Checks
				 * (Not including Page Templates)
				 */
				
				// Frontpage Condition
				if ( is_home() && isset( $attachment['menu-item-object'] ) ) {
					if ( ( 'index_page' == $attachment['menu-item-object'] ) && $sidebar_importance > 10 ) {
						$replacement_exists = true;
						$replacement_id     = $possible_id;
						$sidebar_importance = 10;
						continue; // exit the loop
					}
				}

				// Search Results Condition
				if ( is_search() && isset( $attachment['menu-item-object'] ) ) {
					if ( ( 'search_results' == $attachment['menu-item-object'] ) && $sidebar_importance > 10 ) {
						$replacement_exists = true;
						$replacement_id     = $possible_id;
						$sidebar_importance = 10;
						continue; // exit the loop
					}
				}				

				// Author Archive Condition
				if ( is_author() ) {
					$author = $wp_query->get_queried_object();
					if ( isset( $attachment['menu-item-type'] ) && isset( $attachment['menu-item-object'] ) ) {
						
						if ( ( 'author_archive' == $attachment['menu-item-type'] ) && $sidebar_importance > 10 ) {
							if ( $author->ID == $attachment['menu-item-object'] ) {
								$replacement_exists = true;
								$replacement_id     = $possible_id;
								$sidebar_importance = 10;
								continue; // exit the loop							
							}
						}

						if ( ( 'author_archive_all' == $attachment['menu-item-object'] ) && $sidebar_importance > 20 ) {
							$replacement_exists = true;
							$replacement_id     = $possible_id;
							$sidebar_importance = 20;
							continue; // exit the loop
						}
					}
				}

				// Date Archive Condition
				if ( ( is_date() && isset( $attachment['menu-item-object'] ) ) && $sidebar_importance > 10 ) {
					if ( 'date_archive' == $attachment['menu-item-object'] ) {
						$replacement_exists = true;
						$replacement_id     = $possible_id;
						$sidebar_importance = 10;
						continue; // exit the loop
					}
				}

				/*
				 * Page Conditional Checks
				 * (Including Page Templates)
				 */
				if ( ! is_home() && is_page() ) {

					// Specific Page Condition
					if ( isset( $attachment['menu-item-object-id'] ) ) { 
						if ( ( $post_id == $attachment['menu-item-object-id'] ) && $sidebar_importance > 10 ) {
							$replacement_exists = true;
							$replacement_id     = $possible_id;
							$sidebar_importance = 10;
							continue; // exit the loop							
						}
					}

					// Page Template Condition
					if ( 
						isset( $attachment['menu-item-title'] )  && 
						isset( $attachment['menu-item-object'] ) && 
						isset( $attachment['menu-item-type'] ) ) {

						// Get page templates
						if ( $has_page_template && $sidebar_importance > 20 ) {
							if ( 'page-template' == $attachment['menu-item-object'] && 'template_hierarchy' == $attachment['menu-item-type'] ) {
								
								// strpos() is used in order to cater for plugin translations
								$pos = strpos( $attachment['menu-item-title'], $page_template_name );
								
								if ( $pos !== false ) {
									$replacement_exists = true;
									$replacement_id     = $possible_id;
									$sidebar_importance = 20;
									continue; // exit the loop	
								}
							}
						}						
					}

					// All Pages Condition
					if ( 
						isset( $attachment['menu-item-type'] )   && 
						isset( $attachment['menu-item-object'] ) &&
						$sidebar_importance > 30 ) {

						if ( 'post_type_all' == $attachment['menu-item-type'] && 'page' == $attachment['menu-item-object'] ) {
							$replacement_exists = true;
							$replacement_id     = $possible_id;
							$sidebar_importance = 30;
							continue; // exit the loop								
						}
					}
				} // endif
				
				/*
				 * Post Type Conditional Checks
				 * (Including All Post Types Condition)
				 */
				if ( is_single() ) {

					// Get the current post type
					$current_post_type = get_post_type( $post_id );	

					// Specific Single Post Type Condition
					if ( 
						isset( $attachment['menu-item-object-id'] ) &&
						isset( $attachment['menu-item-object'] )    &&
						isset( $attachment['menu-item-type'] )      &&
						$sidebar_importance > 10 ) { 

						if (
							$attachment['menu-item-object-id'] == $post_id        &&
							$attachment['menu-item-object'] == $current_post_type &&
							'post_type' == $attachment['menu-item-type'] ) {
								$replacement_exists = true;
								$replacement_id     = $possible_id;
								$sidebar_importance = 10;
								continue; // exit the loop			
						}
					}

					// All Posts in Category Condition
					if ( 
						isset( $attachment['menu-item-object-id'] ) &&
						isset( $attachment['menu-item-object'] )    &&
						isset( $attachment['menu-item-type'] )      &&
						$sidebar_importance > 15 ) {

						if ( 
							'post' == get_post_type( $post_id ) &&
							has_category( $attachment['menu-item-object-id'], $post_id ) &&
							'category_posts' == $attachment['menu-item-type'] ) {
								$replacement_exists = true;
								$replacement_id     = $possible_id;
								$sidebar_importance = 15;
								continue; // exit the loop								
						}
					}					

					// Post Format Condition
					if ( get_post_format( $post_id ) ) {
						if ( 
							isset( $attachment['menu-item-type'] )   && 
							isset( $attachment['menu-item-object'] ) &&
							$sidebar_importance > 20 ) {

							if ( 'taxonomy' == $attachment['menu-item-type']      && 
								 'post_format' == $attachment['menu-item-object'] &&
								  get_post_format( $post_id ) == strtolower( $attachment['menu-item-title'] ) ) {
								$replacement_exists = true;
								$replacement_id     = $possible_id;
								$sidebar_importance = 20;
								continue; // exit the loop	
							}
						}
					}

					// All Post Types Condition
					if ( 
						isset( $attachment['menu-item-type'] )   && 
						isset( $attachment['menu-item-object'] ) &&
						$sidebar_importance > 30 ) {

						if ( 'post_type_all' == $attachment['menu-item-type'] && $current_post_type == $attachment['menu-item-object'] ) {
							$replacement_exists = true;
							$replacement_id     = $possible_id;
							$sidebar_importance = 30;
							continue; // exit the loop								
						}
					}

				}// endif is_single()

				/*
				 * Taxonomy Conditional Checks
				 * (Including All Taxonomies Condition)
				 */
				if ( is_tax() || is_category() || is_tag() ) {

					$queried_obj = get_queried_object();
					$tax_term_id = get_queried_object_id();
					$tax_name    = $queried_obj->taxonomy;

					// Specific Taxonomy Term Condition
					if ( 
						isset( $attachment['menu-item-object-id'] ) &&
						isset( $attachment['menu-item-object'] )    &&
						isset( $attachment['menu-item-type'] )      &&
						$sidebar_importance > 10 ) {

						if (
							'taxonomy' == $attachment['menu-item-type']        &&
							$tax_term_id == $attachment['menu-item-object-id'] &&
							$tax_name == $attachment['menu-item-object'] ) {
							

							$replacement_exists = true;
							$replacement_id     = $possible_id;
							$sidebar_importance = 10;
							continue; // exit the loop	

						}
					}

					// All Taxonomy Terms Condition
					if ( 
						isset( $attachment['menu-item-object'] )    &&
						isset( $attachment['menu-item-type'] )      &&
						$sidebar_importance > 20 ) {

						if (
							'taxonomy_all' == $attachment['menu-item-type']    &&
							$tax_name == $attachment['menu-item-object'] ) {
							
							$replacement_exists = true;
							$replacement_id     = $possible_id;
							$sidebar_importance = 20;
							continue; // exit the loop	
						}
					}

				} // endif is_tax()

				/*
				 * Post Type Archive Condition
				 * (Everything Except Post)
				 */	
				if ( is_archive()   && 
					! is_category() && 
					! is_tax()      &&
					! is_tag() ) {
					
					// Get the current post type
					$current_post_type = get_post_type( $post_id );	

					if ( 
						isset( $attachment['menu-item-type'] )   && 
						isset( $attachment['menu-item-object'] ) &&
						$sidebar_importance > 40 ) {

						if ( 'post_type_archive' == $attachment['menu-item-type'] && 
							$current_post_type == $attachment['menu-item-object'] ) {

							$replacement_exists = true;
							$replacement_id     = $possible_id;
							$sidebar_importance = 40;
							continue; // exit the loop								
						}
					}
				}
			} // end foreach loop

		endwhile; // end query loop

		// Reset the post query
		wp_reset_postdata();

		// Return the replacement if it exists
		if ( $replacement_exists ) {
			return $replacement_id;
		} else {
			return false;
		}

	} elseif ( is_404() ) {
		
		/*
		 * 404 Condition
		 * The 404 page not found condition needs its own loop
		 * as there is no post object that is currrently active
		 * when a 404 is triggered.
		 */
		 
		// Local variables
		$replacement_id     = '';
		$replacement_exists = false;

		// Get all sidebar instances that replace the default sidebar passed in the parameter
		$params = array(
			'post_type'      => 'sidebar_instance',
			'meta_key'       => 'sidebar_replacement_id',
			'meta_value'     => $sidebar_id,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'posts_per_page' => -1,
		);

		$query = new WP_Query( $params );

		/*
		 * Determine the best type of sidebar to fetch for this page
		 * and attempt to find the best sidebar replacement.
		 */ 
		while ( $query->have_posts() ) : $query->the_post();
			$possible_id         = get_post_meta( get_the_ID(), 'sidebar_id', true );
			$sidebar_attachments = get_post_meta( get_the_ID(), 'sidebar_attachments', true );

			foreach ( $sidebar_attachments as $attachment ) {		
				// 404 Condition
				if ( is_404() ) {
					if ( '404' == $attachment['menu-item-object'] ) {
						$replacement_exists = true;
						$replacement_id     = $possible_id;
						continue; // exit the loop
					}
				}
			}	
		endwhile;

		// Reset the post query
		wp_reset_postdata();

		// Return the replacement if it exists
		if ( $replacement_exists ) {
			return $replacement_id;
		} else {
			return false;
		}

	} else {
		return false;
	}	
}

