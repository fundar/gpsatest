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


add_action('bp_activity_register_activity_actions','bp_course_register_actions');
function bp_course_register_actions(){
	global $bp;
	$bp_course_action_desc=array(
		'remove_from_course' => __( 'Removed a student from Course', 'vibe' ),
		'submit_course' => __( 'Student submitted a Course', 'vibe' ),
		'start_course' => __( 'Student started a Course', 'vibe' ),
		'submit_quiz' => __( 'Student submitted a Quiz', 'vibe' ),
		'start_quiz' => __( 'Student started a Course', 'vibe' ),
		'unit_complete' => __( 'Student submitted a Course', 'vibe' ),
		'reset_course' => __( 'Course reset for Student', 'vibe' ),
		'bulk_action' => __( 'Bulk action by instructor', 'vibe' ),
		'course_evaluated' => __( 'Course Evaluated for student', 'vibe' ),
		'student_badge'=> __( 'Student got a Badge', 'vibe' ),
		'student_certificate' => __( 'Student got a certificate', 'vibe' ),
		'quiz_evaluated' => __( 'Quiz Evaluated for student', 'vibe' ),
		'subscribe_course' => __( 'Student subscribed for course', 'vibe' ),
		);
	foreach($bp_course_action_desc as $key => $value){
		bp_activity_set_action($bp->activity->id,$key,$value);	
	}
}

add_filter( 'woocommerce_get_price_html', 'course_subscription_filter',100,2 );
function course_subscription_filter($price,$product){

	$subscription=get_post_meta($product->id,'vibe_subscription',true);

		if(vibe_validate($subscription)){
			$x=get_post_meta($product->id,'vibe_duration',true);
			$product_duration_parameter = apply_filters('vibe_product_duration_parameter',86400);
			$t=$x*$product_duration_parameter;

			if($x == 1){
				$price = $price .'<span class="subs"> '.__('per','vibe').' '.tofriendlytime($t).'</span>';
			}else{
				$price = $price .'<span class="subs"> '.__('per','vibe').' '.tofriendlytime($t).'</span>';
			}
		}
		return apply_filters( 'woocommerce_get_price', $price );
}




add_action('woocommerce_after_add_to_cart_button','bp_course_subscription_product');
function bp_course_subscription_product(){
	global $product;
	$check_susbscription=get_post_meta($product->id,'vibe_subscription',true);
	if(vibe_validate($check_susbscription)){
		$duration=get_post_meta($product->id,'vibe_duration',true);	
		$product_duration_parameter = apply_filters('vibe_product_duration_parameter',86400);
		$t=tofriendlytime($duration*$product_duration_parameter);
		echo '<div id="duration"><strong>'.__('SUBSCRIPTION FOR','vibe').' '.$t.'</strong></div>';
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


		if(vibe_validate($subscribed) ){

			$duration=get_post_meta($product_id,'vibe_duration',true);
			$product_duration_parameter = apply_filters('vibe_product_duration_parameter',86400); // Product duration for subscription based
			$t=time()+$duration*$product_duration_parameter;

			foreach($courses as $course){
				update_post_meta($course,$user_id,0);
				update_user_meta($user_id,$course,$t);
				$group_id=get_post_meta($course,'vibe_group',true);
				if(isset($group_id) && $group_id !='')
				groups_join_group($group_id, $user_id );  

				$durationtime = $duration.' '.calculate_duration_time($product_duration_parameter);
				if($duration == '9999')
					$durationtime = __('Unlimited Duration','vibe');
				
				bp_course_record_activity(array(
				      'action' => __('Student subscribed for course ','vibe').get_the_title($course),
				      'content' => __('Student ','vibe').bp_core_get_userlink( $user_id ).__(' subscribed for course ','vibe').get_the_title($course).__(' for ','vibe').$durationtime,
				      'type' => 'subscribe_course',
				      'item_id' => $course,
				      'primary_link'=>get_permalink($course),
				      'secondary_item_id'=>$user_id
		        ));      
			}
		}else{	
			if(isset($courses) && is_array($courses)){
			foreach($courses as $course){
				$duration=get_post_meta($course,'vibe_duration',true);
				$course_duration_parameter = apply_filters('vibe_course_duration_parameter',86400); // Course duration for subscription based
				$t=time()+$duration*$course_duration_parameter;
				update_post_meta($course,$user_id,0);
				update_user_meta($user_id,$course,$t);
				$group_id=get_post_meta($course,'vibe_group',true);
				if(isset($group_id) && $group_id !='')
				groups_join_group($group_id, $user_id );

				$durationtime = $duration.' '.calculate_duration_time($product_duration_parameter);
				if($duration == '9999')
					$durationtime = __('Unlimited Duration','vibe');

				bp_course_record_activity(array(
				      'action' => __('Student subscribed for course ','vibe').get_the_title($course),
				      'content' => __('Student ','vibe').bp_core_get_userlink( $user_id ).__(' subscribed for course ','vibe').get_the_title($course).__(' for ','vibe').$durationtime,
				      'type' => 'subscribe_course',
				      'item_id' => $course,
				      'primary_link'=>get_permalink($course),
				      'secondary_item_id'=>$user_id
		        )); 
				}
			}
		}
		
	}
	 
}

