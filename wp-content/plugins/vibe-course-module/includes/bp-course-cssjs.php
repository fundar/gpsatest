<?php

/**
 * NOTE: You should always use the wp_enqueue_script() and wp_enqueue_style() functions to include
 * javascript and css files.
 */


function bp_course_add_js() {
	global $bp;

	//if ( $bp->current_component == $bp->course->slug ){ // Globals All Messed Up, falling back to WordPress
		wp_enqueue_style( 'bp-course-css', plugins_url( '/vibe-course-module/includes/css/course_template.css' ) );
		wp_enqueue_style( 'bp-course-graph', plugins_url( '/vibe-course-module/includes/css/graph.css' ) );
		wp_enqueue_script( 'bp-confirm-js', plugins_url( '/vibe-course-module/includes/js/jquery.confirm.min.js' ) );
		
		wp_enqueue_script( 'bp-course-js', plugins_url( '/vibe-course-module/includes/js/course.js' ) );
	//}
}
add_action( 'wp_footer', 'bp_course_add_js');


add_action('admin_enqueue_scripts','bp_course_admin_scripts');
function bp_course_admin_scripts(){
	wp_enqueue_script( 'bp-graph-js', plugins_url( '/vibe-course-module/includes/js/jquery.flot.min.js' ) );
}
?>