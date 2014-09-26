<?php

// get the current URL
function nb_curr_url() {
	$pageURL = 'http';
	
	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://" . $_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];

	return $pageURL;
}
	

// get file extension from a filename
function nb_stringToExt($string) {
	$pos = strrpos($string, '.');
	$ext = strtolower(substr($string,$pos));
	return $ext;	
}


// get filename without extension
function nb_stringToFilename($string, $raw_name = false) {
	$pos = strrpos($string, '.');
	$name = substr($string,0 ,$pos);
	if(!$raw_name) {$name = ucwords(str_replace('_', ' ', $name));}
	return $name;	
}


// string to url format // NEW FROM v1.11 for non-latin characters 
function nb_stringToUrl($string){
	
	// if already exist at least an option, use the default encoding
	if(!get_option('mg_non_latin_char')) {
		$trans = array("à" => "a", "è" => "e", "é" => "e", "ò" => "o", "ì" => "i", "ù" => "u");
		$string = trim(strtr($string, $trans));
		$string = preg_replace('/[^a-zA-Z0-9-.]/', '_', $string);
		$string = preg_replace('/-+/', "_", $string);	
	}
	
	else {$string = trim(urlencode($string));}
	
	return $string;
}


// normalize a url string
function nb_urlToName($string) {
	$string = ucwords(str_replace('_', ' ', $string));
	return $string;	
}


// sanitize input field values
function nb_sanitize_input($val) {
	return trim(
		str_replace(array('\'', '"', '<', '>'), array('&apos;', '&quot;', '&lt;', '&gt;'), (string)$val)
	);	
}


// convert HEX to RGB
function nb_hex2rgb($hex) {
   	// if is RGB or transparent - return it
   	$pattern = '/^#[a-f0-9]{6}$/i';
	if(empty($hex) || $hex == 'transparent' || !preg_match($pattern, $hex)) {return $hex;}
  
	$hex = str_replace("#", "", $hex);
   	if(strlen($hex) == 3) {
		$r = hexdec(substr($hex,0,1).substr($hex,0,1));
		$g = hexdec(substr($hex,1,1).substr($hex,1,1));
		$b = hexdec(substr($hex,2,1).substr($hex,2,1));
	} else {
		$r = hexdec(substr($hex,0,2));
		$g = hexdec(substr($hex,2,2));
		$b = hexdec(substr($hex,4,2));
	}
	$rgb = array($r, $g, $b);
  
	return 'rgb('. implode(",", $rgb) .')'; // returns the rgb values separated by commas
}


// convert RGB to HEX
function nb_rgb2hex($rgb) {
   	// if is hex or transparent - return it
   	$pattern = '/^#[a-f0-9]{6}$/i';
	if(empty($rgb) || $rgb == 'transparent' || preg_match($pattern, $rgb)) {return $rgb;}

  	$rgb = explode(',', str_replace(array('rgb(', ')'), '', $rgb));
  	
	$hex = "#";
	$hex .= str_pad(dechex( trim($rgb[0]) ), 2, "0", STR_PAD_LEFT);
	$hex .= str_pad(dechex( trim($rgb[1]) ), 2, "0", STR_PAD_LEFT);
	$hex .= str_pad(dechex( trim($rgb[2]) ), 2, "0", STR_PAD_LEFT);

	return $hex; 
}


// hex color to RGBA
function nb_hex2rgba($hex, $alpha) {
	$rgba = str_replace(array('rgb', ')'), array('rgba', ', '.$alpha.')'), nb_hex2rgb($hex));
	return $rgba;	
}


// know if woocommerce is active
function nb_woocomm_active() {
	return (in_array( 'woocommerce/woocommerce.php', apply_filters('active_plugins', get_option( 'active_plugins' )))) ? true : false;
}

/////////////////////////////


// add custom CSS to core plugin file 
function nb_create_custom_theme() {	
	if(!ini_get('allow_url_fopen')) {return false;} // locked server
	$file = NB_DIR . '/js/nb/themes/wpdt.css';

	ob_start();
	require(NB_DIR.'/custom_theme_css.php');
	
	$css = ob_get_clean();
	if(trim($css) != '') {
		if(!@file_put_contents($file, $css, LOCK_EX)) {$error = true;}
	} else {
		if(file_exists($file))	{ unlink($file); }
	}
	
	if(isset($error)) {return false;}
	else {return true;}
}


