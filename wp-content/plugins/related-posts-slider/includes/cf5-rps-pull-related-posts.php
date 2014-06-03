<?php
function get_cf5_wp_rp_related_posts() {
if(function_exists('wp_get_related_posts')):
	global $cf5_rps;
	$options = wp_rp_get_options();

	$limit = $options['max_related_posts'];
	$title = $options["related_posts_title"];

	$related_posts = array();

	wp_rp_append_posts($related_posts, 'wp_rp_fetch_related_posts_v2', $limit);
	wp_rp_append_posts($related_posts, 'wp_rp_fetch_related_posts', $limit);
	wp_rp_append_posts($related_posts, 'wp_rp_fetch_random_posts', $limit);
	
	$rps_posts = array();
	$i=0;
	if($related_posts){
		foreach ($related_posts as $related_post ){
			if($i<$cf5_rps['num']) {
				$rps_posts[]=$related_post->ID;
				$i++;
			}
			else break;
		}
	}
	return $rps_posts;
 endif;   
}

function get_cf5_yarpp_related_posts() {
if(function_exists('yarpp_get_related')):
	global $cf5_rps;
	$related_posts=yarpp_get_related();
	$rps_posts = array();
	$i=0;
	if($related_posts){
		foreach ($related_posts as $related_post ){
			if($i<$cf5_rps['num']) {
				$rps_posts[]=$related_post->ID;
				$i++;
			}
			else break;
		}
	}
	return $rps_posts;
endif;
}
function get_cf5_MRP_related_posts() {
if(function_exists('MRP_get_related_posts')):
	global $post,$cf5_rps;
	
	$related_posts=MRP_get_related_posts( $post->ID, true );
	
	$rps_posts = array();
	$i=0;
	if($related_posts){
		foreach ($related_posts as $related_post ){
		  if($i<$cf5_rps['num']) { $rps_posts[]=$related_post->ID; $i++;}
		  else break;
		}
	}
	return $rps_posts;
 endif;   
}
function get_cf5_inbuilt_related_posts() { 
	global $post,$cf5_rps;
	$tags = wp_get_post_tags($post->ID);
	if ($tags) {
		$tag_ids = array();
		foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
		$args=array(
			'tag__in' => $tag_ids,
			'post__not_in' => array($post->ID),
			'numberposts'=>$cf5_rps['num'], // Number of related posts that will be shown.
			'orderby'=>'rand' // Randomize the posts
		);
	}
	else {
		$categories = get_the_category($post->ID);
		if ($categories) {
			$category_ids = array();
			foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
			$args=array(
				'category__in' => $category_ids,
				'post__not_in' => array($post->ID),
				'numberposts'=> $cf5_rps['num'], // Number of related posts that will be shown.
				'orderby'=>'rand' // Randomize the posts
			);
		}
	}
	if($args){
		$related_posts=get_posts($args);
		$rps_posts = array();
		if($related_posts){
			foreach ($related_posts as $related_post ){
			  $rps_posts[]=$related_post->ID; 
			}
		}
		return $rps_posts;
	}
	return null;
}
?>