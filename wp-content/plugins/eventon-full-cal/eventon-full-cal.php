<?php
/*
 Plugin Name: EventON - Full cal
 Plugin URI: http://www.myeventon.com/
 Description: Create a full grid calendar with a month view of eventON events.
 Author: Ashan Jay
 Version: 0.10
 Author URI: http://www.ashanjay.com/
 Requires at least: 3.7
 Tested up to: 3.8.1

 */


 
class EventON_full_cal{
	
	public $version='0.10';
	public $eventon_version = '2.2.7';
		
	public 	$day_names = array();
	private $focus_day_data= array();
	public $is_running_fc =false;
	
	
	public $slug;
	public $plugin_slug ;	
	public $plugin_url ;	
	public $template_url ;
	
	public $shortcode_args;
	
	/*
	 * Construct
	 */
	public function __construct(){
		
		$status = $this->requirment_check();
		
		if($status)
		{
			add_action( 'init', array( $this, 'init' ), 0 );				
			add_action('eventon_calendar_header_content',array($this, 'calendar_header_hook'), 10, 1);			
			
			// scripts and styles 
			add_action( 'init', array( $this, 'register_styles_scripts' ) ,15);		
			
			
			// HOOKs		
			add_action('eventon_inline_styles', array($this, 'inline_style_additions'));
			add_action( 'widgets_init', array( $this, 'register_widgets' ) );	

			$this->includes();
		}
			
	}
	
	
	function init(){


		// get plugin slug
		$this->plugin_url = path_join(WP_PLUGIN_URL, basename(dirname(__FILE__)));
		$this->plugin_path = dirname( __FILE__ );
		$this->plugin_slug = plugin_basename(__FILE__);
		list ($t1, $t2) = explode('/', $this->plugin_slug);
        $this->slug = $t1;
		
		$this->add_to_eventon_addons_list();	
		
		// Deactivation
		register_deactivation_hook( __FILE__, array($this,'deactivate'));
		
		// AUTO UPDATE notifier -- using main eventon updater class
		require_once( AJDE_EVCAL_PATH.'/classes/class-evo-updater.php' );		
		$api_url = 'http://update.myeventon.com/';
		$this->evo_updater = new evo_updater( $this->version, $api_url, plugin_basename(__FILE__));
		
		
		// new version notification
		if ( is_admin() ){
			global $eventon;

			$server_version = $this->evo_updater->getRemote_version();
			
			if( version_compare($this->version, $server_version, '<')){
				$eventon->addon_has_new_version(array(
					'version'=>$server_version, 
					'slug'=>'eventon-full-cal/eventon-full-cal', 
					'name'=>'FullCal',
					'slugf'=>'eventon-full-cal',
					)
				);
			}
		}


		$this->set_three_letter_day_names();
		
		$this->shortcodes = new evo_fc_shortcode();
		
	}
	


	/**
	 * Include required core files.
	 */
	function includes(){
		include_once( 'admin/eventonFC_shortcode.php' );
		
		if ( is_admin() )
			include_once( 'admin/admin-init.php' );

		if ( defined('DOING_AJAX') ){
			include_once( 'admin/eventonFC_ajax.php' );
		}
	}
	