// turn jQuery.serializeArray() ajax data into php array
function nb_serArr_to_php($values) {
	$final_arr = array();
	
	foreach($values as $val) {
		if(strpos($val->name, '[]') === false) {
			$index = $val->name;
			$val = nb_sanitize_input($val->value);
		}
		else {
			$index = str_replace('[]', '', $val->name);
			$val = array( nb_sanitize_input($val->value) );
		}
		
		if(isset($final_arr[$index])) { 
			$final_arr[$index][] = $val[0];
		} else {
			$final_arr[$index] = $val;	
		}
	}

	return $final_arr;		
}


// php to javascript value
function nb_php_to_js_datatype($index, $val) {
	if(is_array($val)) {
		$js_val = "'". implode(',', $val) ."'";
	}
	else if(nb_is_bool_opt($index)) {
		$js_val = ($val) ? 'true' : 'false';
	}
	else if(filter_var($val, FILTER_VALIDATE_INT)) {
		$js_val = $val;
	}
	else {
		$js_val = "'". str_replace("'", "\'", $val) ."'";
		
		// nav btn position
		if($index == 'nav_arrows' && $val == 'false') {$js_val = $val;}
	}	
	
	return $js_val;
}


// check if is a boolean field
function nb_is_bool_opt($opt_id) {
	$data = nb_types_opt($opt_id);
	return ($data['type'] == 'bool') ? true : false;	
}


// remote sources - subject option index
function nb_src_opt_index($type) {
	switch($type) {
		case 'rss' : 
		case 'pinterest' :
		case 'soundcloud' :
		case 'tumblr' :
			$index = 'url';
			break;
			
		case 'facebook' :
		case 'google' :
		case 'twitter' : 
		case 'youtube' :
		default :
			$index = 'id';
			break;	
	}

	return $index;
}


/////////////////////////////////////////////////////

// main styles 
function nb_main_styles($style = '') {
	$styles = array(
		'minimal' => array(
			'preview' => 'minimal.jpg',
			'css' => 'minimal.css'
		),
		'light' => array(
			'preview' => 'light.jpg',
			'css' => 'light.css'
		),
		'dark' => array(
			'preview' => 'dark.jpg',
			'css' => 'dark.css'
		)
	);
		
	if($style == '') {return $styles;}
	else {return $styles[$style];}	
}


// news sources
function nb_news_sources($src = '') {
	$sources = array(
		'wp_cat' 		=> __('Wordpress category', 'nb_ml'),
		'qn_cat' 		=> __('Quick News category', 'nb_ml'),
		'rss' 			=> __('RSS Feed', 'nb_ml'),
		'facebook' 		=> __('Facebook page', 'nb_ml'),
		'google' 		=> __('Google+', 'nb_ml'),
		'twitter' 		=> __('Twitter', 'nb_ml'),
		'youtube' 		=> __('Youtube', 'nb_ml'),
		'pinterest' 	=> __('Pinterest', 'nb_ml'),
		'soundcloud' 	=> __('Soundcloud', 'nb_ml'),
		'tumblr' 		=> __('Tumblr', 'nb_ml'),
	);
	
	// woocommerce integration
	if(nb_woocomm_active() || $src != '') { // exception if is viewing an already existing source
		$sources['woo_cat'] = __('WooCommerce category', 'nb_ml');
	}
		
	if($src == '') {return $sources;}
	else {return $sources[$src];}	
}


// navigation arrows positions
function nb_nav_arr_pos($pos = '') {
	$positions = array(
		'false' => __("hidden", 'nb_ml'), 
		'side' => __("on sides", 'nb_ml'),
		'top_l' => __("top-left", 'nb_ml'),
		'top_c' => __("top-center", 'nb_ml'),
		'top_r' => __("top-right", 'nb_ml'),
		'bottom_l' => __("bottom-left", 'nb_ml'),
		'bottom_c' => __("bottom-center", 'nb_ml'),
		'bottom_r' => __("bottom-right", 'nb_ml'),
	);
		
	if($pos == '') {return $positions;}
	else {return $positions[$pos];}	
}



