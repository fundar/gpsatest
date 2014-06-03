<?php do_action( 'bp_before_course_stats' ); ?>
<?php
$user_id=get_current_user_id();
$user_courses=get_posts('post_type=course&numberposts=999&meta_key='.$user_id);
	

echo '<ul id="userstats">';
foreach($user_courses as $course){


$course_complete_status=get_post_meta($course->ID,$user_id,true);


echo '<li>
				<div class="course_avatar">'.bp_course_get_avatar("id=$course->ID&size=thumbnail").'</div>
			  	<h4>'.bp_course_get_course_title("id=$course->ID").'</a></h4>';

$cavg=get_post_meta($course->ID,'average',true);

if(!$cavg)$cavg= 'NOT AVAILABLE';


echo '<span>'.__('AVERAGE PERCENTAGE : ').'<span>'.$cavg.'</span></span>';
if($course_complete_status){

	
	$curriculum=vibe_sanitize(get_post_meta($course->ID,'vibe_course_curriculum',false));

	$average=array();
	echo '<a class="showhide_indetails"><i class="icon-plus-1"></i></a>';
	$myavg=get_post_meta($course->ID,$user_id,true);
	if(!isset($myavg) || $myavg == '')
		$myavg = __('TAKING','vibe');

	echo '<strong>'.__('MY COURSE PERCENTAGE : ').'<span>'.$myavg.'</span></strong>';
	echo '<ul class="in_details">';
	foreach($curriculum as $c){
		if(is_numeric($c)){
			
			if(get_post_type($c) == 'quiz'){

				$myavg=get_post_meta($c,$user_id,true);
				$avg=get_post_meta($c,'average',true);

				$ques = vibe_sanitize(get_post_meta($c,'vibe_quiz_questions',false));
				$max= array_sum($ques['marks']);
				
				
				if(isset($myavg) && $myavg !=''){
					echo '<li>Average Marks in '.get_the_title($c).' : '.$avg.'';
					echo '<strong>My Marks : '.$myavg.' / '.$max.'</strong></li>';
				}
			}
		}
	}
	echo '</ul>';
}else{
	echo '<strong>'.__('User is taking this Course','vibe').'</strong>';
}


echo '</li>';

}

echo '</ul>';
?>
<?php do_action( 'bp_before_course_stats' ); ?>