	/**
	 *	create the content for the full cal grids
	 */
	function content_below_sortbar_this($content){
		
		// check if full cal is running on this calendar
		if(!$this->is_running_fc)
			return;
			
		$day_data = $this->focus_day_data;
		$evcal_val1= get_option('evcal_options_evcal_1');
		
		$start_of_week = get_option('start_of_week');
		
		$hide_arrows = ($evcal_val1['evcal_arrow_hide']=='yes')? true:false;
		
				
		$content.="
		<div class='eventon_fullcal' cal_id='{$day_data['cal_id']}'>
			<div class='evoFC_tip' style='display:none'></div>
			<div class='evofc_months_strip' multiplier='0'>";
				
				$content.= $this->get_grid_month($day_data['day'],$day_data['month'], $day_data['year'], $start_of_week, '', '1');
				
				$content.="
			</div><div class='clear'></div>
		</div>";
		
		echo $content;		
		
		// Stop this from being getting hooked into other calendars on the same page
		remove_action('eventon_below_sorts',array( $this, 'content_below_sortbar_this' ));
	}
	
	
	// month grid including the day names
	function get_grid_month($date, $month, $year, $start_of_week, $filters='', $init=''){
		
		$content ="<div class='evofc_month ".( (!empty($init) && $init=='1')? 'focus':null)."' month='".$month."'>
			<div class='eventon_fc_daynames'>";			
			for($t=1; $t<8; $t++){
			
				$start_of_week = ($start_of_week>7)?$start_of_week-7: 
					( ($start_of_week==0)?7: $start_of_week );
				
				$dow = $start_of_week;

				
				$content.="<p class='evo_fc_day' data-dow='{$dow}'>".$this->day_names[$start_of_week]."</p>";
				$start_of_week++;
			}				
			$content.="<div class='clear'></div>
		</div>
		<div class='eventon_fc_days'>";				
				
				$content .= $this->get_full_cal_view($date,$month, $year, $filters);
				
			$content .="				
		</div></div>";
		
		return $content;
	}
	
			
	/**
	 *	MAIN Function to generate the calendar outter shell
	 *	for full calendar
	 */
	public function generate_eventon_fc_calendar($args, $type=''){
		global $eventon, $wpdb;		
		

		// print_r($args);
		$this->only_fc_actions();
		$this->is_running_fc=true;
		
		
		// call styles for PHP
		if($type=='php')
			$this->print_scripts();
		
		$month_incre = (!empty($args['month_incre']))?$args['month_incre']:0;
		$day_incre = (!empty($args['day_incre']))?$args['day_incre']:0;
		
		/*
			DATE - for the calendar to focus on
		*/		
		$current_timestamp =  current_time('timestamp');
		if($day_incre!=0){
			$today_day = date('j',$current_timestamp);
			$today_day= ((int)$today_day)+ (int)$day_incre;
		}else{
			$today_day = date('j',$current_timestamp);
		}
		
		$focused_day=( !empty($args['fixed_day']) && $args['fixed_day']!=0 )? $args['fixed_day']: $today_day;
		
		
		// MONTH & YEAR
		$focused_month_num = (!empty($args['fixed_month']))?
			$args['fixed_month']:
			date('n', strtotime($month_incre.' month', $current_timestamp) );
			
		$focused_year = (!empty($args['fixed_year']))?
			$args['fixed_year']:
			date('Y', strtotime($month_incre.' month', $current_timestamp) );
		
		// DAY RANGES
		$focus_start_date_range = mktime( 0,0,0,$focused_month_num,$focused_day,$focused_year );
		$focus_end_date_range = mktime(23,59,59,($focused_month_num),$focused_day, ($focused_year));
		
		
		// Set focus day data within the class
		$this->focus_day_data = array(
			'day'=>$focused_day,
			'month'=>$focused_month_num,
			'year'=>$focused_year,
			'focus_start_date_range'=>$focus_start_date_range,
			'focus_end_date_range'=>$focus_end_date_range,
			'cal_id'=>((!empty($args['cal_id']))? $args['cal_id']:'1')
		);
		
		
		
		// Add extra arguments to shortcode arguments
		$new_arguments = array(
			'focus_start_date_range'=>$focus_start_date_range,
			'focus_end_date_range'=>$focus_end_date_range,
		);
		

		$args = (!empty($args) && is_array($args))? array_merge($args, $new_arguments): $new_arguments;
		
		
		// PROCESS variables
		$args__ = $eventon->evo_generator->process_arguments($args, true);
		$this->shortcode_args=$args__;
		
		//print_r($args__);
		
		// ==================
		$content =$eventon->evo_generator->eventon_generate_calendar($args__);

		$this->remove_only_fc_actions();
		
		return  $content;	
		
	}

