<?php

class VibeShortcodes {

    function __construct() 
    {	
    	//require_once( plugin_dir_path( __FILE__ ) .'shortcodes.php' );
    	define('VIBE_TINYMCE_URI', VIBE_PLUGIN_URL.'/vibe-shortcodes/tinymce');
		
        add_action('init', array(&$this, 'init'));
        
        add_action('admin_init', array(&$this, 'admin_icons'));
        add_action('admin_init', array(&$this, 'admin_init'));
        add_action('wp_enqueue_scripts', array(&$this, 'frontend'));
	}
	
	/**
	 * Registers TinyMCE rich editor buttons
	 *
	 * @return	void
	 */
	function init()
	{
		
		
		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
			return;
	
		if ( get_user_option('rich_editing') == 'true' )
		{
			add_filter( 'mce_external_plugins', array(&$this, 'add_rich_plugins') );
			add_filter( 'mce_buttons', array(&$this, 'register_rich_buttons') );
		}
                
                //Include front end scripts
                
                
	}
	
        function frontend(){
            	$minified=vibe_get_option('minified');
    			if(!isset($minified) || !$minified){
                    wp_enqueue_style( 'icons-css', VIBE_PLUGIN_URL.'/vibe-shortcodes/css/fonticons.css');
                    wp_enqueue_style( 'magnific-css', VIBE_PLUGIN_URL.'/vibe-shortcodes/css/magnific-popup.css');
                    wp_enqueue_style( 'mejskin', VIBE_PLUGIN_URL . '/vibe-shortcodes/css/skin/mediaelementplayer.css', false, null );
                    wp_enqueue_style( 'animation-css', VIBE_PLUGIN_URL.'/vibe-shortcodes/css/animation.css');
                    wp_enqueue_script( 'knob-js', VIBE_PLUGIN_URL . '/vibe-shortcodes/js/jquery.knob.js',array('jquery'),'1.0',true);
                    wp_enqueue_script( 'flexslider-js', VIBE_PLUGIN_URL . '/vibe-shortcodes/js/jquery.flexslider-min.js',array('jquery'),'1.0',true);
                    wp_enqueue_script( 'masonry-js', VIBE_PLUGIN_URL . '/vibe-shortcodes/js/masonry.min.js',array('jquery'),'1.0',true);
                    wp_enqueue_script( 'magnific-js', VIBE_PLUGIN_URL . '/vibe-shortcodes/js/jquery.magnific-popup.min.js',array('jquery'),'1.0',true);
                }                
                wp_enqueue_script( 'fitvids-js', VIBE_PLUGIN_URL . '/vibe-shortcodes/js/jquery.fitvids.js',array('jquery','mediaelement'),'1.0',true); 
            	wp_enqueue_style( 'shortcodes-css', VIBE_PLUGIN_URL.'/vibe-shortcodes/css/shortcodes.css',array('thickbox'));
               	wp_enqueue_script( 'shortcode-js', VIBE_PLUGIN_URL . '/vibe-shortcodes/js/shortcodes.js',array('jquery','mediaelement','thickbox'),'1.0',true);
               	$translation_array = array( 
									'sending_mail' => __( 'Sending mail','vibe-shortcodes' ), 
									'error_string' => __( 'Error :','vibe-shortcodes' ),
									'invalid_string' => __( 'Invalid ','vibe-shortcodes' ),
									'captcha_mismatch' => __( 'Captcha Mismatch','vibe-shortcodes' ), 
									);
               	wp_localize_script( 'shortcode-js', 'vibe_shortcode_strings', $translation_array );
        }
	// --------------------------------------------------------------------------
	
	/**
	 * Defins TinyMCE rich editor js plugin
	 *
	 * @return	void
	 */
	function add_rich_plugins( $plugin_array )
	{
		if ( floatval(get_bloginfo('version')) >= 3.9){
			$plugin_array['vibeShortcodes'] = VIBE_TINYMCE_URI . '/plugin.js';
		}else{
			$plugin_array['vibeShortcodes'] = VIBE_TINYMCE_URI . '/plugin.old.js'; // For old versions of WP
		}

		return $plugin_array;
	}
	
	// --------------------------------------------------------------------------
	
	/**
	 * Adds TinyMCE rich editor buttons
	 *
	 * @return	void
	 */
	function register_rich_buttons( $buttons )
	{
		array_push( $buttons, "|", 'vibe_button' );
		return $buttons;
	}
	
	/**
	 * Enqueue Scripts and Styles
	 *
	 * @return	void
	 */
	function admin_init()
	{       
                if(is_admin()){
		
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-livequery', VIBE_TINYMCE_URI . '/js/jquery.livequery.js', false, '1.1.1', false );
		wp_enqueue_script( 'jquery-appendo', VIBE_TINYMCE_URI . '/js/jquery.appendo.js', false, '1.0', false );
		wp_enqueue_script( 'base64', VIBE_TINYMCE_URI . '/js/base64.js', false, '1.0', false );
        wp_localize_script( 'jquery', 'VibeShortcodes', array('shortcodes_folder' => VIBE_PLUGIN_URL .'/vibe-shortcodes') );
        
	        if ( floatval(get_bloginfo('version')) >= 3.9){
			  wp_enqueue_script( 'vibe-popup', VIBE_TINYMCE_URI . '/js/popup.js', array('jquery-ui-core','jquery-ui-widget','jquery-ui-mouse','jquery-ui-draggable','jquery-ui-slider','iris'), '1.0', false );
			}else{
				wp_enqueue_script( 'vibe-popup', VIBE_TINYMCE_URI . '/js/popup.old.js', array('jquery-ui-core','jquery-ui-widget','jquery-ui-mouse','jquery-ui-draggable','jquery-ui-slider','iris'), '1.0', false );
				//For older versions of WP
			}
		}
                
                if(is_admin()){
				wp_enqueue_style( 'vibe-popup', VIBE_TINYMCE_URI . '/css/popup.css', false, '1.0', 'all' );
                wp_enqueue_style( 'shortcodes-css', VIBE_PLUGIN_URL.'/vibe-shortcodes/css/shortcodes.css');
                }   
        }
        
        function admin_css(){
            // css
                if(is_admin()){
		wp_enqueue_style( 'vibe-popup', VIBE_TINYMCE_URI . '/css/popup.css', false, '1.0', 'all' );
                wp_enqueue_style( 'shortcodes-css', VIBE_URL.'/css/shortcodes.css');
                }
               
        }
        function admin_icons(){
            wp_enqueue_style( 'icons-css', VIBE_PLUGIN_URL.'/vibe-shortcodes/css/fonticons.css');
        }
    
}

$vibe_shortcodes = new VibeShortcodes();
?>