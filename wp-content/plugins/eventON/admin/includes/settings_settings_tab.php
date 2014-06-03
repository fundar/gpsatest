<?php
/**
 *	Build settings to work with AJDE backendender setup
 *	version: 2.1
 **/
	
	
	/**
		EventCard Rearranging order STUFF
	**/
		$rearrange_items = apply_filters('eventon_eventcard_boxes', array(
			'ftimage'=>'<p val="ftimage">Featured Image</p>',
			'eventdetails'=>'<p val="eventdetails">Event Details</p>',
			'timelocation'=>'<p val="timelocation">Time and Location</p>',
			'organizer'=>'<p val="organizer">Event Organizer</p>',
			'gmap'=>'<p val="gmap">Google Maps</p>',
			'learnmoreICS'=>'<p val="learnmoreICS">Learn More & Add to your calendar</p>',		
		));
		
		//get directions
		if($evcal_opt[1]['evo_getdir']=='yes')
			$rearrange_items['getdirection']='<p val="getdirection">Get Directions</p>';
		
		//eventbrite
		if($evcal_opt[1]['evcal_evb_events']=='yes')
			$rearrange_items['eventbrite']='<p val="eventbrite">eventbrite</p>';
			
		//paypal
		if($evcal_opt[1]['evcal_paypal_pay']=='yes')
			$rearrange_items['paypal']='<p val="paypal">Paypal</p>';
		
		// custom fields
		for($x=1; $x<4; $x++){
			if( !empty($evcal_opt[1]['evcal_ec_f'.$x.'a1']))
				$rearrange_items['customfield'.$x] = '<p val="customfield'.$x.'">'.$evcal_opt[1]['evcal_ec_f'.$x.'a1'].'</p>';
		}
		$_saved_order = (!empty($evcal_opt[1]['evoCard_order']))? 
			array_filter(explode(',',$evcal_opt[1]['evoCard_order']))
			:null;
		
		
		//print_r($rearrange_items);
		//echo count($rearrange_items).' '.count($_saved_order);
		//print_r($_saved_order);
		// HTML
		$_rearrange_code = '<h4 class="acus_header">Re-arrange the order of eventCard event data boxes</h4>
			<input id="evoCard_order" name="evoCard_order" value="'.( (!empty($evcal_opt[1]['evoCard_order']))? $evcal_opt[1]['evoCard_order']:null).'" type="hidden"/>
			<div id="evoEVC_arrange_box">';
				
				// if an order array exists already
				if($_saved_order){
					
					foreach($_saved_order as $box){
						$_rearrange_code .= (array_key_exists($box, $rearrange_items))? $rearrange_items[$box]:null;
					}	
					
					// if there are new values in possible items add them to the bottom
					if(count($_saved_order) < count($rearrange_items)){
						
						foreach($rearrange_items as $f=>$v){
							$_rearrange_code .= (!in_array($f, $_saved_order))? $rearrange_items[$f]:null;
						}
					}
					
				}
				
				if(empty($rearrange_items) || empty($_saved_order)){	
					$implode = implode('',$rearrange_items);
					$_rearrange_code .=$implode;				
				}
				
			$_rearrange_code .='</div>';
			
		
	//print_r($implode);
	/**
		SETTINGS ARRAY
	*/

	$__appearance_additions = apply_filters('eventon_appearance_add', 

		array(

			array('id'=>'fc_mcolor','type'=>'multicolor','name'=>'Multiple colors',
				'variations'=>array(
					array('id'=>'evcal_hexcode', 'default'=>'206177', 'name'=>'Primary Calendar Color'),
					array('id'=>'evcal_header1_fc', 'default'=>'C6C6C6', 'name'=>'Header Month/Year text color'),
					array('id'=>'evcal__fc2', 'default'=>'ABABAB', 'name'=>'Calendar Date color'),
				)
			),

			array('id'=>'evcal_font_fam','type'=>'text','name'=>'Primary Calendar Font family <i>(Note: type the name of the font that is supported in your website. eg. Arial)</i>'),	
			array('id'=>'fs_sort_options','type'=>'fontation','name'=>'Sort Options Text',
				'variations'=>array(
					array('id'=>'evcal__sot', 'name'=>'Default State', 'type'=>'color', 'default'=>'ededed'),
					array('id'=>'evcal__sotH', 'name'=>'Hover State', 'type'=>'color', 'default'=>'d8d8d8'),
				)
			),

			array('id'=>'evcal_fcx','type'=>'hiddensection_open','name'=>'EventTop Styles', 'display'=>'none'),
			array('id'=>'evcal__fc3','type'=>'color','name'=>'Event Title font color', 'default'=>'6B6B6B'),
			array('id'=>'evcal__fc6','type'=>'color','name'=>'Text under event title (on EventTop. Eg. Time, location etc.)','default'=>'8c8c8c'),
			array('id'=>'fs_fonti','type'=>'fontation','name'=>'Background Color',
				'variations'=>array(
					array('id'=>'evcal__bgc4', 'name'=>'Default State', 'type'=>'color', 'default'=>'fafafa'),
					array('id'=>'evcal__bgc4h', 'name'=>'Hover State', 'type'=>'color', 'default'=>'f4f4f4'),
				)
			),
			array('id'=>'evcal_fcx','type'=>'hiddensection_close',),

			// featured events
			array('id'=>'evcal_fcx','type'=>'subheader','name'=>'Featured Events'),
			array('id'=>'evo_fte_override','type'=>'yesno','name'=>'Override featured event color','legend'=>'This will override the event color you chose for featured event with a different color.','afterstatement'=>'evo_fte_override'),
			array('id'=>'evo_fte_override','type'=>'begin_afterstatement'),
				array('id'=>'evcal__ftec','type'=>'color','name'=>'Featured event left bar color', 'default'=>'ca594a'),
			array('id'=>'evcal_ftovrr','type'=>'end_afterstatement'),
			
			// eventCard Styles
			array('id'=>'evcal_fcxx','type'=>'hiddensection_open','name'=>'EventCard Styles'),
			array('id'=>'fs_fonti1','type'=>'fontation','name'=>'Section Title Text',
				'variations'=>array(
					array('id'=>'evcal__fc4', 'type'=>'color', 'default'=>'6B6B6B'),
					array('id'=>'evcal_fs_001', 'type'=>'font_size', 'default'=>'18px'),
				)
			),
			array('id'=>'evcal__fc5','type'=>'color','name'=>'General font color', 'default'=>'656565'),
			array('id'=>'evcal__bc1','type'=>'color','name'=>'Event Card background color', 'default'=>'eaeaea'),			

			array('id'=>'evcal_fcx','type'=>'subheader','name'=>'Buttons'),
			array('id'=>'fs_fonti3','type'=>'fontation','name'=>'Button Color',
				'variations'=>array(
					array('id'=>'evcal_gen_btn_bgc', 'name'=>'Default State', 'type'=>'color', 'default'=>'237ebd'),
					array('id'=>'evcal_gen_btn_bgcx', 'name'=>'Hover State', 'type'=>'color', 'default'=>'237ebd'),
				)
			),array('id'=>'fs_fonti4','type'=>'fontation','name'=>'Button Text Color',
				'variations'=>array(
					array('id'=>'evcal_gen_btn_fc', 'name'=>'Default State', 'type'=>'color', 'default'=>'ffffff'),
					array('id'=>'evcal_gen_btn_fcx', 'name'=>'Hover State', 'type'=>'color', 'default'=>'ffffff'),
				)
			),array('id'=>'fs_fonti5','type'=>'fontation','name'=>'Close Button Color',
				'variations'=>array(
					array('id'=>'evcal_closebtn', 'name'=>'Default State', 'type'=>'color', 'default'=>'eaeaea'),
					array('id'=>'evcal_closebtnx', 'name'=>'Hover State', 'type'=>'color', 'default'=>'c7c7c7'),
				)
			),
			array('id'=>'evcal_fcx','type'=>'hiddensection_close',),
		)
	);


	$cutomization_pg_array = array(
		array(
			'id'=>'evcal_001',
			'name'=>'General Calendar Settings',
			'display'=>'show',
			'tab_name'=>'General Settings',
			'top'=>'4',
			'fields'=> apply_filters('eventon_settings_general', array(
				array('id'=>'evcal_cal_hide','type'=>'yesno','name'=>'Hide Calendar from front-end',),
				array('id'=>'evcal_arrow_hide','type'=>'yesno','name'=>'Hide Front-end arrow navigation',),
				array('id'=>'evcal_cal_hide_past','type'=>'yesno','name'=>'Hide past events for default calendar(s)','afterstatement'=>'evcal_cal_hide_past'),	
										
				array('id'=>'evcal_cal_hide_past','type'=>'begin_afterstatement'),
				array('id'=>'evcal_past_ev','type'=>'radio','name'=>'Select a precise timing for the cut off time for past events','width'=>'full',
					'options'=>array(
						'local_time'=>'Hide events past current local time',
						'today_date'=>'Hide events past today\'s date')
				),
				array('id'=>'evcal_cal_hide_past','type'=>'end_afterstatement'),
				array('id'=>'evcal_hide_sort','type'=>'yesno','name'=>'Hide sort bar on calendar'),
				
				array('id'=>'evo_usewpdateformat','type'=>'yesno','name'=>'Use WP default Date format in eventON calendar', 'legend'=>'Select this option to use the default WP Date format through out eventON calendar. Default format: yyyy/mm/dd'),
				
				array('id'=>'evcal_header_format','type'=>'text','name'=>'Calendar Header month/year format. <i>(<b>Allowed values:</b> m = month name, Y = 4 digit year, y = 2 digit year)</i>' , 'default'=>'m, Y'),
				
								
				array('id'=>'evcal_fcx','type'=>'subheader','name'=>'Event Type Taxonomies'),
				array('id'=>'evcal_fcx','type'=>'note','name'=>'Use this to assign custom names for the event type taxonomies which you can use to categorize events. Note: Once you update these custom taxonomies refresh the page for the values to show up.'),
				array('id'=>'evcal_eventt','type'=>'text','name'=>'Custom name for Event Type #1',),
				array('id'=>'evcal_eventt2','type'=>'text','name'=>'Custom name for Event Type #2',),
			
		))),		
		
		array(
			'id'=>'evcal_005',
			'name'=>'Google Maps API Settings',
			'tab_name'=>'Google Maps API',
			'top'=>'4',
			'fields'=>array(
				array('id'=>'evcal_cal_gmap_api','type'=>'yesno','name'=>'Disable Google Maps API','legend'=>'This will stop gmaps API from loading on frontend and will stop google maps from generating on event locations.','afterstatement'=>'evcal_cal_gmap_api'),
				array('id'=>'evcal_cal_gmap_api','type'=>'begin_afterstatement'),
				array('id'=>'evcal_gmap_disable_section','type'=>'radio','name'=>'Select which part of Google gmaps API to disable','width'=>'full',
					'options'=>array(
						'complete'=>'Completely disable google maps',
						'gmaps_js'=>'Google maps javascript file only (If the js file is already loaded with another gmaps program)')
				),
				array('id'=>'evcal_cal_gmap_api','type'=>'end_afterstatement'),
				
				array('id'=>'evcal_gmap_scroll','type'=>'yesno','name'=>'Disable scrollwheel zooming on Google Maps','legend'=>'This will stop google maps zooming when mousewheel scrolled.'),
				
				array('id'=>'evcal_gmap_format', 'type'=>'dropdown','name'=>'Google maps display type:',
					'options'=>array(
						'roadmap'=>'ROADMAP Displays the normal default 2D',
						'satellite'=>'SATELLITE Displays photographic tiles',
						'hybrid'=>'HYBRID Displays a mix of photographic tiles and a tile layer',
					)),
				array('id'=>'evcal_gmap_zoomlevel', 'type'=>'dropdown','name'=>'Google starting zoom level:',
					'options'=>array(
						'18'=>'18',
						'16'=>'16',
						'14'=>'14',
						'12'=>'12',
						'10'=>'10',
						'8'=>'8',
					)),
		)),
		array(
			'id'=>'evcal_001a',
			'name'=>'Calendar front-end Sorting and filtering options',
			'tab_name'=>'Sorting and Filtering',
			'top'=>'4',
			'fields'=>array(
				array('id'=>'evcal_sort_options', 'type'=>'checkboxes','name'=>'Event sorting options to show on Calendar <i>(Note: Event Date will be default sorting option that will be always on)</i>',
					'options'=>array(
						'title'=>'Event Main Title',
						'color'=>'Event Color',									
					)),
				array('id'=>'evcal_filter_options', 'type'=>'checkboxes','name'=>'Event filtering options to show on the calendar</i>',
					'options'=>array(
						'event_type'=>$evt_name,
						'event_type_2'=>$evt_name2.' (This is only for filtering purposes will not show in individual events)',
					)),
		)),
		array(
			'id'=>'evcal_002',
			'name'=>'General Frontend Calendar Appearance',
			'tab_name'=>'Appearance',
			'top'=>'40',
			'fields'=>$__appearance_additions
		),
		array(
			'id'=>'evcal_004',
			'name'=>'Custom Icons for Calendar',
			'tab_name'=>'Icons',
			'top'=>'76',
			'fields'=>array(
				array('id'=>'fs_fonti2','type'=>'fontation','name'=>'EventCard Icons',
					'variations'=>array(
						array('id'=>'evcal__ecI', 'type'=>'color', 'default'=>'6B6B6B'),
						array('id'=>'evcal__ecIz', 'type'=>'font_size', 'default'=>'18px'),
					)
				),
				
				array('id'=>'evcal__fai_001','type'=>'icon','name'=>'Event Details Icon','default'=>'fa-align-justify'),
				array('id'=>'evcal__fai_002','type'=>'icon','name'=>'Event Time Icon','default'=>'fa-clock-o'),
				array('id'=>'evcal__fai_003','type'=>'icon','name'=>'Event Location Icon','default'=>'fa-map-marker'),
				array('id'=>'evcal__fai_004','type'=>'icon','name'=>'Event Organizer Icon','default'=>'fa-headphones'),
				array('id'=>'evcal__fai_005','type'=>'icon','name'=>'Event Capacity Icon','default'=>'fa-tachometer'),
				array('id'=>'evcal__fai_006','type'=>'icon','name'=>'Event Learn More Icon','default'=>'fa-link'),
				array('id'=>'evcal__fai_007','type'=>'icon','name'=>'Event Ticket Icon','default'=>'fa-ticket'),
				array('id'=>'evcal__fai_008','type'=>'icon','name'=>'Add to your calendar Icon','default'=>'fa-calendar-o'),
				array('id'=>'evcal__fai_008a','type'=>'icon','name'=>'Get Directions Icon','default'=>'fa-road'),
				

			
			)
		),array(
			'id'=>'evcal_004aa',
			'name'=>'EventTop Settings (EventTop is an event row on calendar)',
			'tab_name'=>'EventTop',
			'fields'=>array(
				array('id'=>'evcal_top_fields', 'type'=>'checkboxes','name'=>'Additional data fields for eventTop: <i>(NOTE: <b>Event Name</b> and <b>Event Date</b> are default fields)</i>',
						'options'=> apply_filters('eventon_eventop_fields', array(
							'time'=>'Event Time (to and from)',
							'location'=>'Event Location Address',
							'locationame'=>'Event Location Name',
							'eventtype'=>'Event Type Value',
							'monthname'=>'Event Start Month eg. SEP',
							'dayname'=>'Event Day Name (Only for one day events)',
							'organizer'=>'Event Organizer',
						)),
				),
			)
		),array(
			'id'=>'evcal_004a',
			'name'=>'EventCard Settings (EventCard is the full event details card)',
			'tab_name'=>'EventCard',
			'fields'=>array(
				array('id'=>'evo_ics','type'=>'yesno','name'=>'Show ICS download to your calendar','legend'=>'This will allow users to download each event as ICS file which can be imported to their calendar of choice.'),
				
				array('id'=>'evo_getdir','type'=>'yesno','name'=>'Show get directions to text field','legend'=>'This will add an input field to eventCard that will allow user to type in their address and get directions to event location in a new window.'),
				
				array('id'=>'evo_morelass','type'=>'yesno','name'=>'Show full event description','legend'=>'If you select this option, you will not see More/less button on EventCard event description.'),
				
				array('id'=>'evo_opencard','type'=>'yesno','name'=>'Open all eventCards by default','legend'=>'This option will load the calendar with all the eventCards open by default and will not need to be clicked to slide down and see details.'),
				

				array('id'=>'evcal_sh001','type'=>'subheader','name'=>'Featured Image'),
				array('id'=>'evo_ftimghover','type'=>'yesno','name'=>'Disable hover effect on features image','legend'=>'Remove the hover moving animation effect from featured image on event.'),
				array('id'=>'evo_ftimgheight','type'=>'text','name'=>'Set event featured image height (value in pixels)', 'default'=>'eg. 400'),
				array('id'=>'evo_ftim_mag','type'=>'yesno','name'=>'Show magnifying glass over featured image','legend'=>'This will convert the mouse cursor to a magnifying glass when hover over featured image. <br/><br/><img src="'.AJDE_EVCAL_URL.'/assets/images/ajde_backender/cursor_mag.jpg"/>'),
				
				array('id'=>'evo_EVC_arrange', 'type'=>'customcode','code'=>$_rearrange_code, ),
				
				
				
				
				
			)
		),array(
			'id'=>'evcal_003',
			'name'=>'Third Party API Support for Event Calendar',
			'tab_name'=>'Third Party APIs',
			'top'=>'112',
			'fields'=>array(
				// eventbrite
				array('id'=>'evcal_s','type'=>'subheader','name'=>'EventBrite'),
				array('id'=>'evcal_evb_events','type'=>'yesno','name'=>'Enable EventBrite data fetching for calendar events','legend'=>'Once enabled, this will allow you to connect eventbrite to event calendar and populate event data such as event name, event location, ticket price for events, event capacity & link to buy ticket','afterstatement'=>'evcal_evb_events'),
				array('id'=>'evcal_evb_events','type'=>'begin_afterstatement'),
				array('id'=>'evcal_evb_api','type'=>'text','name'=>'EventBrite API Key'),
				array('id'=>'evcal_evb_api_note','type'=>'note','name'=>'(In order to get your eventbrite API key <a href=\'https://www.eventbrite.com/api/key/\' target=\'_blank\'>open this</a> and login to your eventbrite account, fill in the required information in this page and click "create key". Once approved, you will receive the API key.)'),
				array('id'=>'evcal_eb_hide','type'=>'end_afterstatement'),
				
				// meetup
				array('id'=>'evcal_s','type'=>'subheader','name'=>'Meetup'),
				array('id'=>'evcal_api_meetup','type'=>'yesno','name'=>'Enable Meetup data fetching for calendar events','legend'=>'Once enabled, this will allow your to connect meetup events and populate calendar events with meetup event data such as event name, event time, location, & meetup event url','afterstatement'=>'evcal_api_meetup'),
				array('id'=>'evcal_api_meetup','type'=>'begin_afterstatement'),
				array('id'=>'evcal_api_mu_key','type'=>'text','name'=>'Meetup API Key'),
				array('id'=>'evcal_api_mu_note','type'=>'note','name'=>'(In order to get your meetup API key, login to meetup and <a href=\'http://www.meetup.com/meetup_api/key/\' target=\'_blank\'>open this</a>.)'),
				array('id'=>'evcal_mu_settings','type'=>'end_afterstatement'),
				
				// paypal
				array('id'=>'evcal_s','type'=>'subheader','name'=>'Paypal'),
				array('id'=>'evcal_paypal_pay','type'=>'yesno','name'=>'Enable PayPal event ticket payments','afterstatement'=>'evcal_paypal_pay', 'legend'=>'This will allow you to add a paypal direct link to each event that will allow visitors to pay for event via paypal.'),
			)
		),array(
			'id'=>'evcal_009',
			'name'=>'Additions to eventON default plugin',
			'tab_name'=>'Additions',
			'fields'=>array(

				

				array('id'=>'evcal__note','type'=>'note','name'=>'You can add upto 3 additional custom fields for each event using the below fields. (* Required values)'),
				
				array('id'=>'evcal_af_1','type'=>'yesno','name'=>'Activate Additional Field #1','legend'=>'This will activate additional event meta field.','afterstatement'=>'evcal_af_1'),
				array('id'=>'evcal_af_1','type'=>'begin_afterstatement'),
				array('id'=>'evcal_ec_f1a1','type'=>'text','name'=>'Field Name*'),
				array('id'=>'evcal_ec_f1a2','type'=>'dropdown','name'=>'Content Type', 'options'=>array('text'=>'Single line Text','textarea'=>'Multiple lines of text')),
				array('id'=>'evcal__fai_00c1','type'=>'icon','name'=>'Icon','default'=>'fa-asterisk'),
				array('id'=>'evcal_ec_f1a3','type'=>'yesno','name'=>'Hide this field from front-end calendar'),
				array('id'=>'evcal_af_1','type'=>'end_afterstatement'),

				array('id'=>'evcal_af_2','type'=>'yesno','name'=>'Activate Additional Field #2','legend'=>'This will activate additional event meta field.','afterstatement'=>'evcal_af_2'),
				array('id'=>'evcal_af_2','type'=>'begin_afterstatement'),
				array('id'=>'evcal_ec_f2a1','type'=>'text','name'=>'Field Name*'),
				array('id'=>'evcal_ec_f2a2','type'=>'dropdown','name'=>'Content Type', 'options'=>array('text'=>'Single line Text','textarea'=>'Multiple lines of text')),
				array('id'=>'evcal__fai_00c2','type'=>'icon','name'=>'Icon','default'=>'fa-asterisk'),
				array('id'=>'evcal_ec_f2a3','type'=>'yesno','name'=>'Hide this field from front-end calendar'),
				array('id'=>'evcal_af_2','type'=>'end_afterstatement'),

				array('id'=>'evcal_af_3','type'=>'yesno','name'=>'Activate Additional Field #3','legend'=>'This will activate additional event meta field.','afterstatement'=>'evcal_af_3'),
				array('id'=>'evcal_af_3','type'=>'begin_afterstatement'),
				array('id'=>'evcal_ec_f3a1','type'=>'text','name'=>'Field Name*'),
				array('id'=>'evcal_ec_f3a2','type'=>'dropdown','name'=>'Content Type', 'options'=>array('text'=>'Single line Text','textarea'=>'Multiple lines of text')),
				array('id'=>'evcal__fai_00c3','type'=>'icon','name'=>'Icon','default'=>'fa-asterisk'),
				array('id'=>'evcal_ec_f3a3','type'=>'yesno','name'=>'Hide this field from front-end calendar'),
				array('id'=>'evcal_af_3','type'=>'end_afterstatement'),


				
				
			)
		)
	);			
?>