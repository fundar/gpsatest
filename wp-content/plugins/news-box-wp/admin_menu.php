<?php
// NB wordpress menu
function nb_admin_menu() {	
	if( (float)substr(get_bloginfo('version'), 0, 3) < 3.8) {
		$menu_img = NB_URL.'/img/nb_logo_small_old.png';
	} else {
		$menu_img = NB_URL.'/img/nb_logo_small.png';
	}
	$capability = 'edit_pages';
	
	add_menu_page('News Box', 'News Box', 'edit_pages', 'nb_menu', 'nb_builder', $menu_img, 56);
	add_submenu_page('nb_menu', __('Manage Boxes', 'nb_ml'), __('Manage Boxes', 'nb_ml'), $capability, 'nb_menu', 'nb_builder');
}
add_action('admin_menu', 'nb_admin_menu', 8);


// submeus after CPT
function nb_admin_late_menu() {	
	$capability = 'edit_pages';
	
	add_submenu_page('nb_menu', __('QN Categories', 'nb_ml'), __('QN Categories', 'nb_ml'), $capability, 'edit-tags.php?taxonomy=nb_news_cat');
	add_submenu_page('nb_menu', __('Settings', 'nb_ml'), __('Settings', 'nb_ml'), 'install_plugins', 'nb_settings', 'nb_settings');
}
add_action('admin_menu', 'nb_admin_late_menu', 25);



// management and builder
function nb_builder() { include_once(NB_DIR . '/builder.php'); }

// settings
function nb_settings() {include_once(NB_DIR . '/settings.php'); }



////////////////////////////////////
// BOXES TAXONOMY

add_action( 'init', 'register_taxonomy_nb_boxes' );
function register_taxonomy_nb_boxes() {
    $labels = array( 
        'name' => __( 'Boxes', 'nb_ml'),
        'singular_name' => __( 'Box', 'nb_ml'),
        'search_items' => __( 'Search Boxes', 'nb_ml'),
        'popular_items' => __( 'Popular Boxes', 'nb_ml'),
        'all_items' => __( 'All Boxes', 'nb_ml'),
        'parent_item' => __( 'Parent Box', 'nb_ml'),
        'parent_item_colon' => __( 'Parent Box:', 'nb_ml'),
        'edit_item' => __( 'Edit Box', 'nb_ml'),
        'update_item' => __( 'Update Box', 'nb_ml'),
        'add_new_item' => __( 'Add New Box', 'nb_ml'),
        'new_item_name' => __( 'New Box', 'nb_ml'),
        'separate_items_with_commas' => __( 'Separate boxes with commas', 'nb_ml'),
        'add_or_remove_items' => __( 'Add or remove Boxes', 'nb_ml'),
        'choose_from_most_used' => __( 'Choose from most used Boxes', 'nb_ml'),
        'menu_name' => __( 'Boxes', 'nb_ml'),
    );

    $args = array( 
        'labels' => $labels,
        'public' => false,
        'show_in_nav_menus' => false,
        'show_ui' => false,
        'show_tagcloud' => false,
        'hierarchical' => false,
        'rewrite' => false,
        'query_var' => true
    );
    register_taxonomy('nb_boxes', null, $args);
}


////////////////////////////////////////////////////////////////////////


////////////////////////////////////
// NEWS CPT