//////////////////////////////////////////////////////////////////////


// news source - form block
function nb_src_form_block($data) {
	$vals = array(
		'src_val' 		=> '',
		'author' 		=> '',
		'hide_elements' => '',
		'link_target' 	=> '',
		'exp_img_pos' 	=> '',
		'exp_img_w' 	=> '',
		'exp_img_h' 	=> 250,
		'strip_tags' 	=> '',
		'remove_tags' 	=> '',
	);
	
	// override defaults
	foreach($data as $i => $v) {
		$vals[$i] = $v;
	}
	
	
	// source value
	if($data['src_type'] == 'wp_cat') {
		$src_val = '
		<label>'. __('Posts category', 'nb_ml') .'</label>
		<select name="src_val" class="lcweb-chosen" data-placeholder="'. __('Select a category', 'nb_ml') .'">
			<option value="">'. __('All', 'nb_ml') .'</option>';
		
			foreach( get_categories() as $cat ) {
				($cat->term_id == $vals['src_val']) ? $sel = 'selected="selected"' : $sel = '';
				$src_val .= '<option value="'.$cat->term_id.'" '.$sel.'>'.$cat->name.'</option>'; 
			}
			
		$src_val .= '</select>';
	}
	else if ($data['src_type'] == 'qn_cat') {	
		$src_val = '
		<label>'. __('News category', 'nb_ml') .'</label>
		<select name="src_val" class="lcweb-chosen" data-placeholder="'. __('Select a category', 'nb_ml') .'">
			<option value="">'. __('All', 'nb_ml') .'</option>';
			
			$cats = get_terms('nb_news_cat', 'orderby=name&hide_empty=0');
			foreach($cats as $cat ) {
				($cat->term_id == $vals['src_val']) ? $sel = 'selected="selected"' : $sel = '';
				$src_val .= '<option value="'.$cat->term_id.'" '.$sel.'>'.$cat->name.'</option>'; 
			}
		
		$src_val .= '</select>';
	}
	else if ($data['src_type'] == 'woo_cat') {	
		$src_val = '
		<label>'. __('Products category', 'nb_ml') .'</label>
		<select name="src_val" class="lcweb-chosen" data-placeholder="'. __('Select a category', 'nb_ml') .'">
			<option value="">'. __('All', 'nb_ml') .'</option>';
			
			if(nb_woocomm_active()) {
				$cats = get_terms('product_cat', 'orderby=name&hide_empty=0');
				foreach($cats as $cat ) {
					($cat->term_id == $vals['src_val']) ? $sel = 'selected="selected"' : $sel = '';
					$src_val .= '<option value="'.$cat->term_id.'" '.$sel.'>'.$cat->name.'</option>'; 
				}
			}
		
		$src_val .= '</select>';
	}
	else {
		// source helper
		switch ($data['src_type']) {
		  case 'rss' 		: $label = __("RSS feed URL", 'nb_ml'); break;
		  case 'facebook' 	: $label = __('Facebook page ID', 'nb_ml').' <a href="http://findmyfacebookid.com/" target="_blank">('. __('get it', 'nb_ml') .')</a>'; break;
		  case 'google' 	: $label = __('Profile URL', 'nb_ml'); break;
		  case 'twitter' 	: $label = __('Profile ID (without "@")', 'nb_ml'); break;
		  case 'youtube' 	: $label = __('Profile ID (contained in profile URL)', 'nb_ml'); break;
		  case 'pinterest' 	: $label = __("User's board URL", 'nb_ml'); break;
		  case 'soundcloud' : $label = __("User's page URL", 'nb_ml'); break;
		  case 'tumblr' 	: $label = __("Tumblr's blog URL", 'nb_ml'); break;
		}
		
		$src_val = '
		<label>'. $label .'</label>
		<input name="src_val" type="text" value="'.nb_sanitize_input($vals['src_val']).'" />';	
	}
	$code = '
	<div class="nb_full_field">'. $src_val .'</div>
	<input name="src_type" type="hidden" value="'.$data['src_type'].'" />';
	
	$common_fields = array('author', 'src_hide_elements', 'link_target', 'exp_img_pos', 'exp_img_w', 'exp_img_h', 'strip_tags', 'remove_tags');
	foreach($common_fields as $field) {
		$val = (isset($vals[$field])) ? $vals[$field] : ''; 
		$code .= nb_fields_builder($field, $val);
	}

	return $code;	
}


