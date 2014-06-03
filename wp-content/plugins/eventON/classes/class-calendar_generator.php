<?php
/**
 * EVO_generator class.
 *
 * @class 		EVO_generator
 * @version		1.11
 * @package		EventON/Classes
 * @category	Class
 * @author 		AJDE
 */

class EVO_generator {
	
	public $google_maps_load, 
		$is_eventcard_open,				
		$evopt1, 
		$evopt2, 
		$evcal_hide_sort;
	
	public $is_upcoming_list = false;
	public $is_eventcard_hide_forcer = false;
	public $_sc_hide_past = false; // shortcode hide past
		
	public $wp_arguments='';
	public $shortcode_args;
	public $filters;
	
	private $lang_array=array();
	
	public $current_event_ids = array();
	
	private $_hide_mult_occur = false;
	private	$events_processed = array();
	
	private $__apply_scheme_SEO = false;
	private $_featured_events = array();

	public $__calendar_type ='default';
	
	
	/**
	 *	Construction function
	 */
	public function __construct(){
		
		
		/** set class wide variables **/
		$options_1 = get_option('evcal_options_evcal_1');
		$this->evopt1= (!empty($options_1))? $options_1:null;
		$this->evopt2= get_option('evcal_options_evcal_2');		
		
		$this->is_eventcard_open = (!empty($this->evopt1['evo_opencard']) && $this->evopt1['evo_opencard']=='yes')? true:false;
		
		// set reused values
		$this->evcal_hide_sort = (!empty($this->evopt1['evcal_hide_sort']))? $this->evopt1['evcal_hide_sort']:null;
		
		// load google maps api only on frontend
		add_action( 'init', array( $this, 'init' ) );		
		
		$this->google_maps_load = get_option('evcal_gmap_load');
		//add_action('wp_enqueue_scripts', array($this, 'load_evo_styles'));

		
	}
	
	function init(){	
		add_action( 'init', array( $this, 'load_google_maps_api' ) );

		$this->reused();
		
	}
	

	// the reused variables and other things within the calendar
	function reused(){
		$lang = (!empty($this->shortcode_args['lang']))? $this->shortcode_args['lang']: 'L1';

		$this->lang_array['et'] = eventon_get_event_tax_name_('et', $lang, $this->evopt1, $this->evopt2); 
		$this->lang_array['et2'] = eventon_get_event_tax_name_('et2', $lang, $this->evopt1, $this->evopt2); 

		$this->lang_array['no_event'] = eventon_get_custom_language($this->evopt2, 'evcal_lang_noeve','No Events');

	}

	// load scripts
	function load_evo_files(){
		global $eventon; 
		$eventon->load_default_evo_scripts();
		$this->load_google_maps_api();
	}
	
	
	function load_google_maps_api(){
		// google maps loading conditional statement
		if( !empty($this->evopt1['evcal_cal_gmap_api']) && ($this->evopt1['evcal_cal_gmap_api']=='yes') 	){
			if(!empty($this->evopt1['evcal_gmap_disable_section']) && $this->evopt1['evcal_gmap_disable_section']=='complete'){
				
				
				update_option('evcal_gmap_load',false);
				
				wp_enqueue_script( 'eventon_init_gmaps_blank');
				wp_enqueue_script( 'eventon_init_gmaps');
			}else{
				
				
				update_option('evcal_gmap_load',true);
				
				wp_enqueue_script( 'eventon_init_gmaps');
			}
			
		}else {
			
			
			update_option('evcal_gmap_load',true);
			wp_enqueue_script( 'evcal_gmaps');
			wp_enqueue_script('eventon_init_gmaps');
			
			// load map files only to frontend
			if ( !is_admin() ){
				wp_enqueue_script( 'evcal_gmaps');
				wp_enqueue_script( 'eventon_init_gmaps');
			}
		}
	}
	
	
	
	// SHORT CODE variables
	function get_supported_shortcode_atts(){		
		return apply_filters('eventon_shortcode_defaults', array(			
			'cal_id'=>'1',
			'sort_by'=>'sort_date',
			'event_count'=>0,
			'month_incre'=>0,			
			'number_of_events'=>5,
			'event_type'=> 'all',
			'event_type_2'=> 'all',
			'focus_start_date_range'=>'',
			'focus_end_date_range'=>'',
			'filters'=>'',
			'fixed_month'=>0,
			'fixed_year'=>0,
			'hide_past'=>'no',			
			'show_et_ft_img'=>'no',
			'event_order'=>'ASC',
			'ft_event_priority'=>'no',
			'number_of_months'=>1,
			'hide_mult_occur'=>'no',
			'show_upcoming'=>0,
			'lang'=>'L1',
			'pec'=>'',// past event cut-off
			'etop_month'=>'no',
			'evc_open'=>'no'// open eventCard by default
		));
	}
	
	/*
		Process the eventON variable arguments
	*/
	function process_arguments($args='', $own_defaults=false, $type=''){
		
		$this->load_evo_files();
		
		$default_arguments = $this->get_supported_shortcode_atts();
		
		//print_r($args);
		
		if(!empty($args)){
		
			// merge default values of shortcode
			if(!$own_defaults)
				$args = shortcode_atts($default_arguments, $args);

			if(!empty($args['event_type']) && $args['event_type']!='all'){
				$filters['filters'][]=array(
					'filter_type'=>'tax',
					'filter_name'=>'event_type',
					'filter_val'=>$args['event_type']
				);
				$args = array_merge($args,$filters);
			}
			if(!empty($args['event_type_2']) && $args['event_type_2']!='all'){
				$filters['filters'][]=array(
					'filter_type'=>'tax',
					'filter_name'=>'event_type_2',
					'filter_val'=>$args['event_type_2']
				);
				$args = array_merge($args,$filters);
			}

				
			$this->shortcode_args=$args; // set global arguments
		
		// empty args
		}else{
			
			if($type=='usedefault'){
				$args = (!empty($this->shortcode_args))? $this->shortcode_args:null;
				
			}else{
				$this->shortcode_args=$default_arguments; // set global arguments
				$args = $default_arguments;
			}
		}
		
		
		// Set hide past value for shortcode hide past event variation
		$this->_sc_hide_past = (!empty($args['hide_past']) && $args['hide_past']=='yes')? true:false;
		
		// check for possible filters
		$this->filters = (!empty($args['filters']))? 'true':'false';
		
		
		//print_r($args);

		return $args;


	}
	
	function update_shortcode_arguments($new_args){
		return array_merge($this->shortcode_args, $new_args);
	}
	

