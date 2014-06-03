<?php


$course_id=get_the_ID();
global $wpdb;

echo '<div class="submissions"><h4 class="minmax">';
_e('QUIZ SUBMISSIONS','vibe');
echo '<i class="icon-plus-1"></i></h4>';
$curriculum=vibe_sanitize(get_post_meta(get_the_ID(),'vibe_course_curriculum',false));
foreach($curriculum as $c){
	if(is_numeric($c)){
		if(get_post_type($c) == 'quiz'){
			// RUN META QUERY : GET ALL POST META WITH VALUE 0 FOR UNCHECKED QUIZ, THE KEY IS THE USERID
			$members_unchecked_quiz = $wpdb->get_results( "select meta_key from $wpdb->postmeta where meta_value = '0' && post_id = $c", ARRAY_A); // Internal Query

			if(count($members_unchecked_quiz)){
				echo '<ul class="quiz_students">';
				foreach($members_unchecked_quiz as $unchecked_quiz ){
					if(is_numeric($unchecked_quiz['meta_key'])){
					$member_id=$unchecked_quiz['meta_key'];
					$bp_name = bp_core_get_userlink( $member_id );
			    	$bp_location = bp_get_profile_field_data('field=Location&user_id='.$member_id);
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
				}
				echo '</ul>';
				
			}
		}
	}
}
wp_nonce_field('vibe_quiz','qsecurity');
echo '</div>';

echo '<div class="submissions"><h4 class="minmax">';
_e('COURSE SUBMISSIONS','vibe');
echo '<i class="icon-plus-1"></i></h4>';
// ALL MEMBERS who SUBMITTED COURSE
$members_submit_course = $wpdb->get_results( "select meta_key from $wpdb->postmeta where meta_value = '2' && post_id = $course_id", ARRAY_A); // Internal Query
if(count($members_submit_course)){
	echo '<ul class="course_students">';
	foreach($members_submit_course as $submit_course ){

		if(is_numeric($submit_course['meta_key'])){
		$member_id=$submit_course['meta_key'];

		$bp_name = bp_core_get_userlink( $member_id );
    	$bp_location = bp_get_profile_field_data('field=Location&user_id='.$member_id);

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
	}
	echo '</ul>';
	wp_nonce_field($course_id,'security');
}
echo '</div>';

?>