add_action('init', 'register_cpt_nb_news');
function register_cpt_nb_news() {
    $labels = array( 
        'name' => __('Quick News', 'nb_ml'),
        'singular_name' => __('News', 'nb_ml'),
        'add_new' => __('Add New', 'nb_ml'),
        'add_new_item' => __('Add New News', 'nb_ml'),
        'edit_item' => __('Edit News', 'nb_ml'),
        'new_item' => __('New News', 'nb_ml'),
        'view_item' => __('View News', 'nb_ml'),
        'search_items' => __('Search News', 'nb_ml'),
        'not_found' => __('No news found', 'nb_ml'),
        'not_found_in_trash' => __('No news found in Trash', 'nb_ml'),
        'parent_item_colon' => __('Parent News:', 'nb_ml'),
        'menu_name' => __('Quick News', 'nb_ml'),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        'supports' => array('title', 'editor', 'thumbnail'),
        'taxonomies' => array('nb_news_cat'),
        'public' => false,
        'show_ui' => true,
        'show_in_nav_menus' => false,
        'publicly_queryable' => true,
        'exclude_from_search' => true,
        'has_archive' => false,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => false,
        'capability_type' => 'post',
		'show_in_menu' => 'nb_menu'
    );
    register_post_type('nb_news', $args);
}

// customize messages
function nb_updated_messages( $messages ) {
  global $post;

  $messages['nb_news'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => __('News updated', 'nb_ml'),
    2 => __('News updated', 'nb_ml'),
    3 => __('News deleted', 'nb_ml'),
    4 => __('News updated', 'nb_ml'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('News restored to revision from %s', 'nb_ml'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => __('News published', 'nb_ml'),
    7 => __('News saved', 'nb_ml'),
    8 => __('News submitted', 'nb_ml'),
    9 => sprintf( __('News scheduled for: <strong>%1$s</strong>', 'nb_ml'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ))),
    10 => __('News draft updated', 'nb_ml'),
  );

  return $messages;
}
add_filter('post_updated_messages', 'nb_updated_messages');




////////////////////////////////////
// NEWS CATEGORY

add_action( 'init', 'register_taxonomy_nb_news_cat');
function register_taxonomy_nb_news_cat() {

    $labels = array( 
        'name' => __('QN Categories', 'nb_ml'),
        'singular_name' => __('QN Category', 'nb_ml'),
        'search_items' => __('Search QN Categories', 'nb_ml'),
        'popular_items' => __('Popular QN Categories', 'nb_ml'),
        'all_items' => __('All QN Categories', 'nb_ml'),
        'parent_item' => __('Parent QN Category', 'nb_ml'),
        'parent_item_colon' => __('Parent QN Category:', 'nb_ml'),
        'edit_item' => __('Edit QN Category', 'nb_ml'),
        'update_item' => __('Update QN Category', 'nb_ml'),
        'add_new_item' => __('Add New QN Category', 'nb_ml'),
        'new_item_name' => __('New QN Category', 'nb_ml'),
        'separate_items_with_commas' => __('Separate news categories with commas', 'nb_ml'),
        'add_or_remove_items' => __('Add or remove news categories', 'nb_ml'),
        'choose_from_most_used' => __('Choose from the most used news categories', 'nb_ml'),
        'menu_name' => __('QN Categories', 'nb_ml'),
    );

    $args = array( 
        'labels' => $labels,
        'public' => false,
        'show_in_nav_menus' => false,
        'show_ui' => true,
        'show_tagcloud' => false,
        'show_admin_column' => true,
        'hierarchical' => true,
        'rewrite' => false,
        'query_var' => true
    );

    register_taxonomy('nb_news_cat', array('nb_news'), $args);
}



// fix posts count link
function nb_cat_column_row($row_content, $column_name, $term_id) {
	if($column_name == 'posts') {
		$row_content = str_replace('?nb_news_cat=', '?post_type=nb_news&nb_news_cat=', $row_content);
	}
	return $row_content;
}
add_filter('manage_nb_news_cat_custom_column', 'nb_cat_column_row', 10, 3);



// fix to set the taxonomy as menu page sublevel
function nb_menu_correction($parent_file) {
	global $current_screen;

	// hack for taxonomy
	if(isset($current_screen->taxonomy)) {
		$taxonomy = 'nb_news_cat';
		if($taxonomy == $current_screen->taxonomy) {
			$parent_file = 'nb_menu';
		}	
	}
	
	return $parent_file;
}
add_action('parent_file', 'nb_menu_correction');