	// shortcode arguments as attrs for the calendar header
	function shortcode_args_for_cal(){
		
		$arg = $this->shortcode_args;
		$_cd='';

		//print_r($arg);
		
		$cdata = apply_filters('eventon_calhead_shortcode_args', array(
			'hide_past'=>$arg['hide_past'],
			'show_et_ft_img'=>$arg['show_et_ft_img'],
			'event_order'=>$arg['event_order'],
			'ft_event_priority'=>((!empty($arg['ft_event_priority']))? $arg['ft_event_priority']: null),
			'lang'=>$arg['lang'],
			'evc_open'=>((!empty($arg['evc_open']))? $arg['evc_open']:'no'),
		));

		foreach ($cdata as $f=>$v){
			$_cd .=''.$f.'="'.$v.'" ';
		}

		return "<div class='cal_arguments' style='display:none' {$_cd}></div>";
		
	}
	
	
	// GET: Calendar top Header
	function get_calendar_header($arguments){
		
		// SHORTCODE
		$args = $this->shortcode_args;
		extract($args);
		
		// FUNCTION
		$defaults = array(
			'focused_month_num'=>1,
			'focused_year'=>2014,			
			'range_start'=>0,
			'range_end'=>0,
			'send_unix'=>false,
			'header_title'=>'',
			'_html_evcal_list'=>true,
			'sortbar'=>true,
			'_html_sort_section'=>true,
			'date_header'=>true,
		);
		$arg_x = array_merge($defaults, $arguments);
		extract($arg_x);


		$cal_version =  get_option('eventon_plugin_version');			
		
		//BASE settings to pass to calendar
		
		$eventcard_open = ($this->is_eventcard_open)? 'eventcard="1"':null;		
		
		$__cal_classes=apply_filters('eventon_cal_class',array('ajde_evcal_calendar'));
		
		$cal_header_title = get_eventon_cal_title_month($focused_month_num, $focused_year, $args['lang']);


		// calendar data variables
		$_cd='';
		$cdata = apply_filters('eventon_cal_jqdata', array(
			'cyear'=>$focused_year,
			'cmonth'=>$focused_month_num,
			'runajax'=>'1',
			'evc_open'=>((!empty($args['evc_open']) && $args['evc_open']=='yes')? '1':'0'),
			'cal_ver'=>$cal_version,
			'mapscroll'=> ((!empty($this->evopt1['evcal_gmap_scroll']) && $this->evopt1['evcal_gmap_scroll']=='yes')?'false':'true'),
			'mapformat'=> (($this->evopt1['evcal_gmap_format']!='')?$this->evopt1['evcal_gmap_format']:'roadmap'),
			'mapzoom'=>(($this->evopt1['evcal_gmap_zoomlevel']!='')?$this->evopt1['evcal_gmap_zoomlevel']:'12'),
			'ev_cnt'=>$args['event_count'],
			'sort_by'=>'sort_date',
			'filters_on'=>$this->filters,
			'range_start'=>$range_start,
			'range_end'=>$range_end,
			'send_unix'=>( ($send_unix)?'1':'0'),
		));
		foreach ($cdata as $f=>$v){
			$_cd .='data-'.$f.'="'.$v.'" ';
		}

		$content='';
		// Calendar SHELL
		$content .= "<div id='evcal_calendar_".$cal_id."' class='".( implode(' ', $__cal_classes))."' >
			<div class='evo-data' {$_cd} ></div>";

				$sort_class = ($this->evcal_hide_sort=='yes')?'evcal_nosort':null;
		
		// HTML 
		$content.="<div id='evcal_head' class='calendar_header ".$sort_class."' >";

		// if the calendar arrows and headers are to show 
		if($date_header){
			$hide_arrows_check = ($this->evopt1['evcal_arrow_hide']=='yes')?"style='display:none'":null;
			$content.="<span id='evcal_prev' class='evcal_arrows evcal_btn_prev' ".$hide_arrows_check."></span>
				<p id='evcal_cur'> ".$cal_header_title."</p>
				<span id='evcal_next' class='evcal_arrows evcal_btn_next' ".$hide_arrows_check."></span>";	
		}else if(!empty($header_title)){
			$content.="<p>". $header_title ."</p>";
		}
		
		// (---) Hook for addon
			if(has_action('eventon_calendar_header_content')){
				ob_start();
				do_action('eventon_calendar_header_content', $content);
				$content.= ob_get_clean();
			}
		
		// Shortcode arguments
		$content.= $this->shortcode_args_for_cal();
		$content.="<div class='clear'></div></div>";
		
						
		// SORT BAR
		$content.= ($_html_sort_section)? $this->eventon_get_cal_sortbar($args['event_type'], $args['event_type_2'], $sortbar):null;
		
		$content .= ($_html_evcal_list)? "<div id='evcal_list' class='eventon_events_list'>":null;

		return $content;
	}
	

	

	// GET: single calendar month body content
	function get_calendar_month_body( $get_new_monthyear, $focus_start_date_range='', $focus_end_date_range=''){
		
		// CHECK if start and end day ranges are provided for this function
		$defined_date_ranges = ( empty($focus_start_date_range) && empty($focus_end_date_range) )?false: true;
		
		$args = $this->shortcode_args;
		extract($args);

		// update the languages array
		$this->reused();
		
		//print_r($args);
		
		// check if date ranges present
		if( !$defined_date_ranges){	
		
			// default start end date range -- for month view
			$get_new_monthyear = $get_new_monthyear;
			
			$focus_start_date_range = mktime( 0,0,0,$get_new_monthyear['month'],1,$get_new_monthyear['year'] );
			$time_string = $get_new_monthyear['year'].'-'.$get_new_monthyear['month'].'-1';		
			
			$focus_end_date_range = mktime(23,59,59,($get_new_monthyear['month']),(date('t',(strtotime($time_string) ))), ($get_new_monthyear['year']));
			
		}
				
				
		// generate events within the focused date range
		$eve_args = array(
			'focus_start_date_range'=>$focus_start_date_range,
			'focus_end_date_range'=>$focus_end_date_range,
			'sort_by'=>'sort_date', // by default sort events by start date					
			'event_count'=>$event_count,
			'ev_type'=>$event_type,
			'ev_type_2'=>$event_type_2,
			'filters'=>$filters,
			'number_months'=>$number_of_months // to determine empty label 
		);
		
		$eve_args =$this->update_shortcode_arguments($eve_args);
		$content_li = $this->eventon_generate_events($eve_args);	
		
		
		
		ob_start();
		if($content_li != 'empty'){
			// Eventon Calendar events list
			//echo "<div id='evcal_list' class='eventon_events_list'>";
			echo $content_li;
			//echo "</div>"; 
		}else{
			// ONLY UPCOMING LIST empty months
			if( $this->is_upcoming_list && !empty($hide_empty_months) && $hide_empty_months=='yes'){
				echo 'false';
			}else{
				//echo "<div id='evcal_list' class='eventon_events_list'>";
				echo "<div class='eventon_list_event'><p class='no_events'>".$this->lang_array['no_event']."</p></div>";
				//echo "</div>";
			}
		}
		
		return ob_get_clean();
		
	}
	



	/* INDEPENDENCY */

		
		// HEADER
		public function calendar_shell_header($arg){

			$defaults = array(
				'sort_bar'=> true,
				'title'=>'none',
				'date_header'=>true,
				'month'=>'1',
				'year'=>2014,
				'date_range_start'=>0,
				'date_range_end'=>0,
				'send_unix'=>false
			);

			$args = array_merge($defaults, $arg);

			$date_range_start =($args['date_range_start']!=0)? $args['date_range_start']: '0';
			$date_range_end =($args['date_range_end']!=0)? $args['date_range_end']: '0';

			$content ='';

			$content .= $this->get_calendar_header(
				array(
					'focused_month_num'=>$args['month'], 
					'focused_year'=>$args['year'], 
					'sortbar'=>$args['sort_bar'], 
					'date_header'=>$args['date_header'],
					'range_start'=>$date_range_start, 
					'range_end'=>$date_range_end , 
					'send_unix'=>$args['send_unix'],
					'header_title'=>$args['title']
				)
			);

			return $content;
		}

		// FOOTER
		public function calendar_shell_footer(){

			$content ='';

			$content.="</div><!-- #evcal_list-->
			<div class='clear'></div>
			</div><!-- .ajde_evcal_calendar-->";

			return $content;
		}



	// GET: calendar starting month and year data 
	function get_starting_monthYear(){
		$args = $this->shortcode_args;
		extract($args);
		
		// current focus month calculation
		//$current_timestamp =  current_time('timestamp');
			
		
		// *** GET STARTING month and year 
		if($fixed_month!=0 && $fixed_year!=0){
			$focused_month_num = $fixed_month;
			$focused_year = $fixed_year;
		}else{
		// GET offset month/year values
			$this_month_num = date('n');
			$this_year_num = date('Y');

			
			if($month_incre !=0){

				$mi_int = (int)$month_incre;

				$new_month_num = $this_month_num +$mi_int;
				
				//month
				$focused_month_num = ($new_month_num>12)? 
					$new_month_num-12:
					( ($new_month_num<1)?$new_month_num+12:$new_month_num );
				
				// year		
				$focused_year = ($new_month_num>12)? 
					$this_year_num+1:
					( ($new_month_num<1)?$this_year_num-1:$this_year_num );

				
			}else{
				$focused_month_num = $this_month_num;
				$focused_year = $this_year_num;
			}

		}
		

		//echo strtotime($month_incre.' month', $current_timestamp);

		return array('focused_month_num'=>$focused_month_num, 'focused_year'=>$focused_year);
	}



