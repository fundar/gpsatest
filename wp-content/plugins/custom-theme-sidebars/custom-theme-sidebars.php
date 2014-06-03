<?php
/**
 * Plugin Name: Custom WordPress Sidebar Plugin
 * Plugin URI: http://www.titaniumthemes.com/wordpress-sidebar-plugin
 * Description: A simple and easy way to add custom sidebars/widget areas to your WordPress theme.
 * Version: 1.4
 * Author: Titanium Themes
 * Author URI: http://www.titaniumthemes.com
 * License: GPL2
 * 
 */

/**
 * Theme Sidebar Generator
 *
 * This file is responsible for enabling custom sidebars
 * to be generated in the WordPress Admin Area. 
 * 
 * @package     WordPress
 * @subpackage  Custom_Theme_Sidebars
 * @author      Sunny Johal - Titanium Themes
 * @copyright   Copyright (c) 2014, Titanium Themes
 * @version     1.4
 * 
 */

/**
 * Load Plugin Files
 *
 * Load all of the sidebar interface functions and classes
 * and loads the translation text domain.
 *
 * @link    http://codex.wordpress.org/Function_Reference/load_plugin_textdomain     load_plugin_textdomain()
 * @link    http://codex.wordpress.org/Function_Reference/plugin_basename            plugin_basename()
 * @link    http://codex.wordpress.org/Function_Reference/plugin_dir_path            plugin_dir_path()
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_load_plugin_files() {
    
    // Load Plugin Translations
    load_plugin_textdomain( 'theme-translate', false, dirname( plugin_basename( __FILE__ ) ) );

    // Load Plugin Classes
    require_once ( plugin_dir_path(__FILE__) . '/includes/classes/class-master-walker-sidebar-edit.php' ); 
    require_once ( plugin_dir_path(__FILE__) . '/includes/classes/class-master-walker-sidebar-checklist.php' ); 
    
    // Load Plugin Functions
    require_once ( plugin_dir_path(__FILE__) . '/includes/theme-sidebar-functions.php' ); 
    require_once ( plugin_dir_path(__FILE__) . '/includes/theme-sidebar-admin-page-functions.php' ); 
    require_once ( plugin_dir_path(__FILE__) . '/includes/theme-sidebar-ajax-actions.php' ); 

}
add_action( 'plugins_loaded', 'master_load_plugin_files', 0 ); // High Priority Loading

/**
 * Create The Custom Sidebar Theme Page
 *
 * Registers a new theme page with WordPress which will
 * display the custom sidebar generator page.
 *
 * @link    http://codex.wordpress.org/Function_Reference/add_theme_page            add_theme_page() 
 * @link    http://codex.wordpress.org/Function_Reference/add_action                add_action() 
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_add_sidebar_page() {

    global $custom_theme_sidebars;

    if ( current_user_can( 'edit_theme_options' ) ) {
        $custom_theme_sidebars = add_theme_page( 
            __( 'Theme Sidebars', 'theme-translate' ), 
            __( 'Theme Sidebars', 'theme-translate' ), 
            'edit_theme_options', 
            'custom_theme_sidebars', 
            'master_output_sidebar_admin_page' );
        
        /*
         * Set up the custom sidebar metaboxes. Function is
         * defined in includes/theme-sidebar-admin-page-functions
         */
        master_setup_sidebar_metaboxes();

        /*
         * Use the retrieved $custom_theme_sidebars to hook the function that enqueues our styles/scripts.
         * This hook invokes the function only on our plugin administration screen,
         * see: http://codex.wordpress.org/Administration_Menus#Page_Hook_Suffix
         */
        add_action('admin_print_scripts-' . $custom_theme_sidebars, 'master_enqueue_sidebar_admin_styles');
        add_action('admin_print_scripts-' . $custom_theme_sidebars, 'master_enqueue_sidebar_admin_scripts');

        /*
         * Use the retrieved $custom_theme_sidebars to hook the function that enqueues our contextual help tabs.
         * This hook invokes the function only on our plugin administration screen,
         * see: http://codex.wordpress.org/Administration_Menus#Page_Hook_Suffix
         */
        add_action('load-'.$custom_theme_sidebars, 'master_add_sidebar_page_help_tabs');
        add_action('load-'.$custom_theme_sidebars, 'master_add_sidebar_screen_options_tab');        
    }

}
add_action( 'admin_menu', 'master_add_sidebar_page' );

