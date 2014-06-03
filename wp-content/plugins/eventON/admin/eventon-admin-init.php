<?php
/**
 * EventON Admin
 *
 * Main admin file which loads all settings panels and sets up admin menus.
 *
 * @author 		AJDE
 * @category 	Admin
 * @package 	EventON/Admin
 * @version     1.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Functions for the ajde_events post type
 */
 
include_once('eventon-admin-functions.php' );

include_once('eventon-admin-taxonomies.php' );

include_once('post_types/ajde_events.php' );

include_once('includes/welcome.php' );



/**
 * Setup the Admin menu in WordPress
 *
 * @access public
 * @return void
 */
function eventon_admin_menu() {
    global $menu, $eventon, $pagenow;

    if ( current_user_can( 'manage_eventon' ) )
    $menu[] = array( '', 'read', 'separator-eventon', '', 'wp-menu-separator eventon' );
	
	// check for saved plugin update status to modify menu button
	$licenses = get_option('_evo_licenses');
	$evo_notification = (!empty($licenses['eventon']['has_new_update']) && $licenses['eventon']['has_new_update'])? ' <span class="update-plugins count-1" title="1 Plugin Update"><span class="update-count">1</span></span>':null;
	
	// Create admin menu page 
	$main_page = add_menu_page(
		__('EventON - Event Calendar','eventon'), 
		'myEventON'.$evo_notification,
		'manage_eventon',
		'eventon',
		'eventon_settings_page', 
		AJDE_EVCAL_URL.'/assets/images/eventon_menu_icon.png'
	);


    add_action( 'load-' . $main_page, 'eventon_admin_help_tab' );	
	
	
	// includes
	if( $pagenow == 'post-new.php' || $pagenow == 'post.php' || $pagenow == 'edit.php' ) {
		include_once( 'post_types/ajde_events_meta_boxes.php' );
	}
}
add_action('admin_menu', 'eventon_admin_menu', 9);


// VERSION information and display
//add_action('after_plugin_row_eventON/eventon.php','eventon_plugin_row');
function eventon_plugin_row(){
	$version='55';
	$new_version =  __('There is a new version of Gravity Forms available.', 'gravityforms') .' <a class="thickbox" title="Gravity Forms" href="plugin-install.php?tab=plugin-information&plugin=gravityforms&TB_iframe=true&width=640&height=808">'. 
		sprintf(__('View version %s Details', 'gravityforms'), $version) . '</a>. ';

	echo '</tr><tr class="plugin-update-tr"><td colspan="3" class="plugin-update"><div class="update-message">' . $new_version . __('<a href="' . admin_url() . 'admin.php?page=gf_settings">Register</a> your copy of Gravity Forms to receive access to automatic upgrades and support. Need a license key? <a href="http://www.gravityforms.com">Purchase one now</a>.', 'gravityforms') . '</div></td>';
}

/**
 * Highlights the correct top level admin menu item for Settings
 */
function eventon_admin_menu_highlight() {
	global $submenu;
	
	if ( isset( $submenu['eventon'] )  )  {
		$submenu['eventon'][0][0] = 'Settings';
		//unset( $submenu['eventon'][2] );
	}
}

add_action( 'admin_head', 'eventon_admin_menu_highlight' );




/**
 * Include some admin files conditonally.
 *
 * @access public
 * @return void
 */
function eventon_admin_init() {
	global $pagenow, $typenow, $wpdb, $post;	
	
	if ( $typenow == 'post' && ! empty( $_GET['post'] ) ) {
		$typenow = $post->post_type;
	} elseif ( empty( $typenow ) && ! empty( $_GET['post'] ) ) {
        $post = get_post( $_GET['post'] );
        $typenow = $post->post_type;
    }
	
	if ( $typenow == '' || $typenow == "ajde_events" ) {
	
		// Event Post Only
		$print_css_on = array( 'post-new.php', 'post.php' );

		foreach ( $print_css_on as $page ){
			add_action( 'admin_print_styles-'. $page, 'eventon_admin_post_css' );
			add_action( 'admin_print_scripts-'. $page, 'eventon_admin_post_script' );
		}
		
		// filter event post permalink edit options
		if(!defined('EVO_SIN_EV')){
			eventon_perma_filter();
		}
	}
	
		
	// create necessary pages	
	$_eventon_create_pages = get_option('_eventon_create_pages'); // get saved status for creating pages
	$events_page = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name='events'");
	
	if(empty($events_page) && (empty($_eventon_create_pages) || $_eventon_create_pages!= 1)	){
		require_once( 'eventon-admin-install.php' );
		eventon_create_pages();
		update_option('_eventon_create_pages',1);
	}
	
	
}
add_action('admin_init', 'eventon_admin_init');



/**
 * Include and display the settings page.
 */
function eventon_settings_page() {
	

	include_once( 'eventon-admin-settings.php' );
	eventon_settings();
}

/** 
 *	Load styles for EVENT POST TYPE
 */
function eventon_admin_post_css() {
	global $wp_scripts;
	
	
	// JQ UI styles
	$jquery_version = isset( $wp_scripts->registered['jquery-ui-core']->ver ) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.9.2';		
	wp_enqueue_style( 'jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/' . $jquery_version . '/themes/smoothness/jquery-ui.css' );
	
	wp_enqueue_style( 'backend_evcal_post',AJDE_EVCAL_URL.'/assets/css/backend_evcal_post.css');
	wp_enqueue_style( 'evo_backend_admin',AJDE_EVCAL_URL.'/assets/css/admin.css');
	
}

