<?php
/*
Plugin Name: WonderPlugin Carousel
Plugin URI: http://www.wonderplugin.com
Description: WordPress Image and Video Carousel Plugin
Version: 1.1
Author: Magic Hills Pty Ltd
Author URI: http://www.wonderplugin.com
License: Copyright 2014 Magic Hills Pty Ltd, All Rights Reserved
*/

define('WONDERPLUGIN_CAROUSEL_VERSION', '1.1');
define('WONDERPLUGIN_CAROUSEL_URL', plugin_dir_url( __FILE__ ));
define('WONDERPLUGIN_CAROUSEL_PATH', plugin_dir_path( __FILE__ ));

require_once 'app/class-wonderplugin-carousel-controller.php';

class WonderPlugin_Carousel_Plugin {
	
	function __construct() {
	
		$this->init();
	}
	
	public function init() {
		
		// init controller
		$this->wonderplugin_carousel_controller = new WonderPlugin_Carousel_Controller();
		
		add_action( 'admin_menu', array($this, 'register_menu') );
		
		add_shortcode( 'wonderplugin_carousel', array($this, 'shortcode_handler') );
		
		add_action( 'wp_footer', array($this, 'add_lightbox_options') );
		add_action( 'init', array($this, 'register_script') );
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_script') );
		
		if ( is_admin() )
		{
			add_action( 'wp_ajax_wonderplugin_carousel_save_item', array($this, 'wp_ajax_save_item') );
			add_action( 'admin_init', array($this, 'admin_init_hook') );
		}
	}
	
	function register_menu()
	{
		$menu = add_menu_page(
				__('WonderPlugin Carousel', 'wonderplugin_carousel'),
				__('WonderPlugin Carousel', 'wonderplugin_carousel'),
				'manage_options',
				'wonderplugin_carousel_overview',
				array($this, 'show_overview'),
				WONDERPLUGIN_CAROUSEL_URL . 'images/logo-16.png' );
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		$menu = add_submenu_page(
				'wonderplugin_carousel_overview',
				__('Overview', 'wonderplugin_carousel'),
				__('Overview', 'wonderplugin_carousel'),
				'manage_options',
				'wonderplugin_carousel_overview',
				array($this, 'show_overview' ));
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		$menu = add_submenu_page(
				'wonderplugin_carousel_overview',
				__('New Carousel', 'wonderplugin_carousel'),
				__('New Carousel', 'wonderplugin_carousel'),
				'manage_options',
				'wonderplugin_carousel_add_new',
				array($this, 'add_new' ));
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		$menu = add_submenu_page(
				'wonderplugin_carousel_overview',
				__('Manage Carousels', 'wonderplugin_carousel'),
				__('Manage Carousels', 'wonderplugin_carousel'),
				'manage_options',
				'wonderplugin_carousel_show_items',
				array($this, 'show_items' ));
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		$menu = add_submenu_page(
				null,
				__('View Carousel', 'wonderplugin_carousel'),
				__('View Carousel', 'wonderplugin_carousel'),	
				'manage_options',	
				'wonderplugin_carousel_show_item',	
				array($this, 'show_item' ));
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		$menu = add_submenu_page(
				null,
				__('Edit Carousel', 'wonderplugin_carousel'),
				__('Edit Carousel', 'wonderplugin_carousel'),
				'manage_options',
				'wonderplugin_carousel_edit_item',
				array($this, 'edit_item' ) );
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
	}
	
	function register_script()
	{		
		wp_register_script('wonderplugin-carousel-template-script', WONDERPLUGIN_CAROUSEL_URL . 'app/wonderplugincarouseltemplate.js', array('jquery'), WONDERPLUGIN_CAROUSEL_VERSION, false);
		wp_register_script('wonderplugin-carousel-skins-script', WONDERPLUGIN_CAROUSEL_URL . 'engine/wonderplugincarouselskins.js', array('jquery'), WONDERPLUGIN_CAROUSEL_VERSION, false);
		wp_register_script('wonderplugin-carousel-script', WONDERPLUGIN_CAROUSEL_URL . 'engine/wonderplugincarousel.js', array('jquery'), WONDERPLUGIN_CAROUSEL_VERSION, false);
		wp_register_script('wonderplugin-carousel-creator-script', WONDERPLUGIN_CAROUSEL_URL . 'app/wonderplugin-carousel-creator.js', array('jquery'), WONDERPLUGIN_CAROUSEL_VERSION, false);
		wp_register_style('wonderplugin-carousel-admin-style', WONDERPLUGIN_CAROUSEL_URL . 'wonderplugincarousel.css');
	}
	
