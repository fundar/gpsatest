<?php
// HIDDEN CODE TO BE SHOWN IN THICKBOX - explain date format placeholders

function nb_date_helper() {
	$cs = get_current_screen();
	$hooked = array('news-box_page_nb_settings', 'toplevel_page_nb_menu');
	
	//var_dump(get_current_screen()); // debug
	if(in_array($cs->base, $hooked)) {
		echo '
		<div id="nb_date_helper" style="display: none;">
		<p>'. __('HTML code and other textual elements can be placed between placeholders', 'nb_ml') .'</p>
		<table width="100%" cellpadding="4" cellspacing="0" id="nb_date_helper_table">
          <tr>
          	<th style="width: 130px;">'. __('Placeholder', 'nb_ml') .'</th>
            <th>'. __('Description', 'nb_ml') .'</th>
            <th>'. __('Example', 'nb_ml') .'</th> 
          </tr>
 
          <tr>
          	<td>SS</td>
            <td>'. __('seconds (with leading zeros)', 'nb_ml') .'</td>
            <td>20</td>
          </tr>  
          <tr>
          	<td>MM</td>
            <td>'. __('minutes (with leading zeros)', 'nb_ml') .'</td>
            <td>05</td>
          </tr>
          
          <tr>
          	<td>HHH</td>
            <td>'. __('hours with AM/PM', 'nb_ml') .'</td>
            <td>9 AM</td>
          </tr>
          <tr>
          	<td>HH</td>
            <td>'. __('hours (with leading zeros)', 'nb_ml') .'</td>
            <td>09</td>
          </tr> 
          <tr>
          	<td>H</td>
            <td>'. __('hours (without leading zeros)', 'nb_ml') .'</td>
            <td>9</td>
          </tr>
         
          <tr>
          	<td>dddd</td>
            <td>'. __('day with Monday-Sunday format', 'nb_ml') .'</td>
            <td>'. __('Saturday', 'nb_ml') .'</td>
          </tr>
          <tr>
          	<td>ddd</td>
            <td>'. __('day with Mon-Sun format', 'nb_ml') .'</td>
            <td>'. __('Sat', 'nb_ml') .'</td>
          </tr> 
          <tr>
          	<td>dd</td>
            <td>'. __('day number (with leading zeros)', 'nb_ml') .'</td>
            <td>06</td>
          </tr>
          <tr>
          	<td>d</td>
            <td>'. __('day number (without leading zeros)', 'nb_ml') .'</td>
            <td>6</td>
          </tr>
          
          <tr>
          	<td>mmmm</td>
            <td>'. __('month with January-December format', 'nb_ml') .'</td>
            <td>'. __('July', 'nb_ml') .'</td>
          </tr>
          <tr>
          	<td>mmm</td>
            <td>'. __('day with Jan-Dec format', 'nb_ml') .'</td>
            <td>'. __('Jul', 'nb_ml') .'</td>
          </tr> 
          <tr>
          	<td>mm</td>
            <td>'. __('day number (with leading zeros)', 'nb_ml') .'</td>
            <td>07</td>
          </tr>
          <tr>
          	<td>m</td>
            <td>'. __('day number (without leading zeros)', 'nb_ml') .'</td>
            <td>7</td>
          </tr>
         
          <tr>
          	<td>yyyy</td>
            <td>'. __('year in four digits format', 'nb_ml') .'</td>
            <td>2013</td>
          </tr>
          <tr>
          	<td>mmm</td>
            <td>'. __('year in two digits format', 'nb_ml') .'</td>
            <td>13</td>
          </tr> 
		</table> 
		</div>';	
	}
}
add_action('admin_footer', 'nb_date_helper');
