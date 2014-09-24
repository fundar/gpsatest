<?php
/**
 * FILE: custom-post-types.php 
 * Created on Feb 18, 2013 at 7:47:20 PM 
 * Author: Mr.Vibe 
 * Credits: www.VibeThemes.com
 * Project: WPLMS
 * License: GPLv2
 */

if ( !defined( 'ABSPATH' ) ) exit;


add_action( 'admin_menu', 'register_lms_menu_page' );

function register_lms_menu_page(){
    add_menu_page( __('Learning Management System','vibe-customtypes'), __('LMS','vibe-customtypes'), 'edit_posts', 'lms', 'vibe_lms_dashboard','',7 );
    add_submenu_page( 'lms', 'Statistics', 'Statistics',  'edit_posts', 'lms-stats', 'vibe_lms_stats' );
    add_submenu_page( 'lms', 'Settings', 'Settings',  'manage_options', 'lms-settings', 'vibe_lms_settings' );
    //admin.php?page=lms
   // add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function )
}

/*== PORTFOLIO == */
if(!function_exists('register_lms')){
function register_lms() {
	if ( ! defined( 'WPLMS_COURSE_SLUG' ) )
		define( 'WPLMS_COURSE_SLUG', 'course' );

	if ( ! defined( 'BP_COURSE_SLUG' ) )
		define( 'BP_COURSE_SLUG', 'course' );

	if ( ! defined( 'WPLMS_COURSE_CATEGORY_SLUG' ) )
		define( 'WPLMS_COURSE_CATEGORY_SLUG', 'course-cat' );

	if ( ! defined( 'WPLMS_UNIT_SLUG' ) )
		define( 'WPLMS_UNIT_SLUG', 'unit' );

	if ( ! defined( 'WPLMS_QUIZ_SLUG' ) )
		define( 'WPLMS_QUIZ_SLUG', 'quiz' );

	if ( ! defined( 'WPLMS_QUESTION_SLUG' ) )
		define( 'WPLMS_QUESTION_SLUG', 'question' );

	if ( ! defined( 'WPLMS_EVENT_SLUG' ) )
		define( 'WPLMS_EVENT_SLUG', 'event' );

	if ( ! defined( 'WPLMS_ASSIGNMENT_SLUG' ) )
		define( 'WPLMS_ASSIGNMENT_SLUG', 'assignment' );
	
	if(!function_exists('vibe_get_option')){ // Defining GET OPTION function
	function vibe_get_option($field,$compare = NULL){
	    
	    $option=get_option('wplms');
	    
	    $return = isset($option[$field])?$option[$field]:NULL;
	    if(isset($return)){
	        if(isset($compare)){
	        if($compare === $return){
	            return true;
	        }else
	            return false;
	    }
	        return $return;
	    }else
	    	return NULL;
	   }   
	}
	register_post_type( 'course',
		array(
			'labels' => array(
				'name' => __('Courses','vibe-customtypes'),
				'menu_name' => __('Courses','vibe-customtypes'),
				'singular_name' => __('Course','vibe-customtypes'),
				'add_new_item' => __('Add New Course','vibe-customtypes'),
				'all_items' => __('All Courses','vibe-customtypes')
			),
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'capapbility_type' => 'post',
            'has_archive' => true,
			'show_in_menu' => 'lms',
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'taxonomies' => array( 'course-cat'),
			'supports' => array( 'title','editor','thumbnail','author','comments','excerpt','revisions','custom-fields', 'page-attributes'),
			'hierarchical' => true,
            'show_in_nav_menus' => true,
			'rewrite' => array( 'slug' => WPLMS_COURSE_SLUG, 'hierarchical' => true, 'with_front' => false )
		)
	);

    register_taxonomy( 'course-cat', array( 'course'),
		array(
			'labels' => array(
				'name' => __('Course Category','vibe-customtypes'),
				'menu_name' => __('Category','vibe-customtypes'),
				'singular_name' => __('Category','vibe-customtypes'),
				'add_new_item' => __('Add New Course Category','vibe-customtypes'),
				'all_items' => __('All Categories','vibe-customtypes')
			),
			'public' => true,
			'hierarchical' => true,
			'show_ui' => true,
			'show_in_menu' => 'lms',
			'show_admin_column' => true,
            'query_var' => 'course-cat',           
			'show_in_nav_menus' => true,
			'rewrite' => array( 'slug' => WPLMS_COURSE_CATEGORY_SLUG, 'hierarchical' => true, 'with_front' => false ),
		)
	);


    register_post_type( 'unit',
		array(
			'labels' => array(
				'name' => __('Units','vibe-customtypes'),
				'menu_name' => __('Units','vibe-customtypes'),
				'singular_name' => __('Unit','vibe-customtypes'),
				'add_new_item' => __('Add New Unit','vibe-customtypes'),
				'all_items' => __('All Units','vibe-customtypes')
			),
			'public' => true,
			'taxonomies' => array( 'module-tag'),
			'publicly_queryable' => true,
			'show_ui' => true,
            'has_archive' => true,
			'show_in_menu' => 'lms',
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'post-formats', 'revisions','custom-fields' ),
			'hierarchical' => true,
            'show_in_nav_menus' => false,
			'rewrite' => array( 'slug' => WPLMS_UNIT_SLUG, 'hierarchical' => true, 'with_front' => false )
		)
	 );   
    

     register_taxonomy( 'module-tag', array( 'unit'),
		array(
			'labels' => array(
				'name' => __('Tag','vibe-customtypes'),
				'menu_name' => __('Tag','vibe-customtypes'),
				'singular_name' => __('Tag','vibe-customtypes'),
				'add_new_item' => __('Add New Tag','vibe-customtypes'),
				'all_items' => __('All Tags','vibe-customtypes')
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
				'name' => __('Quizes','vibe-customtypes'),
				'menu_name' => __('Quizes','vibe-customtypes'),
				'singular_name' => __('Quiz','vibe-customtypes'),
				'all_items' => __('All Quizes','vibe-customtypes')
			),
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
            'has_archive' => true,
			'show_in_menu' => 'lms',
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'permalink_epmask' => EP_PAGES,
			'supports' => array( 'title','editor','thumbnail', 'revisions','custom-fields' ),
			'hierarchical' => true,
            'show_in_nav_menus' => false,
			'rewrite' => array( 'slug' => WPLMS_QUIZ_SLUG,'hierarchical' => true, 'with_front' => false )
		)
	 );  

    	 
	 register_post_type( 'question',
		array(
			'labels' => array(
				'name' => __('Question Bank','vibe-customtypes'),
				'menu_name' => __('Question Bank','vibe-customtypes'),
				'singular_name' => __('Question','vibe-customtypes'),
				'all_items' => __('All Questions','vibe-customtypes')
			),
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
            'has_archive' => true,
			'show_in_menu' => 'lms',
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'supports' => array( 'title','editor', 'comments','revisions' ,'custom-fields'),
			'hierarchical' => true,
            'show_in_nav_menus' => false,
			'rewrite' => array( 'slug' => WPLMS_QUESTION_SLUG,'hierarchical' => true, 'with_front' => false )
		)
	 ); 

    	 
	 register_taxonomy( 'question-tag', array( 'question'),
		array(
			'labels' => array(
				'name' => __('Question Tag','vibe-customtypes'),
				'menu_name' => __('Tag','vibe-customtypes'),
				'singular_name' => __('Tag','vibe-customtypes'),
				'add_new_item' => __('Add New Tag','vibe-customtypes'),
				'all_items' => __('All Tags','vibe-customtypes')
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

/*====== Version 1.4 EVENTS =====*/

if ( in_array( 'wplms-events/wplms-events.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {     

register_post_type( 'wplms-event',
		array(
			'labels' => array(
				'name' => __('Events','vibe-customtypes'),
				'menu_name' => __('Events','vibe-customtypes'),
				'singular_name' => __('Event','vibe-customtypes'),
				'add_new_item' => __('Add New Events','vibe-customtypes'),
				'all_items' => __('All Events','vibe-customtypes')
			),
			'public' => true,
			'taxonomies' => array( 'event-type'),
			'publicly_queryable' => true,
			'show_ui' => true,
            'has_archive' => true,
			'show_in_menu' => 'lms',
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'post-formats', 'revisions','custom-fields' ),
			'hierarchical' => true,
            'show_in_nav_menus' => false,
			'rewrite' => array( 'slug' => WPLMS_EVENT_SLUG, 'hierarchical' => true, 'with_front' => true )
		)
	 );   
    

 register_taxonomy( 'event-type', array( 'wplms-event'),
		array(
			'labels' => array(
				'name' => __('Event type','vibe-customtypes'),
				'menu_name' => __('Event type','vibe-customtypes'),
				'singular_name' => __('Event type','vibe-customtypes'),
				'add_new_item' => __('Add New Event type','vibe-customtypes'),
				'all_items' => __('All Event types','vibe-customtypes')
			),
			'public' => true,
			'hierarchical' => true,
			'show_ui' => true,
			'show_admin_column' => true,
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'rewrite' => array( 'slug' => 'event-type', 'hierarchical' => true, 'with_front' => false ),
		)
	);
 add_post_type_support('wplms-event','comments');
}

/*====== Version 1.5 ASSIGNMENTS =====*/

if ( in_array( 'wplms-assignments/wplms-assignments.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {     

register_post_type( 'wplms-assignment',
		array(
			'labels' => array(
				'name' => __('Assignments','vibe-customtypes'),
				'menu_name' => __('Assignments','vibe-customtypes'),
				'singular_name' => __('Assignment','vibe-customtypes'),
				'add_new_item' => __('Add New Assignment','vibe-customtypes'),
				'all_items' => __('All Assignments','vibe-customtypes')
			),
			'public' => true,
			'taxonomies' => array( 'assignment-type'),
			'publicly_queryable' => true,
			'show_ui' => true,
            'has_archive' => true,
			'show_in_menu' => 'lms',
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'post-formats', 'revisions','custom-fields' ),
			'hierarchical' => true,
            'show_in_nav_menus' => false,
			'rewrite' => array( 'slug' => WPLMS_ASSIGNMENT_SLUG, 'hierarchical' => true, 'with_front' => true )
		)
	 );   
    

 register_taxonomy( 'assignment-type', array( 'wplms-assignment'),
		array(
			'labels' => array(
				'name' => __('Assignment type','vibe-customtypes'),
				'menu_name' => __('Assignment type','vibe-customtypes'),
				'singular_name' => __('Assignment type','vibe-customtypes'),
				'add_new_item' => __('Add New Assignment type','vibe-customtypes'),
				'all_items' => __('All Assignment types','vibe-customtypes')
			),
			'public' => true,
			'hierarchical' => true,
			'show_ui' => true,
			'show_admin_column' => true,
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'rewrite' => array( 'slug' => 'Assignment-type', 'hierarchical' => true, 'with_front' => false ),
		)
	);
 add_post_type_support('wplms-assignment','comments');
}

/*====== Version 1.3 RECORD PAYMENTS =====*/
	register_post_type( 'payments',
		array(
			'labels' => array(
				'name' => __('Payments','vibe-customtypes'),
				'menu_name' => __('Payments','vibe-customtypes'),
				'singular_name' => __('Payment','vibe-customtypes'),
				'add_new_item' => __('Add New Payment','vibe-customtypes'),
				'all_items' => __('Payments History','vibe-customtypes')
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
   
     


/*====== Version 1.3.2 CERTIFICATE TEMPLATES =====*/
	register_post_type( 'certificate',
		array(
			'labels' => array(
				'name' => __('Certificate Template','vibe-customtypes'),
				'menu_name' => __('Certificates Template','vibe-customtypes'),
				'singular_name' => __('Certificate Template','vibe-customtypes'),
				'add_new_item' => __('Add New Certificate','vibe-customtypes'),
				'all_items' => __('Certificate Templates','vibe-customtypes')
			),
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
            'has_archive' => false,
			'show_in_menu' => 'lms',
			'show_in_nav_menus' => false,
			'supports' => array( 'title','editor'),
			'hierarchical' => false,
			'rewrite' => array( 'slug' => 'certificates', 'hierarchical' => false, 'with_front' => false )
		)
	 ); 
/*====== Version 1.6.2 LINKAGE =====*/
	$linkage = vibe_get_option('linkage');
	if(isset($linkage) && $linkage){
		register_taxonomy( 'linkage', array( 'course','unit','quiz','question','certificate','wplms-assignment','wplms-event','forum','product'),
			array(
				'labels' => array(
					'name' => __('Linkage','vibe-customtypes'),
					'menu_name' => __('Linkage','vibe-customtypes'),
					'singular_name' => __('Linkage','vibe-customtypes'),
					'add_new_item' => __('Add New Link','vibe-customtypes'),
					'all_items' => __('All Links','vibe-customtypes')
				),
				'public' => true,
				'hierarchical' => false,
				'show_ui' => true,
				'show_admin_column' => 'true',
				'show_in_nav_menus' => true,
				'rewrite' => array( 'slug' => 'linkage', 'hierarchical' => true, 'with_front' => false ),
			)
		);
	}
/*====== Version 1.6.5 LEVEL =====*/	
	$level = vibe_get_option('level');
	if(isset($level) && $level){
		register_taxonomy( 'level', array( 'course'),
			array(
				'labels' => array(
					'name' => __('Level','vibe-customtypes'),
					'menu_name' => __('Level','vibe-customtypes'),
					'singular_name' => __('Level','vibe-customtypes'),
					'add_new_item' => __('Add New Level','vibe-customtypes'),
					'all_items' => __('All Levels','vibe-customtypes')
				),
				'public' => true,
				'hierarchical' => true,
				'show_ui' => true,
				'show_in_menu' => 'lms',
				'show_admin_column' => true,
	            'query_var' => 'level',           
				'show_in_nav_menus' => true,
				'rewrite' => array( 'slug' => WPLMS_LEVEL_SLUG, 'hierarchical' => true, 'with_front' => false ),
			)
		);
	}	

	}
}


/*== Testimonials == */
if(!function_exists('register_testimonials')){
function register_testimonials() {
	register_post_type( 'testimonials',
		array(
			'labels' => array(
				'name' => __('Testimonials','vibe-customtypes'),
				'menu_name' => __('Testimonials','vibe-customtypes'),
				'singular_name' => __('Testimonial','vibe-customtypes'),
				'all_items' => __('All Testimonials','vibe-customtypes')
			),
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'supports' => array( 'title', 'editor','excerpt', 'thumbnail'),
			'hierarchical' => false,
			'has_archive' => true,
            'menu_position' => 10,
            'show_in_nav_menus' => false,
			'rewrite' => array( 'slug' => 'testimonial', 'hierarchical' => true, 'with_front' => false )
		)
	);
        
   
}
}
/*== Popups == */
if(!function_exists('register_popups')){
function register_popups() {
	register_post_type( 'popups',
		array(
			'labels' => array(
				'name' => __('Popups','vibe-customtypes'),
				'menu_name' => __('Popups','vibe-customtypes'),
				'singular_name' => __('Popup','vibe-customtypes'),
				'all_items' => __('All Popups','vibe-customtypes')
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
     
}

}


add_action( 'init', 'register_lms',1 );
add_action( 'init', 'register_testimonials' );
add_action( 'init', 'register_popups' );


 
?>