	/**
	 * GENERATE: function to build the entire event calendar
	 */
	public function eventon_generate_calendar($args){
		global $EventON, $wpdb;		
				
		// extract the variable values 
		$args__ = $this->process_arguments($args);
		extract($args__);
		
		//echo get_template_directory();
		//echo AJDE_EVCAL_PATH;
		//print_r($args__);
		//print_r($args);
		
		// Before beginning the eventON calendar Action
		if(has_action('eventon_cal_variable_action'))		
			do_action('eventon_cal_variable_action', $args);			
		
		// If settings set to hide calendar
		if( $show_upcoming!=1 && ( !empty($this->evopt1['evcal_cal_hide']) && $this->evopt1['evcal_cal_hide']=='no') ||  empty($this->evopt1['evcal_cal_hide'])):		
			
			
			$evcal_plugin_url= AJDE_EVCAL_URL;			
			$content = $content_li='';	
			
			// Check for empty month_incre values
			$month_incre = (!empty($month_incre))? $month_incre:0;
			
			
			// *** GET STARTING month and year 
			extract( $this->get_starting_monthYear() );
			
			// ========================================
			// HEADER with month and year name	- for NONE upcoming list events
			$content.= $this->get_calendar_header(array(
				'focused_month_num'=>$focused_month_num, 
				'focused_year'=>$focused_year
				)
			);
						
			
			// Calendar month body
			$get_new_monthyear = eventon_get_new_monthyear($focused_month_num, $focused_year,0);
			$content.= $this->get_calendar_month_body($get_new_monthyear, $focus_start_date_range, $focus_end_date_range);
			
			$content.="</div><div class='clear'></div></div>";
				
			// action to perform at the end of the calendar
			do_action('eventon_cal_end');
			
			return  $content;	

			
		
		// support for show_upcoming shortcode -- deprecated in the future
		elseif($show_upcoming==1 && $number_of_months>0):	
			
			return $this->generate_events_list($args);	
		endif;
		
		
	}

	/* GENERATE: upcoming list events*/
	function generate_events_list($args=''){
		
		$type = (empty($args))? 'usedefault':null;
		
		$args__ = $this->process_arguments($args, '', $type);
		extract($args__);
		$content='';
				
		// HIDE or show multiple occurance of events in upcoming list
		$this->_hide_mult_occur= ($hide_mult_occur=='yes')?true:false;
		
		
		// check if upcoming list calendar view
		if($number_of_months>0){
			$this->is_upcoming_list= true;
			$this->is_eventcard_open = false;			
		}
		
		// *** GET STARTING month and year 
		extract( $this->get_starting_monthYear() );
		
		// Calendar SHELL
		$content.=$this->get_calendar_header(array(
			'focused_month_num'=>$focused_month_num, 
			'focused_year'=>$focused_year,
			'sortbar'=>false,
			'date_header'=>false,
			'_html_evcal_list'=>false,
			'_html_sort_section'=>false
			)
		);
		
		
		// generate each month
		for($x=0; $x<$number_of_months; $x++){
			$month_body='';
			
			$get_new_monthyear = eventon_get_new_monthyear($focused_month_num, $focused_year,$x);
			
			$active_month_name = eventon_returnmonth_name_by_num($get_new_monthyear['month']);
			
			// check settings to see if year should be shown or not
			$active_year = (!empty($show_year) && $show_year=='yes')?
				$get_new_monthyear['year']:null;
				
			// body content of the month
			$month_body= $this->get_calendar_month_body($get_new_monthyear);
			
			
			if($month_body=='false' && !empty($hide_empty_months) && $hide_empty_months=='yes' ){
				//$content.= "<div class='evcal_month_line'><p>".$active_month_name.' '.$active_year."</p></div>";
			}else{
				// Construct months exterior 				
				$content.= "<div class='evcal_month_line'><p>".$active_month_name.' '.$active_year."</p></div>";

				$content.= "<div id='evcal_list' class='eventon_events_list'>";
				$content.= $month_body;
				$content.= "</div>";
			
			}
		}
		
		
		$content.="<div class='clear'></div></div>";
		
		return $content;
			
	}
	
	
	/**
	 * MAIN function to generate individual events.
	 *
	 * @access public
	 * @return void
	 /*
		possible values
		array(
			'focus_start_date_range'
			'focus_end_date_range'
			'sort_by'=>sort_date,sort_title,sort_color,event_type, event_type_2
			'ev_type'
			'ev_type_2'
			'event_count'
			'number_months'
			'filters'
		)
	*/	 
	public function eventon_generate_events($args){
		
		global $EventON;				
				
		// get required shortcode based argument values
		if(empty($this->shortcode_args)){
			$ecv = $this->process_arguments($args);
		}else{
			$args =array_merge($this->shortcode_args,$args );
			$ecv =$this->process_arguments($args);
		}
		
		
		//print_r($args);
		//print_r($ecv);
		
		// ===========================
		// WPQUery Arguments
		$wp_arguments = array (
			'post_type' 		=> 'ajde_events' ,
			'post_status'		=>'publish',
			'posts_per_page'	=>-1 ,
			'order'				=>'ASC',					
		);
		
		// apply other filters to wp argument
		$wp_arguments = $this->apply_evo_filters_to_wp_argument($wp_arguments, $ecv['filters'],$ecv['event_type'],$ecv['event_type_2']);
		
		
		//print_r($wp_arguments);
				
		// -----------------------------
		// hook for addons
		if(has_filter('eventon_wp_query_args')){
			$wp_arguments = apply_filters('eventon_wp_query_args',$wp_arguments);
		}
		
		$this->wp_arguments = $wp_arguments;
		
		//print_r($wp_arguments);
		
		
		// ========================	
		// GET: list of events for wp argument
		$event_list_array = $this->wp_query_event_cycle(
			$wp_arguments, 
			$ecv['focus_start_date_range'], 
			$ecv['focus_end_date_range']
		);
		
		
		//print_r($event_list_array);		
		
		//print_r($this->_featured_events);
		
		// SORT: events array
		if(is_array($event_list_array)){			
			switch($ecv['sort_by']){
				case has_action("eventon_event_sorting_{$ecv['sort_by']}"):
					do_action("eventon_event_sorting_{$ecv['sort_by']}", $event_list_array);
					
				break;
				case 'sort_date':
					usort($event_list_array, 'cmp_esort_startdate' );
				break;case 'sort_title':
					usort($event_list_array, 'cmp_esort_title' );
				break; case 'sort_color':
					usort($event_list_array, 'cmp_esort_color' );
				break;
				
			}
		}
		//print_r($event_list_array);
		
		// ALT: reverse events order if set
		$event_list_array = ($this->shortcode_args['event_order']=='DESC')? 
			array_reverse($event_list_array) : $event_list_array;
		
		if(has_filter('eventon_sorted_dates'))
			apply_filters('eventon_sorted_dates', $event_list_array);
		
		
		// GET: eventTop and eventCard for each event in order
		$months_event_array = $this->generate_event_data( 
			$event_list_array, 
			$ecv['focus_start_date_range']
		);
		//print_r($months_event_array);
		
		
		// MOVE: featured events to top if set
		if($this->shortcode_args['ft_event_priority']=='yes' && !empty($this->_featured_events) && count($this->_featured_events)>0){
			
			$ft_events = $events = array();
			
			foreach($months_event_array as $event){
				//print_r($event_list_array);
				
				if(in_array($event['event_id'], $this->_featured_events)){
					$ft_events[]=$event;
				}else{
					$events[]=$event;
				}
			}
			
			// move featured events to top
			$months_event_array =array_merge($ft_events,$events);
		}
		
		
		// ========================
		// RETURN VALUES
		$content_li='';
		// month array with events
		if( is_array($months_event_array) && count($months_event_array)>0){
			if($ecv['event_count']==0 ){
				foreach($months_event_array as $event){
					$content_li.= $event['content'];
				}
				
			}else if($ecv['event_count']>0){
				
				// make sure we take lesser value of count
				$lesser_of_count = (count($months_event_array)<$ecv['event_count'])?
					count($months_event_array): $ecv['event_count'];
				
				// for each event until count
				for($x=0; $x<$lesser_of_count; $x++){
					$content_li.= $months_event_array[$x]['content'];
				}
			}
		}else{	
			// EMPTY month array
			if($this->is_upcoming_list && !empty($ecv['hide_empty_months']) && $ecv['hide_empty_months']=='yes'){
				$content_li = "empty";				
			}else{
				$content_li = "<div class='eventon_list_event'><p class='no_events'>".$this->lang_array['no_event']."</p></div>";
			}
			
		}
		return $content_li;
		
	}// END evcal_generate_events()
	
	
	
