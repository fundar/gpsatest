<?php

/********************************************************************************
 * Screen Functions
 *
 * Screen functions are the controllers of BuddyPress. They will execute when their
 * specific URL is caught. They will first save or manipulate data using business
 * functions, then pass on the user to a template file.
 */



/**
 * If your component uses a top-level directory, this function will catch the requests and load
 * the index page.
 *
 * @package BuddyPress_Template_Pack
 * @since 1.6
 */
function bp_course_directory_setup() {
	if ( bp_is_course_component() && !bp_current_action() && !bp_current_item() ) {
		// This wrapper function sets the $bp->is_directory flag to true, which help other
		// content to display content properly on your directory.
		bp_update_is_directory( true, BP_COURSE_SLUG );

		// Add an action so that plugins can add content or modify behavior
		do_action( 'bp_course_directory_setup' );

		bp_core_load_template( apply_filters( 'course_directory_template', 'course/index' ) );
	}
}
add_action( 'bp_screens', 'bp_course_directory_setup' );


function bp_course_my_results(){
	do_action( 'bp_course_screen_my_results' );
	bp_core_load_template( apply_filters( 'bp_course_template_my_courses', 'members/single/home' ) );
}

/**
 * bp_course_my_courses()
 *
 * Sets up and displays the screen output for the sub nav item "course/my_courses"
 */

function bp_course_my_courses() {

	do_action( 'bp_course_screen_my_courses' );

	bp_core_load_template( apply_filters( 'bp_course_template_my_courses', 'members/single/home' ) );
}

function bp_course_stats() {
	do_action( 'bp_course_screen_course_stats' );
	bp_core_load_template( apply_filters( 'bp_course_template_course_stats', 'members/single/home' ) );

}

/**
 * bp_course_instructor_courses()
 *
 * Sets up and displays the screen output for the sub nav item "course/instructor-courses"
 */

function bp_course_instructor_courses() {

	do_action( 'bp_course_instructing_courses' );

	bp_core_load_template( apply_filters( 'bp_course_instructor_courses', 'members/single/home' ) );
}
/**
 * The following screen functions are called when the Settings subpanel for this component is viewed
 */
function bp_course_screen_settings_menu() {
	global $bp, $current_user, $bp_settings_updated, $pass_error;

	if ( isset( $_POST['submit'] ) ) {
		/* Check the nonce */
		check_admin_referer('bp-course-admin');

		$bp_settings_updated = true;

		/**
		 * This is when the user has hit the save button on their settings.
		 * The best place to store these settings is in wp_usermeta.
		 */
		update_user_meta( $bp->loggedin_user->id, 'bp-course-option-one', attribute_escape( $_POST['bp-course-option-one'] ) );
	}

	add_action( 'bp_template_content_header', 'bp_course_screen_settings_menu_header' );
	add_action( 'bp_template_title', 'bp_course_screen_settings_menu_title' );
	add_action( 'bp_template_content', 'bp_course_screen_settings_menu_content' );

	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

	function bp_course_screen_settings_menu_header() {
		_e( 'Course Settings Header', 'bp-course' );
	}

	function bp_course_screen_settings_menu_title() {
		_e( 'course Settings', 'bp-course' );
	}

	function bp_course_screen_settings_menu_content() {
		global $bp, $bp_settings_updated; ?>

		<?php if ( $bp_settings_updated ) { ?>
			<div id="message" class="updated fade">
				<p><?php _e( 'Changes Saved.', 'bp-course' ) ?></p>
			</div>
		<?php } ?>

		<form action="<?php echo $bp->loggedin_user->domain . 'settings/course-admin'; ?>" name="bp-course-admin-form" id="account-delete-form" class="bp-course-admin-form" method="post">

			<input type="checkbox" name="bp-course-option-one" id="bp-course-option-one" value="1"<?php if ( '1' == get_user_meta( $bp->loggedin_user->id, 'bp-course-option-one', true ) ) : ?> checked="checked"<?php endif; ?> /> <?php _e( 'Do you love clicking checkboxes?', 'bp-course' ); ?>
			<p class="submit">
				<input type="submit" value="<?php _e( 'Save Settings', 'bp-course' ) ?> &raquo;" id="submit" name="submit" />
			</p>

			<?php
			/* This is very important, don't leave it out. */
			wp_nonce_field( 'bp-course-admin' );
			?>

		</form>
	<?php
	}


/*=== SINGLE COURSE SCREENS ====*/	


function bp_screen_course_home() {

	if ( ! bp_is_single_item() ) {
		return false;
	}

	do_action( 'bp_screen_course_home' );

	bp_core_load_template( apply_filters( 'bp_template_course_home', 'courses/single/home' ) );
}

function bp_screen_course_structure(){
	
}

?>