// inline source - nb code
function nb_inline_code($src, $limit, $man_exp_img) {
	
	// check if woocommerce source and plugin is not active
	if(!nb_woocomm_active() && $src['src_type'] == 'woo_cat') {return '';}
	
	/*** query ***/
	switch($src['src_type']) {
		case 'qn_cat'	: $pt = 'nb_news'; break;	
		case 'woo_cat'	: $pt = 'product'; break;
		default			: $pt = 'post'; break;	
	}

	$args = array(
		'numberposts' => (int)$limit,
		'post_type' => $pt,
	);
	if(!empty($src['src_val'])) {
		if($src['src_type'] == 'wp_cat') {
			$args['category'] = $src['src_val'];
		}
		elseif($src['src_type'] == 'qn_cat') {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'nb_news_cat',
					'field' => 'id',
					'terms' => $src['src_val'],
					'include_children' => true
				)
			);
		}
		else { // woo
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field' => 'id',
					'terms' => $src['src_val'],
					'include_children' => true
				)
			);
		}
	}
	
	$query = get_posts($args);
	if(!is_array($query) || count($query) == 0) {return '';}
	
	/*** code building ***/ 
	// image size control attributes
	if(get_option('nb_img_check')) {
		$img_chk_attr = 'img_max_w="'.get_option('nb_max_img_w', 800).'" img_max_h="'.get_option('nb_max_img_h', 600).'"';
	} else {
		$img_chk_attr = '';
	}
	
	// exp image control attributes
	if($man_exp_img) {
		$exp_img_attr = 'exp_img_pos="'.$src['exp_img_pos'].'" exp_img_w="'.$src['exp_img_w'].'" exp_img_h="'.$src['exp_img_h'].'"';
	} else {
		$exp_img_attr = 'exp_img_pos="'.$src['exp_img_pos'].'"';
	}
	
	$code = '<div class="nb_news_wrap" style="display: none;" '.$img_chk_attr.' '.$exp_img_attr.'>';
	
	foreach($query as $post) {
		// image
		if(has_post_thumbnail($post->ID)) {
			$img_id = get_post_thumbnail_id($post->ID);
			$img_src = wp_get_attachment_image_src($img_id, 'full');
			$img = '<img src="'.$img_src[0].'" />';
		} 
		else {$img = '';}
		
		// media
		if($src['src_type'] == 'qn_cat') {
			$media = (trim((string)get_post_meta($post->ID, 'nb_media', true))) ? '<div class="lcnb_video" src="'.get_post_meta($post->ID, 'nb_media', true).'"></div>' : '';
		}
		else {$media = '';}
		
		// link
		if($src['src_type'] == 'qn_cat') {
			$link = (trim((string)get_post_meta($post->ID, 'nb_link', true))) ? '<a href="'.(string)get_post_meta($post->ID, 'nb_link', true).'" class="lcnb_inline_link"></a>' : '';
		}
		else {$link = '<a href="'.get_permalink($post->ID).'" class="lcnb_inline_link"></a>';}
		
		$code .= '
		<article datetime="'.get_post_time('c', true, $post->ID).'">
            <header>'.trim($post->post_title).'</header>
            <section>'.do_shortcode(wpautop(trim($post->post_content))).'</section>
			'.$img.'
			'.$media.'
			'.$link.'
        </article>';	
	}
	
	return $code . '</div>';
}



/////////////////////////////////////////////////////////////////////////


