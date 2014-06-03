<?php

/*
	eventcard each box HTML
*/
	
function eventon_eventcard_print($array, $evOPT, $evoOPT2){
	
	//print_r($array);
	
	$OT ='';
	$count = 1;
	$items = count($array);
	
	
	// close button
	$close = "<div class='evcal_evdata_row evcal_close' title='".eventon_get_custom_language($evoOPT2, 'evcal_lang_close','Close')."'></div>";
	
	foreach($array as $box_f=>$box){
		
		$end = ($count == $items)? $close: null;
		$end_row_class = ($count == $items)? ' lastrow': null;
		
		// convert to an object
		$object = new stdClass();
		foreach ($box as $key => $value){
			$object->$key = $value;
		}
		
		//print_r($box_f);
		//print_r($object);
		//$OT.="".$items.'-'.$count." ".$box_f;
		
		// each eventcard type
		switch($box_f){
			
			// Event Details
			case 'eventdetails':
				
				// check if character length of description is longer than X size
				if( $evOPT['evo_morelass']!='yes' && (strlen($object->fulltext) )>600 ){
					$more_code = 
						"<div class='eventon_details_shading_bot'>
							<p class='eventon_shad_p' content='less'><span class='ev_more_text' txt='".eventon_get_custom_language($evoOPT2, 'evcal_lang_less','less')."'>".eventon_get_custom_language($evoOPT2, 'evcal_lang_more','more')."</span><span class='ev_more_arrow'></span></p>
						</div>";
					$evo_more_active_class = 'shorter_desc';
				}else{
					$more_code=''; $evo_more_active_class = '';
				}
								
				
				$OT.="<div class='evorow evcal_evdata_row bordb evcal_event_details".$end_row_class."'>
						".$object->excerpt."
						<span class='evcal_evdata_icons'><i class='fa ".get_eventON_icon('evcal__fai_001', 'fa-align-justify',$evOPT )."'></i></span>
						<div class='evcal_evdata_cell ".$evo_more_active_class."'>".$more_code."<div class='eventon_full_description'>
								<h3 class='padb5 evo_h3'>".eventon_get_custom_language($evoOPT2, 'evcal_evcard_details','Event Details')."</h3><div class='eventon_desc_in' itemprop='description'>
								".apply_filters('the_content__',$object->fulltext)."</div><div class='clear'></div>

							</div>
						</div>
					".$end."</div>";
							
			break;


			// TIME and LOCATION
			case 'timelocation':
				
				if($object->location){
					
					$OT.= 
					"<div class='evorow bordb".$end_row_class." evo_metarow_time_location'>
					<div class='tb' >
						<div class='tbrow'>
						<div class='evcal_col50 bordr'>
							<div class='evcal_evdata_row evo_time'>
								<span class='evcal_evdata_icons'><i class='fa ".get_eventON_icon('evcal__fai_002', 'fa-clock-o',$evOPT )."'></i></span>
								<div class='evcal_evdata_cell'>							
									<h3 class='evo_h3'>".eventon_get_custom_language($evoOPT2, 'evcal_lang_time','Time')."</h3><p>".$object->timetext."</p>
								</div>
							</div>
						</div><div class='evcal_col50'>
							<div class='evcal_evdata_row evo_location'>
								<span class='evcal_evdata_icons'><i class='fa ".get_eventON_icon('evcal__fai_003', 'fa-map-marker',$evOPT )."'></i></span>
								<div class='evcal_evdata_cell'>							
									<h3 class='evo_h3'>".eventon_get_custom_language($evoOPT2, 'evcal_lang_location','Location')."</h3>". ( (!empty($object->location_name))? "<p class='evo_location_name'>".$object->location_name."</p>":null ) ."<p>".$object->location."</p>
								</div>
							</div>
						</div><div class='clear'></div>
						</div></div>
					".$end."</div>";
					
				}else{
				// time only
					
					$OT.="<div class='evorow evcal_evdata_row bordb evcal_evrow_sm evo_metarow_time".$end_row_class."'>
						<span class='evcal_evdata_icons'><i class='fa ".get_eventON_icon('evcal__fai_002', 'fa-clock-o',$evOPT )."'></i></span>
						<div class='evcal_evdata_cell'>							
							<h3 class='evo_h3'>".eventon_get_custom_language($evoOPT2, 'evcal_lang_time','Time')."</h3><p>".$object->timetext."</p>
						</div>
					".$end."</div>";
					
				}
				
			break;
			
			// GOOGLE map
			case 'gmap':
				
				$OT.="<div class='evorow evcal_gmaps bordb evo_metarow_gmap' id='".$object->id."_gmap'></div>";
				
			break;
			
			
			
			
			// Featured image
			case 'ftimage':
				
				$OT.= "<div class='evorow evcal_evdata_img evo_metarow_fimg".$end_row_class."' imgheight='".$object->img[2]."' imgwidth='".$object->img[1]."'  style='background-image: url(".$object->img[0].")'>".$end."</div>";
				
			break;
			
			// event organizer
			case 'organizer':
				
				$evcal_evcard_org = eventon_get_custom_language($evoOPT2, 'evcal_evcard_org','Organizer');
				
				$OT.= "<div class='evorow evcal_evdata_row bordb evcal_evrow_sm evo_metarow_organizer".$end_row_class."'>
						<span class='evcal_evdata_icons'><i class='fa ".get_eventON_icon('evcal__fai_004', 'fa-headphones',$evOPT )."'></i></span>
						<div class='evcal_evdata_cell'>							
							<h3 class='evo_h3'>".$evcal_evcard_org."</h3><p class='evo_data_val'>".$object->value."</p>
						</div>
					".$end."</div>";
				
			break;
			
			case 'getdirection':
				
				$_lang_1 = eventon_get_custom_language($evoOPT2, 'evcalL_getdir_placeholder','Type your address to get directions');
				$_lang_2 = eventon_get_custom_language($evoOPT2, 'evcalL_getdir_title','Click here to get directions');
				
				$OT.="<div class='evorow evcal_evdata_row bordb evcal_evrow_sm getdirections'>
					<form action='http://maps.google.com/maps' method='get' target='_blank'>
					<input type='hidden' name='daddr' value='{$object->fromaddress}'/> 
					<p><input class='evoInput' type='text' name='saddr' placeholder='{$_lang_1}' value=''/>
					<button type='submit' class='evcal_evdata_icons evcalicon_9' title='{$_lang_2}'><i class='fa ".get_eventON_icon('evcal__fai_008a', 'fa-road',$evOPT )."'></i></button>
					</p></form>
				</div>";
				
			break;
			
			
			// custom field
			case 'customfield1':
			case 'customfield2':
			case 'customfield3':
				
				$OT .="<div class='evorow evcal_evdata_row bordb evcal_evrow_sm '>
						<span class='evcal_evdata_custometa_icons'><i class='fa ".$object->imgurl."'></i></span>
						<div class='evcal_evdata_cell'>							
							<h3 class='evo_h3'>".$evOPT['evcal_ec_f'.$object->x.'a1']."</h3>
							<div class='evo_custom_content evo_data_val'>".apply_filters('the_content__', $object->value)."</div>
						</div>
					".$end."</div>";
				
			break;
			
			// learnmore ICS and close button
			case 'learnmoreICS':
				

				//$__ics_data_vars = "data-start='{$object->estart}' data-end='{$object->eend}' data-location='{$object->eloc}' data-summary='{$object->etitle}' data-stamp='{$object->estamp}'";
				$__ics_url =admin_url('admin-ajax.php').'?action=eventon_ics_download&event_id='.$object->event_id.'&sunix='.$object->estart.'&eunix='.$object->eend;

				// learn more and ICS
				if( !empty($object->learnmorelink) && !empty($evOPT['evo_ics']) && $evOPT['evo_ics']=='yes'){
					
					$OT.= "
					<div class='evorow bordb".$end_row_class."'>
					<div class='tb'>
						<div class='tbrow'>
						<a class='evcal_col50 dark1 bordr evo_clik_row' href='".$object->learnmorelink."' ".$object->learnmore_target.">
							<span class='evcal_evdata_row ' >
								<span class='evcal_evdata_icons'><i class='fa ".get_eventON_icon('evcal__fai_006', 'fa-link',$evOPT )."'></i></span>
								<h3 class='evo_h3'>".eventon_get_custom_language($evoOPT2, 'evcal_evcard_learnmore2','Learn More')."</h3>
							</span>
						</a>						
						<a href='".$__ics_url."' class='evo_ics evcal_col50 dark1 evo_clik_row' >
							<span class='evcal_evdata_row ' >
								<span class='evcal_evdata_icons'><i class='fa ".get_eventON_icon('evcal__fai_008', 'fa-calendar-o',$evOPT )."'></i></span>													
								<h3 class='evo_h3'>".eventon_get_custom_language($evoOPT2, 'evcal_evcard_addics','Add to your calendar')."</h3>
							</span>
						</a></div></div>
					".$end."</div>";
				
				// only learn more
				}else if(!empty($object->learnmorelink) ){
					$OT.="<div class='evorow bordb'>
						<a class='evcal_evdata_row evo_clik_row dark1 ' href='".$object->learnmorelink."' ".$object->learnmore_target.">
							<span class='evcal_evdata_icons'><i class='fa ".get_eventON_icon('evcal__fai_006', 'fa-link',$evOPT )."'></i></span>
							<h3 class='evo_h3'>".eventon_get_custom_language($evoOPT2, 'evcal_evcard_learnmore2','Learn More')."</h3>
						</a>
						".$end."</div>";

				// only ICS
				}else if(!empty($evOPT['evo_ics']) && $evOPT['evo_ics']=='yes'){
					$OT.="<div class='evorow bordb'>";

					$OT.="<a href='".$__ics_url."' class='evo_ics evcal_evdata_row evo_clik_row dark1' >
						<span class='evcal_evdata_icons'><i class='fa ".get_eventON_icon('evcal__fai_008', 'fa-calendar-o',$evOPT )."'></i></span>													
						<h3 class='evo_h3'>".eventon_get_custom_language($evoOPT2, 'evcal_evcard_addics','Add to your calendar')."</h3>
					</a>
					".$end."</div>";
				}
			
			break;
			
			
			// paypal link
			case 'paypal':
				
				$OT.="<div class='evorow evcal_evdata_row bordb evo_paypal'>
						<span class='evcal_evdata_icons'><i class='fa ".get_eventON_icon('evcal__fai_007', 'fa-ticket',$evOPT )."'></i></span>
						<div class='evcal_evdata_cell'>
							<p>".eventon_get_custom_language($evoOPT2, 'evcal_evcard_tix1','Buy ticket via Paypal')."</p>
							<a href='".$object->link."' class='evcal_btn'>".eventon_get_custom_language($evoOPT2, 'evcal_evcard_btn1','Buy Now')."</a>
						</div>
					".$end."</div>";
				
			break;
			
			// eventbrite
			case 'eventbrite':
				
				// GET Custom language text
				$evcal_tx_1 = eventon_get_custom_language($evoOPT2, 'evcal_evcard_tix2','Ticket for the event');
				$evcal_tx_2 = eventon_get_custom_language($evoOPT2, 'evcal_evcard_btn2','Buy Now');
				$evcal_tx_3 = eventon_get_custom_language($evoOPT2, 'evcal_evcard_cap','Event Capacity');
				
				// EVENTBRITE with event capacity
				if(!empty($object->capacity )){
					$OT.= "<div class='evorow bordb".$end_row_class." eventbrite'>
					<div class='evcal_col50'>
						<div class='evcal_evdata_row bordr '>
							<span class='evcal_evdata_icons'><i class='fa ".get_eventON_icon('evcal__fai_007', 'fa-ticket',$evOPT )."'></i></span>
							<div class='evcal_evdata_cell'>
								<h2 class='bash'>".$object->tix_price."</h2>
								<p>".$evcal_tx_1."</p>
								<a href='".$object->url."' class='evcal_btn'>".$evcal_tx_2."</a>
							</div>
						</div>
					</div><div class='evcal_col50'>
						<div class='evcal_evdata_row'>
							<span class='evcal_evdata_icons'><i class='fa ".get_eventON_icon('evcal__fai_005', 'fa-tachometer',$evOPT )."'></i></span>
							<div class='evcal_evdata_cell'>
								<h2 class='bash'>".$object->capacity."</h2>
								<p>".$evcal_tx_3."</p>
							</div>
						</div>
					</div><div class='clear'></div>
					".$end."</div>";
				}else{	
					// No event capacity
					$OT.= "<div class='evorow bordb eventbrite'>
						<div class='evcal_evdata_row bordr '>
							<span class='evcal_evdata_icons'><i class='fa ".get_eventON_icon('evcal__fai_007', 'fa-ticket',$evOPT )."'></i></span>
							<div class='evcal_evdata_cell'>
								<h2 class='bash'>".$object->tix_price."</h2>
								<p>".$evcal_tx_1."</p>
								<a href='".$object->url."' class='evcal_btn'>".$evcal_tx_2."</a>
							</div>
						</div>
					<div class='clear'></div>
					".$end."</div>";
				}
				
				
			break;
			
		}// end switch
		
		$count++;
	
	}// end foreach
	
	return $OT;
	
}	
	
	
?>