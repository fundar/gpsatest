<?php

/**
 * NOTE: You should always use the wp_enqueue_script() and wp_enqueue_style() functions to include
 * javascript and css files.
 */


function bp_course_add_js() {
	global $bp;
	if ( ! function_exists( 'vibe_logo_url' ) ) return; // Checks if WPLMS is active in current site in WP Multisite
	$minified=vibe_get_option('minified');
    if(!isset($minified) || !$minified){
		wp_enqueue_style( 'bp-course-graph', plugins_url( '/vibe-course-module/includes/css/graph.css' ) );
		wp_enqueue_script( 'bp-confirm-js', plugins_url( '/vibe-course-module/includes/js/jquery.confirm.min.js' ) );
		wp_enqueue_script( 'bp-html2canvas-js', plugins_url( '/vibe-course-module/includes/js/html2canvas.js' ) );
		//wp_enqueue_script( 'bp-print-js', plugins_url( '/vibe-course-module/includes/js/jQuery.print.js' ) ); no longer required
	}	
		wp_enqueue_style( 'bp-course-css', plugins_url( '/vibe-course-module/includes/css/course_template.css' ) );
		wp_enqueue_script( 'bp-course-js', plugins_url( '/vibe-course-module/includes/js/course.js' ),array('jquery','wp-mediaelement','jquery-ui-core','jquery-ui-sortable','jquery-ui-droppable'));
		$color=bp_wplms_get_theme_color();
		$single_dark_color=bp_wplms_get_theme_single_dark_color();
		$translation_array = array( 
			'too_fast_answer' => __( 'Too Fast or Answer not marked.','vibe' ), 
			'answer_saved' => __( 'Answer Saved.','vibe' ), 
			'processing' => __( 'Processing...','vibe' ), 
			'saving_answer' => __( 'Saving Answer...please wait','vibe' ), 
			'remove_user_text' => __( 'This step is irreversible. Are you sure you want to remove the User from the course ?','vibe' ), 
			'remove_user_button' => __( 'Confirm, Remove User from Course','vibe' ), 
			'cancel' => __( 'Cancel','vibe' ), 
			'reset_user_text' => __( 'This step is irreversible. All Units, Quiz results would be reset for this user. Are you sure you want to Reset the Course for this User?','vibe' ), 
			'reset_user_button' => __( 'Confirm, Reset Course for this User','vibe' ), 
			'quiz_reset' => __( 'This step is irreversible. All Questions answers would be reset for this user. Are you sure you want to Reset the Quiz for this User? ','vibe' ), 
			'quiz_reset_button' => __( 'Confirm, Reset Quiz for this User','vibe' ), 
			'marks_saved' => __( 'Marks Saved','vibe' ), 
			'quiz_marks_saved' => __( 'Quiz Marks Saved','vibe' ), 
			'submit_quiz' => __( 'Submit Quiz','vibe' ), 
			'sending_messages' => __( 'Sending Messages ...','vibe' ), 
			'adding_students' => __( 'Adding Students to Course ...','vibe' ), 
			'successfuly_added_students' => __( 'Students successfully added to Course','vibe' ),
			'unable_add_students' => __( 'Unable to Add students to Course','vibe' ),
			'theme_color' => $color,
			'single_dark_color' => $single_dark_color
			);
    	wp_localize_script( 'bp-course-js', 'vibe_course_module_strings', $translation_array );
    	
	//}
}
add_action( 'wp_footer', 'bp_course_add_js');


add_action('admin_enqueue_scripts','bp_course_admin_scripts');
function bp_course_admin_scripts(){
	wp_enqueue_script( 'bp-graph-js', plugins_url( '/vibe-course-module/includes/js/jquery.flot.min.js' ) );
}
?>