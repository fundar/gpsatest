<?php
global $post;
$id= get_the_ID();

?>

<div class="course_title">
	<h2><?php  _e('Course Curriculum','vibe'); ?></h2>
</div>

<div class="course_curriculum">
<?php
$course_curriculum = vibe_sanitize(get_post_meta($id,'vibe_course_curriculum',false));

if(isset($course_curriculum)){


	foreach($course_curriculum as $lesson){
		if(is_numeric($lesson)){
			$icon = get_post_meta($lesson,'vibe_type',true);

			if(get_post_type($lesson) == 'quiz')
				$icon='task';

					$href=get_the_title($lesson);
					$free='';
					$free = get_post_meta($lesson,'vibe_free',true);

					$curriculum_course_link = apply_filters('wplms_curriculum_course_link',0);
					if(vibe_validate($free) || ($post->post_author == get_current_user_id()) || current_user_can('manage_options') || $curriculum_course_link){
						$href=apply_filters('wplms_course_curriculum_free_access','<a href="'.get_permalink($lesson).'?id='.get_the_ID().'">'.get_the_title($lesson).(vibe_validate($free)?'<span>'.__('FREE','vibe').'</span>':'').'</a>',$lesson,$free);
					}

			echo '<div class="course_lesson">

					<i class="icon-'.$icon.'"></i><h6>'.$href.'</h6>';
					$minutes=0;
					$hours=0;
					$min = get_post_meta($lesson,'vibe_duration',true);
					$minutes = $min;
					if($minutes){
						if($minutes > 60){
							$hours = intval($minutes/60);
							$minutes = $minutes - $hours*60;
						}
					echo apply_filters('wplms_curriculum_time_filter','<span><i class="icon-clock"></i> '.(isset($hours)?$hours.__(' Hours','vibe'):'').' '.$minutes.' '.__('minutes','vibe').'</span>',$min);
					}	

					echo '</div>';
		}else{
			echo '<h5 class="course_section">'.$lesson.'</h5>';
		}
	}
}
	?>
</div>

<?php

?>