	function enqueue_script()
	{
		wp_enqueue_script('wonderplugin-carousel-skins-script');
		wp_enqueue_script('wonderplugin-carousel-script');
	}
	
	function enqueue_admin_script($hook)
	{
		wp_enqueue_script('post');
		if (function_exists("wp_enqueue_media"))
		{
			wp_enqueue_media();
		}
		else
		{
			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox');
			wp_enqueue_script('media-upload');
		}
		wp_enqueue_script('wonderplugin-carousel-template-script');
		wp_enqueue_script('wonderplugin-carousel-skins-script');
		wp_enqueue_script('wonderplugin-carousel-script');
		wp_enqueue_script('wonderplugin-carousel-creator-script');
		wp_enqueue_style('wonderplugin-carousel-admin-style');
	}
	
	function add_lightbox_options()
	{
		echo '<div id="wondercarousellightbox_options" data-skinsfoldername=""  data-jsfolder="' . WONDERPLUGIN_CAROUSEL_URL . 'engine/" style="display:none;"></div>';
	}
	
	function admin_init_hook()
	{
		// change text of history media uploader
		if (!function_exists("wp_enqueue_media"))
		{
			global $pagenow;
			
			if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {
				add_filter( 'gettext', array($this, 'replace_thickbox_text' ), 1, 3 );
			}
		}
		
		// add meta boxes
		$this->wonderplugin_carousel_controller->add_metaboxes();
	}
	
	function replace_thickbox_text($translated_text, $text, $domain) {
		
		if ('Insert into Post' == $text) {
			$referer = strpos( wp_get_referer(), 'wonderplugin-carousel' );
			if ( $referer != '' ) {
				return __('Insert into carousel', 'wonderplugin_carousel' );
			}
		}
		return $translated_text;
	}
	
	function show_overview() {
		
		$this->wonderplugin_carousel_controller->show_overview();
	}
	
	function show_items() {
		
		$this->wonderplugin_carousel_controller->show_items();
	}
	
	function add_new() {
		
		$this->wonderplugin_carousel_controller->add_new();
	}
	
	function show_item() {
		
		$this->wonderplugin_carousel_controller->show_item();
	}
	
	function edit_item() {
	
		$this->wonderplugin_carousel_controller->edit_item();
	}
	
	function shortcode_handler($atts) {
		
		if ( !isset($atts['id']) )
			return __('Please specify a carousel id', 'wonderplugin_carousel');
		
		return $this->wonderplugin_carousel_controller->generate_body_code( $atts['id'], false);
	}
	
	function wp_ajax_save_item() {
				
		header('Content-Type: application/json');
		echo json_encode($this->wonderplugin_carousel_controller->save_item($_POST["item"]));
		die();
	}
	
}

/**
 * Init the plugin
 */
$wonderplugin_carousel_plugin = new WonderPlugin_Carousel_Plugin();

/**
 * Global php function
 * @param $id
 */
function wonderplugin_carousel($id) {

	echo $wonderplugin_carousel_plugin->wonderplugin_carousel_controller->generate_body_code($id, false);
}

/**
 * Uninstallation
 */
function wonderplugin_carousel_uninstall() {

	global $wpdb;
	$table_name = $wpdb->prefix . "wonderplugin_carousel";
	$wpdb->query("DROP TABLE IF EXISTS $table_name");
}

if ( function_exists('register_uninstall_hook') )
{
	register_uninstall_hook( __FILE__, 'wonderplugin_carousel_uninstall' );
}

define('WONDERPLUGIN_CAROUSEL_VERSION_TYPE', 'F');