	/**
	 *	Function to OUTPUT the full cal view
	 */
	function get_full_cal_view($day, $month, $year, $filters=''){
		global $eventon;
		
		$number_days_in_month = $this->days_in_month( $month, $year);
		
		
		$focus_month_beg_range = mktime( 0,0,0,$month,1,$year );
		$focus_month_end_range = mktime( 23,59,59,$month,$number_days_in_month,$year );
		
		
		// build a separate WP_Query for day list with events
		$wp_arguments = array (
			'post_type' 		=> 'ajde_events',
			'posts_per_page'	=>-1 ,
			'order'				=>'ASC',					
		);
		
		// GET GENERAL shortcode arguments if set class-wide
		$shortcode_args = $this->shortcode_args;
		
		//print_r($shortcode_args);
		
		// check for available filters and append them to argument
		if(!empty($shortcode_args['filters']) && count($shortcode_args['filters'])>0){
			$wp_arguments = $eventon->evo_generator->apply_evo_filters_to_wp_argument($wp_arguments, $shortcode_args['filters']);
		}
		// check for filters via AJAX call to change months
		if(!empty($filters) && count($filters)>0){
			$wp_arguments = $eventon->evo_generator->apply_evo_filters_to_wp_argument($wp_arguments, $filters);
		}
		
		
		// GET events list for the month
		// GET: full month
		$event_list_array = $eventon->evo_generator->wp_query_event_cycle(
			$wp_arguments, 
			$focus_month_beg_range,
			$focus_month_end_range
		);
		
		//print_r($event_list_array);
		// build a month array with days that have events
		$date_with_events= array();
		if(is_array($event_list_array) && count($event_list_array)>0){
			
			foreach($event_list_array as $event){				
				
				
				$start_date = (int)(date('j',$event['event_start_unix']));
				$start_month = (int)(date('n',$event['event_start_unix']));
				
				$end_date = (int)(date('j',$event['event_end_unix']));
				$end_month = (int)(date('n',$event['event_end_unix']));
				
				
				$__duration='';
				$__dur_type ='';
				// same month
				if($start_month == $end_month){
					// same date
					if($start_date == $end_date){
						$__no_events = (!empty($date_with_events[$start_date]))?
							$date_with_events[$start_date]:0;
						
						$date_with_events[$start_date] = $__no_events+1;
						
					}else if($start_date<$end_date){
					// different date
						$__duration = $end_date - $start_date;						
					}
				}else{
					// different month
					// start on this month
					if($start_month == $month){						
						$__duration = $number_days_in_month - $start_date;
						$__dur_type = ($__duration==0)? 'eom':'';
					}else{

						if( $end_month != $month){
							// end on next month
							$start_date=1;
							$__duration = $number_days_in_month;
							
						}else{
							// start on a past month
							$start_date=1;
							$__duration = $end_date-1;
						}
						
					}
				}
				
				//echo $__duration;
				
				// run multi-day
				if(!empty($__duration) || $__dur_type=='eom'){
					for($x=0; $x<=$__duration; $x++){
						if( $number_days_in_month >= ($start_date+$x) ){
							
							$__this_date = $start_date+($x);
							
							// events on this day
							$__no_events = (!empty($date_with_events[$__this_date]))?
							$date_with_events[$__this_date]:0;
							
							$date_with_events[$__this_date] = $__no_events+1;
							//$date_with_events[$start_date+$x] = $start_date+$x.'-'.$event['event_id'];
						}
					}
				}
				
				
			}
			
			
		}	
		
		//print_r($date_with_events);
		
		$start_of_week = get_option('start_of_week');
		
		//ob_start();
		$_box_count=1;
		$output='';
		for($x=0; $x<$number_days_in_month; $x++){
			$day_of_week = date('N',strtotime($year.'-'.$month.'-'.($x+1)));
			
			if(is_array($date_with_events) && count($date_with_events)>0){
				$days_with_events_class = (array_key_exists($x+1, $date_with_events))?
					' has_events':null;
			}else{
				$days_with_events_class=null;
			}
			
			if($x==0){
				//echo $day_of_week.' '.$start_of_week.' '.$month.' '.$x;
				$boxes = ( $day_of_week < $start_of_week)? 
					((7-$start_of_week) +$day_of_week): ($day_of_week- $start_of_week);
				
				if($day_of_week != $start_of_week){
					for($y=0; $y<( $boxes );$y++){
						$output .= "<p class='evo_fc_day evo_fc_empty' data-cnt='{$_box_count}'>-</p>";
						$_box_count++;
					}
				}
			}
			
			// get number of events per this date
			$__events = (!empty($date_with_events[$x+1]))? 'data-events="'.$date_with_events[$x+1].'"':null;
			
			// HTML for the day box
			$focus_cls = ($day==($x+1))?' on_focus':null;
			$day_attr = $x+1;
			
			$output.= "<p class='evo_fc_day{$days_with_events_class}{$focus_cls}' data-dow='{$day_of_week}' {$__events} day='{$day_attr}' data-cnt='{$_box_count}'>".($x+1)."</p>";
			
			$_box_count++;
		}
		$output.= "<div class='clear'></div>";
		
		return $output;
		//return ob_get_clean();
	}
	
	



