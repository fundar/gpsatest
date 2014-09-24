<?php
$user_id=get_current_user_id();
?>
<div class="item-list-tabs no-ajax " id="subnav" role="navigation">
	<ul>
		<li class="course_sub_action <?php if(!isset($_GET['submissions']) && !isset($_GET['stats'])) echo 'current'; ?>">
			<a id="course" href="?action=admin">Members</a>
		</li>	
		<li class="course_sub_action <?php if(isset($_GET['submissions'])) echo 'current'; ?>">
			<a id="course" href="?action=admin&submissions">Submissions</a>
		</li>
		<li class="course_sub_action <?php if(isset($_GET['stats'])) echo 'current'; ?>">
			<a id="course" href="?action=admin&stats">Stats</a>
		</li>
	</ul>
</div>
<div id="message" class="info vnotice">
</div>
<?php

if(isset($_GET['submissions'])){

$course_id=get_the_ID();
global $wpdb;

echo '<div class="submissions"><h4 class="minmax">';
_e('QUIZ SUBMISSIONS');
echo '<i class="icon-plus-1"></i></h4>';
$curriculum=vibe_sanitize(get_post_meta(get_the_ID(),'vibe_course_curriculum',false));
foreach($curriculum as $c){
	if(is_numeric($c)){
		if(get_post_type($c) == 'quiz'){
			// RUN META QUERY : GET ALL POST META WITH VALUE 0 FOR UNCHECKED QUIZ, THE KEY IS THE USERID
			$members_unchecked_quiz = $wpdb->get_results( $wpdb->prepare("select meta_key from {$wpdb->postmeta} where meta_value = '0' && post_id = %d",$c), ARRAY_A);

			if(count($members_unchecked_quiz)){
				echo '<ul class="quiz_students">';
				foreach($members_unchecked_quiz as $unchecked_quiz ){
					$member_id=$unchecked_quiz['meta_key'];
					$bp_name = bp_core_get_userlink( $member_id );
					$bp_location ='';

					if(function_exists('vibe_get_option'))
					$field = vibe_get_option('student_field');

					if(bp_is_active('xprofile'))
			    	$bp_location = bp_get_profile_field_data('field='.$field.'&user_id='.$member_id);
					echo '<li id="s'.$member_id.'">';
			    	echo get_avatar($member_id);
			    	echo '<h6>'. $bp_name . '</h6>';
				    if ($bp_location) {
				    	echo '<span>'. $bp_location . '</span>';
				    }
				    // PENDING AJAX SUBMISSIONS
				    echo '<ul> 
				    		<li><a class="tip reset_quiz_user" data-quiz="'.$c.'" data-user="'.$member_id.'" title="'.__('Reset Quiz for User','vibe').'"><i class="icon-reload"></i></a></li>
				    		<li><a class="tip evaluate_quiz_user" data-quiz="'.$c.'" data-user="'.$member_id.'" title="'.__('Evaluate Quiz for User','vibe').'"><i class="icon-check-clipboard-1"></i></a></li>
				    	  </ul>';
				    echo '</li>';
				}
				echo '</ul>';
				
			}
		}
	}
}
wp_nonce_field('vibe_quiz','qsecurity');
echo '</div>';

echo '<div class="submissions"><h4 class="minmax">';
_e('COURSE SUBMISSIONS');
echo '<i class="icon-plus-1"></i></h4>';
// ALL MEMBERS who SUBMITTED COURSE
$members_submit_course = $wpdb->get_results( "select meta_key from $wpdb->postmeta where meta_value = '2' && post_id = $course_id", ARRAY_A);
if(count($members_submit_course)){
	echo '<ul class="course_students">';
	foreach($members_submit_course as $submit_course ){

		$member_id=$submit_course['meta_key'];

		$bp_name = bp_core_get_userlink( $member_id );

		if(function_exists('vibe_get_option'))
		$field = vibe_get_option('student_field');

		if(bp_is_active('xprofile'))
    	$bp_location = bp_get_profile_field_data('field='.$field.'&user_id='.$member_id);

		echo '<li id="s'.$member_id.'">';
    	echo get_avatar($member_id);
    	echo '<h6>'. $bp_name . '</h6>';
	    if ($bp_location) {
	    	echo '<span>'. $bp_location . '</span>';
	    }
	    // PENDING AJAX SUBMISSIONS
	    echo '<ul> 
	    		<li><a class="tip evaluate_course_user" data-course="'.$course_id.'" data-user="'.$member_id.'" title="'.__('Evaluate Course for User','vibe').'"><i class="icon-check-clipboard-1"></i></a></li>
	    	  </ul>';
	    echo '</li>';
	}
	echo '</ul>';
	wp_nonce_field($course_id,'security');
}
echo '</div>';
}else{
	if(isset($_GET['stats'])){


		$course_id=get_the_ID();
		$students=get_post_meta($course_id,'vibe_students',true);

		$avg=get_post_meta($course_id,'average',true);
		$pass=get_post_meta($course_id,'pass',true);
		$badge=get_post_meta($course_id,'badge',true);


		echo '<div class="course_grade">
				<ul>
					<li>'.__('Total Number of Students who took this course','vibe').' <strong>'.$students.'</strong></li>
					<li>'.__('Average Percentage obtained by Students','vibe').' <strong>'.$avg.' <span>out of 100</span></strong></li>
					<li>'.__('Number of Students who got a Badge','vibe').' <strong>'.$badge.'</strong></li>
					<li>'.__('Number of Passed Students','vibe').' <strong>'.$pass.'</strong></li>
					<li>'.__('Number of Students who did not pass ','vibe').' <strong>'.($students-$pass).'</strong></li>
				</ul>
			</div>';
		echo '<div id="average">'.__('Average Marks obtained by Students','vibe').'<input type="text" class="dial" data-max="100" value="'.$avg.'"></div>';
		echo '<div id="pass">'.__('Number of Passed Students','vibe').' <input type="text" class="dial" data-max="'.$students.'" value="'.$pass.'"></div>';	
		echo '<div id="badge">'.__('Number of Students who got a Badge','vibe').'<input type="text" class="dial" data-max="'.$students.'" value="'.$badge.'"></div>';

		
		
		
		$curriculum=vibe_sanitize(get_post_meta(get_the_ID(),'vibe_course_curriculum',false));
		foreach($curriculum as $c){
			if(is_numeric($c)){
				if(get_post_type($c) == 'quiz'){
					$qavg=get_post_meta($c,'average',true);

					$ques = vibe_sanitize(get_post_meta($c,'vibe_quiz_questions',false));
					$qmax= array_sum($ques['marks']);

					echo '<div class="course_quiz">
							<h5>'.__('Average Marks in Quiz ','vibe').' '.get_the_title($c).'</h5>
							<input type="text" class="dial" data-max="'.$qmax.'" value="'.$qavg.'">
						</div>';			
				}
			}
		}
		

		echo '<div class="calculate_panel"><strong>'.__('Calculate :','vibe').'</strong>';
			echo '<a href="#" id="calculate_avg_course" data-courseid="'.get_the_ID().'" class="tip" title="'.__('Calculate Statistics for Course','vibe').'"> <i class="icon-calculator"></i> </a>';
			wp_nonce_field('vibe_security','security'); // Just random text to verify
		echo '</div>';

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

			    if(function_exists('vibe_get_option'))
				$field = vibe_get_option('student_field');

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
}

?>