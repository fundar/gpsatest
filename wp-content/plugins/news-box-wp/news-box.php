<?php
/* 
Plugin Name: News Box
Plugin URI: http://codecanyon.net/user/LCweb?ref=LCweb
Description: Display contents from different sources in an unique, responsive, environment. Create your own style and customize every single aspect of the box.
Author: Luca Montanari
Version: 1.03
Author URI: http://codecanyon.net/user/LCweb?ref=LCweb
*/  


/////////////////////////////////////////////
/////// MAIN DEFINES ////////////////////////
/////////////////////////////////////////////

// plugin path
$wp_plugin_dir = substr(plugin_dir_path(__FILE__), 0, -1);
define( 'NB_DIR', $wp_plugin_dir);

// plugin url
$wp_plugin_url = substr(plugin_dir_url(__FILE__), 0, -1);
define( 'NB_URL', $wp_plugin_url);



/////////////////////////////////////////////
/////// MULTILANGUAGE SUPPORT ///////////////
/////////////////////////////////////////////

function nb_multilanguage() {
	$param_array = explode('/', NB_DIR);
 	$folder_name = end($param_array);
	load_plugin_textdomain( 'nb_ml', false, $folder_name . '/languages'); 
}
add_action('init', 'nb_multilanguage', 1);



/////////////////////////////////////////////
/////// MAIN SCRIPT & CSS INCLUDES //////////
/////////////////////////////////////////////

// check for jQuery UI slider
function nb_register_scripts() {
    global $wp_scripts;
    if( !is_object( $wp_scripts ) ) {return;}
	
    if( !isset( $wp_scripts->registered['jquery-ui-slider'] ) ) {
		wp_register_script('lcwp-jquery-ui-slider', NB_URL.'/js/jquery.ui.slider.min.js', 999, '1.8.16', true);
		wp_enqueue_script('lcwp-jquery-ui-slider');
	}
	else {wp_enqueue_script('jquery-ui-slider');}
 
	return true;
}


// global script enqueuing
function nb_global_scripts() {
	wp_enqueue_script('jquery');

	// admin css & js
	if (is_admin()) {  
		nb_register_scripts();
		wp_enqueue_style('nb_admin', NB_URL . '/css/admin.css', 999);
		
		// chosen
		wp_enqueue_style( 'lcwp-chosen-style', NB_URL.'/js/chosen/chosen.css', 999);
		
		// iphone checks
		wp_enqueue_style( 'lcwp-ip-checks', NB_URL.'/js/iphone_checkbox/style.css', 999);
		
		// colorpicker
		wp_enqueue_style( 'nb-colpick', NB_URL.'/js/colpick/css/colpick.css', 999);
		
		// LCWP jQuery ui
		wp_enqueue_style( 'lcwp-ui-theme', NB_URL.'/css/ui-wp-theme/jquery-ui-1.8.17.custom.css', 999);
		
		wp_enqueue_script('jquery-ui-tabs' );
	}
	
	if (!is_admin()) {
		// frontent JS on header or footer
		if(get_option('nb_js_head') != '1') {
			wp_enqueue_script('nb-core', NB_URL.'/js/nb/news-box.min.js', 100, '1.03-1.24', true);	
		} else { 
			wp_enqueue_script('nb-core', NB_URL.'/js/nb/news-box.min.js', 99, '1.03-1.24');
		}
		
		// global JS vars
		add_action('wp_footer', 'nb_footer_js', 999);
		
		// core CSS
		wp_enqueue_style('nb-core',  NB_URL.'/js/nb/news-box-layout.min.css', 900);

		// frontend custom css inline
		if(get_option('nb_inline_css') || get_option('nb_force_inline_css')) {
			add_action('wp_head', 'nb_inline_css', 989);
		}	
	}
}
add_action('init', 'nb_global_scripts');


// USE FRONTEND CSS INLINE
function nb_inline_css(){
	$custom_css = get_option('nb_custom_css');
	if(!empty($custom_css)) {echo '<style type="text/css">'.$custom_css.'</style>';}
	
	// if locked server - use custom theme inline
	if(get_option('nb_custom_style')) {
		if(get_option('nb_inline_css') || get_option('nb_force_inline_css')) {
			echo '<style type="text/css">';
			include_once(NB_DIR . '/custom_theme_css.php');
			echo '</style>';		
		}
	}
}


