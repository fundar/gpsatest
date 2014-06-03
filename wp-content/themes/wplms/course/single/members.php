<?php
global $post;
$students=get_post_meta(get_the_ID(),'vibe_students',true);
?>

<div class="course_title">
	<h1><?php the_title(); ?></h1>
</div>
<h4 class="total_students"><?php _e('Total number of Students in course','vibe'); ?><span><?php echo $students; ?></span></h4>
<h3><?php _e('Students Currently taking this course','vibe'); ?></h3>
<?php

$students_undertaking= bp_course_get_students_undertaking(); 
//vibe_sanitize(get_post_meta(get_the_ID(),'vibe_students_undertaking',false));

if(count($students_undertaking)>0){
	echo '<ul class="course_students">';
	foreach($students_undertaking as $student){

		if (function_exists('bp_get_profile_field_data')) {
		    $bp_name = bp_core_get_userlink( $student );
		    $bp_location = bp_get_profile_field_data('field=Location&user_id='.$student);
		    
		    if ($bp_name) {
		    	echo '<li>';
		    	echo get_avatar($student);
		    	echo '<h6>'. $bp_name . '</h6>';
			    if ($bp_location) {
			    	echo '<span>'. $bp_location . '</span>';
			    }
			    echo '</li>';
		    }
		    
		}
	}
	echo '</ul>';
}
?>