	/**
	 * WP_Query function to generate relavent events for a given month
	 * return events list within start - end date range for WP_Query arg.
	 * return array
	 */
	public function wp_query_event_cycle($wp_arguments, $focus_month_beg_range, $focus_month_end_range, $some=''){
		
		//echo $focus_month_beg_range.' '. $focus_month_end_range.'ff'.$some;
		
		
		$event_list_array= $featured_events = array();
		$wp_arguments= (!empty($wp_arguments))?$wp_arguments: $this->wp_arguments;
		//print_r($wp_arguments);
		
		

		// check if multiple occurance of events b/w months allowed
		$__run_occurance_check = (($this->is_upcoming_list && $this->_hide_mult_occur) || (!empty($this->shortcode_args['hide_mult_occur']) && $this->shortcode_args['hide_mult_occur']=='yes'))? true:false;
		
		/** RUN through all events **/
		$events = new WP_Query( $wp_arguments);
		if ( $events->have_posts() ) :
			
			date_default_timezone_set('UTC');	
			// override past event cut-off
				if(!empty($this->shortcode_args['pec'])){

					//shortcode driven hide_past value
					$evcal_cal_hide_past= ($this->_sc_hide_past)? 'yes': 
						( (!empty($this->evopt1['evcal_cal_hide_past']))? $this->evopt1['evcal_cal_hide_past']: 'no');

					if( $this->shortcode_args['pec']=='cd'){
						// this is based on local time
						$current_time = strtotime( date("m/j/Y", current_time('timestamp')) );	
					}else{
						// this is based on UTC time zone
						$current_time = current_time('timestamp');		
					}

				}else{
					// Define option values for the front-end
					$cur_time_basis = (!empty($this->evopt1['evcal_past_ev']) )? $this->evopt1['evcal_past_ev'] : null;
					//shortcode driven hide_past value
					$evcal_cal_hide_past= ($this->_sc_hide_past)? 'yes': 
						( (!empty($this->evopt1['evcal_cal_hide_past']))? $this->evopt1['evcal_cal_hide_past']: 'no');
					
					//date_default_timezone_set($tzstring);	
					if($evcal_cal_hide_past=='yes' && $cur_time_basis=='today_date'){
						// this is based on local time
						$current_time = strtotime( date("m/j/Y", current_time('timestamp')) );	
					}else{
						// this is based on UTC time zone
						$current_time = current_time('timestamp');		
					}
				}//pec not present



			
			while( $events->have_posts()): $events->the_post();
				
				$p_id = get_the_ID();
				$ev_vals = get_post_custom($p_id);
				
				$is_recurring_event = (!empty($ev_vals['evcal_repeat']) )? $ev_vals['evcal_repeat'][0]: null;
				//$__is_all_day_event = (!empty($ev_vals['evcal_allday']) && $ev_vals['evcal_allday'][0]=='yes')?true:false;
				
				// initial event start and end UNIX
				$row_start = (!empty($ev_vals['evcal_srow']))? 
					$ev_vals['evcal_srow'][0] :null;
				$row_end = ( !empty($ev_vals['evcal_erow']) )? 
					$ev_vals['evcal_erow'][0]:$row_start;
				
				$evcal_event_color_n= (!empty($ev_vals['evcal_event_color_n']))?$ev_vals['evcal_event_color_n'][0]:'0';
				
				$_is_featured = (!empty($ev_vals['_featured']))? 
					$ev_vals['_featured'][0] :'no';
				
				// check for recurring event 
				if($is_recurring_event=='yes'){
					$frequency = $ev_vals['evcal_rep_freq'][0];
					$repeat_gap_num = $ev_vals['evcal_rep_gap'][0];
					$repeat_num = (int)$ev_vals['evcal_rep_num'][0];
					
					
					// each repeating instance	
					$monthly_row_start = $row_start;
					for($x=0; $x<=($repeat_num); $x++){
						
						$feature='no';
												
						$repeat_multiplier = ((int)$repeat_gap_num) * $x;
						//$multiply_term = '+'.$repeat_multiplier.' '.$term;
						
						// Get repeat terms for different frequencies
						switch($frequency){
							// Additional frequency filters
							case has_filter("eventon_event_frequency_{$frequency}"):
								$terms = apply_filters("eventon_event_frequency_{$frequency}", $repeat_multiplier);								
								$term = $terms['term'];
								$term_ar = $terms['term_ar'];
							break;
							case 'yearly':
								$term = 'year';	$term_ar = 'ry';
								$feature = ($_is_featured!='no')?'yes':'no';
							break;
							case 'monthly':
								$term = 'month';	$term_ar = 'rm';
								$feature = ($_is_featured!='no')?'yes':'no';
							break; 
							case 'weekly':
								$term = 'week';	$term_ar = 'rw';
							break;							
							default: $term = $term_ar = ''; break;
						}
						
						
						$E_start_unix = strtotime('+'.$repeat_multiplier.' '.$term, $row_start);
						$E_end_unix = strtotime('+'.$repeat_multiplier.' '.$term, $row_end);
								

						
						$fe = ( (!empty($this->shortcode_args['el_type']))? true: eventon_is_future_event($current_time, $E_end_unix, $evcal_cal_hide_past) );

						$me = eventon_is_event_in_daterange($E_start_unix,$E_end_unix, $focus_month_beg_range,$focus_month_end_range, $this->shortcode_args);
						

						if($fe && $me){
							if($__run_occurance_check && !in_array($p_id, $this->events_processed) ||!$__run_occurance_check){
							
								$event_list_array[] = array(
									'event_id' => $p_id,
									'event_start_unix'=>$E_start_unix,
									'event_end_unix'=>$E_end_unix,
									'event_title'=>get_the_title(),
									'event_color'=>$evcal_event_color_n,
									'event_type'=>$term_ar,
								);
								
								if($feature!='no'){
									$featured_events[]=$p_id;
								}
							}
							$this->events_processed[]=$p_id;	
						}					
					}	
					
				}else{
				// Non recurring event
					$fe = ( (!empty($this->shortcode_args['el_type']))? true: eventon_is_future_event($current_time, $row_end, $evcal_cal_hide_past));
					$me = eventon_is_event_in_daterange($row_start,$row_end, $focus_month_beg_range,$focus_month_end_range, $this->shortcode_args);
					
					//echo get_the_title().$row_end.' v '.$current_time.'-</br>';

					if($fe && $me){
						
						if($__run_occurance_check && !in_array($p_id, $this->events_processed) ||!$__run_occurance_check){
							
							$feature = ($_is_featured!='no')?'yes':'no';
							
							$event_list_array[] = array(
								'event_id' => $p_id,
								'event_start_unix'=>$row_start,
								'event_end_unix'=>$row_end,
								'event_title'=>get_the_title(),
								'event_color'=>$evcal_event_color_n,
								'event_type'=>'nr',
							);	
							
							if($feature!='no'){
								$featured_events[]=$p_id;
							}
							
							$this->events_processed[]=$p_id;
						}
					}		
				}
				
				
			endwhile;
			
			$this->_featured_events=$featured_events;
			
		endif;
		wp_reset_query();
		
		return $event_list_array;
	}
	
	
	/**
	 *	output single event data
	 */
	public function get_single_event_data($event_id){

		$this->__calendar_type = 'single';
		
		// GET Eventon files to load for single event
		$this->load_evo_files();
		
		$this->is_eventcard_open= ($this->is_eventcard_hide_forcer)?false:true;
		
		$emv = get_post_custom($event_id);
		
		$event_array[] = array(
			'event_id' => $event_id,
			'event_start_unix'=>$emv['evcal_srow'][0],
			'event_end_unix'=>$emv['evcal_erow'][0],
			'event_title'=>get_the_title($event_id),
			'event_color'=>$emv['evcal_event_color_n'][0],
			'event_type'=>'nr'
		);
		
		$month_int = date('n', time() );

		return $this->generate_event_data($event_array, '', $month_int);
		
	}
	
	
	/**
	 * GENERATE individual event data
	 */
	public function generate_event_data(
		$event_list_array, 
		$focus_month_beg_range='', 
		$FOCUS_month_int='', 
		$FOCUS_year_int=''
	){
		
		
		$months_event_array='';
		
		// Initial variables
		$wp_time_format = get_option('time_format');
		$default_event_color = (!empty($this->evopt1['evcal_hexcode']))?$this->evopt1['evcal_hexcode']:'#ffa800';
		$__shortC_arg = $this->shortcode_args;

		//print_r($__shortC_arg);
		
				
		// EVENT CARD open by default variables		
		$eventcard_styles = ($this->is_eventcard_open || (!empty($__shortC_arg['evc_open']) && $__shortC_arg['evc_open']=='yes' ))? null:"style='display:none'";
		$eventcard_script_class = ($this->is_eventcard_open)? "gmaponload":null;
		
		
		// GET: Event Type's custom names
		$evt_name = $this->lang_array['et'];
		$evt_name2 = $this->lang_array['et2'];
		
		
		$CURRENT_month_INT = (!empty($FOCUS_month_int))?$FOCUS_month_int: date('n', $focus_month_beg_range ); // 
		
		// check featured events are prioritized
		$__feature_events = ($this->shortcode_args['ft_event_priority']!='no')?true:false;
		
		
		// GET EventTop fields - v2.1.17
		$eventop_fields = (!empty($this->evopt1['evcal_top_fields']))?$this->evopt1['evcal_top_fields']:null;
		
		
		// eventCARD HTML
		require_once(AJDE_EVCAL_PATH.'/admin/includes/eventon_eventCard.php');
		
		
		// EACH EVENT
		if(is_array($event_list_array) ){
		foreach($event_list_array as $event):
			
			$event_id = $event['event_id'];
			$event_start_unix = $event['event_start_unix'];
			$event_end_unix = $event['event_end_unix'];
			$event_type = $event['event_type'];
			
			$event = get_post($event_id);
			$ev_vals = get_post_custom($event_id);
			
			
			// define variables
			$ev_other_data = $ev_other_data_top = $html_event_type_info= $_event_date_HTML=$_eventcard='';	
			$_is_end_date=true;
			
			$DATE_start_val=eventon_get_formatted_time($event_start_unix);
			if(empty($event_end_unix)){
				$_is_end_date=false;
				$DATE_end_val= $DATE_start_val;
			}else{
				$DATE_end_val=eventon_get_formatted_time($event_end_unix);
			}

			// if this event featured
			$__featured = (!empty($ev_vals['_featured']) && $ev_vals['_featured'][0]=='yes')? true:false;

			
			// Unique ID generation
			$unique_varied_id = 'evc'.$event_start_unix.(uniqid()).$event_id;
			$unique_id = 'evc_'.$event_start_unix.$event_id;
			
			// All day event variables
			$_is_allday = (!empty($ev_vals['evcal_allday']) && $ev_vals['evcal_allday'][0]=='yes')? true:false;
			$_hide_endtime = (!empty($ev_vals['evo_hide_endtime']) && $ev_vals['evo_hide_endtime'][0]=='yes')? true:false;
			$evcal_lang_allday = eventon_get_custom_language( $this->evopt2,'evcal_lang_allday', 'All Day');
			
			
			/*
				evo_hide_endtime
				NOTE: if its set to hide end time, meaning end time and date would be empty on wp-admin, which will fall into same start end month category.
			*/
				
			/** EVENT TYPE = start and end in SAME MONTH **/
				if($DATE_start_val['n'] == $DATE_end_val['n']){
					
					/** EVENT TYPE = start and end in SAME DAY **/
					if($DATE_start_val['j'] == $DATE_end_val['j']){
						
						// check all days event
						if($_is_allday){					
							$__from_to ="<em class='evcal_alldayevent_text'>(".$evcal_lang_allday.": ".$DATE_start_val['l'].")</em>";
							$__prettytime = $evcal_lang_allday.' ('.$DATE_start_val['l'].')';
						}else{
							
							$__from_to = ($_hide_endtime)?
								date($wp_time_format,($event_start_unix)):
								date($wp_time_format,($event_start_unix)).' - '. date($wp_time_format,($event_end_unix));
							
							$__prettytime ='('.$DATE_start_val['l'].') '.$__from_to;
						}
						
						$_event_date_HTML = array(
							'html_date'=>$DATE_start_val['j'],
							'html_fromto'=> apply_filters('eventon_evt_fe_time', $__from_to),
							'html_prettytime'=> apply_filters('eventon_evt_fe_ptime', $__prettytime),
							'class_daylength'=>"sin_val"
						);	
						
					}else{
						// different start and end date
						
						// check all days event
						if($_is_allday){
							$__from_to ="<em class='evcal_alldayevent_text'>(".$evcal_lang_allday.")</em>";
							$__prettytime = $DATE_start_val['j'].' ('.$DATE_start_val['l'].') - '.$DATE_end_val['j'].' ('.$DATE_end_val['l'].')';
						}else{
							$__from_to = date($wp_time_format,($event_start_unix)).' - '.date($wp_time_format,($event_end_unix)). ' ('.$DATE_end_val['j'].')';
							$__prettytime =$DATE_start_val['j'].' ('.$DATE_start_val['l'].') '.date($wp_time_format,($event_start_unix)).' - '.$DATE_end_val['j'].' ('.$DATE_end_val['l'].') '.date($wp_time_format,($event_end_unix));
						}
						
						$_event_date_HTML = array(							
							'html_date'=>$DATE_start_val['j'].'<span> - '.$DATE_end_val['j'].'</span>',
							'html_fromto'=> apply_filters('eventon_evt_fe_time', $__from_to),
							'html_prettytime'=> apply_filters('eventon_evt_fe_ptime', $__prettytime),
							'class_daylength'=>"mul_val"
						);	
					}					
				}else{
					/** EVENT TYPE = different start and end months **/
					
					/** EVENT TYPE = start month is before current month **/
					if($CURRENT_month_INT != $DATE_start_val['n']){
						// check all days event
						if($_is_allday){
							$__from_to ="<em class='evcal_alldayevent_text'>(".$evcal_lang_allday.")</em>";						
						}else{
							$__from_to = 
								'('.$DATE_start_val['F'].' '.$DATE_start_val['j'].') '.date($wp_time_format,($event_start_unix)).' - ('.$DATE_end_val['F'].' '.$DATE_end_val['j'].') '.date($wp_time_format,($event_end_unix));
						}
											
												
					}else{
						/** EVENT TYPE = start month is current month **/
						// check all days event
						if($_is_allday){
							$__from_to ="<em class='evcal_alldayevent_text'>(".$evcal_lang_allday.")</em>";						
						}else{
							$__from_to =
								date($wp_time_format,($event_start_unix)).' - ('.$DATE_end_val['F'].' '.$DATE_end_val['j'].') '.date($wp_time_format,($event_end_unix));	
						}
					}
					
					
					// check all days event
					if($_is_allday){
						$__prettytime = $DATE_start_val['F'].' '.$DATE_start_val['j'].' ('.$DATE_start_val['l'].') - '.$DATE_end_val['F'].' '.$DATE_end_val['j'].' ('.$DATE_end_val['l'].')';
					}else{
						$__prettytime = 
							$DATE_start_val['F'].' '.$DATE_start_val['j'].' ('.$DATE_start_val['l'].') '.date($wp_time_format,($event_start_unix)).' - '.$DATE_end_val['F'].' '.$DATE_end_val['j'].' ('.$DATE_end_val['l'].') '.date($wp_time_format,($event_end_unix));	
					}
					
					
					$_event_date_HTML = apply_filters('evo_eventcard_dif_SEM', array(
						'html_date'=>$DATE_start_val['j'].'<span> - '.$DATE_end_val['j'].'</span>',
						'html_fromto'=> apply_filters('eventon_evt_fe_time', $__from_to),
						'html_prettytime'=>apply_filters('eventon_evt_fe_ptime', $__prettytime),
						'class_daylength'=>"mul_val"
					));
				}
				
			
		
			// (---) hook for addons
			if(has_filter('eventon_eventcard_date_html'))
				apply_filters('eventon_eventcard_date_html', $_event_date_HTML, $event_id);
		
			
			
			// EVENT FEATURES IMAGE
				$img_id =get_post_thumbnail_id($event_id);
				if($img_id!=''){				
					$img_src = wp_get_attachment_image_src($img_id,'full');
					$img_thumb_src = wp_get_attachment_image_src($img_id,'thumbnail');
									
					// append to eventcard array
					$_eventcard['ftimage'] = array(
						'img'=>$img_src,
					);	
									
				}else{		$img_thumb_src='';		}
				
			// EVENT DESCRIPTION
				$evcal_event_content =apply_filters('the_content', $event->post_content);
				
				if(!empty($evcal_event_content) ){
					$event_full_description = $evcal_event_content;
				}else{
					// event description compatibility from older versions.
					$event_full_description =(!empty($ev_vals['evcal_description']))?$ev_vals['evcal_description'][0]:null;
				}			
				if(!empty($event_full_description) ){				
					
					$except = $event->post_excerpt;
					$event_excerpt = eventon_get_event_excerpt($event_full_description, 30, $except);
					
					$_eventcard['eventdetails'] = array(
						'fulltext'=>$event_full_description,
						'excerpt'=>$event_excerpt,
					);				
					
				}
			
			
			// EVENT TIME						
			// EVENT LOCATION
				$lonlat = (!empty($ev_vals['evcal_lat']) && !empty($ev_vals['evcal_lon']) )?
						'latlon="1" latlng="'.$ev_vals['evcal_lat'][0].','.$ev_vals['evcal_lon'][0].'" ': null;
							
				
				$__location = (!empty($ev_vals['evcal_location']))?
					$ev_vals['evcal_location'][0]:null;
				
				// location name
					$__location_name = (!empty($ev_vals['evcal_location_name']))?
						$ev_vals['evcal_location_name'][0]:null;
				
				$_eventcard['timelocation'] = array(
					'timetext'=>$_event_date_HTML['html_prettytime'],
					'location'=>$__location,
					'location_name'=>$__location_name
				);
			
						
			// GOOGLE maps			
				if( ($this->google_maps_load) && !empty($ev_vals['evcal_location']) && (!empty($ev_vals['evcal_gmap_gen']) && $ev_vals['evcal_gmap_gen'][0]=='yes') ){
					
					$gmap_api_status='';				
					$_eventcard['gmap'] = array(
						'id'=>$unique_varied_id,
					);
					
					
					// GET directions
					if($this->evopt1['evo_getdir']=='yes'){
						$_eventcard['getdirection'] = array(
							'fromaddress'=>$ev_vals['evcal_location'][0],
						);
					}
									
				}else{	$gmap_api_status = 'data-gmap_status="null"';	}
				
			
			// EVENT BRITE
			// check if eventbrite actually used in this event
				if(!empty($ev_vals['evcal_eventb_data_set'] ) && $ev_vals['evcal_eventb_data_set'][0]=='yes'){			
					// Event brite capacity
					if( 
						!empty($ev_vals['evcal_eventb_tprice'] ) &&				
						!empty($ev_vals['evcal_eventb_url'] ) )
					{					
						
						$_eventcard['eventbrite'] = array(
							'capacity'=>(( !empty($ev_vals['evcal_eventb_capacity']))?$ev_vals['evcal_eventb_capacity'][0]:null),
							'tix_price'=>$ev_vals['evcal_eventb_tprice'][0],
							'url'=>$ev_vals['evcal_eventb_url'][0]
						);
						
					}				
				}
			
			
			// PAYPAL Code
				if(!empty($ev_vals['evcal_paypal_link'][0]) && $this->evopt1['evcal_paypal_pay']=='yes'){
					
					$_eventcard['paypal'] = array(
						'link'=>$ev_vals['evcal_paypal_link'][0]
					);
					
				}			
			
			// Event Organizer
				if(!empty($ev_vals['evcal_organizer'] )){
					
					$_eventcard['organizer'] = array(
						'value'=>$ev_vals['evcal_organizer'][0]
					);			
					
				}
						
			// Custom fields
				for($x =1; $x<4; $x++){
					if( !empty($this->evopt1['evcal_ec_f'.$x.'a1']) && !empty($this->evopt1['evcal__fai_00c'.$x])	&& !empty($ev_vals["_evcal_ec_f".$x."a1_cus"])	){
						
						// check if hide this from eventCard set to yes
						if(empty($this->evopt1['evcal_ec_f'.$x.'a3']) || $this->evopt1['evcal_ec_f'.$x.'a3']=='no'){

							$faicon = $this->evopt1['evcal__fai_00c'.$x];
							
							$_eventcard['customfield'.$x] = array(
								'imgurl'=>$faicon,
								'x'=>$x,
								'value'=>$ev_vals["_evcal_ec_f".$x."a1_cus"][0]
							);
						}
					}
				}
						
			// LEARN MORE and ICS
				if(!empty($ev_vals['evcal_lmlink']) || !empty($this->evopt1['evo_ics']) && $this->evopt1['evo_ics']=='yes'){
					$_eventcard['learnmoreICS'] = array(						
						'event_id'=>$event_id,
						'learnmorelink'=>( (!empty($ev_vals['evcal_lmlink']))? $ev_vals['evcal_lmlink'][0]: null),
						'learnmore_target'=> ((!empty($ev_vals['evcal_lmlink_target'])  && $ev_vals['evcal_lmlink_target'][0]=='yes')? 'target="_blank"':null),
						'estart'=> ($event_start_unix),
						'eend'=>($event_end_unix),
						'etitle'=>$event->post_title,
					);
				}
			
			//print_r($_eventcard);
			
			// =======================
			/** CONSTRUCT the EVENT CARD	 **/		
				if(!empty($_eventcard) && count($_eventcard)>0){
					
					// if an order is set reorder things
					$_eventcard = eventon_EVC_sort($_eventcard, $this->evopt1['evoCard_order']);
					
					ob_start();
				
					echo "<div class='event_description evcal_eventcard' ".$eventcard_styles.">";
					
					echo  eventon_eventcard_print($_eventcard, $this->evopt1, $this->evopt2);
					
					
					// (---) hook for addons
					if(has_action('eventon_eventcard_additions')){
						do_action('eventon_eventcard_additions', $event_id, $this->__calendar_type, $event->post_title, $event_full_description, $img_thumb_src);
					}
				
					echo "</div>";
					
					$html_event_detail_card = ob_get_clean();				
					
				}else{
					$html_event_detail_card=null;
				}
			
			
			
			/** Trigger attributes **/
			$event_description_trigger = (!empty($html_event_detail_card))? "desc_trig":null;
			$gmap_trigger = (!empty($ev_vals['evcal_gmap_gen']) && $ev_vals['evcal_gmap_gen'][0]=='yes')? 'data-gmtrig="1"':'data-gmtrig="0"';
			
			
			//event color			
			$event_color = (!empty($ev_vals['evcal_event_color']) )?
				(($ev_vals["evcal_event_color"][0][0] == '#')?
						$ev_vals["evcal_event_color"][0]:
						'#'.$ev_vals["evcal_event_color"][0] )
					: $default_event_color;
				
			//event type taxonomies #1
			$evcal_terms = wp_get_post_terms($event_id,'event_type');
				$term_class ='';
				if($evcal_terms){
					
					$html_event_type_info .="<span class='evcal_event_types'><em><i>".$evt_name.":</i></em>";
					foreach($evcal_terms as $termA):
						$term_class = ' evo_'.$termA->slug;
						$html_event_type_info .="<em>".$termA->name."</em>";
					endforeach; 
					$html_event_type_info .="<i class='clear'></i></span>";
				}
			
			
			
			// event ex link
			$exlink_option = (!empty($ev_vals['_evcal_exlink_option']) )?$ev_vals['_evcal_exlink_option'][0]:1;
			$event_permalink = get_permalink($event_id);
			
			$href = (!empty($ev_vals['evcal_exlink']) && $exlink_option!='1' )? 
				'data-exlk="1" href="'.$ev_vals['evcal_exlink'][0].'"': 'data-exlk="0"';
			// target
			$target_ex = (!empty($ev_vals['_evcal_exlink_target'])  && $ev_vals['_evcal_exlink_target'][0]=='yes')?
				'target="_blank"':null;
			
			
			
			// EVENT LOCATION
				if(!empty($ev_vals['evcal_location'])){
					$event_location_variables = ((!empty($lonlat))? $lonlat:null ). ' add_str="'.$ev_vals['evcal_location'][0].'" ';
					
					$__scheme_data_location = '
						<item style="display:none" itemprop="location" itemscope itemtype="http://schema.org/Place">
							<span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
								<item itemprop="streetAddress">'.$ev_vals['evcal_location'][0].'</item>
							</span>
						</item>';
						
					$ev_location =				
						'<em class="evcal_location" '.( (!empty($lonlat))? $lonlat:null ).' add_str="'.$ev_vals['evcal_location'][0].'">'.$ev_vals['evcal_location'][0].'</em>';
				}else{
					$ev_location = $event_location_variables= $__scheme_data_location= null;
				}
				// location name 
				$event_location_variables .= (!empty($ev_vals['evcal_location_name']))? 'data-location_name="'.$ev_vals['evcal_location_name'][0].'"':null;

			
			/* -------------------
			// 	HTML		
			// 	EventTop - building of the eventTop section
			-------------*/
			$eventtop_html='';
				
				// featured image
				$eventtop_html[] = (!empty($img_thumb_src) && $__shortC_arg['show_et_ft_img']=='yes')? "<p class='ev_ftImg' style='background-image:url(".$img_thumb_src[0].")'></p>":null;
				
				// date number 
				$eventtop_html[]="<p class='evcal_cblock' style='bgcolor='".$event_color."' smon='".$DATE_start_val['F']."' syr='".$DATE_start_val['Y']."'><em class='evo_date'>".$_event_date_HTML['html_date'].'</em>';
				
				// CHECK for event top fields array
				$eventop_fields_ = (is_array($eventop_fields) )? true:false;
				
				// month name
				if($eventop_fields_ && in_array('monthname',$eventop_fields) || ( !empty($this->shortcode_args['etop_month']) && $this->shortcode_args['etop_month']=='yes') )
					$eventtop_html[]="<em class='evo_month' mo='".$DATE_start_val['M']."'>".$DATE_start_val['M']."</em>";
				
				// day name
				if($eventop_fields_ && in_array('dayname',$eventop_fields))
					$eventtop_html[]="<em class='evo_day' >".$DATE_start_val['D']."</em>";
				
				$eventtop_html[]="<em class='clear'></em></p>";
				
				// event title
				$eventtop_html[]= "<p class='evcal_desc' {$event_location_variables}><span class='evcal_desc2 evcal_event_title' itemprop='name'>".$event->post_title."</span>";
				
				$eventtop_html[]= "<span class='evcal_desc_info' >";
				
				// time
				if($eventop_fields_ && in_array('time',$eventop_fields))
					$eventtop_html[]= "<em class='evcal_time'>".$_event_date_HTML['html_fromto']."</em> ";
				
				// location
				if($eventop_fields_ && in_array('location',$eventop_fields))
					$eventtop_html[]= $ev_location;

				// location Name
				if($eventop_fields_ && in_array('locationame',$eventop_fields)){
					$__location_name = (!empty($ev_vals['evcal_location_name']))?
						$ev_vals['evcal_location_name'][0]:null;
					$eventtop_html[]= '<em class="evcal_location event_location_name">'.$__location_name.'</em>';
				}
				
				$eventtop_html[]= "</span><span class='evcal_desc3'>";
				
				// organizer
				if($eventop_fields_ && in_array('organizer',$eventop_fields) && !empty($ev_vals['evcal_organizer']))
					$eventtop_html[]= "<em class='evcal_oganizer'><i>".( eventon_get_custom_language( $this->evopt2,'evcal_evcard_org', 'Event Organized By')  ).':</i> '.$ev_vals['evcal_organizer'][0]."</em> ";
				
				// event type
				if($eventop_fields_ && in_array('eventtype',$eventop_fields))
					$eventtop_html[]= $html_event_type_info;
				
				$eventtop_html[]= "</p>";
			
				$eventtop_html = apply_filters('eventon_eventtop_html',$eventtop_html);
			// --
			
			
			// Combine the event top individual sections
			$html_info_line = implode('', $eventtop_html);
			
			
			
			// (---) hook for addons
			if(has_filter('eventon_event_cal_short_info_line') ){
				$html_info_line = apply_filters('eventon_event_cal_short_info_line', $html_info_line);
			}
			
			
			// SCHEME SEO
				$__scheme_data = 
					'<div class="evo_event_schema" style="display:none" >
					<a href="'.$event_permalink.'" itemprop="url"></a>				
					<time itemprop="startDate" datetime="'.$DATE_start_val['Y'].'-'.$DATE_start_val['n'].'-'.$DATE_start_val['j'].'"></time>'.
					$__scheme_data_location.
					'</div>'
				
				;
			
			
			
			// ## Eventon Calendar events list -- single event
			
			// CLASES - attribute
			$_ft_imgClass = (!empty($img_thumb_src) && $__shortC_arg['show_et_ft_img']=='yes')? 'hasFtIMG':null;
			$__attr_class = "evcal_list_a ".$event_description_trigger." "
				.	$_event_date_HTML['class_daylength']." ".(($event_type!='nr')?'event_repeat ':null). $eventcard_script_class.$_ft_imgClass;
			$_ft_event = ($__feature_events && !empty($ev_vals['_featured']) && $ev_vals['_featured'][0]=='yes')?' ft_event ':null;
			
			// class attribute for event
			$__a_class = $__attr_class.$_ft_event.$term_class. ( ($__featured)? ' featured_event':null) ;
			
			
			// div or an e tag
			$html_tag = ($exlink_option=='1')? 'div':'a';

			$event_html_code="<div class='eventon_list_event' event_id='{$event_id}' itemscope itemtype='http://schema.org/Event'>{$__scheme_data}
			<{$html_tag} id='".$unique_id."' class='".$__a_class."' ".$href." ".$target_ex." style='border-color: ".$event_color."' ".$gmap_trigger." ".(!empty($gmap_api_status)?$gmap_api_status:null)." data-ux_val='{$exlink_option}'>{$html_info_line}</{$html_tag}>".$html_event_detail_card."<div class='clear'></div></div>";	
			
			//evc_open
			
			// prepare output
			$months_event_array[]=array(
				'event_id'=>$event_id,
				'srow'=>$event_start_unix,
				'erow'=>$event_end_unix,
				'content'=>$event_html_code
			);
			
			
		endforeach;
		
		}else{
			$months_event_array;
		}
		
		return $months_event_array;
	}
	
