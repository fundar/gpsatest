<?php
$user_id=get_current_user_id();
?>
<div class="item-list-tabs no-ajax " id="subnav" role="navigation">
	<ul>
		<li class="course_sub_action <?php if(!isset($_GET['submissions']) && !isset($_GET['stats']) && !isset($_GET['activity'])) echo 'current'; ?>">
			<a id="course_members" href="?action=admin"><?php _e('Members','vibe'); ?></a>
		</li>	
		<li class="course_sub_action <?php if(isset($_GET['submissions'])) echo 'current'; ?>">
			<a id="course_submissions" href="?action=admin&submissions"><?php _e('Submissions','vibe'); ?></a>
		</li>
		<li class="course_sub_action <?php if(isset($_GET['activity'])) echo 'current'; ?>">
			<a id="course_activity" href="?action=admin&activity"><?php _e('Activity','vibe'); ?></a>
		</li>
		<li class="course_sub_action <?php if(isset($_GET['stats'])) echo 'current'; ?>">
			<a id="course_stats" href="?action=admin&stats"><?php _e('Stats','vibe'); ?></a>
		</li>
	</ul>
</div>
<div id="message" class="info vnotice">
  <?php do_action('bp_course_custom_notice_instructors'); ?>
</div>
<?php

if(isset($_GET['activity'])){

	locate_template( array( 'course/single/activity.php'  ), true );

}else if(isset($_GET['submissions'])){

locate_template( array( 'course/single/submissions.php'  ), true );

}else if(isset($_GET['stats'])){

	locate_template( array( 'course/single/stats.php'  ), true );
}else{



	global $post;
	$students=get_post_meta(get_the_ID(),'vibe_students',true);
?>	
	<h4 class="total_students"><?php _e('Total number of Students in course','vibe'); ?><span><?php echo $students; ?></span></h4>
	<h3><?php _e('Students Currently taking this course','vibe'); ?></h3>
	<?php

	$students_undertaking=apply_filters('bp_course_admin_before_course_students_list',bp_course_get_students_undertaking(),get_the_ID());
	
	echo '<ul class="course_students">';
	if(count($students_undertaking)>0){
		foreach($students_undertaking as $student){

			if (function_exists('bp_get_profile_field_data')) {
			    $bp_name = bp_core_get_userlink( $student );

			    $bp_location='';

			    $ifield = vibe_get_option('student_field');
				if(!isset($field) || $field =='')$field='Location';
			    if(bp_is_active('xprofile'))
			    $bp_location = bp_get_profile_field_data('field='.$field.'&user_id='.$student);
			    
			    if ($bp_name) {
			    	echo '<li id="s'.$student.'"><input type="checkbox" class="member" value="'.$student.'"/>';
			    	echo get_avatar($student);
			    	echo '<h6>'. $bp_name . '</h6>';
				    if ($bp_location) {
				    	echo '<span>'. $bp_location . '</span>';
				    }
				    // PENDING AJAX SUBMISSIONS
				    echo '<ul> 
				    		<li><a class="tip reset_course_user" data-course="'.get_the_ID().'" data-user="'.$student.'" title="'.__('Reset Course for User','vibe').'"><i class="icon-reload"></i></a></li>
				    		<li><a class="tip course_stats_user" data-course="'.get_the_ID().'" data-user="'.$student.'" title="'.__('See Course stats for User','vibe').'"><i class="icon-bars"></i></a></li>
				    		<li><a href="?action=admin&activity&student='.$student.'" class="tip" title="'.__('See User Activity in Course','vibe').'"><i class="icon-atom"></i></a></li>
				    		<li><a class="tip remove_user_course" data-course="'.get_the_ID().'" data-user="'.$student.'" title="'.__('Remove User from this Course','vibe').'"><i class="icon-x"></i></a></li>
				    		'.do_action('wplms_course_admin_members_functions').'
				    	  </ul>';
				    echo '</li>';
			    }
			}
		}
		wp_nonce_field('vibe_security','security'); // Just random text to verify
		}else{
			
			echo '<div id="message" class="notice"><p>'.__('No Students found.','vibe').'</p></div>';
		}
		echo '</ul>';
		
		echo '<div class="course_bulk_actions">
				<strong>'.__('BULK ACTIONS','vibe').'</strong> ';

		do_action('wplms_course_admin_bulk_actions');
		wp_nonce_field('security'.get_the_ID(),'bulk_action');
		
		echo '</div>';
		if(count($students_undertaking)>0){
			echo bp_course_paginate_students_undertaking();	
		}
	
  }
?>