<?php
/*
Plugin Name: Vibe Course Module
Plugin URI: http://www.VibeThemes.com
Description: This is the Course module for WPLMS WordPress Theme by VibeThemes
Version: 1.3.2
Requires at least: WP 3.8, BuddyPress 1.9 
Tested up to: 1.9
License: (Themeforest License : http://themeforest.net/licenses)
Author: Mr.Vibe 
Author URI: http://www.VibeThemes.com
Network: true
*/

// Checks if Course Module is Installed
define( 'BP_COURSE_MOD_INSTALLED', 1 );

// Checks the Course Module Version and necessary changes are hooked to this component
define( 'BP_COURSE_MOD_VERSION', '1.0' );

// FILE PATHS of Course Module
define( 'BP_COURSE_MOD_PLUGIN_DIR', dirname( __FILE__ ) );

/* Database Version for Course Module */
define ( 'BP_COURSE_DB_VERSION', '1' );

define ( 'BP_COURSE_CPT', 'course' );
define ( 'BP_COURSE_SLUG', 'course' );


/* Only load the component if BuddyPress is loaded and initialized. */
function bp_course_init() {
	// Because our loader file uses BP_Component, it requires BP 1.5 or greater.
	if ( version_compare( BP_VERSION, '1.3', '>' ) )
		require( dirname( __FILE__ ) . '/includes/bp-course-loader.php' );
}
add_action( 'bp_include', 'bp_course_init' );

/* Setup procedures to be run when the plugin */
function bp_course_activate() {

}
register_activation_hook( __FILE__, 'bp_course_activate' );

/* clean up On deacativation */
function bp_course_deactivate() {
	
}
register_deactivation_hook( __FILE__, 'bp_course_deactivate' );

add_action( 'init', 'vibe_course_module_update' );
function vibe_course_module_update() {

    /* Load Plugin Updater */
    require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'autoupdate/class-plugin-update.php' );

    /* Updater Config */
    $config = array(
        'base'      => plugin_basename( __FILE__ ), //required
        'dashboard' => true,
        'repo_uri'  => 'http://www.vibethemes.com/',  //required
        'repo_slug' => 'vibe-course-module',  //required
    );

    /* Load Updater Class */
    new Vibe_Course_Module_Auto_Update( $config );
}

?>
