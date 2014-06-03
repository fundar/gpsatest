<?php

/**
 * Check to see if a high five is being given, and if so, save it.
 *
 * Hooked to bp_actions, this function will fire before the screen function. We use our function
 * bp_is_course_component(), along with the bp_is_current_action() and bp_is_action_variable()
 * functions, to detect (based on the requested URL) whether the user has clicked on "send high
 * five". If so, we do a bit of simple logic to see what should happen next.
 *
 * @package BuddyPress_Course_Component
 * @since 1.6
 */




add_filter( 'woocommerce_get_price_html', 'course_subscription_filter',100,2 );
function course_subscription_filter($price,$product){

	$subscription=get_post_meta($product->id,'vibe_subscription',true);

		if(isset($subscription) && $subscription !='' && $subscription !='H'){
			$x=get_post_meta($product->id,'vibe_duration',true);

			$t=$x*86400;

			if($x == 1){
				$price = $price .'<span class="subs"> per '.tofriendlytime($t).'</span>';
			}else{
				$price = $price .'<span class="subs"> per '.tofriendlytime($t).'</span>';
			}
		}
		return apply_filters( 'woocommerce_get_price', $price );
}




add_action('woocommerce_after_add_to_cart_button','bp_course_subscription_product');
function bp_course_subscription_product(){
	global $product;
	$check_susbscription=get_post_meta($product->id,'vibe_subscription',true);
	if(isset($check_susbscription) && $check_susbscription !='' && $check_susbscription != 'H'){
		$duration=get_post_meta($product->id,'vibe_duration',true);	
		$t=tofriendlytime($duration*86400);
		echo '<div id="duration"><strong>SUBSCRIPTION FOR '.$t.'</strong></div>';
	}
}
//woocommerce_order_status_completed
add_action('woocommerce_order_status_completed','bp_course_enable_access');

function bp_course_enable_access($order_id){
	$order = new WC_Order( $order_id );

	$items = $order->get_items();
	$user_id=$order->user_id;
	foreach($items as $item){
		$product_id = $item['product_id'];

		$subscribed=get_post_meta($product_id,'vibe_subscription',true);

		$courses=vibe_sanitize(get_post_meta($product_id,'vibe_courses',false));


		if(isset($subscribed) && $subscribed !='' && $subscribed!='H'){

			$duration=get_post_meta($product_id,'vibe_duration',true);
			$t=time()+$duration*86400;

			foreach($courses as $course){
				update_post_meta($course,$user_id,0);
				update_user_meta($user_id,$course,$t);
				$group_id=get_post_meta($course,'vibe_group',true);
				if(isset($group_id) && $group_id !='')
				groups_join_group($group_id, $user_id );  

				bp_course_record_activity(array(
				      'action' => 'Student subscribed for course '.get_the_title($course),
				      'content' => 'Student '.bp_core_get_userlink( $user_id ).' subscribed for course '.get_the_title($course).' for '.$duration.' days',
				      'type' => 'subscribe_course',
				      'item_id' => $course,
				      'primary_link'=>get_permalink($course),
				      'secondary_item_id'=>$user_id
		        ));      
			}
			
		}else{	

			foreach($courses as $course){
				$duration=get_post_meta($course,'vibe_duration',true);
				$t=time()+$duration*86400;
				update_post_meta($course,$user_id,0);
				update_user_meta($user_id,$course,$t);
				$group_id=get_post_meta($course,'vibe_group',true);
				if(isset($group_id) && $group_id !='')
				groups_join_group($group_id, $user_id );

				bp_course_record_activity(array(
				      'action' => 'Student subscribed for course '.get_the_title($course),
				      'content' => 'Student '.bp_core_get_userlink( $user_id ).' subscribed for course '.get_the_title($course).' for '.$duration.' days',
				      'type' => 'subscribe_course',
				      'item_id' => $course,
				      'primary_link'=>get_permalink($course),
				      'secondary_item_id'=>$user_id
		        )); 
			}
		}
		
	}
	 
}


add_action('pre_get_posts', 'hdb_add_custom_type_to_query');

function hdb_add_custom_type_to_query( $notused ){ //Authors Page
     if (! is_admin() ){
        global $wp_query;
        if ( is_author()){
            $wp_query->set( 'post_type',  array( BP_COURSE_SLUG ) );
        }
     }
}

add_action('bp_members_directory_member_types','bp_course_instructor_member_types');

function bp_course_instructor_member_types(){
	?>
		<li id="members-instructors"><a href="#"><?php printf( __( 'All Instructors <span>%s</span>', 'vibe' ), bp_get_total_instructor_count() ); ?></a></li>
	<?php
}
?>