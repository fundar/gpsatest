<?php

/**
Plugin Name: TouchCarousel
Plugin URI: http://dimsemenov.com/plugins/touchcarousel-wp/
Description: Touch-Based Any Posts Slider
Author: Dmitry Semenov
Version: 1.2
Author URI: http://dimsemenov.com
*/

if (!class_exists("TouchCarouselAdmin")) {
	
	require_once dirname( __FILE__ ) . '/TouchCarouselAdmin.php';	
	
	$touchcarousel =& new TouchCarouselAdmin(__FILE__);		
	
	function get_touchcarousel($id) 
	{
	
		global $touchcarousel;		
		return $touchcarousel->get_carousel($id, false);
	
	}

}

?>