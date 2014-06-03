<?php
/**
 * Template Name: Start Course Page
 */


$user_id = get_current_user_id();

$expire=time()+24*3600; // One Day logged in Limit for the course

if ( isset($_POST['start_course']) && wp_verify_nonce($_POST['start_course'],'start_course') ){
	$course_id=$_POST['course_id'];
	$coursetaken=1;
    $cflag=0;
    $precourse=get_post_meta($course_id,'vibe_pre_course',true);
    if(isset($precourse) && $precourse !=''){
        $preid=get_post_meta($precourse,$user_id,true);
        if(isset($preid) && $preid !='' && $preid > 2){
            $cflag=1;
        }
    }else{
        $cflag=1;
    }

    if($cflag){
        setcookie('course',$course_id,$expire,'/');
        $students=get_post_meta($course_id,'vibe_students',true);
        $students++;
        update_post_meta($course_id,'vibe_students',$students);
        update_post_meta($course_id,$user_id,1); // COURSE STARTED

        bp_course_record_activity(array(
          'action' => 'Student started course '.get_the_title($course_id),
          'content' => 'Student '.bp_core_get_userlink( $user_id ).' started the course '.get_the_title($course_id),
          'type' => 'start_course',
          'item_id' => $course_id,
          'primary_link'=>get_permalink($course_id),
          'secondary_item_id'=>$user_id
        ));

    }else{
        
        header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error=precourse');
        
    }

	

}else if ( isset($_POST['continue_course']) && wp_verify_nonce($_POST['continue_course'],'continue_course') ){
	$course_id=$_POST['course_id'];
	$coursetaken=get_user_meta($user_id,$course_id,true);
	setcookie('course',$course_id,$expire,'/');
}else{
	if(isset($_COOKIE['course'])){
		$course_id=$_COOKIE['course'];
		$coursetaken=1;
	}else
		wp_die( __('This Course can not be taken. Contact Administrator.','vibe'), 'Contact Admin', array(500,true) );
}
get_header('buddypress');
$course_curriculum=vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));
if(isset($coursetaken) && $coursetaken){
	foreach($course_curriculum as $uid){
		if(is_numeric($uid) && (get_post_type($uid) != 'quiz')){
			$unittaken=get_user_meta($user_id,$uid,true);
			if(!isset($unittaken) || !$unittaken){
				break;
			}
			$unit_id=$uid; // Last un finished unit
		}
	}
}	


if(!isset($unit_id)) $unit_id='';

if ( have_posts() ) : while ( have_posts() ) : the_post();

