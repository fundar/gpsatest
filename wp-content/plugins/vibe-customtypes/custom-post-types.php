<?php

/**
 * FILE: custom-post-types.php 
 * Created on Feb 18, 2013 at 7:47:20 PM 
 * Author: Mr.Vibe 
 * Credits: www.VibeThemes.com
 * Project: Vizard
 * License: GPLv2
 */


add_action( 'admin_menu', 'register_lms_menu_page' );

function register_lms_menu_page(){
    add_menu_page( 'Learning Management System', 'LMS', 'edit_posts', 'lms', 'vibe_lms_dashboard','',6 );
    add_submenu_page( 'lms', 'Statistics', 'Statistics',  'edit_posts', 'lms-stats', 'vibe_lms_stats' );
    add_submenu_page( 'lms', 'Settings', 'Settings',  'manage_options', 'lms-settings', 'vibe_lms_settings' );
    //admin.php?page=lms
   // add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function )
}

/*== PORTFOLIO == */
if(!function_exists('register_lms')){
function register_lms() {


	register_post_type( 'course',
		array(
			'labels' => array(
				'name' => 'Courses',
				'menu_name' => 'Courses',
				'singular_name' => 'Course',
				'add_new_item' => 'Add New Course',
				'all_items' => 'All Courses'
			),
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'capapbility_type' => 'post',
            'has_archive' => true,
			'show_in_menu' => 'lms',
			'show_in_nav_menus' => true,
			'taxonomies' => array( 'course-cat'),
			'supports' => array( 'title','editor','thumbnail','author','comments','excerpt','revisions'),
			'hierarchical' => true,
            'show_in_nav_menus' => true,
			'rewrite' => array( 'slug' => 'course', 'hierarchical' => true, 'with_front' => false )
		)
	);
        flush_rewrite_rules( false );

    register_taxonomy( 'course-cat', array( 'course'),
		array(
			'labels' => array(
				'name' => 'Category',
				'menu_name' => 'Category',
				'singular_name' => 'Category',
				'add_new_item' => 'Add New Category',
				'all_items' => 'All Categories'
			),
			'public' => true,
			'hierarchical' => true,
			'show_ui' => true,
			'show_admin_column' => 'true',
            'query_var' => 'course-cat',
			'show_in_nav_menus' => true,
			'rewrite' => array( 'slug' => 'course-cat', 'hierarchical' => true, 'with_front' => false ),
		)
	);


    register_post_type( 'unit',
		array(
			'labels' => array(
				'name' => 'Units',
				'menu_name' => 'Units',
				'singular_name' => 'Unit',
				'add_new_item' => 'Add New Unit',
				'all_items' => 'All Units'
			),
			'public' => true,
			'taxonomies' => array( 'module-tag'),
			'publicly_queryable' => true,
			'show_ui' => true,
            'has_archive' => true,
			'show_in_menu' => 'lms',
			'show_in_nav_menus' => true,
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'post-formats', 'revisions' ),
			'hierarchical' => true,
            'show_in_nav_menus' => false,
			'rewrite' => array( 'slug' => 'unit', 'hierarchical' => true, 'with_front' => false )
		)
	 );   
     flush_rewrite_rules( false );

     register_taxonomy( 'module-tag', array( 'unit'),
		array(
			'labels' => array(
				'name' => 'Tag',
				'menu_name' => 'Tag',
				'singular_name' => 'Tag',
				'add_new_item' => 'Add New Tag',
				'all_items' => 'All Tags'
			),
			'public' => true,
			'hierarchical' => false,
			'show_ui' => true,
			'show_admin_column' => 'true',
			'show_in_nav_menus' => true,
			'rewrite' => array( 'slug' => 'module-tag', 'hierarchical' => true, 'with_front' => false ),
		)
	);

	 register_post_type( 'quiz',
		array(
			'labels' => array(
				'name' => 'Quizes',
				'menu_name' => 'Quizes',
				'singular_name' => 'Quiz',
				'all_items' => 'All Quizes'
			),
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
            'has_archive' => true,
			'show_in_menu' => 'lms',
			'show_in_nav_menus' => true,
			'supports' => array( 'title','editor','thumbnail', 'revisions' ),
			'hierarchical' => true,
            'show_in_nav_menus' => false,
			'rewrite' => array( 'slug' => 'quiz','hierarchical' => true, 'with_front' => false )
		)
	 );  

	 register_post_type( 'question',
		array(
			'labels' => array(
				'name' => 'Question Bank',
				'menu_name' => 'Question Bank',
				'singular_name' => 'Question',
				'all_items' => 'All Questions'
			),
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
            'has_archive' => true,
			'show_in_menu' => 'lms',
			'show_in_nav_menus' => true,
			'supports' => array( 'title','editor', 'comments','revisions' ),
			'hierarchical' => true,
            'show_in_nav_menus' => false,
			'rewrite' => array( 'slug' => 'question','hierarchical' => true, 'with_front' => false )
		)
	 ); 

	 register_taxonomy( 'question-tag', array( 'question'),
		array(
			'labels' => array(
				'name' => 'Tag',
				'menu_name' => 'Tag',
				'singular_name' => 'Tag',
				'add_new_item' => 'Add New Tag',
				'all_items' => 'All Tags'
			),
			'public' => true,
			'hierarchical' => false,
			'show_ui' => true,
			'show_admin_column' => 'true',
			'show_in_nav_menus' => true,
			'rewrite' => array( 'slug' => 'question-tag', 'hierarchical' => false, 'with_front' => false ),
		)
	); 

	add_post_type_support('question','comments');
	
/*====== Version 1.3 RECORD PAYMNETS =====*/
	register_post_type( 'payments',
		array(
			'labels' => array(
				'name' => 'Payments',
				'menu_name' => 'Payments',
				'singular_name' => 'Payment',
				'add_new_item' => 'Add New Payment',
				'all_items' => 'Payments History'
			),
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
            'has_archive' => false,
			'show_in_menu' => 'lms',
			'show_in_nav_menus' => false,
			'supports' => array( 'title'),
			'hierarchical' => false,
			'rewrite' => array( 'slug' => 'payments', 'hierarchical' => false, 'with_front' => false )
		)
	 );   
     flush_rewrite_rules( false );

/*====== Version 1.4 CERTIFICATE TEMPLATES ====
	register_post_type( 'certificate',
		array(
			'labels' => array(
				'name' => 'Certificate Template',
				'menu_name' => 'Certificates Template',
				'singular_name' => 'Certificate Template',
				'add_new_item' => 'Add New Certificate',
				'all_items' => 'Certificate Templates'
			),
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
            'has_archive' => false,
			'show_in_menu' => 'lms',
			'show_in_nav_menus' => false,
			'supports' => array( 'title','editor','thumbnail'),
			'hierarchical' => false,
			'rewrite' => array( 'slug' => 'certificates', 'hierarchical' => false, 'with_front' => false )
		)
	 );  =*/ 
	}
}