	/**
	 *	 Add other filters to wp_query argument
	 */
	public function apply_evo_filters_to_wp_argument($wp_arguments, $filters='', $ev_type='', $ev_type_2=''){
		// -----------------------------
		// FILTERING events	
		
		// values from filtering events
		if(!empty($filters)){			
			
			// build out the proper format for filtering with WP_Query
			$cnt =0;
			$filter_tax['relation']='AND';
			foreach($filters as $filter){
				if($filter['filter_type']=='tax'){					
					
					$filter_val = explode(',', $filter['filter_val']);
					$filter_tax[] = array(
						'taxonomy'=>$filter['filter_name'],
						'field'=>'id',
						'terms'=>$filter_val						
					);
					$cnt++;
				}else{				
					$filter_meta[] = array(
						'key'=>$filter['filter_name'],				
						'value'=>$filter['filter_val'],				
					);
				}				
			}
			
			
			if(!empty($filter_tax)){
				
				// for multiple taxonomy filtering
				if($cnt>1){					
					$filters_tax_wp_argument = array(
						'tax_query'=>$filter_tax
					);
				}else{
					$filters_tax_wp_argument = array(
						'tax_query'=>$filter_tax
					);
				}
				$wp_arguments = array_merge($wp_arguments, $filters_tax_wp_argument);
			}
			if(!empty($filter_meta)){
				$filters_meta_wp_argument = array(
					'meta_query'=>$filter_meta
				);
				$wp_arguments = array_merge($wp_arguments, $filters_meta_wp_argument);
			}		
		}else{
			
			
			// to support event_type and event_type_2 variables from older version
			if(!empty($ev_type) && $ev_type !='all'){
				$ev_type = explode(',', $ev_type);
				$ev_type_ar = array(
						'tax_query'=>array( 
						array('taxonomy'=>'event_type','field'=>'id','terms'=>$ev_type) )	
					);
				
				$wp_arguments = array_merge($wp_arguments, $ev_type_ar);
			}
			
			//event type 2
			if(!empty($ev_type_2) && $ev_type_2 !='all'){
				$ev_type_2 = explode(',', $ev_type_2);
				$ev_type_ar_2 = array(
						'tax_query'=>array( 
						array('taxonomy'=>'event_type_2','field'=>'id','terms'=>$ev_type_2) )	
					);
				$wp_arguments = array_merge($wp_arguments, $ev_type_ar_2);
			}
			
			
		}
		
		//print_r($wp_arguments);
		return $wp_arguments;
	}
	