/**
 * Generate Sidebar Settings Page
 *
 * This function is responsible for generating and outputting
 * the html settings page for the sidebar generator. This
 * functionality is based on the new WordPress nav menu screen
 * that is arriving in v3.6.
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_output_sidebar_admin_page() {
   require_once ( plugin_dir_path(__FILE__) . '/includes/theme-sidebar-admin-page.php' );
}

/**
 * Load Sidebar Admin Page JavaScript
 *
 * Will only load scripts on the sidebar admin page of the website. Hooks 
 * into the admin_print_scripts-custom_theme_sidebars action which is 
 * defined in the master_add_sidebar_page() function.
 *
 * @link    http://codex.wordpress.org/Function_Reference/wp_deregister_script  wp_deregister_script()
 * @link    http://codex.wordpress.org/Function_Reference/wp_register_script    wp_register_script()
 * @link    http://codex.wordpress.org/Function_Reference/wp_enqueue_script     wp_enqueue_script()
 * @link    http://codex.wordpress.org/Function_Reference/wp_localize_script    wp_localize_script()
 * @link    http://codex.wordpress.org/Function_Reference/wp_is_mobile          wp_is_mobile()
 * @link    http://codex.wordpress.org/Function_Reference/plugins_url           plugins_url()
 * 
 * @since 1.0
 * @version 1.4
 *
 */
function master_enqueue_sidebar_admin_scripts() {
    
    // Load jQuery and jQuery UI
    wp_enqueue_script ( 'jquery' );
    wp_enqueue_script ( 'utils' );
    wp_enqueue_script ( 'jquery-ui-core' );
    wp_enqueue_script ( 'jquery-effects-core' );
    wp_enqueue_script ( 'jquery-effects-fade' );
    wp_enqueue_script ( 'jquery-ui-sortable' );
    wp_enqueue_script ( 'jquery-ui-position' );
    wp_enqueue_script ( 'jquery-ui-widget' );
    wp_enqueue_script ( 'jquery-ui-mouse' );
    wp_enqueue_script ( 'jquery-ui-draggable' );
    wp_enqueue_script ( 'jquery-ui-droppable' );

    // Load PostBox
    wp_enqueue_script ( 'postbox' );

    // Sidebar Shortcode Mananger Admin (Registered here and enqueued on the page itself)
    wp_deregister_script('master-sidebar-menu');
    wp_register_script( 
        'master-sidebar-menu', 
        plugins_url( 'custom-theme-sidebars' ) . '/js/sidebar-menu.js', 
        array('jquery'), 
        '1.0', 
        false 
    );
    wp_enqueue_script( 'master-sidebar-menu' );

    // Sidebar Shortcode Mananger Accordion (Registered here and enqueued on the page itself)
    wp_deregister_script('accordion-sidebar');
    wp_register_script( 
        'accordion-sidebar', 
        plugins_url( 'custom-theme-sidebars' ) . '/js/accordion-sidebar.js', 
        array('jquery'),
        '1.0', 
        false 
    );
    wp_enqueue_script( 'accordion-sidebar' );

    if ( wp_is_mobile() ) 
            wp_enqueue_script( 'jquery-touch-punch' );

    // Translation JavaScript Object Variables
    $l10n = array(
        'activateSidebar'            => '&mdash; ' . __( 'Select a Sidebar', 'theme-translate' ) . ' &mdash;',
        'addButtonText'              => __( 'Add to Sidebar', 'theme-translate' ),
        'ajax_url'                   => admin_url( 'admin-ajax.php' ),
        'confirmation'               => __( 'This page is asking you to confirm that you want to leave - data you have entered may not be saved.', 'theme-translate' ),
        'deleteAllWarning'           => __( "Warning! You are about to permanently delete all sidebars. 'Cancel' to stop, 'OK' to delete.", 'theme-translate' ),
        'deleteWarning'              => __( "You are about to permanently delete this sidebar. 'Cancel' to stop, 'OK' to delete.", 'theme-translate' ),
        'deactivateSidebar'          => '&mdash; ' . __( 'Deactivate Sidebar', 'theme-translate' ) . '&mdash; ',
        'leavePage'                  => __( 'Leave Page', 'theme-translate' ) ,
        'stayOnPage'                 => __( 'Stay on Page', 'theme-translate' ) ,
        'noResultsFound'             => __( 'No Results Found.', 'theme-translate' ),
        'oneThemeLocationNoSidebars' => __( 'No Sidebars', 'theme-translate')
    );
    wp_localize_script( 'master-sidebar-menu', 'sidebarsL10n', $l10n );
}

