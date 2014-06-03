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
					if(isset($free) && $free !='' && $free !='H'){
						$href='<a href="'.get_permalink($lesson).'?id='.get_the_ID().'">'.get_the_title($lesson).'<span>FREE</span></a>';
					}

			echo '<div class="course_lesson">

					<i class="icon-'.$icon.'"></i><h6>'.$href.'</h6>';
					$minutes=0;
					$minutes = get_post_meta($lesson,'vibe_duration',true);

					if($minutes){
						if($minutes > 60){
							$hours = intval($minutes/60);
							$minutes = $minutes - $hours*60;
						}
					echo '<span><i class="icon-clock"></i> '.(isset($hours)?$hours.' Hours':'').' '.$minutes.' minutes</span>';
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