/** 
 *	Load scripts for EVENT POST TYPE
 */
function eventon_admin_post_script() {
	
	/** COLOR PICKER **/
	wp_enqueue_script('color_picker',AJDE_EVCAL_URL.'/assets/js/colorpicker.js' ,array('jquery'),'1.0', true);
	wp_enqueue_style( 'ajde_backender_colorpicker_styles',AJDE_EVCAL_URL.'/assets/css/colorpicker_styles.css');
	
	
	// other scripts 
	wp_enqueue_script('evcal_backend_post',AJDE_EVCAL_URL.'/assets/js/admin/eventon_backend_post.js', array('jquery','jquery-ui-core','jquery-ui-datepicker'), 1.0, true );
	
	wp_localize_script( 'evcal_backend_post', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));	
	
	do_action('eventon_admin_post_script');
}


/**
 * Include admin scripts and styles.
 */
function eventon_admin_scripts() {
	global $eventon, $pagenow;
	
	// JQuery UI Styles
	$calendar_ui_style_src = AJDE_EVCAL_URL.'/assets/css/jquery-ui.min.css';
	wp_enqueue_style( 'eventon_JQ_UI',$calendar_ui_style_src);
	
	// Scripts/ Styles for eventON Settings page only		
	if( (!empty($pagenow) && $pagenow=='admin.php')
	 && (!empty($_GET['page']) && $_GET['page']=='eventon'|| $_GET['page']=='action_user') 
	 ){
		wp_enqueue_script('evcal_backend_all',AJDE_EVCAL_URL.'/assets/js/eventon_all_backend.js',array('jquery'),1.0,true);
		wp_enqueue_script('evcal_backend',AJDE_EVCAL_URL.'/assets/js/eventon_backend.js',array('jquery'),1.0,true);		
		wp_enqueue_style( 'backend_evcal_settings',AJDE_EVCAL_URL.'/assets/css/backend_evcal_settings.css');
		wp_enqueue_style( 'evo_backend_admin',AJDE_EVCAL_URL.'/assets/css/admin.css');
		
		wp_localize_script( 'evcal_backend_all', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
		
		// LOAD thickbox
		if(isset($_GET['tab']) && $_GET['tab']=='evcal_5'){
			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox');
		}
		
		$eventon->enqueue_backender_styles();
		$eventon->register_backender_scripts();

		do_action('eventon_admin_scripts');
	}
	
	// LOAD custom google fonts for skins		
	$gfont="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400,300";
	wp_register_style( 'evcal_google_fonts', $gfont, '', '', 'screen' );
	//wp_enqueue_style( 'evcal_google_fonts' );
}
add_action( 'admin_enqueue_scripts', 'eventon_admin_scripts' );




/** scripts and styles for all backend **/
function eventon_all_backend_files(){
	global $wp_version;

	wp_localize_script( 'evcal_backend_post', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
	wp_enqueue_script('evcal_backend_all',AJDE_EVCAL_URL.'/assets/js/eventon_all_backend.js',array('jquery'),1.0,true);

	global $eventon;
	wp_enqueue_style( 'eventon_admin_menu_styles', AJDE_EVCAL_URL . '/assets/css/menu.css' );
	wp_enqueue_style( 'evo_backend_admin',AJDE_EVCAL_URL.'/assets/css/admin.css');

	wp_register_style('evo_font_icons',AJDE_EVCAL_URL.'/assets/fonts/font-awesome.css');
	wp_enqueue_style( 'evo_font_icons' );	

	// styles for WP>=3.8
	if($wp_version>=3.8)
		wp_enqueue_style( 'newwp',AJDE_EVCAL_URL.'/assets/css/wp3.8.css');
}
add_action( 'admin_enqueue_scripts', 'eventon_all_backend_files' );



/**
 * Include and add help tabs to WordPress admin.
 */
function eventon_admin_help_tab() {
	include_once( 'eventon-admin-content.php' );
	eventon_admin_help_tab_content();
}

/**
 * Admin Head
 *
 * Outputs some styles in the admin <head> to show icons on the woocommerce admin pages
 */
function eventon_admin_head() {
	global $eventon;	?>
	
	<style type="text/css">
		#adminmenuwrap #menu-posts-ajde_events .wp-menu-image:before{content:"";}
		#menu-posts-ajde_events .wp-menu-image {background: url(<?php echo AJDE_EVCAL_URL;?>/assets/images/calendar-day.png) no-repeat 6px -17px !important;}
		#menu-posts-ajde_events:hover .wp-menu-image, #menu-posts-ajde_events.wp-has-current-submenu .wp-menu-image {background-position:6px 7px!important;}
		
		<?php if ( isset($_GET['taxonomy']) && $_GET['taxonomy']=='event_type' ) : ?>
			.icon32-posts-ajde_events { background-position: -64px -0px !important; }
		<?php elseif ( isset($_GET['taxonomy']) && $_GET['taxonomy']=='event_type_2' ) : ?>
			.icon32-posts-ajde_events { background-position: -64px -0px !important; }
		<?php endif; ?>
	</style>
	<?php
}

add_action('admin_head', 'eventon_admin_head');




/**
 * Duplicate event action
 */
function eventon_duplicate_event_action() {
	include_once('post_types/duplicate_event.php');
	eventon_duplicate_event();
}

add_action('admin_action_duplicate_event', 'eventon_duplicate_event_action');





?>