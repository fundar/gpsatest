<?php
/*
Plugin Name: WPLMS Events
Plugin URI: http://www.Vibethemes.com
Description: COURSE Events plugin for WPLMS 
Version: 1.7.5
Author: VibeThemes
Author URI: http://www.vibethemes.com
License: as Per Themeforest GuideLines
*/
/*
Copyright 2014  VibeThemes  (email : vibethemes@gmail.com)

WPLMS Events is a plugin made for WPLMS Theme. This plugin is only meant to work with WPLMS and can only be used with WPLMS.
WPLMS Events program is not a free software; you can not redistribute it and/or modify.
Please consult VibeThemes.com or email us at vibethemes@gmail.com.

*/

if ( !defined( 'ABSPATH' ) ) exit;
include_once 'classes/events_class.php';
include_once 'classes/events_widget.php';


define ( 'WPLMS_EVENTS_CPT', 'wplms-event' );
define ( 'WPLMS_EVENTS_SLUG', 'event' );

if(class_exists('WPLMS_Events_Interface'))
{	
    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('WPLMS_Events_Interface', 'activate'));
    register_deactivation_hook(__FILE__, array('WPLMS_Events_Interface', 'deactivate'));

    // instantiate the plugin class
    $wplms_events = new WPLMS_Events_Interface();
}


add_action( 'init', 'wplms_events_update' );
function wplms_events_update() {

	/* Load Plugin Updater */
	require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'autoupdate/class-plugin-update.php' );

	/* Updater Config */
	$config = array(
		'base'      => plugin_basename( __FILE__ ), //required
		'dashboard' => true,
		'repo_uri'  => 'http://www.vibethemes.com/',  //required
		'repo_slug' => 'wplms-events',  //required
	);

	/* Load Updater Class */
	new WPLMS_Events_Auto_Update( $config );
}


add_action( 'plugins_loaded', 'wplms_events_language_setup' );
function wplms_events_language_setup(){
    $locale = apply_filters("plugin_locale", get_locale(), 'wplms-events');
    
    $lang_dir = dirname( __FILE__ ) . '/languages/';
    $mofile        = sprintf( '%1$s-%2$s.mo', 'wplms-events', $locale );
    $mofile_local  = $lang_dir . $mofile;
    $mofile_global = WP_LANG_DIR . '/plugins/' . $mofile;

    if ( file_exists( $mofile_global ) ) {
        load_textdomain( 'wplms-events', $mofile_global );
    } else {
        load_textdomain( 'wplms-events', $mofile_local );
    }   
}
?>