add_action('woocommerce_order_status_cancelled','bp_course_disable_access');
add_action('woocommerce_order_status_refunded','bp_course_disable_access');

function bp_course_disable_access($order_id){
	$order = new WC_Order( $order_id );

	$items = $order->get_items();
	$user_id=$order->user_id;
	foreach($items as $item){
		$product_id = $item['product_id'];
		$subscribed=get_post_meta($product_id,'vibe_subscription',true);
		$courses=vibe_sanitize(get_post_meta($product_id,'vibe_courses',false));

			if(isset($courses) && is_array($courses)){
			foreach($courses as $course){
				delete_post_meta($course,$user_id);
				delete_user_meta($user_id,$course);
				$group_id=get_post_meta($course,'vibe_group',true);
				groups_remove_member($user_id,$group_id);

				bp_course_record_activity(array(
			      'action' => __('Student ','vibe').bp_core_get_userlink($user_id).__(' removed from course ','vibe').get_the_title($course_id),
			      'content' => __('Student ','vibe').bp_core_get_userlink($user_id).__(' removed from the course ','vibe').get_the_title($course_id),
			      'type' => 'remove_from_course',
			      'primary_link' => get_permalink($course_id),
			      'item_id' => $course_id,
			      'secondary_item_id' => $user_id
			    ));
				}
			}
		} 
}

add_action('bp_members_directory_member_types','bp_course_instructor_member_types');

function bp_course_instructor_member_types(){
	?>
		<li id="members-instructors"><a href="#"><?php printf( __( 'All Instructors <span>%s</span>', 'vibe' ), bp_get_total_instructor_count() ); ?></a></li>
	<?php
}


add_filter('bp_course_admin_before_course_students_list','bp_course_admin_search_course_students',10,2);
function bp_course_admin_search_course_students($students,$course_id){

	echo '<form method="post">
			<input type="text" name="search" value="'.$_POST['search'].'" placeholder="'.__('Enter student name/email','vibe').'" class="input" />
			<input type="submit" value="'.__('Search','vibe').'" />
		  </form>';
    if(isset($_POST['search'])){

    	$args = array(
			'search'         => $_POST['search'],
			'search_columns' => array( 'login', 'email','nicename' ),
			'fields' => array('ID'),
			'meta_query' => array(
				array(
					'key' => $course_id,
					'compare' => 'EXISTS'
					)
				),
		);
    	$user_query = new WP_User_Query( $args );
		if(count($user_query)){
			$students=array();
			foreach($user_query as $user){
				if(is_array($user))
					if(is_object($user[0]) && isset($user[0]->ID))
						$students[]=$user[0]->ID;
			}
		}
    }
	return $students;
}

?>