// all types option - global cumulative array
function nb_types_opt($type) {
	$opts = array(
		
		// news sources
		'author' => array(
			'type' => 'text',
			'label' => __("Author", 'nb_ml'),
			'optional' => true
		),
		'src_hide_elements' => array(
			'type' => 'select',
			'label' => __("Hide news elements", 'nb_ml'),
			'opts' => array('title' => __('title', 'nb_ml'), 'image' => __('main image', 'nb_ml'), 'link'=> __('link', 'nb_ml')),
			'multiple' => true,
			'optional' => true
		),
		'link_target' => array(
			'type' => 'select',
			'label' => __("Open links", 'nb_ml'),
			'opts' => array('_top' => __('in the same page', 'nb_ml'), '_blank' => __('in a new page', 'nb_ml')),
		),
		'exp_img_pos' => array(
			'type' => 'select',
			'label' => __("Main image position <small>(expanded mode)</small>", 'nb_ml'),
			'opts' => array('inside' => __('inside news text', 'nb_ml'), 'side' => __('on left side', 'nb_ml'), 'hidden' => __('hidden', 'nb_ml')),
		),
		'exp_img_w' => array(
			'type' => 'select',
			'label' => __("Image's container width <small>(expanded mode)</small>", 'nb_ml'),
			'opts' => array(
						'1_4' => __("one fourth's wrapper width", 'nb_ml'), 
						'1_3' => __("one third's wrapper width", 'nb_ml'), 
						'1_2' => __('half wrapper width', 'nb_ml'), 
						'1_1' => __('fullwidth', 'nb_ml')
					),
			'def' => '1_3'
		),
		'exp_img_h' => array(
			'type' => 'text',
			'label' => __("Image's container height <small>(in pixels or AUTO value - expanded mode)</small>", 'nb_ml'),
			'optional' => true
		),
		'strip_tags' => array(
			'type' => 'text',
			'label' => __("Strip HTML tags <small>(JS selectors, comma divided)</small>", 'nb_ml'),
			'optional' => true
		),
		'remove_tags' => array(
			'type' => 'text',
			'label' => __("Remove HTML tags <small>(JS selectors, comma divided)</small>", 'nb_ml'),
			'optional' => true
		),
		
		/*** initial mode ***/
		'max_news' => array(
			'type' => 'slider',
			'label' => __("News to keep", 'nb_ml'),
			'min_val' => '1',
			'max_val' => '20',
			'step' => '1',
			'value' => '',
			'def' => '6'
		),
		'news_per_time' => array(
			'type' => 'slider',
			'label' => __("News displayed per time", 'nb_ml'),
			'min_val' => '1',
			'max_val' => '15',
			'step' => '1',
			'value' => '',
			'def' => '3'
		),
		'height' => array(
			'type' => 'slider',
			'label' => __("Box height", 'nb_ml'),
			'min_val' => '80',
			'max_val' => '1000',
			'step' => '10',
			'value' => 'px',
			'def' => '300'
		),
		'layout' => array(
			'type' => 'select',
			'label' => __("Box layout", 'nb_ml'),
			'opts' => array('horizontal' => __("horizontal", 'nb_ml'), 'vertical' => __('vertical', 'nb_ml')),
		),
		'boxed_news' => array(
			'type' => 'bool',
			'label' => __("Boxed style?", 'nb_ml'),
		),
		'buttons_position' => array(
			'type' => 'select',
			'label' => __("Buttons position", 'nb_ml'),
			'opts' => array('bottom' => __("bottom", 'nb_ml'), 'top' => __('top', 'nb_ml'), 'side' => __('side (only for vertical and boxed layout)', 'nb_ml')),
		),
		'hide_elements' => array(
			'type' => 'select',
			'label' => __("Hide elements", 'nb_ml'),
			'opts' => array('date' => __("date", 'nb_ml'), 'title' => __('title', 'nb_ml'), 'image' => __('main image', 'nb_ml'), 'link' => __('link', 'nb_ml'), 'socials' => __('social share', 'nb_ml')),
			'multiple' => true,
			'optional' => true
		),
		'btn_over_img' => array(
			'type' => 'bool',
			'label' => __("Side buttons over image?", 'nb_ml'),
		),
		'show_src_logo' => array(
			'type' => 'bool',
			'label' => __("Show news source logo? <small>(only vertical layout)</small>", 'nb_ml'),
		),
		'horiz_img_h' => array(
			'type' => 'slider',
			'label' => __("Image's height <small>(for horizontal layout)</small>", 'nb_ml'),
			'min_val' => '50',
			'max_val' => '500',
			'step' => '10',
			'value' => 'px',
			'def' => '180',
			'optional' => true
		),
		'vert_img_w' => array(
			'type' => 'slider',
			'label' => __("Image's width <small>(for vertical layout)</small>", 'nb_ml'),
			'min_val' => '40',
			'max_val' => '400',
			'step' => '10',
			'value' => 'px',
			'def' => '160',
			'optional' => true
		),
		'title_behavior' => array(
			'type' => 'select',
			'label' => __("News title behavior", 'nb_ml'),
			'opts' => array('expand' => __("expand news", 'nb_ml'), 'none' => __('no action', 'nb_ml'), 'link' => __('redirect to news link', 'nb_ml')),
		),
		'img_behavior' => array(
			'type' => 'select',
			'label' => __("Main image behavior", 'nb_ml'),
			'opts' => array('lightbox' => __("trigger lightbox", 'nb_ml'), 'expand' => __("expand news", 'nb_ml'), 'none' => __('no action', 'nb_ml'), 'link' => __('redirect to news link', 'nb_ml')),
		),
		'date_format' => array(
			'type' => 'text',
			'label' => __('Date format', 'nb_ml').' (<a id="nb_show_date_helper" href="#">'. __('show guide', 'nb_ml') .'</a>)',
			'def' => get_option('nb_date_format', 'd mmmm yyyy')
		),
		'elapsed_time' => array(
			'type' => 'bool',
			'label' => __("Use elapsed time?", 'nb_ml'),
		),
		'read_more_btn' => array(
			'type' => 'bool',
			'label' => __('Replace date with "read more" button?', 'nb_ml'),
		),
		'read_more_btn_txt' => array(
			'type' => 'text',
			'label' => __('"Read more"', 'nb_ml').' '. __("button's text", 'nb_ml'),
			'def' => __('Read more', 'nb_ml'),
			'optional' => true
		),
		'lightbox' => array(
			'type' => 'bool',
			'label' => __("Use lightbox?", 'nb_ml'),
		),
		'touchswipe' => array(
			'type' => 'bool',
			'label' => __("use touchSwipe?", 'nb_ml'),
		),
		
		/*** expanded mode ***/
		'expandable_news' => array(
			'type' => 'bool',
			'label' => __('Expandable news?', 'nb_ml'),
		),
		'scroll_exp_elem' => array(
			'type' => 'bool',
			'label' => __('Keep close button and side image visible on scroll?', 'nb_ml'),
		),
		/*'exp_main_img_pos' => array(
			'type' => 'select',
			'label' => __("Main image position", 'nb_ml'),
			'opts' => array('inside' => __("on top of news text", 'nb_ml'), 'side' => __("on left side", 'nb_ml'), 'hidden' => __('hide it', 'nb_ml')),
		),*/
		'manage_exp_images' => array(
			'type' => 'bool',
			'label' => __('Manage news images?', 'nb_ml'),
		),
		/*'exp_img_w' => array(
			'type' => 'select',
			'label' => __("Image's container width", 'nb_ml'),
			'opts' => array('1_3' => __("one third's wrapper width", 'nb_ml'), '1_2' => __("half wrapper width", 'nb_ml'), '1_1' => __('fullwidth', 'nb_ml')),
		),
		'exp_img_h' => array( 
			'type' => 'slider',
			'label' => __("Image's container height", 'nb_ml'),
			'min_val' => '50',
			'max_val' => '600',
			'step' => '10',
			'value' => 'px',
			'def' => '225',
			'optional' => true
		),*/
		
		/*** navigation settings ***/
		'nav_arrows' => array(
			'type' => 'select',
			'label' => __("Navigation arrows position", 'nb_ml'),
			'opts' => nb_nav_arr_pos(),
		),
		'carousel' => array(
			'type' => 'bool',
			'label' => __('Carousel mode?', 'nb_ml'),
		),
		'animation_time' => array(
			'type' => 'slider',
			'label' => __("Animation time", 'nb_ml'),
			'min_val' => '0',
			'max_val' => '20000',
			'step' => '100',
			'value' => 'ms',
			'def' => '700',
		),
		'autoplay' => array(
			'type' => 'bool',
			'label' => __('Autoplay slideshow?', 'nb_ml'),
		),
		'slideshow_time' => array(
			'type' => 'slider',
			'label' => __("Slideshow interval", 'nb_ml'),
			'min_val' => '0',
			'max_val' => '10000',
			'step' => '100',
			'value' => 'ms',
			'def' => '4000',
		)
	);
	
	return $opts[$type];	
}


