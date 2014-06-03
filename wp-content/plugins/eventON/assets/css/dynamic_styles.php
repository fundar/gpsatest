<?php
/**
 * dynamic styles for front end
 *
 * @version		0.1
 * @package		eventon/Styles
 * @author 		AJDE
 */


	// Load variables
	$opt= get_option('evcal_options_evcal_1');
	

	// complete styles array
	$style_array = apply_filters('eventon_inline_styles_array', array(
		array(
			'item'=>'.eventon_events_list .eventon_list_event .desc_trig',
			'css'=>'background-color:#$', 'var'=>'evcal__bgc4',	'default'=>'fafafa'
		),array(
			'item'=>'.eventon_events_list .eventon_list_event .desc_trig:hover',
			'css'=>'background-color:#$', 'var'=>'evcal__bgc4h',	'default'=>'f4f4f4'
		),

		array(
			'item'=>'.ajde_evcal_calendar .calendar_header p, .eventon_sort_line p, .eventon_filter_line p, .eventon_events_list .eventon_list_event .evcal_cblock, .evcal_cblock, .eventon_events_list .eventon_list_event .evcal_desc span.evcal_desc2, .evcal_desc span.evcal_desc2, .evcal_evdata_row .evcal_evdata_cell h2, .evcal_evdata_row .evcal_evdata_cell h3.evo_h3, .evcal_month_line p, .evo_clik_row .evo_h3',
			'css'=>'font-family:$', 'var'=>'evcal_font_fam',	'default'=>"oswald, 'arial narrow'"
		),array(
			'item'=>'.ajde_evcal_calendar .evo_sort_btn, .eventon_sf_field p',
			'css'=>'color:#$', 'var'=>'evcal__sot',	'default'=>'ededed'
		),array(
			'item'=>'.ajde_evcal_calendar .evo_sort_btn:hover',
			'css'=>'color:#$', 'var'=>'evcal__sotH',	'default'=>'d8d8d8'
		),array(
			'item'=>'#evcal_list .eventon_list_event .evcal_desc em',
			'css'=>'color:#$', 'var'=>'evcal__fc6',	'default'=>'8c8c8c'
		),

		array(
			'item'=>'#evcal_list .eventon_list_event .event_description .evcal_btn, .evo_pop_body .evcal_btn',
			'multicss'=>array(
				array('css'=>'color:#$', 'var'=>'evcal_gen_btn_fc',	'default'=>'ffffff'),
				array('css'=>'background-color:#$', 'var'=>'evcal_gen_btn_bgc',	'default'=>'237ebd')
			)	
		),array(
			'item'=>'#evcal_list .eventon_list_event .event_description .evcal_btn:hover, .evo_pop_body .evcal_btn:hover',
			'multicss'=>array(
				array('css'=>'color:#$', 'var'=>'evcal_gen_btn_fcx',	'default'=>'fff'),
				array('css'=>'background-color:#$', 'var'=>'evcal_gen_btn_bgcx',	'default'=>'237ebd')
			)	
		),array(
			'item'=>'.evcal_evdata_row .evcal_evdata_icons i, .evcal_evdata_row .evcal_evdata_custometa_icons i',
			'multicss'=>array(
				array('css'=>'color:#$', 'var'=>'evcal__ecI',	'default'=>'6B6B6B'),
				array('css'=>'font-size:$', 'var'=>'evcal__ecIz',	'default'=>'18px')
			)	
		),array(
			'item'=>'#eventon_loadbar',
			'css'=>'background-color:#$', 'var'=>'evcal_header1_fc',	'default'=>'6B6B6B'
		),array(
			'item'=>'.evcal_evdata_row .evcal_evdata_cell h3, .evo_clik_row .evo_h3',
			'css'=>'font-size:$', 'var'=>'evcal_fs_001',	'default'=>'18px'
		),array(
			'item'=>'#evcal_list .eventon_list_event .evcal_cblock',
			'css'=>'color:#$', 'var'=>'evcal__fc2',	'default'=>'ABABAB'
		),array(
			'item'=>'.evcal_evdata_row .evcal_evdata_cell h2, .evcal_evdata_row .evcal_evdata_cell h3',
			'css'=>'color:#$', 'var'=>'evcal__fc4',	'default'=>'6B6B6B'
		),array(
			'item'=>'#evcal_list .eventon_list_event .evcal_eventcard p',
			'css'=>'color:#$', 'var'=>'evcal__fc5',	'default'=>'656565'
		),array(
			'name'=>'Event Card color',
			'item'=>'.ajde_evcal_calendar #evcal_head.calendar_header #evcal_cur, .ajde_evcal_calendar .evcal_month_line p',
			'css'=>'color:#$', 'var'=>'evcal_header1_fc',	'default'=>'C6C6C6'
		),array(
			'name'=>'Event Card color',
			'item'=>'.eventon_events_list .eventon_list_event .evcal_eventcard, .evcal_evdata_row, .evorow .tbrow',
			'css'=>'background-color:#$', 'var'=>'evcal__bc1',	'default'=>'EAEAEA'
		),array(
		'name'=>'Event title color',
			'item'=>'#evcal_list .eventon_list_event .evcal_desc span.evcal_event_title',
			'css'=>'color:#$', 'var'=>'evcal__fc3',	'default'=>'6B6B6B'
		),array(
			'item'=>'.fp_popup_option i',
			'multicss'=>array(
				array('css'=>'color:#$', 'var'=>'fp__f1',	'default'=>'999'),
				array('css'=>'font-size:$', 'var'=>'fp__f1b',	'default'=>'22px')
			)			
		)
	));


	foreach($style_array as $sa){
		if(!empty($sa['multicss']) && is_array($sa['multicss'])){

			echo $sa['item'].'{';

			foreach($sa['multicss'] as $sin_CSS){
				$css_val  = (!empty($opt[ $sin_CSS['var'] ] ))? $opt[ $sin_CSS['var'] ] : $sin_CSS['default'];
				$css = str_replace('$',$css_val,$sin_CSS['css'] );
				echo $css.';';
			}
			echo '}';
		}else{
			$css_val  = (!empty($opt[ $sa['var'] ] ))? $opt[ $sa['var'] ] : $sa['default'];
			$css = str_replace('$',$css_val,$sa['css'] );
			echo $sa['item'].'{'.$css.'}';
		}
	}
	





	// magnifying glass cursor
	echo (!empty($opt['evo_ftim_mag']) && $opt['evo_ftim_mag']=='yes')? ".evcal_evdata_img{cursor: url(".AJDE_EVCAL_URL."/assets/images/zoom.png), auto;}":null;
	
	// hover effect
	echo (!empty($opt['evo_ftimghover']) && $opt['evo_ftimghover']=='yes')? 
		".evcal_evdata_img:hover{background-position: 50% 50%;}":
		".evcal_evdata_img:hover{background-position: 50% 45%;}";
	
	

	// STYLES
	echo (!empty($opt['evo_ftimgheight']))?
			".evcal_evdata_img{height:".$opt['evo_ftimgheight']."px}":null ;
		
		if(!empty($opt['evcal__fc6'])){
			echo "#evcal_widget .eventon_events_list .eventon_list_event .evcal_desc .evcal_desc_info em{
				color:#". $opt['evcal__fc6']."
			}";
		}
		

		// featured event styles
		if(!empty($opt['evo_fte_override']) && $opt['evo_fte_override']=='yes'){
			echo "#evcal_list .eventon_list_event .evcal_list_a.featured_event{border-left-color:#".eventon_styles('ca594a','evcal__ftec', $opt)."!important;}";
		}




	// (---) Hook for addons
	if(has_action('eventon_inline_styles')){
		do_action('eventon_inline_styles');
	}
	
	echo get_option('evcal_styles');

	