	// SUPPROT FUNCTIONS
		// ONLY for FC calendar actions 
		public function only_fc_actions(){
			add_filter('eventon_cal_class', array($this, 'eventon_cal_class'), 10, 1);		
			add_action('eventon_below_sorts',array( $this, 'content_below_sortbar_this' ), 10,1);

		}
		public function remove_only_fc_actions(){
			//add_filter('eventon_cal_class', array($this, 'remove_eventon_cal_class'), 10, 1);	
			remove_filter('eventon_cal_class', array($this, 'eventon_cal_class'));
			
		}
		// add class name to calendar header for EM
		function eventon_cal_class($name){
			$name[]='evoFC';
			return $name;
		}
		// remove class name to calendar header for EM
		function remove_eventon_cal_class($name){
			if(($key = array_search('evoFC', $name)) !== false) {
			    unset($name[$key]);
			}
			return $name;
		}

		/**
		 *	Styles for the tab page
		 */	
		public function register_styles_scripts(){		
			
			wp_register_style( 'evo_fc_styles',$this->plugin_url.'/assets/fc_styles.css');
			wp_register_script('evo_fc_ease',$this->plugin_url.'/assets/jquery.easing.1.3.js', array('jquery'), 1.0, true );
			wp_register_script('evo_fc_mobile',$this->plugin_url.'/assets/jquery.mobile.min.js', array('jquery'), 1.0, true );
			wp_register_script('evo_fc_script',$this->plugin_url.'/assets/fc_script.js', array('jquery'), 1.0, true );	

			if(has_eventon_shortcode('add_eventon_fc')){
				// LOAD JS files
				$this->print_scripts();
					
			}
			add_action( 'wp_enqueue_scripts', array($this,'print_styles' ));
				
		}
		public function print_scripts(){	
			
			wp_enqueue_script('evo_fc_ease');	
			//wp_enqueue_script('evo_fc_mobile');	
			wp_enqueue_script('evo_fc_script');	
		}

		function print_styles(){
			wp_enqueue_style( 'evo_fc_styles');	
		}


		/**
	 	* register_widgets function.
		 */
		function register_widgets() {
			// Include - no need to use autoload as WP loads them anyway
			include_once( 'class-evo-fc-widget.php' );
			
			// Register widgets
			register_widget( 'evoFC_Widget' );
		}

