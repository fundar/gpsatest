<?php
/*
Plugin Name: WPLMS Customizer Plugin
Plugin URI: http://www.Vibethemes.com
Description: A simple WordPress plugin to modify WPLMS template
Version: 1.0
Author: VibeThemes
Author URI: http://www.vibethemes.com
License: GPL2
*/
/*
Copyright 2014  VibeThemes  (email : vibethemes@gmail.com)

wplms_customizer program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

wplms_customizer program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with wplms_customizer program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


include_once 'classes/customizer_class.php';



if(class_exists('WPLMS_Customizer_Plugin_Class'))
{	
    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('WPLMS_Customizer_Plugin_Class', 'activate'));
    register_deactivation_hook(__FILE__, array('WPLMS_Customizer_Plugin_Class', 'deactivate'));

    // instantiate the plugin class
    $wplms_customizer = new WPLMS_Customizer_Plugin_Class();
}

function wplms_customizer_enqueue_scripts(){
    wp_enqueue_style( 'wplms-customizer-css', plugins_url( 'css/custom.css' , __FILE__ ));
    wp_enqueue_script( 'wplms-customizer-js', plugins_url( 'js/custom.js' , __FILE__ ));
}

add_action('wp_head','wplms_customizer_enqueue_scripts');

add_action('wp_enqueue_scripts','wplms_customizer_custom_cssjs');

/**
 * Objective: Register & Enqueue your Custom scripts
 * Developer notes:
 * Hook you custom scripts required for the plugin here.
 */
function wplms_customizer_custom_cssjs(){
    wp_enqueue_style( 'wplms-customizer-css', plugins_url( 'css/custom.css' , __FILE__ ));
    wp_enqueue_script( 'wplms-customizer-js', plugins_url( 'js/custom.js' , __FILE__ ));
}

?>