/**
 * Load Sidebar Admin Page Styles
 *
 * Load CSS on the sidebar admin page of the website. Hooks into the 
 * admin_print_scripts-custom_theme_sidebars action which is defined 
 * in the master_add_sidebar_page() function.
 * 
 * @link    http://codex.wordpress.org/Function_Reference/wp_deregister_style   wp_deregister_style()
 * @link    http://codex.wordpress.org/Function_Reference/wp_register_style     wp_register_style()
 * @link    http://codex.wordpress.org/Function_Reference/wp_enqueue_style      wp_enqueue_style()
 * @link    http://codex.wordpress.org/Function_Reference/plugins_url           plugins_url()
 *
 * @since 1.0
 * @version  1.4
 * 
 */
function master_enqueue_sidebar_admin_styles() {
    wp_deregister_style('sidebar-admin-css');
    wp_register_style( 
        'sidebar-admin-styles', 
        plugins_url( 'custom-theme-sidebars' ) . '/css/sidebar-admin.css', 
        null, 
        '1.0', 
        false 
    );
    wp_enqueue_style( 'sidebar-admin-styles' );  
}

/**
 * Add Help Tabs To The Custom Sidebar Page
 *
 * Adds contextual help tabs to the custom themes sidebar page.
 * This function is attached to an action that ensures that the
 * help tabs are only displayed on the custom sidebar page.
 *
 * @uses global $custom_theme_sidebars
 * @link    http://codex.wordpress.org/Function_Reference/get_current_screen      get_current_screen()
 * @link    http://codex.wordpress.org/Function_Reference/add_help_tab            add_help_tab()
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_add_sidebar_page_help_tabs(){
    global $custom_theme_sidebars;

     $screen = get_current_screen();

    /*
     * Don't add help tab if the current screen is not 
     * the custom sidebar page
     */
    if ( $screen->id != $custom_theme_sidebars )
        return;

    // Overview Tab
    $overview  = '<p>' . __( 'This screen is used for managing your custom sidebar menus. It provides a way to replace the default sidebars that have been registed with your theme. If your theme does not natively support sidebar widget areas you can learn about adding this support by following the Documentation link to the side.', 'theme-translate' ) . '</p>';
    $overview .= '<p>' . __( 'From this screen you can:' ) . '</p>';
    $overview .= '<ul><li>' . __( 'Create, edit, and delete custom sidebars', 'theme-translate' ) . '</li>';
    $overview .= '<li>' . __( 'Choose which sidebar you would like to replace', 'theme-translate' ) . '</li>';
    $overview .= '<li>' . __( 'Add, organize, and modify pages/posts etc that belong to a custom sidebar', 'theme-translate' ) . '</li></ul>';
    
    $screen->add_help_tab( array(
        'id'      => 'overview',
        'title'   => __('Overview', 'theme-translate'),
        'content' => $overview,
    ) );

    $screen->set_help_sidebar(
        '<p><strong>' . __('For more information:', 'theme-translate') . '</strong></p>' .
        '<p><a href="http://codex.wordpress.org/Function_Reference/register_sidebar" target="_blank">' . __('Documentation on Registering Sidebars', 'theme-translate') . '</a></p>' .
        '<p><a href="http://wordpress.org/support/" target="_blank">' . __('Support Forums') . '</a></p>'
    );
}

/**
 * Get Help Tab Options
 *
 * This function has been created in order to give developers
 * a hook by which to add their own screen options.
 *
 * @since 1.0
 * @version 1.4
 * 
 */
function master_add_sidebar_screen_options_tab() {
   
    global $custom_theme_sidebars;

    $screen = get_current_screen();

    /*
     * Don't add help tab if the current screen is not 
     * the custom sidebar page
     */
    if ( $screen->id != $custom_theme_sidebars )
        return;
    
    // Only display the Screen Options tab on the edit sidebars page
    $is_edit_screen = true;

    if ( isset( $_GET['screen'] ) ) {
        if( $_GET['screen'] == 'sidebar_replacements' ) {
            $is_edit_screen = false;
        }
    }

    if ( $is_edit_screen ) {

    }  
}