// fields builder
function nb_fields_builder($field, $value = '') {
	$data = nb_types_opt($field);
	$pre_code = ($data['type'] == 'textarea') ? '<div class="nb_full_field nb_f_'.$field.'_wrap">' : '<div class="nb_field nb_f_'.$field.'_wrap">';
	$pre_code .= '<label>'.$data['label'].'</label>';
	
	$def_val = (isset($data['def'])) ? $data['def'] : '';
	if((empty($value) && $value !== '0') && isset($def_val)) {$value = $def_val;}
	
	$optional = (isset($data['optional'])) ? 'nb_optional_f' : '';
	$class = str_replace('[]', '', $field); 
	
	switch($data['type']) {

		case 'color':
			$code = '
			<div class="lcwp_colpick">
				<span class="lcwp_colblock" style="background-color: '.$value.';"></span>
				<input type="text" name="'.$field.'" value="'.$value.'" class="nb_f_'.$class.' '.$optional.'" />
			</div>';
			break;
			
		case 'slider':
			$code = '
			<div class="lcwp_slider" step="'.$data['step'].'" max="'.$data['max_val'].'" min="'.$data['min_val'].'"></div>
			<input type="text" value="'.$value.'" name="'.$field.'" class="lcwp_slider_input nb_f_'.$class.' '.$optional.'" />
			<span>'.$data['value'].'</span>';
			break;
		
		case 'select':
			if(isset($data['multiple'])) { 
				$multiple = 'multiple="multiple"';
				$mfn = '[]';
			} else {
				$multiple = '';
				$mfn = '';
			}
			
			$options = '';
			foreach($data['opts'] as $k => $v) {
				if(is_array($value)) {
					$sel = (in_array($k, $value)) ? 'selected="selected"' : ''; 
				} else {
					$sel = ($value == $k) ? 'selected="selected"' : '';	
				}
				
				$options .= '<option value="'.$k.'" '.$sel.'>'.$v.'</option>';
			}
			
			$code = '
			<select name="'.$field.$mfn.'" class="lcweb-chosen nb_f_'.$class.' '.$optional.'" '.$multiple.' data-placeholder="" tabindex="2">
				'.$options.'
			</select> ';
			break;
			
		case 'bool':
			$sel = ($value == 1) ? 'checked="checked"' : '';
			$code = '<input type="checkbox" value="1" name="'.$field.'" class="ip-checkbox nb_f_'.$class.'" '.$sel.' />';
			break;	
		
		case 'textarea':
			$code = '<textarea name="'.$field.'" class="nb_f_'.$field.' '.$optional.'">'.$value.'</textarea>';
			break;	
			
		case 'padding_arr':
			if(!is_array($value)) {$value = array('','','','');}
			
			$code = '
			<input type="text" value="'.$value[0].'" name="'.$field.'[]" class="lcwp_slider_input nb_f_'.$class.' '.$optional.'" maxlength="2" />
			<input type="text" value="'.$value[1].'" name="'.$field.'[]" class="lcwp_slider_input nb_f_'.$class.' '.$optional.'" maxlength="2" />
			<input type="text" value="'.$value[2].'" name="'.$field.'[]" class="lcwp_slider_input nb_f_'.$class.' '.$optional.'" maxlength="2" />
			<input type="text" value="'.$value[3].'" name="'.$field.'[]" class="lcwp_slider_input nb_f_'.$class.' '.$optional.'" maxlength="2" />
			<span>'.$data['value'].'</span>';
			break;
			
		default : // text
			$code = '<input type="text" name="'.$field.'" class="nb_f_'.$class.' '.$optional.'" value="'.nb_sanitize_input($value).'" />';
			break;
	}
	
	return $pre_code . $code . '</div>';
}

