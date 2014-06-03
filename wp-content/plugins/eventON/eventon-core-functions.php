<?php
/**
 * EventON Core Functions
 *
 * Functions available on both the front-end and admin.
 *
 * @author 		AJDE
 * @category 	Core
 * @package 	EventON/Functions
 * @version     1.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// check whether custom fields are activated and have values set ready
function eventon_is_custom_meta_field_good($number, $opt=''){
	$opt = (!empty($opt))? $opt: get_option('evcal_options_evcal_1');
	return ( !empty($opt['evcal_af_'.$number]) 
		&& $opt['evcal_af_'.$number]=='yes'
		&& !empty($opt['evcal_ec_f'.$number.'a1']) 
		&& !empty($opt['evcal__fai_00c'.$number])  )? true: false;
}


/*
	Dynamic styles generation 
*/
function eventon_generate_options_css($newdata='') {
 
	/** Define some vars **/
	$data = $newdata; 
	$uploads = wp_upload_dir();
	
	//$css_dir = get_template_directory() . '/css/'; // Shorten code, save 1 call
	$css_dir = AJDE_EVCAL_DIR . '/'. EVENTON_BASE.  '/assets/css/'; // Shorten code, save 1 call
	
	/** Save on different directory if on multisite **/
	if(is_multisite()) {
		$aq_uploads_dir = trailingslashit($uploads['basedir']);
	} else {
		$aq_uploads_dir = $css_dir;
	}
	
	/** Capture CSS output **/
	ob_start();
	require($css_dir . 'dynamic_styles.php');
	$css = ob_get_clean();

	//print_r($css);
	
	/** Write to options.css file **/
	WP_Filesystem();
	global $wp_filesystem;
	if ( ! $wp_filesystem->put_contents( $aq_uploads_dir . 'eventon_dynamic_styles.css', $css, 0644) ) {
	    return true;
	}
	
}


