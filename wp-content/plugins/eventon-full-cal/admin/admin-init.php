<?php
/*
	Full Cal Admin init
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


function evoFC_admin_init(){

	add_filter( 'eventon_appearance_add', 'evoFC_appearance_settings' , 10, 1);
	add_filter( 'eventon_inline_styles_array','evoFC_dynamic_styles' , 10, 1);
}
add_action('admin_init', 'evoFC_admin_init');

function evoFC_appearance_settings($array){
	
	$new[] = array('id'=>'evofc','type'=>'hiddensection_open','name'=>'FullCal Styles');
	$new[] = array('id'=>'evofc','type'=>'fontation','name'=>'Date Number Font Color',
		'variations'=>array(
			array('id'=>'evofc_1', 'name'=>'Default','type'=>'color', 'default'=>'d4d4d4'),
			array('id'=>'evofc_2', 'name'=>'Default (Hover)','type'=>'color', 'default'=>'9e9e9e'),
			array('id'=>'evofc_3', 'name'=>'Days with events','type'=>'color', 'default'=>'dfa872'),
			array('id'=>'evofc_4', 'name'=>'Days with events (Hover)','type'=>'color', 'default'=>'9e9e9e')
			,array('id'=>'evofc_5', 'name'=>'Focus Day','type'=>'color', 'default'=>'d4d4d4'),
			array('id'=>'evofc_6', 'name'=>'Focus Day (Hover)','type'=>'color', 'default'=>'9e9e9e'),					
		)
	);
	$new[] = array('id'=>'evofc','type'=>'fontation','name'=>'Date Number Box Color',
		'variations'=>array(
			array('id'=>'evofc_1b', 'name'=>'Default','type'=>'color', 'default'=>'ffffff'),
			array('id'=>'evofc_2b', 'name'=>'Default (Hover)','type'=>'color', 'default'=>'fbfbfb'),
			array('id'=>'evofc_3b', 'name'=>'Days with events','type'=>'color', 'default'=>'ffffff'),
			array('id'=>'evofc_4b', 'name'=>'Days with events (Hover)','type'=>'color', 'default'=>'fbfbfb')
			,array('id'=>'evofc_5b', 'name'=>'Focus Day','type'=>'color', 'default'=>'f7f7f7'),
			array('id'=>'evofc_6b', 'name'=>'Focus Day (Hover)','type'=>'color', 'default'=>'fbfbfb'),					
		)
	);
	$new[] = array('id'=>'evofc','type'=>'fontation','name'=>'Day Name Color',
		'variations'=>array(
			array('id'=>'evofc_7', 'name'=>'Default','type'=>'color', 'default'=>'9e9e9e'),
			array('id'=>'evofc_7b', 'name'=>'Default (Hover)','type'=>'color', 'default'=>'d4d4d4'),
							
		)
	);
	$new[] = array('id'=>'evofc','type'=>'hiddensection_close','name'=>'FullCal Styles');

	return array_merge($array, $new);
}

function evoFC_dynamic_styles($_existen){
	$new= array(
		array(
			'item'=>'.eventon_fc_days .evo_fc_day',
			'multicss'=>array(
				array('css'=>'background-color:#$', 'var'=>'evofc_1b','default'=>'ffffff'),
				array('css'=>'color:#$', 'var'=>'evofc_1','default'=>'d4d4d4')
			)						
		),array(
			'item'=>'.eventon_fc_days .evo_fc_day:hover',
			'multicss'=>array(
				array('css'=>'background-color:#$', 'var'=>'evofc_2b','default'=>'fbfbfb'),
				array('css'=>'color:#$', 'var'=>'evofc_2','default'=>'9e9e9e')
			)						
		),array(
			'item'=>'.eventon_fc_days .evo_fc_day.has_events',
			'multicss'=>array(
				array('css'=>'background-color:#$', 'var'=>'evofc_3b','default'=>'ffffff'),
				array('css'=>'color:#$', 'var'=>'evofc_3','default'=>'dfa872')
			)						
		),array(
			'item'=>'.eventon_fc_days .evo_fc_day.has_events:hover',
			'multicss'=>array(
				array('css'=>'background-color:#$', 'var'=>'evofc_4b','default'=>'fbfbfb'),
				array('css'=>'color:#$', 'var'=>'evofc_4','default'=>'9e9e9e')
			)						
		),array(
			'item'=>'.eventon_fc_days .evo_fc_day.on_focus',
			'multicss'=>array(
				array('css'=>'background-color:#$', 'var'=>'evofc_5b','default'=>'f7f7f7'),
				array('css'=>'color:#$', 'var'=>'evofc_5','default'=>'d4d4d4')
			)						
		),array(
			'item'=>'.eventon_fc_days .evo_fc_day.on_focus:hover',
			'multicss'=>array(
				array('css'=>'background-color:#$', 'var'=>'evofc_6b','default'=>'fbfbfb'),
				array('css'=>'color:#$', 'var'=>'evofc_6','default'=>'9e9e9e')
			)						
		),
		array(
			'item'=>'.eventon_fc_daynames .evo_fc_day',
			'css'=>'color:#$', 'var'=>'evofc_7',	'default'=>'9e9e9e'
		),array(
			'item'=>'.eventon_fc_daynames .evo_fc_day:hover',
			'css'=>'color:#$', 'var'=>'evofc_7b',	'default'=>'d4d4d4'
		)
	);
	

	return (is_array($_existen))? array_merge($_existen, $new): $_existen;
}

