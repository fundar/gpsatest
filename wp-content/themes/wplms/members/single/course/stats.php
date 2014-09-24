<?php 
do_action( 'bp_before_course_stats' ); 
$user_id=get_current_user_id();
$user_courses=get_posts('post_type=course&numberposts=999&meta_key='.$user_id);
	

echo '<ul id="userstats">';
foreach($user_courses as $course){


$course_complete_status=get_post_meta($course->ID,$user_id,true);
$user_course_status=get_user_meta($user_id,$course->ID,true);

echo '<li>
		<div class="course_avatar">'.bp_course_get_avatar("id=$course->ID&size=thumbnail").'</div>
	  	<h4>'.bp_course_get_course_title("id=$course->ID").'</a></h4>';

$cavg=get_post_meta($course->ID,'average',true);

if(!$cavg)$cavg= __('NOT AVAILABLE','vibe');


echo '<span>'.__('AVERAGE PERCENTAGE : ','vibe').'<span>'.$cavg.'</span></span>';


if($course_complete_status > 1){

	$curriculum=vibe_sanitize(get_post_meta($course->ID,'vibe_course_curriculum',false));

	$average=array();
	echo '<a class="showhide_indetails"><i class="icon-plus-1"></i></a>';
	$myavg=get_post_meta($course->ID,$user_id,true);
	if(!isset($myavg) || $myavg == '')
		$myavg = __('TAKING','vibe');

	echo '<strong>'.__('MY COURSE PERCENTAGE : ','vibe').'<span>'.apply_filters('wplms_course_marks',$myavg.'/100',$course->ID).'</span></strong>';
	echo '<ul class="in_details">';
	if(isset($curriculum) && is_array($curriculum))
	foreach($curriculum as $c){
		if(is_numeric($c)){
			
			if(get_post_type($c) == 'quiz'){

				$myavg=get_post_meta($c,$user_id,true);
				$avg=get_post_meta($c,'average',true);

				$ques = vibe_sanitize(get_post_meta($c,'vibe_quiz_questions',false));
				if(isset($ques['marks']) && is_array($question['marks']))
				$max= array_sum($ques['marks']);
				
				
				if(isset($myavg) && $myavg !=''){
					echo '<li>'.__('Average Marks in','vibe').' '.get_the_title($c).' : '.$avg.'';
					echo '<strong>'.__('My Marks','vibe').' : '.$myavg.' / '.$max.'</strong></li>';
				}
			}
		}
	}
	echo '</ul>';
}else{
	if($course_complete_status == 1){
		if($user_course_status < time()){
			echo '<strong>'.__('Course Expired','vibe').'</strong>';
		}else{
			echo '<strong>'.__('User is taking this Course','vibe').'</strong>';
		}
	}else
		echo '<strong>'.__('User is taking this Course','vibe').'</strong>';
}


echo '</li>';

}

echo '</ul>';
do_action( 'bp_before_course_stats' ); 

 ?>