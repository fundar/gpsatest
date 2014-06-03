<?php

/*
Plugin Name: Vibe Custom Types
Plugin URI: http://www.vibethemes.com/
Description: This plugin creates Custom Post Types and Custom Meta boxes for WPLMS theme.
Version: 1.3.1
Author: Mr.Vibe
Author URI: http://www.vibethemes.com/
Text Domain: vibe
Domain Path: /lang/
*/

/*  Copyright 2013 VibeThemes  (email: vibethemes@gmail.com)

    This file is part of Relevanssi, a search plugin for WordPress.

    This is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This plugin is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Relevanssi.  If not, see <http://www.gnu.org/licenses/>.
*/

if( !defined('VIBE_PLUGIN_URL')){
    define('VIBE_PLUGIN_URL',plugins_url());
}

/*====== BEGIN VSLIDER======*/

include_once('custom-post-types.php');
include_once('errorhandle.php');
include_once('featured.php');
include_once('statistics.php');
include_once('settings.php');
include_once('metaboxes/meta_box.php');
include_once('metaboxes/library/vibe-editor.php');
include_once('custom_meta_boxes.php');

/*====== INSTALLATION HOOK ======*/        

register_activation_hook(__FILE__,'vibe_customtype_init');
function vibe_customtype_init() {
   //If anything is required here....
}

if(!function_exists('animation_effects')){
    function animation_effects(){
    }
}


add_action( 'init', 'vibe_custom_types_update' );
function vibe_custom_types_update() {

    /* Load Plugin Updater */
    require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'autoupdate/class-plugin-update.php' );

    /* Updater Config */
    $config = array(
        'base'      => plugin_basename( __FILE__ ), //required
        'dashboard' => true,
        'repo_uri'  => 'http://www.vibethemes.com/',  //required
        'repo_slug' => 'vibe-customtypes',  //required
    );

    /* Load Updater Class */
    new Vibe_Custom_Types_Auto_Update( $config );
}
?>
