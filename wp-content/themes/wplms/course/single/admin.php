<?php
$user_id=get_current_user_id();
?>
<div class="item-list-tabs no-ajax " id="subnav" role="navigation">
	<ul>
		<li class="course_sub_action <?php if(!isset($_GET['submissions']) && !isset($_GET['stats']) && !isset($_GET['activity'])) echo 'current'; ?>">
			<a id="course_members" href="?action=admin">Members</a>
		</li>	
		<li class="course_sub_action <?php if(isset($_GET['submissions'])) echo 'current'; ?>">
			<a id="course_submissions" href="?action=admin&submissions">Submissions</a>
		</li>
		<li class="course_sub_action <?php if(isset($_GET['activity'])) echo 'current'; ?>">
			<a id="course_activity" href="?action=admin&activity">Activity</a>
		</li>
		<li class="course_sub_action <?php if(isset($_GET['stats'])) echo 'current'; ?>">
			<a id="course_stats" href="?action=admin&stats">Stats</a>
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

	$students_undertaking=bp_course_get_students_undertaking();
	//vibe_sanitize(get_post_meta(get_the_ID(),'vibe_students_undertaking',false));
	if(count($students_undertaking)>0){
		echo '<ul class="course_students">';
		foreach($students_undertaking as $student){

			if (function_exists('bp_get_profile_field_data')) {
			    $bp_name = bp_core_get_userlink( $student );
			    $bp_location = bp_get_profile_field_data('field=Location&user_id='.$student);
			    
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
				    		<li><a class="tip remove_user_course" data-course="'.get_the_ID().'" data-user="'.$student.'" title="'.__('Remove User from this Course','vibe').'"><i class="icon-x"></i></a></li>
				    	  </ul>';
				    echo '</li>';
			    }
			}
		}
		echo '</ul>';
		wp_nonce_field('vibe_security','security'); // Just random text to verify

		echo '<div class="course_bulk_actions">
				<strong>BULK ACTIONS</strong> 
				 <a href="#" class="send_course_message"><i class="icon-letter-mail-1"></i> Message</a>
					<div class="course_message">
						<input type="text" id="bulk_subject" class="form_field" placeholder="Type Message Subject">
						<textarea id="bulk_message" placeholder="Type Message"></textarea>
				 		<a href="#" id="send_course_message" class="button full">Send Message</a>
				 		<input type="hidden" id="sender" value="'.$user_id.'" />
				 	</div>
			</div>';
			wp_nonce_field('security','bulk_message');
	}
  }
?>