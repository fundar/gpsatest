<?php
/**
 * Plugin Name: EventON
 * Plugin URI: http://www.myeventon.com/
 * Description: A complete event calendar experience
 * Version: 2.2.7
 * Author: AshanJay
 * Author URI: http://www.ashanjay.com
 * Requires at least: 3.7
 * Tested up to: 3.8.1
 *
 * Text Domain: eventon
 * Domain Path: /lang/languages/
 *
 * @package EventON
 * @category Core
 * @author AJDE
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( "AJDE_EVCAL_DIR", WP_PLUGIN_DIR );
define( "AJDE_EVCAL_PATH", dirname( __FILE__ ) );
define( "AJDE_EVCAL_FILE", ( __FILE__ ) );
define( "AJDE_EVCAL_URL", path_join(WP_PLUGIN_URL, basename(dirname(__FILE__))) );
define( "AJDE_EVCAL_BASENAME", plugin_basename(__FILE__) );
define( "EVENTON_BASE", basename(dirname(__FILE__)) );
define( "BACKEND_URL", get_bloginfo('url').'/wp-admin/' ); 




// main eventon class
if ( ! class_exists( 'EventON' ) ) {

class EventON {
	public $version = '2.2.7';
	/**
	 * @var evo_generator
	 */
	public $evo_generator;	
	
	public $template_url;
	
	private $content;
	/**
	 * Constructor.
	 */
	public function __construct() {		
		
		// Installation
		register_activation_hook( __FILE__, array( $this, 'activate' ) );


		// Updates
		add_action( 'admin_init', array( $this, 'verify_plugin_version' ), 5 );
		
		// Include required files
		$this->includes();
				
		// Hooks
		add_action( 'init', array( $this, 'init' ), 0 );
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
		add_action( 'after_setup_theme', array( $this, 'compatibility' ) );
		
		// Deactivation
		register_deactivation_hook( AJDE_EVCAL_FILE, array($this,'deactivate'));
		
		
	}
	
	
	/**
	 * Include required core files used in admin and on the frontend.
	 */
	function includes() {
		if ( is_admin() )
			$this->admin_includes();
		if ( ! is_admin() || defined('DOING_AJAX') )
			$this->frontend_includes();
		if ( defined('DOING_AJAX') )
			$this->ajax_includes();
		
		// Functions
		include_once( 'eventon-core-functions.php' );					// Contains core functions for the front/back end
		include_once( 'classes/class-calendar_generator.php' );		// Main class to generate calendar
		
	}
	
	/**
	 * Include required admin files.
	 */
	public function admin_includes() {
		include_once( 'admin/eventon-admin-init.php' );			// Admin section
		
		//admin only classes
		include_once( 'classes/class-evo-event.php' );
		$this->evo_event = new evo_event();
	}
	/**
	 * Include required ajax files.
	 *
	 * @access public
	 * @return void
	 */
	public function ajax_includes() {
		include_once( 'eventon-ajax.php' );						// Ajax functions for admin and the front-end
	}
	
	/**
	 * Include required frontend files.
	 */
	public function frontend_includes() {
		// Functions
		include_once( 'eventon-functions.php' );// Contains functions for various front-end events
		// Classes
		include_once( 'classes/shortcodes/class-evo-shortcodes.php' );// Shortcodes class
	}
	/**
	 * register_widgets function.
	 */
	function register_widgets() {
		// Include - no need to use autoload as WP loads them anyway
		include_once( 'classes/widgets/class-evo-widget-main.php' );
		
		// Register widgets
		register_widget( 'EvcalWidget' );
		register_widget( 'EvcalWidget_SC' );
	}
	
	/**
	 * Init eventON when WordPress Initialises.
	 */
	public function init() {
		
		// Set up localisation
		$this->load_plugin_textdomain();
		
		$this->template_url = apply_filters('eventon_template_url','eventon/');
		
		$this->evo_generator	= new EVO_generator();			// Query class handles generating calendar
				
		// Classes/actions loaded for the frontend and for ajax requests
		if ( ! is_admin() || defined('DOING_AJAX') ) {
			// Class instances			
			$this->shortcodes		= new EVO_Shortcodes();		
					
			// Hooks
			add_action( 'init', array( $this, 'register_scripts' ), 10 );

			add_action( 'wp_enqueue_scripts', array( $this, 'load_default_evo_styles' ), 10 );
			add_action( 'wp_head', array( $this, 'generator' ) );			
			add_filter( 'template_include', array( $this, 'template_loader' ) );
		}
		
		// Check for plugin addons
		$this->include_addons();
				
		// Init taxonomies for eventON
		$this->init_taxonomy();
		
		
		// roles and capabilities
		eventon_init_caps();
		
		
		// Initiate eventon update checker		
		require_once( 'classes/class-evo-updater.php' );		
		//$api_url = 'http://update.myeventon.com/';
		$api_url = 'http://update.myeventon.com/index.php';
		$this->evo_updater = new evo_updater ( $this->version, $api_url, AJDE_EVCAL_BASENAME);
		
	
		// Init action
		do_action( 'eventon_init' );
		
		
	}
	
	
	/**
	 * output the inpage popup window for eventon
	 */
	public function output_eventon_pop_window($arg){
	
		$defaults = array(
			'content'=>'',
			'class'=>'',
			'attr'=>'',
			'title'=>'',
			'type'=>'normal',
			'hidden_content'=>''
		);
		$args = (!empty($arg) && is_array($arg) && count($arg)>0) ? array_merge($defaults, $arg) : $defaults;
		
		
		
		$_padding_class = (!empty($args['type']) && $args['type']=='padded')? ' padd':null;

		$content = 
		"<div id='eventon_popup_outter'>
			<div id='eventon_popup' class='{$args['class']}{$_padding_class}' {$args['attr']} style='display:none'>				
				<div class='evoPOP_header'><p id='evoPOP_title'>{$args['title']}</p><a class='eventon_close_pop_btn'>X</a></div>
						
				<div id='eventon_loading'></div>
				
				<div class='eventon_popup_text'>{$args['content']}</div>
				<p class='message'></p>
				
			</div>
		</div>";
		
		$this->content = $content;
		add_action('admin_footer', array($this, 'actual_output_popup'));
	}
	
	function actual_output_popup($content){
		echo $this->content;
	}
	
	/*
		Legend popup box across wp-admin
	*/
	public function throw_guide($content, $position='', $echo=true){
		
		$L = (!empty($position) && $position=='L')? ' L':null;
		
		$content = "<span class='evoGuideCall{$L}'>?<em>{$content}</em></span>";
		
		if($echo){ echo $content;  }else{ return $content; }
	}
	
	
	/**
	 * function to include addon files in the plugin
	 */
	public function include_addons(){
		$eventon_addons_opt = get_option('eventon_addons');
		
		if(!empty($eventon_addons_opt) && is_array($eventon_addons_opt) ){
			foreach($eventon_addons_opt as $ev_opt){
								
				if(!empty($ev_opt['status']) && $ev_opt['status']=='active' && !empty($ev_opt['path']) ){
					include_once($ev_opt['path']);	
				}
			}
		}
	}
	
	// ADDON new version notifications
		private $addon_info ='';
		public function addon_has_new_version($values){
			add_action('after_plugin_row_'.$values['slug'].'.php',array($this,'addon_new_version_message'));
			$this->addon_info = $values;
		}
		public function addon_new_version_message(){

			$addon = $this->addon_info;

			$new_version =  __('There is a new version of '.$addon['name'].' available.', 'eventon') .' <a class="thickbox" title="'.$addon['name'].'" href="plugin-install.php?tab=plugin-information&plugin='.$addon['slugf'].'&TB_iframe=true&width=640&height=808">'. 
				sprintf(__('View version %s Details', 'eventon'), $addon['version']) . '</a>. ';

			echo '</tr><tr class="plugin-update-tr"><td colspan="3" class="plugin-update"><div class="update-message">' . $new_version . __(' Download the new version from <a href="http://www.myeventon.com/my-account/" target="_blank">My Account</a> at myeventon.com.', 'eventon') . '</div></td>';
		}

	/**
	 * Init EvenON taxonomies.
	 */
	public function init_taxonomy() {
		if ( post_type_exists('ajde_events') )
			return;
		
		/**
		 * Taxonomies
		 **/
		do_action( 'eventon_register_taxonomy' );
		
		$evcal_opt1= get_option('evcal_options_evcal_1');
		
		
		// Event type custom taxonomy NAMES
		$evt_name = eventon_get_event_tax_name('et',$evcal_opt1);
		$evt_name2 = eventon_get_event_tax_name('et2',$evcal_opt1);
		
		$__capabilities = array(
					'manage_terms' 		=> 'manage_eventon_terms',
					'edit_terms' 		=> 'edit_eventon_terms',
					'delete_terms' 		=> 'delete_eventon_terms',
					'assign_terms' 		=> 'assign_eventon_terms',
				);

		register_taxonomy( 'event_location', 
			apply_filters( 'eventon_taxonomy_objects_event_location', array('ajde_events') ),
			apply_filters( 'eventon_taxonomy_args_event_location', array(
				'hierarchical' => false, 
				'label' => __('Event Location','eventon'), 
				'show_ui' => true,
				'query_var' => true,
				'capabilities'			=> $__capabilities,
				'rewrite' => array( 'slug' => 'event-type' ) 
			)) 
		);

		register_taxonomy( 'event_type', 
			apply_filters( 'eventon_taxonomy_objects_event_type', array('ajde_events') ),
			apply_filters( 'eventon_taxonomy_args_event_type', array(
				'hierarchical' => true, 
				'label' => $evt_name, 
				'show_ui' => true,
				'query_var' => true,
				'capabilities'			=> $__capabilities,
				'rewrite' => array( 'slug' => 'event-type' ) 
			)) 
		);
		register_taxonomy('event_type_2', 
			apply_filters( 'eventon_taxonomy_objects_event_type_2', array('ajde_events') ),
			apply_filters( 'eventon_taxonomy_args_event_type_2', array(
				'hierarchical' => true, 
				'label' => $evt_name2, 
				'show_ui' => true,
				'query_var' => true,
				'capabilities'			=> $__capabilities,
				'rewrite' => array( 'slug' => 'event-type-2' ) 
			) )
		); 
		
		
		/**
		 * Post Types
		 **/
		do_action( 'eventon_register_post_type' );
		
		$labels = eventon_get_proper_labels('Event','Events');
		register_post_type('ajde_events', 
			apply_filters( 'eventon_register_post_type_ajde_events',
				array(
					'labels' => $labels,
					'description' 			=> __( 'This is where you can add new events to your calendar.', 'eventon' ),
					'public' 				=> true,
					'show_ui' 				=> true,
					'capability_type' 		=> 'eventon',
					'publicly_queryable' 	=> true,
					'hierarchical' 			=> false,
					'rewrite' 				=> apply_filters('eventon_event_slug', array('slug'=>'events')),
					'query_var'		 		=> true,
					'supports' 				=> array('title','editor','custom-fields','thumbnail'),
					//'supports' 			=> array('title','editor','thumbnail'),
					'menu_position' 		=> 5, 
					'has_archive' 			=> true
				)
			)
		);	
	}
	
	/**
	 * Register/queue frontend scripts.
	 *
	 * @access public
	 * @return void
	 */
	public function register_scripts() {
		global $post;
		
		$evcal_val1= get_option('evcal_options_evcal_1');
		
		
		// Google gmap API script -- loadded from class-calendar_generator.php		

		wp_register_script('add_to_cal', AJDE_EVCAL_URL. '/assets/js/add_to_calendar.js', array('jquery'),'1.0',true );

		wp_register_script('evcal_ajax_handle', AJDE_EVCAL_URL. '/assets/js/eventon_script.js', array('jquery'),'1.0',true );
		wp_localize_script( 'evcal_ajax_handle', 'the_ajax_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));



		// google maps	
		wp_register_script('eventon_gmaps', AJDE_EVCAL_URL. '/assets/js/eventon_gen_maps.js', array('jquery'),'1.0',true );	
		wp_register_script('eventon_init_gmaps', AJDE_EVCAL_URL. '/assets/js/eventon_init_gmap.js', array('jquery'),'1.0',true );
		wp_register_script( 'eventon_init_gmaps_blank', AJDE_EVCAL_URL. '/assets/js/eventon_init_gmap_blank.js', array('jquery'),'1.0',true ); // load a blank initiate gmap javascript

		wp_register_script( 'evcal_gmaps', apply_filters('eventon_google_map_url', 'https://maps.googleapis.com/maps/api/js?sensor=false'), array('jquery'),'1.0',true);



	// STYLES

		wp_register_style('evo_font_icons',AJDE_EVCAL_URL.'/assets/fonts/font-awesome.css');		
		
		// select the current skin
		$skin  = (!empty($evcal_val1['evcal_skin']))? $evcal_val1['evcal_skin'] : 'slick';		
				
		// Defaults styles and dynamic styles
		wp_register_style('evcal_cal_default',AJDE_EVCAL_URL.'/assets/css/eventon_styles.css');	
		//wp_register_style('evo_dynamic_css', admin_url('admin-ajax.php').'?action=evo_dynamic_css');

		// LOAD custom google fonts for skins		
		$gfont="http://fonts.googleapis.com/css?family=Oswald:400,300|Open+Sans:400,300";
		wp_register_style( 'evcal_google_fonts', $gfont, '', '', 'screen' );
		
		

		$this->register_evo_dynamic_styles();

	}	

	public function register_evo_dynamic_styles(){
		if(is_multisite()) {
			$uploads = wp_upload_dir();
			wp_register_style('eventon_dynamic_styles', $uploads['baseurl'] . '/eventon_dynamic_styles.css', 'style');
		} else {
			wp_register_style('eventon_dynamic_styles', 
				AJDE_EVCAL_URL. '/assets/css/eventon_dynamic_styles.css', 'style');
		}
	}
	
	public function load_default_evo_scripts(){
		//wp_enqueue_script('add_to_cal');
		wp_enqueue_script('evcal_ajax_handle');
		wp_enqueue_script('eventon_gmaps');
	}
	public function load_default_evo_styles(){
		
		wp_enqueue_style( 'evcal_cal_default');
		wp_enqueue_style( 'eventon_dynamic_styles');
		//wp_enqueue_style( 'evo_dynamic_css');		
		wp_enqueue_style( 'evcal_google_fonts' );
		wp_enqueue_style( 'evo_font_icons' );



		//wp_enqueue_style( 'eventon_dynamic_inline_styles');
	}
	public function evo_styles(){
		add_action('wp_head', array($this, 'load_default_evo_scripts'));
	}
	
	/**
	 * Activate function to store version.
	 */
	public function activate(){
		set_transient( '_evo_activation_redirect', 1, 60 * 60 );
		
		do_action('eventon_activate');
	}
	
	// update function
	public function update(){
		//set_transient( '_evo_activation_redirect', 1, 60 * 60 );
		
		
		
	}
	
	
	/** check plugin version **/
	public function verify_plugin_version(){
		
		$plugin_version = $this->version;
			
		// check installed version
		$installed_version = get_option('eventon_plugin_version');
		
		if($installed_version != $plugin_version){
			if (  isset( $_GET['page'] ) && 'eventon' == $_GET['page']   ){
				

				update_option('eventon_plugin_version', $plugin_version);
				wp_safe_redirect( admin_url( 'index.php?page=evo-about&evo-updated=true' ) );
			}
			//set_transient( '_evo_update_redirect', 1, 60 * 60 );
			//update_option( '_evo_updated', 'true');
			
		}else if(!$installed_version ){
			add_option('eventon_plugin_version', $plugin_version);			
		}else{
			update_option('eventon_plugin_version', $plugin_version);			
		}
		
		// delete options saved on previous version
		delete_option('evcal_plugin_version');
	}
	
	
	public function deactivate(){
		//delete_option('evcal_options');		
		do_action('eventon_deactivate');
	}
	

	
	/**
	 * Add Compatibility for various bits.
	 *
	 * @access public
	 * @return void
	 */
	public function compatibility() {
		// Post thumbnail support
		if ( ! current_theme_supports( 'post-thumbnails', 'ajde_events' ) ) {
			add_theme_support( 'post-thumbnails' );
			remove_post_type_support( 'post', 'thumbnail' );
			remove_post_type_support( 'page', 'thumbnail' );
		} else {
			add_post_type_support( 'ajde_events', 'thumbnail' );
		}
	}
	
	
	/**
	 * LOAD Backender UI and functionalities for settings.
	 */
	public function load_ajde_backender(){
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');

		wp_enqueue_script('backender_colorpicker');
		wp_enqueue_script('ajde_backender_script');

		include_once('admin/ajde_backender.php');
		
	}
	
		public function enqueue_backender_styles(){
			wp_enqueue_style( 'ajde_backender_styles',AJDE_EVCAL_URL.'/assets/css/ajde_backender_style.css');
			wp_enqueue_style( 'colorpicker_styles',AJDE_EVCAL_URL.'/assets/css/colorpicker_styles.css');
			
		}
		public function register_backender_scripts(){
			wp_register_script('backender_colorpicker',AJDE_EVCAL_URL.'/assets/js/colorpicker.js' ,array('jquery'),'1.0', true);
			wp_register_script('ajde_backender_script',AJDE_EVCAL_URL.'/assets/js/ajde_backender_script.js', array('jquery', 'jquery-ui-core', 'jquery-ui-sortable'), '1.0', true );
			
		}
	
	
	/**
	 * Output generator to aid debugging.
	 */
	public function generator() {
		echo "\n\n" . '<!-- EventON Version -->' . "\n" . '<meta name="generator" content="EventON ' . esc_attr( $this->version ) . '" />' . "\n\n";
	}
	
	
	/**
	 * Load a template.
	 *
	 * Handles template usage so that we can use our own templates instead of the themes.
	 *
	 * Templates are in the 'templates' folder. eventon looks for theme
	 * overrides in /theme/eventon/ by default
	 *
	 * For beginners, it also looks for a eventon.php template first. If the user adds
	 * this to the theme (containing a eventon() inside) this will be used for all
	 * eventon templates.
	 *
	 * @access public
	 * @param mixed $template
	 * @return string
	 */
	public function template_loader( $template ) {
		global $eventon_sin_event;
		
		$file='';
		$sure_path = AJDE_EVCAL_PATH . '/templates/';
		
		
		// Paths to check
		$paths = apply_filters('eventon_template_paths', array(
			0=>TEMPLATEPATH.'/',
			1=>TEMPLATEPATH.'/'.$this->template_url,
		));
		
		
		$events_page_id = get_option('eventon_events_page_id');
		
		// single and archieve events page
		if( is_single() && get_post_type() == 'ajde_events' ) {
			$file 	= 'single-ajde_events.php';
		}elseif ( is_post_type_archive( 'ajde_events' ) || ( !empty($events_page_id) && is_page( $events_page_id )  )) {
			$file 	= 'archive-ajde_events.php';
			$paths[] 	= AJDE_EVCAL_PATH . '/templates/';
		}
		
		// FILE Exist
		if ( $file ) {
			
			// each path
			foreach($paths as $path){				
				if(file_exists($path.$file) ){	
					$template = $path.$file;	
					break;
				}
			}		
			
				
			if ( ! $template ) { 
				$template = AJDE_EVCAL_PATH . '/templates/' . $file;
			
			}
		}
		
		
		
		return $template;
	}

	
	
	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present
	 *
	 * @access public
	 * @return void
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'eventon' );
		$formal = 'yes' == get_option( 'eventon_informal_localisation_type' ) ? 'informal' : 'formal';

		load_textdomain( 'eventon', WP_LANG_DIR . "/eventon/eventon-$locale.mo" );

		// Load admin specific MO files
		if ( is_admin() ) {
			load_textdomain( 'eventon', WP_LANG_DIR . "/eventon/eventon-admin-$locale.mo" );
			load_textdomain( 'eventon', AJDE_EVCAL_PATH . "/lang/languages/eventon-admin-$locale.mo" );
		}

		load_plugin_textdomain( 'eventon', false, AJDE_EVCAL_URL . "/lang/languages/$formal" );
		load_plugin_textdomain( 'eventon', false, AJDE_EVCAL_URL . "/lang/languages" );
	}
	
	public function get_current_version(){
		return $this->version;
	}	
	
	/** return eventon option settings values **/
	public function evo_get_options($field, $array_field=''){
		if(!empty($array_field)){
			$options = get_option($field);
			$options = $options[$array_field];
		}else{
			$options = get_option($field);
		}		
		return !empty($options)?$options:null;
	}

}

}// class exists

/**
 * Init eventon class
 */
$GLOBALS['eventon'] = new EventON();

//include_once('admin/update-notifier.php');	
?>