// check for a shortcode in post content
function has_eventon_shortcode( $shortcode='', $post_content=''){

	global $post;

	$shortcode = (!empty($shortcode))? $shortcode : 'add_eventon';
 
	$post_content = (!empty($post_content))? $post_content: 
		( (!empty($post->post_content))? $post->post_content:'' );

	if(!empty($post_content)){
		if(has_shortcode($post_content, $shortcode) || 
			has_shortcode($post_content, $shortcode)){
	
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}




// CHECEK if the date is future date	
function eventon_is_future_event($current_time, $row_end_unix, $evcal_cal_hide_past){
	$future_event = ($row_end_unix >= $current_time )? true:false;
	
	if( 
		( ($evcal_cal_hide_past=='yes' ) && $future_event )
		|| ( ($evcal_cal_hide_past=='no' ) || ($evcal_cal_hide_past=='' ))
	){
		return true;
	}else{
		return false;
	}
}

function eventon_is_event_in_daterange($Estart_unix, $Eend_unix, $Mstart_unix, $Mend_unix, $shortcode=''){	

	// past event only cal
	if(!empty($shortcode['el_type']) && $shortcode['el_type']=='pe'){
		if(		
			( $Eend_unix <= $Mend_unix) &&
			( $Eend_unix >= $Mstart_unix)
		){
			return true;
		}else{
			return false;
		}
	}else{
		if(
			($Estart_unix<=$Mstart_unix && $Eend_unix>=$Mstart_unix) ||
			($Estart_unix<=$Mend_unix && $Eend_unix>=$Mend_unix) ||
			($Mstart_unix<=$Estart_unix && $Estart_unix<=$Mend_unix && $Eend_unix=='') ||		
			($Mstart_unix<=$Estart_unix && $Estart_unix<=$Mend_unix && $Eend_unix==$Estart_unix) 	||
			($Mstart_unix<=$Estart_unix && $Estart_unix<=$Mend_unix && $Eend_unix!=$Estart_unix)
		){
			return true;
		}else{
			return false;
		}
	}
}


// GET time for ICS adjusted for unix
function evo_get_adjusted_utc($unix){
	$offset = (get_option('gmt_offset', 0) * 3600);

	

	/*
		We are making time (mktime) and getting time (date) using php server timezone
		So we first adjust save UNIX to get unix at UTC/GMT 0 and then we adjust that time to offset for timezone saved on wordpress settings.
	*/
	$__unix = $unix - (date('Z')) - $offset;

	//$the_date = $unix;
	//date_default_timezone_get();
	//date_default_timezone_set("UTC");
	//$new_timeT = gmdate("Ymd", $unix);
	//$new_timeT = date_i18n("Ymd", $__unix);
	$new_timeT = date("Ymd", $__unix);
	$new_timeZ = date("Hi", $__unix);

	return $new_timeT.'T'.$new_timeZ.'00Z';

}

function evo_unix_offset($unix){
	$offset = (get_option('gmt_offset', 0) * 3600);

}

// RETURN: formatted event time in multiple formats
function eventon_get_formatted_time($row_unix){
	/*
			D = Mon - Sun
		1	j = 1-31
			l = Sunday - Saturday
		3	N - day of week 1 (monday) -7(sunday)
			S - st, nd rd
		5	n - month 1-12
			F - January - Decemer
		7	t - number of days in month
			z - day of the year
			Y - 2000
			g = hours
			i = minute
			a = am/pm
			M = Jan - Dec
			m = 01-12
			d = 01-31
			H = hour 00 - 23
	*/

	date_default_timezone_set('UTC');
			
	$key = array('D','j','l','N','S','n','F','t','z','Y','g','i','a','M','m','d','H');
	
	$date = date('D-j-l-N-S-n-F-t-z-Y-g-i-a-M-m-d-H',$row_unix);
	$date = explode('-',$date);
	
	
	foreach($date as $da=>$dv){
		// month name
		if($da==6){
			$output[$key[$da]]= eventon_return_timely_names_('month_num_to_name',$date[5]); 
		}else if($da==2){
			
			// day name - full day name
			$output[$key[$da]]= eventon_return_timely_names_('day',$date[2]); 
		
		// 3 letter month name
		}else if($da==13){
			$output[$key[$da]]= eventon_return_timely_names_('month_num_to_name',$date[5],'three'); 

		// 3 letter day name
		}else if($da==0){
			$output[$key[$da]]= eventon_return_timely_names_('day_num_to_name',$date[3],'three'); 
		}else{
			$output[$key[$da]]= $dv;
		}
	}
	
	return $output;
}

/*
	return date value and time values from unix timestamp
*/
function eventon_get_editevent_kaalaya($unix, $dateformat='', $timeformat24=''){
	
	
	// in case of empty date format provided
	// find it within system
	$DT_format = eventon_get_timeNdate_format();
	
	$offset = (get_option('gmt_offset', 0) * 3600);

	date_default_timezone_set('UTC');
	$unix = $unix ;

	$dateformat = (!empty($dateformat))? $dateformat: $DT_format[1];
	$timeformat24 = (!empty($timeformat24))? $timeformat24: $DT_format[2];
	
	$date = date($dateformat, $unix);
	
	
	$timestring = ($timeformat24)? 'H-i': 'g-i-A';
	$times_val = date($timestring, $unix);
	$time_data = explode('-',$times_val);
	
	
	$output = array_merge( array($date), $time_data);
	
	return $output;
}

/*
	GET event UNIX time from date and time format $_POST values
*/
function eventon_get_unix_time($data='', $date_format='', $time_format=''){
	
	$data = (!empty($data))? $data : $_POST;
	
	// END DATE
	$__evo_end_date =(empty($data['evcal_end_date']))?
		$data['evcal_start_date']: $data['evcal_end_date'];
	
	// date format
	$_wp_date_format = (!empty($date_format))? $date_format: 
		( (isset($_POST['_evo_date_format']))? $_POST['_evo_date_format']
			: get_option('date_format')
		);
	
	$_is_24h = (!empty($time_format) && $time_format=='24h')? true:
		( (isset($_POST['_evo_time_format']) && $_POST['_evo_time_format']=='24h')? 
			true: false
		); // get default site-wide date format
		
	
	//$_wp_date_str = split("[\s|.|,|/|-]",$_wp_date_format);
	
	// ---
	// START UNIX
	if( !empty($data['evcal_start_time_hour'])  && !empty($data['evcal_start_date']) ){
		
		$__Sampm = (!empty($data['evcal_st_ampm']))? $data['evcal_st_ampm']:null;

		//get hours minutes am/pm 
		$time_string = $data['evcal_start_time_hour']
			.':'.$data['evcal_start_time_min'].$__Sampm;
		
		// event start time string
		$date = $data['evcal_start_date'].' '.$time_string;
		
		// parse string to array by time format
		$__ti = ($_is_24h)?
			date_parse_from_format($_wp_date_format.' H:i', $date):
			date_parse_from_format($_wp_date_format.' g:ia', $date);
			
		date_default_timezone_set('UTC');	
		// GENERATE unix time
		$unix_start = mktime($__ti['hour'], $__ti['minute'],0, $__ti['month'], $__ti['day'], $__ti['year'] );
		
				
	}else{ $unix_start =0; }
	
	// ---
	// END TIME UNIX
	if( !empty($data['evcal_end_time_hour'])  && !empty($data['evcal_end_date']) ){
		
		$__Eampm = (!empty($data['evcal_et_ampm']))? $data['evcal_et_ampm']:null;

		//get hours minutes am/pm 
		$time_string = $data['evcal_end_time_hour']
			.':'.$data['evcal_end_time_min'].$__Eampm;
		
		// event start time string
		$date = $__evo_end_date.' '.$time_string;
				
		
		// parse string to array by time format
		$__ti = ($_is_24h)?
			date_parse_from_format($_wp_date_format.' H:i', $date):
			date_parse_from_format($_wp_date_format.' g:ia', $date);
		
		date_default_timezone_set('UTC');		
		// GENERATE unix time
		$unix_end = mktime($__ti['hour'], $__ti['minute'],0, $__ti['month'], $__ti['day'], $__ti['year'] );		
				
		
	}else{ $unix_end =0; }
		
		
	$unix_end =(!empty($unix_end) )?$unix_end:$unix_start;
	
	// output the unix timestamp
	$output = array(
		'unix_start'=>$unix_start,
		'unix_end'=>$unix_end
	);
	
	return $output;
}

/*
	return jquery and HTML UNIVERSAL date format for the site
	added: version 2.1.19
*/
function eventon_get_timeNdate_format($evcal_opt=''){
	
	if(empty($evcal_opt))
		$evcal_opt = get_option('evcal_options_evcal_1');
	
	if(!empty($evcal_opt) && $evcal_opt['evo_usewpdateformat']=='yes'){
				
		/** get date formate and convert to JQ datepicker format**/				
		$wp_date_format = get_option('date_format');
		$format_str = str_split($wp_date_format);
		
		foreach($format_str as $str){
			switch($str){							
				case 'j': $nstr = 'd'; break;
				case 'd': $nstr = 'dd'; break;	
				case 'D': $nstr = 'D'; break;	
				case 'l': $nstr = 'DD'; break;	
				case 'm': $nstr = 'mm'; break;
				case 'M': $nstr = 'M'; break;
				case 'n': $nstr = 'm'; break;
				case 'F': $nstr = 'MM'; break;							
				case 'Y': $nstr = 'yy'; break;
				case 'y': $nstr = 'y'; break;
										
				default :  $nstr = ''; break;							
			}
			$jq_date_format[] = (!empty($nstr))?$nstr:$str;
			
		}
		$jq_date_format = implode('',$jq_date_format);
		$evo_date_format = $wp_date_format;
	}else{
		$jq_date_format ='yy/mm/dd';
		$evo_date_format = 'Y/m/d';
	}
	
	
	// time format
	$wp_time_format = get_option('time_format');
	
	$hr24 = (strpos($wp_time_format, 'H')!==false)?true:false;
	
	return array(
		$jq_date_format, 
		$evo_date_format,
		$hr24
	);
}

// return 24h or 12h or true false
function eventon_get_time_format($return='tf'){
	// time format
	$wp_time_format = get_option('time_format');

	if($return=='tf'){
		return  (strpos($wp_time_format, 'H')!==false)?true:false;
	}else{
		return  (strpos($wp_time_format, 'H')!==false)?'24h':'12h';
	}
}	

/*
	RETURN calendar header with month and year data
	string - should be m, Y if empty
*/
function get_eventon_cal_title_month($month_number, $year_number, $lang=''){
	
	$evopt = get_option('evcal_options_evcal_1');
	
	$string = ($evopt['evcal_header_format']!='')?$evopt['evcal_header_format']:'m, Y';

	$str = str_split($string, 1);
	$new_str = '';
	
	foreach($str as $st){
		switch($st){
			case 'm':
				$new_str.= eventon_return_timely_names_('month_num_to_name',$month_number, 'full', $lang);
				
			break;
			case 'Y':
				$new_str.= $year_number;
			break;
			case 'y':
				$new_str.= substr($year_number, -2);
			break;
			default:
				$new_str.= $st;
			break;
		}
	}
	
	return $new_str;
}




/*
	function to return day names and month names in correct language
	type: day, month, month_num_to_name, day_num_to_name
*/
function eventon_return_timely_names_($type, $data, $len='full', $lang=''){


	$eventon_day_names = array(
	1=>'monday','tuesday','wednesday','thursday','friday','saturday','sunday');
	$eventon_month_names = array(1=>'january','february','march','april','may','june','july','august','september','october','november','december');
	
	global $eventon;
	$output ='';
	
	// lower case the data values
	$data = strtolower($data);
	
	$evo_options = $eventon->evo_generator->evopt2;
	$shortcode_arg = $eventon->evo_generator->shortcode_args;
	
	// check which language is called for
	$evo_options = (!empty($evo_options))? $evo_options: get_option('evcal_options_evcal_2');
	
	// check for language preference
	$_lang_variation = ( (!empty($lang))? $lang: 
		( (!empty($shortcode_arg['lang']))? $shortcode_arg['lang']:'L1' ) );
	
	// day name
	if($type=='day'){
		
		//global $eventon_day_names;
		$text_num = array_search($data, $eventon_day_names); // 1-7
				
		if($len=='full'){			
			
			$option_name_prefix = 'evcal_lang_day';
			$_not_value = $eventon_day_names[ $text_num];
			
		
		// 3 letter day names
		}else if($len=='three'){
			
			$option_name_prefix = 'evo_lang_3Ld_';
			$_not_value = substr($eventon_day_names[ $text_num], 0 , 3);			
			
		}
	
	// day number to name
	}else if($type=='day_num_to_name'){
	
		$text_num = $data; // 1-7
		
		
		
		if($len=='full'){			
			
			$option_name_prefix = 'evcal_lang_day';
			$_not_value = $eventon_day_names[ $text_num];
			
		
		// 3 letter day names
		}else if($len=='three'){
			
			$option_name_prefix = 'evo_lang_3Ld_';
			$_not_value = substr($eventon_day_names[ $text_num], 0 , 3);			
			
		}
	
		
	// month names
	}else if($type=='month'){
		//global $eventon_month_names;
		$text_num = array_search($data, $eventon_month_names); // 1-12
		
		if($len == 'full'){
		
			$option_name_prefix = 'evcal_lang_';
			$_not_value = $eventon_month_names[ $text_num];
			
		}else if($len=='three'){
		
			$option_name_prefix = 'evo_lang_3Lm_';
			$_not_value = substr($eventon_month_names[ $text_num], 0 , 3);
			
		}
	
	// month number to name
	}else if($type=='month_num_to_name'){
		
		//global $eventon_month_names;
		$text_num = $data; // 1-12
		
		
		if($len == 'full'){
		
			$option_name_prefix = 'evcal_lang_';
			$_not_value = $eventon_month_names[ $text_num];
			
		}else if($len=='three'){
		
			$option_name_prefix = 'evo_lang_3Lm_';
			$_not_value = substr($eventon_month_names[ $text_num], 0 , 3);
			
		}
	}
	
	$output = (!empty($evo_options[$_lang_variation][$option_name_prefix.$text_num]))? 
				$evo_options[$_lang_variation][$option_name_prefix.$text_num]
				: $_not_value;
	
	return $output;
}


function eventon_get_event_day_name($day_number){

	return eventon_return_timely_names_('day_num_to_name',$day_number);
}

function eventon_get_new_monthyear($current_month_number, $current_year, $difference){
	$month_num = $current_month_number + $difference;
	if($month_num>12){
		$next_m_n = $month_num-12;
		$next_y = $current_year+1;
	}else{
		$next_m_n =$month_num;
		$next_y = $current_year;
	}
	
	$ra = array(
		'month'=>$next_m_n, 'year'=>$next_y
	);
	return $ra;
}




// =========
// LANGUAGE 

/** return custom language text saved in settings **/
function eventon_get_custom_language($evo_options='', $field, $default_val, $lang=''){
	global $eventon;
		
	// check which language is called for
	$evo_options = (!empty($evo_options))? $evo_options: get_option('evcal_options_evcal_2');
	
	// check for language preference
	$shortcode_arg = $eventon->evo_generator->shortcode_args;
	$_lang_variation = (!empty($shortcode_arg['lang']))? $shortcode_arg['lang']:'L1';
	
	
	$new_lang_val = (!empty($evo_options[$_lang_variation][$field]) )?
		stripslashes($evo_options[$_lang_variation][$field]): $default_val;
		
	return $new_lang_val;
}

function eventon_process_lang_options($options){
	$new_options = array();
	
	foreach($options as $f=>$v){
		$new_options[$f]= stripslashes($v);
	}
	return $new_options;
}


/** SORTING arrangement functions **/
function cmp_esort_startdate($a, $b){
	return $a["event_start_unix"] - $b["event_start_unix"];
}
function cmp_esort_title($a, $b){
	return strcmp($a["event_title"], $b["event_title"]);
}
function cmp_esort_color($a, $b){
	return strcmp($a["event_color"], $b["event_color"]);
}


// GET EVENT
function get_event($the_event){
	global $eventon;
}


// Returns a proper form of labeling for custom post type
/**
 * Function that returns an array containing the IDs of the products that are on sale.
 */
if( !function_exists ('eventon_get_proper_labels')){
	function eventon_get_proper_labels($sin, $plu){
		return array(
		'name' => _x($plu, 'post type general name'),
		'singular_name' => _x($sin, 'post type singular name'),
		'add_new' => _x('Add New', $sin),
		'add_new_item' => __('Add New '.$sin),
		'edit_item' => __('Edit '.$sin),
		'new_item' => __('New '.$sin),
		'all_items' => __('All '.$plu),
		'view_item' => __('View '.$sin),
		'search_items' => __('Search '.$plu),
		'not_found' =>  __('No '.$plu.' found'),
		'not_found_in_trash' => __('No '.$plu.' found in Trash'), 
		'parent_item_colon' => '',
		'menu_name' => $plu
	  );
	}
}
// Return formatted time 
if( !function_exists ('ajde_evcal_formate_date')){
	function ajde_evcal_formate_date($date,$return_var){	
		$srt = strtotime($date);
		$f_date = date($return_var,$srt);
		return $f_date;
	}
}

if( !function_exists ('returnmonth')){
	function returnmonth($n){
		$timestamp = mktime(0,0,0,$n,1,2013);
		return date('F',$timestamp);
	}
}
if( !function_exists ('eventon_returnmonth_name_by_num')){
	function eventon_returnmonth_name_by_num($n){
				
		return eventon_return_timely_names_('month_num_to_name', $n);
	}
}

/*
	eventON return font awesome icons names
*/
function get_eventON_icon($var, $default, $options_value){

	$options_value = (!empty($options_value))? $options_value: get_option('evcal_options_evcal_1');

	return (!empty( $options_value[$var]))? $options_value[$var] : $default;
}


// Return a excerpt of the event details
function eventon_get_event_excerpt($text, $excerpt_length, $default_excerpt='', $title=true){
	global $eventon;
	
	$content='';
	
	if(empty($default_excerpt) ){
	
		$words = explode(' ', $text, $excerpt_length + 1);
		if(count($words) > $excerpt_length) :
			array_pop($words);
			array_push($words, '[...]');
			$content = implode(' ', $words);
		endif;
		$content = strip_shortcodes($content);
		$content = str_replace(']]>', ']]&gt;', $content);
		$content = strip_tags($content);
	}else{
		$content = $default_excerpt;
	}
	
	
	$titletx = ($title)? '<h3 class="padb5 evo_h3">' . eventon_get_custom_language($eventon->evo_generator->evopt2, 'evcal_evcard_details','Event Details').'</h3>':null;
	
	$content = '<div class="event_excerpt" style="display:none">'.$titletx.'<p>'. $content . '</p></div>';
	
	return $content;
}
	function eventon_get_normal_excerpt($text, $excerpt_length){
		$content='';

		$words = explode(' ', $text, $excerpt_length + 1);
		if(count($words) > $excerpt_length) :
			array_pop($words);
			array_push($words, '...');
			$content = implode(' ', $words);
		endif;
		$content = strip_shortcodes($content);
		$content = str_replace(']]>', ']]&gt;', $content);
		$content = strip_tags($content);

		return $content;
	}


/**
 * eventon Term Meta API - Get term meta
 */
function get_eventon_term_meta( $term_id, $key, $single = true ) {
	return get_metadata( 'eventon_term', $term_id, $key, $single );
}

/**
 * Get template part (for templates like the event-loop).
 */
function eventon_get_template_part( $slug, $name = '' , $preurl='') {
	global $eventon;
	$template = '';
	
	
	if($preurl){
		$template =$preurl."/{$slug}-{$name}.php";
	}else{
		// Look in yourtheme/slug-name.php and yourtheme/eventon/slug-name.php
		if ( $name )
			$template = locate_template( array ( "{$slug}-{$name}.php", "{$eventon->template_url}{$slug}-{$name}.php" ) );

		// Get default slug-name.php
		if ( !$template && $name && file_exists( AJDE_EVCAL_PATH . "/templates/{$slug}-{$name}.php" ) )
			$template = AJDE_EVCAL_PATH . "/templates/{$slug}-{$name}.php";

		// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/eventon/slug.php
		if ( !$template )
			$template = locate_template( array ( "{$slug}.php", "{$eventon->template_url}{$slug}.php" ) );

		
	}
	
	if ( $template )
		load_template( $template, false );
}




if(!function_exists('date_parse_from_format')){
	function date_parse_from_format($_wp_format, $date){
		
		$date_pcs = preg_split('/ (?!.* )/',$_wp_format);
		$time_pcs = preg_split('/ (?!.* )/',$date);
		
		$_wp_date_str = preg_split("/[\s . , \: \- \/ ]/",$date_pcs[0]);
		$_ev_date_str = preg_split("/[\s . , \: \- \/ ]/",$time_pcs[0]);
		
		$check_array = array(
			'Y'=>'year',
			'y'=>'year',
			'm'=>'month',
			'n'=>'month',
			'M'=>'month',
			'F'=>'month',
			'd'=>'day',
			'j'=>'day',
			'D'=>'day',
			'l'=>'day',
		);
		
		foreach($_wp_date_str as $strk=>$str){
			
			if($str=='M' || $str=='F' ){
				$str_value = date('n', strtotime($_ev_date_str[$strk]));
			}else{
				$str_value=$_ev_date_str[$strk];
			}
			
			if(!empty($str) )
				$ar[ $check_array[$str] ]=$str_value;		
			
		}
		
		$ar['hour']= date('H', strtotime($time_pcs[1]));
		$ar['minute']= date('i', strtotime($time_pcs[1]));
		
		
		return $ar;
	}
}



if( !function_exists('date_parse_from_format') ){
	function date_parse_from_format($format, $date) {
	  $dMask = array(
		'H'=>'hour',
		'i'=>'minute',
		's'=>'second',
		'y'=>'year',
		'm'=>'month',
		'd'=>'day'
	  );
	  $format = preg_split('//', $format, -1, PREG_SPLIT_NO_EMPTY); 
	  $date = preg_split('//', $date, -1, PREG_SPLIT_NO_EMPTY); 
	  foreach ($date as $k => $v) {
		if ($dMask[$format[$k]]) $dt[$dMask[$format[$k]]] .= $v;
	  }
	  return $dt;
	}
}

/** Return integer value for a hex color code **/
function eventon_get_hex_val($color){
    if ($color[0] == '#')
        $color = substr($color, 1);

    if (strlen($color) == 6)
        list($r, $g, $b) = array($color[0].$color[1],
                                 $color[2].$color[3],
                                 $color[4].$color[5]);
    elseif (strlen($color) == 3)
        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
    else
        return false;

    $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

    $val = (int)(($r+$g+$b)/3);
	
    return $val;
}



/**
 * Get capabilities for Eventon - these are assigned to admin during installation or reset
 */
function eventon_get_core_capabilities(){
	$capabilities = array();

	$capabilities['core'] = apply_filters('eventon_core_capabilities',array(
		"manage_eventon"
	));
	
	
	
	$capability_types = array( 'eventon' );

	foreach( $capability_types as $capability_type ) {

		$capabilities[ $capability_type ] = array(

			// Post type
			"publish_{$capability_type}",
			"edit_{$capability_type}",
			"read_{$capability_type}",
			"delete_{$capability_type}",
			"edit_{$capability_type}s",
			"edit_others_{$capability_type}s",
			"publish_{$capability_type}s",
			"read_private_{$capability_type}s",
			"delete_{$capability_type}s",
			"delete_private_{$capability_type}s",
			"delete_published_{$capability_type}s",
			"delete_others_{$capability_type}s",
			"edit_private_{$capability_type}s",
			"edit_published_{$capability_type}s",

			// Terms
			"manage_{$capability_type}_terms",
			"edit_{$capability_type}_terms",
			"delete_{$capability_type}_terms",
			"assign_{$capability_type}_terms"
		);
	}

	return $capabilities;
}


// sort eventcard fields 
function eventon_EVC_sort($array, $order_option_val){
	
	$new_array = array();
	
	// create an array
	$correct_order = (!empty($order_option_val))? 
		explode(',',$order_option_val): null;
	
	if(!empty($correct_order)){
		foreach($correct_order as $box){
			if(array_key_exists($box, $array))
				$new_array[$box]=$array[$box];
		}
	}else{
		$new_array = $array;
	}	
	return $new_array;
}


/* Initiate capabilities for eventON */
function eventon_init_caps(){
	global $wp_roles;
	
	if ( class_exists('WP_Roles') )
		if ( ! isset( $wp_roles ) )
			$wp_roles = new WP_Roles();
	
	$capabilities = eventon_get_core_capabilities();
	
	foreach( $capabilities as $cap_group ) {
		foreach( $cap_group as $cap ) {
			$wp_roles->add_cap( 'administrator', $cap );
		}
	}
}


// for style values
/*
	Default value
	field variable name
	wp options array
*/
function eventon_styles($default, $field, $options){	
	return (!empty($options[$field]))? $options[$field]:$default;
}


/**
 * Clean variables
 *
 * @access public
 * @param string $var
 * @return string
 */
function eventon_clean( $var ) {
	return sanitize_text_field( $var );
}




// GET  event custom taxonomy field names
function eventon_get_event_tax_name($tax, $options=''){
	$output ='';

	$options = (!empty($options))? $options: get_option('evcal_options_evcal_1');

	if($tax =='et'){
		$output = (!empty($options['evcal_eventt']))? $options['evcal_eventt']:'Event Type';
	}elseif($tax=='et2'){
		$output = (!empty($options['evcal_eventt2']))? $options['evcal_eventt2']:'Event Type 2';
	}

	return $output;
}

// GET  event custom taxonomy field names -- FOR FRONT END w/ Lang
function eventon_get_event_tax_name_($tax, $lang='', $options='', $options2=''){
	$output ='';

	$options = (!empty($options))? $options: get_option('evcal_options_evcal_1');
	$options2 = (!empty($options2))? $options2: get_option('evcal_options_evcal_2');
	$_lang_variation = (!empty($lang))? $lang:'L1';

	$_tax = ($tax =='et')? 'evcal_eventt': 'evcal_eventt2';
	$_tax_lang_field = ($tax =='et')? 'evcal_lang_et1': 'evcal_lang_et2';


	// check for language first
	if(!empty($options2[$_lang_variation][$_tax_lang_field]) ){
		$output = stripslashes($options2[$_lang_variation][$_tax_lang_field]);
	
	// no lang value -> check set custom names
	}elseif(!empty($options[$_tax])) {		
		$output = $options[$_tax];
	}else{
		$output = ($tax =='et')? 'Event Type': 'Event Type 2';
	}

	return $output;
}



?>