	/**
	 *	 out put just the sort bar for the calendar
	 */
	public function eventon_get_cal_sortbar($default_event_type='all', $default_event_type_2='all', $sortbar=true){
		
		// define variable values		
		$evt_name = $this->lang_array['et'];
		$evt_name2 = $this->lang_array['et2'];

		$sorting_options = (!empty($this->evopt1['evcal_sort_options']))?$this->evopt1['evcal_sort_options']:null;
		$filtering_options = (!empty($this->evopt1['evcal_filter_options']))?$this->evopt1['evcal_filter_options']:array();
		$content='';
			

		// START the magic	
		ob_start();
		
		// IF sortbar is set to be shown
		if($sortbar){
			echo ( $this->evcal_hide_sort!='yes' )? "<a class='evo_sort_btn'>".eventon_get_custom_language($this->evopt2, 'evcal_lang_sopt','Sort Options')."</a>":null;
		}
		
		echo "<div class='eventon_sorting_section' >";
		if( $this->evcal_hide_sort!='yes' ){ // if sort bar is set to show	
		
		// sorting section
			echo "
			<div class='eventon_sort_line evo_sortOpt' style='display:none'>
				<div class='eventon_sf_field'>
					<p>".eventon_get_custom_language($this->evopt2, 'evcal_lang_sort','Sort By').":</p>
				</div>
				<div class='eventon_sf_cur_val evs'>
					<p class='sorting_set_val'>".eventon_get_custom_language($this->evopt2, 'evcal_lang_sdate','Date')."</p>
				</div>
				<div class='eventon_sortbar_selection evs_3 evs' style='display:none'>
					<p val='sort_date' type='date' class='evs_btn evs_hide'>".eventon_get_custom_language($this->evopt2, 'evcal_lang_sdate','Date')."</p>";
				
				$evsa1 = array(	'title'=>'Title','color'=>'Color');
				$cnt =1;
				if(is_array($sorting_options) ){
					foreach($evsa1 as $so=>$sov){
						if(in_array($so, $sorting_options) ){	
															
							echo "<p val='sort_".$so."' type='".$so."' class='evs_btn' >"
								.eventon_get_custom_language($this->evopt2, 'evcal_lang_s'.$so,$sov)
								."</p>";						
						}
						$cnt++;
					}
				}
			echo "</div><div class='clear'></div></div>";
		}
		
		
		// filtering section
		echo "
			<div class='eventon_filter_line'>";
			
			// event_type line
			if(in_array('event_type', $filtering_options) && $default_event_type=='all'){				
				
				echo "
				<div class='eventon_filter evo_sortOpt' filter_field='event_type' filter_val='all' filter_type='tax' style='display:none'>
					<div class='eventon_sf_field'><p>".$evt_name.":</p></div>				
				
					<div class='eventon_filter_selection'>
						<p class='filtering_set_val' opts='evs4_in'>"
								.eventon_get_custom_language($this->evopt2, 'evcal_lang_all', 'All')."</p>
						<div class='eventon_filter_dropdown' style='display:none'>";
					
						$cats = get_categories(array( 'taxonomy'=>'event_type'));
						echo "<p filter_val='all'>All</p>";
						foreach($cats as $ct){
							echo "<p filter_val='".$ct->term_id."' filter_slug='".$ct->slug."'>".$ct->name."</p>";
						}				
					echo "</div>
					</div><div class='clear'></div>
				</div>";
			}else if($default_event_type!='all'){
				echo "<div class='eventon_filter' filter_field='event_type' filter_val='{$default_event_type}' filter_type='tax'></div>";
			}
			
			// event_type_2 line
			if(in_array('event_type_2', $filtering_options) && $default_event_type_2=='all'){
				echo "
				<div class='eventon_filter evo_sortOpt' filter_field='event_type_2' filter_val='all' filter_type='tax' style='display:none'>
					<div class='eventon_sf_field'><p>".$evt_name2.":</p></div>				
				
					<div class='eventon_filter_selection'>
						<p class='filtering_set_val' opts='evs4_in'>"
								.eventon_get_custom_language($this->evopt2, 'evcal_lang_all', 'All')."</p>
						<div class='eventon_filter_dropdown' style='display:none'>";
					
						$cats = get_categories(array( 'taxonomy'=>'event_type_2'));
						echo "<p filter_val='all'>All</p>";
						foreach($cats as $ct){
							echo "<p filter_val='".$ct->term_id."' filter_slug='".$ct->slug."'>".$ct->name."</p>";
						}				
					echo "</div>
					</div><div class='clear'></div>
				</div>";
			}else if($default_event_type_2!='all'){
				echo "<div class='eventon_filter' filter_field='event_type_2' filter_val='{$default_event_type_2}' filter_type='tax'></div>";
			}
			
			// (---) Hook for addon
			if(has_action('eventon_sorting_filters')){
				echo  do_action('eventon_sorting_filters', $content);
			}
				
			echo "</div>"; // #eventon_filter_line
		
		echo "</div>"; // #eventon_sorting_section
		
		// (---) Hook for addon
		if(has_action('eventon_below_sorts')){
			echo  do_action('eventon_below_sorts', $content);
		}
		
		// load bar for calendar
		echo "<div id='eventon_loadbar_section'><div id='eventon_loadbar'></div></div>";				
		
		
		return ob_get_clean();
	}
	

	
	
} // class EVO_generator


?>