?>
<section id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="unit_content">
                <div class="unit_title">
                	<?php
            		if(isset($unit_id)){
                		the_unit_tags($unit_id);
                		the_unit_instructor($unit_id);
                	}
                	?>
                	<h1><?php 
                    if(isset($course_id)){
                    	echo get_the_title($unit_id);
                    }else
                    	the_title();
                     ?></h1>
                    <?php
                    	$minutes=0;
						$minutes = get_post_meta($unit_id,'vibe_duration',true);
						if($minutes){
							if($minutes > 60){
								$hours = intval($minutes/60);
								$minutes = $minutes - $hours*60;
							}
						echo '<span><i class="icon-clock"></i> '.(isset($hours)?$hours.' Hours':'').' '.$minutes.' minutes</span>';
						}
					if(isset($course_id)){
						echo '<h3>';
                    	the_sub_title($unit_id);
                    	echo '</h3>';
                    }else{
                    	echo '<h3>';
                    	the_sub_title();	
                    	echo '</h3>';
                    }	
                    ?>	
                </div>
                    <?php

                    if(isset($coursetaken) && $coursetaken && $unit_id !=''){
                    	if(isset($course_curriculum) && is_array($course_curriculum)){
							the_unit($unit_id);
                    	}else{
                    		echo '<h3>';
                    		_e('Course Curriculum Not Set.','vibe');
                    		echo '</h3>';
                    	}
                    }else{
                        the_content();
                    }
                    
                endwhile;
                endif;
                ?>
                <?php
                $units=array();
                  foreach($course_curriculum as $key=>$curriculum){
                    if(is_numeric($curriculum)){
                        $units[]=$curriculum;
                    }
                  }

            	  $next=$k+1;
                  $prev=$k-1;
                  $max=count($units)-1;

                  echo  '<div class="unit_prevnext"><div class="col-md-3">';
                  if($prev >=0){

                    if(get_post_type($units[$prev]) == 'quiz')
                      echo '<a href="'.get_permalink($units[$prev]).'" class=" unit_button">Back to Quiz</a>';
                    else    
                      echo '<a href="#" id="prev_unit" data-unit="'.$units[$prev].'" class="unit unit_button">Previous Unit</a>';
                  }
                  echo '</div>';

                  echo  '<div class="col-md-6">
                          '.((isset($done_flag) && $done_flag)?'': '<a href="#" id="mark-complete" data-unit="'.$units[($k)].'" class="unit_button">'.__('Mark this Unit Complete','vibe').'</a>').
                        '</div>';

                  echo  '<div class="col-md-3">';

                  if($next <= $max){

                    if(get_post_type($units[$next]) == 'quiz')
                      echo '<a href="'.get_permalink($units[$next]).'"class=" unit_button">Proceed to Quiz</a>';
                    else  
                      echo '<a href="#" id="next_unit" data-unit="'.$units[$next].'" class="unit unit_button">Next Unit</a>';
                  }
                  echo '</div></div>';
	            ?>
                </div>
                <?php
                	wp_nonce_field('security','hash');
                	echo '<input type="hidden" id="course_id" name="course" value="'.$course_id.'" />';
                ?>
            </div>
            <div class="col-md-3">
            	<div class="course_time">
            		<?php
            			the_course_time("course_id=$course_id&user_id=$user_id");
            		?>
            	</div>
            	<div class="course_timeline">
            		<ul>
            		<?php

            			if(isset($course_curriculum) && is_array($course_curriculum)){
                    		foreach($course_curriculum as $unit_id){
								if(is_numeric($unit_id)){
									$unittaken=get_user_meta($user_id,$unit_id,true);
									$class='';
									if($uid == $unit_id){
										$class .=' active';
									}
									if(isset($unittaken) && $unittaken){
										$class .=' done';
									}
									echo '<li id="unit'.$unit_id.'" class="unit_line '.$class.'"><span></span> <a href="'.get_permalink($unit_id).'" class="'.((get_post_type($unit_id) == 'quiz')?'quiz':'unit').'" data-unit="'.$unit_id.'">'.get_the_title($unit_id).'</a></li>';
								}else{
									echo '<li class="section"><h4>'.$unit_id.'</h4></li>';
								}
							}
                    	}else{
                    		echo '<li><h3>';
                    		_e('Course Curriculum Not Set.','vibe');
                    		echo '</h3></li>';
                    	}
            		?>
            		</ul>
            	</div>
            	<?php
            	if(isset($course_curriculum) && is_array($course_curriculum)){
            		?>
            	<div class="more_course">
            		<a href="<?php echo get_permalink($course_id); ?>" class="unit_button full button"><?php _e('BACK TO COURSE','vibe'); ?></a>
            		<form action="<?php echo get_permalink($course_id); ?>" method="post">
            		<?php
            		$finishbit=get_post_meta($course_id,$user_id,true);
            		if(isset($finishbit) && $finishbit!=''){
            			if($finishbit>0){
            			echo '<input type="submit" name="review_course" class="review_course unit_button full button" value="'. __('REVIEW COURSE ','vibe').'" />';
            			echo '<input type="submit" name="submit_course" class="review_course unit_button full button" value="'. __('FINISH COURSE ','vibe').'" />';
            			}
            		}
            		?>	
            		<?php wp_nonce_field($course_id,'review'); ?>
            		</form>
            	</div>
            	<?php
            		}
            	?>	
            </div>
        </div>
    </div>
</section>
</div>

<?php
get_footer();
?>