/*== Testimonials == */
if(!function_exists('register_testimonials')){
function register_testimonials() {
	register_post_type( 'testimonials',
		array(
			'labels' => array(
				'name' => 'Testimonials',
				'menu_name' => 'Testimonials',
				'singular_name' => 'Testimonial',
				'all_items' => 'All Testimonials'
			),
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'supports' => array( 'title', 'editor','excerpt', 'thumbnail'),
			'hierarchical' => false,
			'has_archive' => true,
            'menu_position' => 7,
            'show_in_nav_menus' => false,
			'rewrite' => array( 'slug' => 'testimonial', 'hierarchical' => true, 'with_front' => false )
		)
	);
        
          flush_rewrite_rules();
}
}
/*== Popups == */
if(!function_exists('register_popups')){
function register_popups() {
	register_post_type( 'popups',
		array(
			'labels' => array(
				'name' => 'Popups',
				'menu_name' => 'Popups',
				'singular_name' => 'Popup',
				'all_items' => 'All Popups'
			),
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => false,
			'supports' => array( 'title', 'editor','excerpt' ),
			'hierarchical' => false,
			'has_archive' => false,
            'menu_position' => 8,
            'show_in_nav_menus' => false,
			'rewrite' => array( 'slug' => 'popup', 'hierarchical' => true, 'with_front' => false )
		)
	);
        
          flush_rewrite_rules();
}

}


add_action( 'init', 'register_lms' );
add_action( 'init', 'register_testimonials' );
add_action( 'init', 'register_popups' );


add_filter('post_link', 'course_cat_permalink', 10, 3);
add_filter('post_type_link', 'course_cat_permalink', 10, 3);
 
function course_cat_permalink($permalink, $post, $leavename) {
	
    if (strpos($permalink, '%course_cat%') === FALSE) return $permalink;
     
        // Get post
        if(get_post_type($post->ID) != 'course')
        	return $permalink;

        $post = get_post($post->ID);
        if (!$post) return $permalink;
 
        // Get taxonomy terms
        $terms = wp_get_object_terms($post->ID, 'course_cat');   


        if (!is_wp_error($terms) && !empty($terms) && is_object($terms[0])){
        	$taxonomy_slug = $terms[0]->slug;
        	return str_replace('%course_cat%', $taxonomy_slug, $permalink);
        }
    return;
}  
?>