// FOOTER JAVASCRIPT - GLOBAL VARS AND INLINE THEME TWEAK
function nb_footer_js(){
	?>
    <script type="text/javascript">
	nb_lightbox = <?php echo (get_option('nb_lightbox')) ? 'true' : 'false'; ?>;
	nb_touchswipe = <?php echo (get_option('nb_touchswipe')) ? 'true' : 'false'; ?>;
	nb_min_news_h = <?php echo get_option('nb_min_news_h', 150) ?>;
	nb_min_news_w = <?php echo get_option('nb_min_news_w', 200) ?>;
	nb_min_horiz_w = <?php echo get_option('nb_min_horiz_w', 400) ?>;
	nb_read_more_txt = <?php echo '"'.str_replace('"', '\"', get_option('nb_read_more_txt', '..')).'"' ?>;
	nb_fb_share_fix = <?php echo '"'.NB_URL.'/lcis_fb_img_fix.php"' ?>;
	nb_script_basepath = <?php echo '"'.NB_URL.'/js/nb/"' ?>;
	
	nb_short_d_names = ["<?php _e('Sun', 'nb_ml') ?>", "<?php _e('Mon', 'nb_ml') ?>", "<?php _e('Tue', 'nb_ml') ?>", "<?php _e('Wed', 'nb_ml') ?>", "<?php _e('Thu', 'nb_ml') ?>", "<?php _e('Fri', 'nb_ml') ?>", "<?php _e('Sat', 'nb_ml') ?>"];
	nb_full_d_names = ["<?php _e('Sunday', 'nb_ml') ?>", "<?php _e('Monday', 'nb_ml') ?>", "<?php _e('Tuesday', 'nb_ml') ?>", "<?php _e('Wednesday', 'nb_ml') ?>", "<?php _e('Thursday', 'nb_ml') ?>", "<?php _e('Friday', 'nb_ml') ?>", "<?php _e('Saturday', 'nb_ml') ?>"];
	nb_short_m_names = ["<?php _e('Jan', 'nb_ml') ?>", "<?php _e('Feb', 'nb_ml') ?>", "<?php _e('Mar', 'nb_ml') ?>", "<?php _e('Apr', 'nb_ml') ?>", "<?php _e('May', 'nb_ml') ?>", "<?php _e('Jun', 'nb_ml') ?>", "<?php _e('Jul', 'nb_ml') ?>", "<?php _e('Aug', 'nb_ml') ?>", "<?php _e('Sep', 'nb_ml') ?>", "<?php _e('Oct', 'nb_ml') ?>", "<?php _e('Nov', 'nb_ml') ?>", "<?php _e('Dec', 'nb_ml') ?>"];
	nb_full_m_names = ["<?php _e('January', 'nb_ml') ?>", "<?php _e('February', 'nb_ml') ?>", "<?php _e('March', 'nb_ml') ?>", "<?php _e('April', 'nb_ml') ?>", "<?php _e('May', 'nb_ml') ?>", "<?php _e('June', 'nb_ml') ?>", "<?php _e('July', 'nb_ml') ?>", "<?php _e('August', 'nb_ml') ?>", "<?php _e('September', 'nb_ml') ?>", "<?php _e('October', 'nb_ml') ?>", "<?php _e('November', 'nb_ml') ?>", "<?php _e('December', 'nb_ml') ?>"];
	nb_elapsed_names = ["<?php _e('ago', 'nb_ml') ?>", "<?php _e('seconds', 'nb_ml') ?>", "<?php _e('minute', 'nb_ml') ?>", "<?php _e('minutes', 'nb_ml') ?>", "<?php _e('hour', 'nb_ml') ?>", "<?php _e('hours', 'nb_ml') ?>", "<?php _e('day', 'nb_ml') ?>", "<?php _e('days', 'nb_ml') ?>", "<?php _e('week', 'nb_ml') ?>", "<?php _e('weeks', 'nb_ml') ?>", "<?php _e('month', 'nb_ml') ?>", "<?php _e('months', 'nb_ml') ?>"];
	
	<?php if(get_option('nb_custom_style') && (get_option('nb_inline_css') || get_option('nb_force_inline_css'))) : ?>
	if( typeof(lcnb_loaded_themes) == 'undefined' ) {lcnb_loaded_themes = new Array();}
	lcnb_loaded_themes.push('wpdt');
	<?php endif; ?>
	</script>
	<?php
}



/////////////////////////////////////////////
/////// MAIN INCLUDES ///////////////////////
/////////////////////////////////////////////

// admin menu and cpt and taxonomy
include_once(NB_DIR . '/admin_menu.php');

// quick news metaboxes
include_once(NB_DIR . '/metaboxes.php');

// shortcode
include_once(NB_DIR . '/shortcodes.php');

// tinymce btn
include_once(NB_DIR . '/tinymce_btn.php');

// ajax
include_once(NB_DIR . '/ajax.php');

// date placeholders helper
include_once(NB_DIR . '/date_format_helper.php');

// box preview
include_once(NB_DIR . '/nb_preview.php');


////////////
// UPDATE NOTIFIER
if(!class_exists('lc_update_notifier')) {
	include_once(NB_DIR . '/lc_update_notifier.php');
}
$lcun = new lc_update_notifier(__FILE__, 'http://projects.lcweb.it/envato_update/nb-wp.php');
////////////




//////////////////////////////////////////////////
// ACTIONS ON PLUGIN ACTIVATION
function nb_init_custom_css() {
	include_once(NB_DIR . '/functions.php');
	
	// if enabled - create custom theme
	if(get_option('nb_custom_style')) {
		if(!nb_create_custom_theme()) {
			if(!get_option('nb_inline_css')) {update_option('nb_inline_css', 1);}
		}
		else {delete_option('nb_inline_css');}
	}
}
register_activation_hook(__FILE__, 'nb_init_custom_css');




//////////////////////////////////////////////////
// REMOVE WP HELPER FROM PLUGIN PAGES

function nb_remove_wp_helper() {
	$cs = get_current_screen();
	$hooked = array('news-box_page_nb_settings', 'toplevel_page_nb_menu');
	
	if(in_array($cs->base, $hooked)) {
		echo '
		<style type="text/css">
		#screen-meta-links {display: none;}
		</style>';	
	}
	
	//var_dump(get_current_screen()); // debug
}
add_action('admin_head', 'nb_remove_wp_helper', 999);
