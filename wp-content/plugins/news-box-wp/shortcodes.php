<?php
// SHORCODE TO DISPLAY NEWSBOX

// [newsbox] 
function nb_shortcode($atts, $content = null) {
	include_once(NB_DIR . '/functions.php');
	
	extract( shortcode_atts( array(
		'id' => ''
	), $atts ) );
	if($id == '') {return '';}
	
	// get newsbox data
	$box = get_term($id, 'nb_boxes');
	$data = (empty($box->description)) ? '' : $box->description;
	if(empty($data)) {return '';}
	
	$data = unserialize($data);
	$opt = $data['settings'];
	
	// remote sources
	$rm_src = array();
	foreach($data['src'] as $src) {
		if($src['src_type'] != 'wp_cat' && $src['src_type'] != 'qn_cat' && $src['src_type'] != 'woo_cat') {
			$rm_src[] = $src;	
		}
	}
	
	
	/************************/
	
	
	// wrapper
	$nb = '<div id="lcnb_'.$id.'" class="lcnb_wp_wrap">%INLINE%</div>';
	
	// check if inline news are needed
	if(count($rm_src) != count($data['src'])) {
		
		$inl_code = '';
		foreach($data['src'] as $src) {
			if($src['src_type'] == 'wp_cat' || $src['src_type'] == 'qn_cat' || $src['src_type'] == 'woo_cat') {
				$inl_code .= nb_inline_code($src, $opt['max_news'], $opt['manage_exp_images']);		
			}
		}
		$nb = str_replace('%INLINE%', $inl_code, $nb);
	}
	else {$nb = str_replace('%INLINE%', '', $nb);}
	
	
	//// javascript
	$nb .= '
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery("#lcnb_'.$id.'").lc_news_box({
	';
		// sources
		if(count($rm_src) > 0) {
			$nb .= 'src:[';

			$sc = 1;
			foreach($rm_src as $src) {			
				$nb .= '{';
				
				// image size check
				if(get_option('nb_img_check')) {
					$nb .= 'max_img_size:{w : '.get_option('nb_max_img_w', 800).', h : '.get_option('nb_max_img_h', 600).'},
		';	
				}
				
				// expanded images management
				$nb .= 'exp_img_manag:{pos : "'.$src['exp_img_pos'].'", w : "'.$src['exp_img_w'].'", h : "'.$src['exp_img_h'].'"},
		'; 
				
				$a = 1;
				foreach($src as $i=>$v) {
				
					if($i != 'src_val' && $i != 'exp_img_pos' && $i != 'exp_img_w' && $i != 'exp_img_h') {
						if($i == 'src_type') {
							$nb .= 'type:"'.$v.'",';
							$nb .= nb_src_opt_index($v) .': "'. $src['src_val'] .'",
		';	
						}
						else {
							if($i == 'src_hide_elements') {$i = 'hide_elements';}	
							$ending_comma = ($a == count($src)) ? '
	' : ',
	';
							$val = nb_php_to_js_datatype($i, $v);
							$nb .= $i.':'.$val.$ending_comma;
						}		
					}
					
					$a++;
				}
				
				$src_comma = ($sc == count($rm_src)) ? '
	' : ',
	';
				$nb .= '}'.$src_comma;
				$sc++;
			}
			
			$nb .= '],
	';
		}
		
		
		// global options
		$theme = (get_option('nb_custom_style')) ? 'wpdt' : get_option('nb_main_style', 'minimal');
		$nb .= 'theme:"'. $theme .'",
				lightbox:nb_lightbox,
				touchswipe:nb_touchswipe,
				min_news_h:nb_min_news_h,
				min_news_w:nb_min_news_w,
				min_horiz_w:nb_min_horiz_w,
				read_more_txt:nb_read_more_txt,
				fb_share_fix:nb_fb_share_fix,
				script_basepath:nb_script_basepath,
				short_d_names:nb_short_d_names,
				full_d_names:nb_full_d_names,
				short_m_names:nb_short_m_names,
				full_m_names:nb_full_m_names,
				elapsed_names:nb_elapsed_names,
		';

	
		// options
		$b = 1;
		foreach ($opt as $i=>$v) {
			if($i != 'box_id') {
				$ending_comma = ($b == count($opt)) ? '
	' : ',
	';
				$val = nb_php_to_js_datatype($i, $v);
				$nb .= $i.':'.$val.$ending_comma;
				
				// if hide elements - check for socials
				if($i == 'hide_elements' && is_array($v) && in_array('socials', $v)) {
					$nb .= 'social_share:false,';
				}
			}
			$b++;
		}
	
	$nb .= '
		});
	});
	</script>';

	return str_replace(array("\r", "\n", "\t", "\v"), '', $nb);
}
add_shortcode('newsbox', 'nb_shortcode');

?>