		// activation function 
		function requirment_check(){
			//if(!in_array( 'eventON/eventon.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ){
			if(!in_array( 'eventON/eventon.php',  get_option( 'active_plugins' )  ) ){

				add_action('admin_notices', array($this, '_no_eventon_warning'));
				return false;

			}else{
				global $eventon;

				$eventON_version = $eventon->version;

				// if eventON version is lower than what we need
				if(version_compare($this->eventon_version, $eventON_version)>0){
					add_action('admin_notices', array($this, '_old_eventon_warning'));
				}
				return true;
			}	
			
		}
		// three letter day array
		function set_three_letter_day_names(){
			
			// Build 3 letter day name array to use in the fullcal from custom language
			for($x=1; $x<8; $x++){			
				$evcal_day_is[$x] =eventon_return_timely_names_('day_num_to_name',$x, 'three');
				
			}	
			
			$this->day_names = $evcal_day_is;
		}
	
		
		
		// add full cal hidden field to calendar header
		function calendar_header_hook($content){
			// check if full cal is running on this calendar
			if($this->is_running_fc){			
			
				$day_data = $this->focus_day_data;
				$add = "<input type='hidden' class='eventon_other_vals' name='fc_focus_day' value='".$day_data['day']."'/>";
				
				echo $add;
			}else{
				wp_dequeue_script('evo_fc_script');
			}
		}
	
	
		function inline_style_additions(){
			$evcal_val1= get_option('evcal_options_evcal_1');
			echo ".evo_day.has_events span{color:#".$evcal_val1['evcal_hexcode']."}";
		}
	
	
	
		function days_in_month($month, $year) { 
			return date('t', mktime(0, 0, 0, $month+1, 0, $year)); 
		}
		


	// SECONDARY FUNCTIONS	
		

		
		/** Add this extension's information to EventON addons tab **/
		function add_to_eventon_addons_list(){
			global $eventon; 
			
			$plugin_path = dirname( __FILE__ );
			
			$plugin_details = array(
				'name'=> 		'Full Cal for EventON',
				'version'=> 	$this->version,			
				'slug'=>		$this->slug,
				'guide_file'=>		( file_exists($plugin_path.'/guide.php') )? 
					$this->plugin_url.'/guide.php':null,
				'type'=>'extension'
			);
			
			require_once( AJDE_EVCAL_PATH.'/classes/class-evo-addons.php' );
			$evo_addons = new evo_addons();
			echo $evo_addons->add_to_eventon_addons_list($plugin_details);
			
		}
	
		function _no_eventon_warning(){
	        ?>
	        <div class="message error"><p><?php printf(__('EventON Full Cal is enabled but not effective. It requires <a href="%s">EventON</a> in order to work.', 'eventon'),  'http://www.myeventon.com/'); ?></p></div>
	        <?php
	    }
	    function _old_eventon_warning(){
	        ?>
	        <div class="message error"><p><?php printf(__('EventON version is older than the required version to run <b>%s</b> properly.', 'eventon'),  'fullCal'); ?></p></div>
	        <?php
	    }
	
		
		/*
			remove this plugin from myEventON Addons list
		*/
		function deactivate(){
			$eventon_addons_opt = get_option('eventon_addons');
			
			if(is_array($eventon_addons_opt) && array_key_exists($this->slug, $eventon_addons_opt)){
				foreach($eventon_addons_opt as $addon_name=>$addon_ar){
					
					if($addon_name==$this->slug){
						unset($eventon_addons_opt[$addon_name]);
					}
				}
			}
			
			update_option('eventon_addons',$eventon_addons_opt);
		}
	
}

// Initiate this addon within the plugin
$GLOBALS['eventon_fc'] = new EventON_full_cal();


// php tag
function add_eventon_fc($args='') {
	global $eventon_fc, $eventon;
	
	/*
	// connect to support arguments
	$supported_defaults = $eventon->evo_generator->get_supported_shortcode_atts();
	
	$args = shortcode_atts( $supported_defaults, $args ) ;
	*/
	
	$content = $eventon_fc->generate_eventon_fc_calendar($args, 'php');
	
	echo $content;
}


?>