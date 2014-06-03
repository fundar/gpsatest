<?php

/***
 * You can hook in ajax functions in WordPress/BuddyPress by using the 'wp_ajax' action.
 * 
 * When you post your ajax call from javascript using jQuery, you can define the action
 * which will determin which function to run in your PHP component code.
 *
 * Here's an course:
 *
 * In Javascript we can post an action with some parameters via jQuery:
 * 
 * 			jQuery.post( ajaxurl, {
 *				action: 'my_course_action',
 *				'cookie': encodeURIComponent(document.cookie),
 *				'parameter_1': 'some_value'
 *			}, function(response) { ... } );
 *
 * Notice the action 'my_course_action', this is the part that will hook into the wp_ajax action.
 * 
 * You will need to add an add_action( 'wp_ajax_my_course_action', 'the_function_to_run' ); so that
 * your function will run when this action is fired.
 * 
 * You'll be able to access any of the parameters passed using the $_POST variable.
 *
 * Below is an course of the addremove_friend AJAX action in the friends component.
 */

//add_action('wp_ajax_course_filter','course_filter');
//add_action('wp_ajax_no_priv_course_filter','course_filter');


add_action('wp_ajax_complete_unit', 'complete_unit');

function complete_unit(){
  $unit_id= $_POST['id'];
  $course_id = $_POST['course_id'];
  if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security') ){
     _e('Security check Failed. Contact Administrator.','vibe');
     die();
  }

  // Check if user has taken the course
  $user_id = get_current_user_id();
  $coursetaken=get_user_meta($user_id,$course_id,true);
  if(isset($coursetaken) && $coursetaken){
    add_user_meta($user_id,$unit_id,time());
    bp_course_record_activity(array(
      'action' => 'Student finished unit '.get_the_title($unit_id),
      'content' => 'Student finished the unit '.get_the_title($unit_id).' in course '.get_the_title($course_id),
      'type' => 'unit_complete',
      'primary_link' => get_permalink($unit_id),
      'item_id' => $unit_id,
      'secondary_item_id' => $user_id
    ));
  }
}

add_action( 'wp_ajax_calculate_stats_course', 'calculate_stats_course' ); // RESETS QUIZ FOR USER
function calculate_stats_course(){
	$course_id=$_POST['id'];
	$flag=0;
	if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'vibe_security') ){
        echo '<p>'.__('Security check failed !','vibe').'</p>';
        die();
    }

    if ( !isset($course_id) || !$course_id){
    	echo '<p>'.__('Incorrect Course selected.','vibe').'</p>';
        die();
    }
    $badge=$pass=$total_qmarks=$gross_qmarks=0;
    $users=array();
	global $wpdb;

	$badge_val=get_post_meta($course_id,'vibe_course_badge_percentage',true);
	$pass_val=get_post_meta($course_id,'vibe_course_passing_percentage',true);

	$members_course_grade = $wpdb->get_results( $wpdb->prepare("select meta_value,meta_key from {$wpdb->postmeta} where post_id = %d",$course_id), ARRAY_A);


	if(count($members_course_grade)){
		foreach($members_course_grade as $meta){
			if(is_numeric($meta['meta_key']) && $meta['meta_value'] > 2){

       
						if($meta['meta_value'] > $pass_val)
							$badge++;

						if($meta['meta_value'] > $badge_val)
							$pass++;

						$users[]=$meta['meta_key'];
					}
			}  // META KEY is NUMERIC ONLY FOR USERIDS
	}

	if($pass)
		update_post_meta($course_id,'pass',$pass);


	if($badge)
		update_post_meta($course_id,'badge',$badge);

	

if($flag !=1){
	$curriculum=vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));
		foreach($curriculum as $c){
			if(is_numeric($c)){

				if(get_post_type($c) == 'quiz'){
          $i=$qmarks=0;

					foreach($users as $user){
						$k=get_post_meta($c,$user,true);
						$qmarks +=$k;
            $i++;
						$gross_qmarks +=$k;
					}
          if($i==0)$i=1;
					
          $qavg=$qmarks/$i;

					if($qavg)
						update_post_meta($c,'average',$qavg);
					else{
						$flag=1;
						break;
					}
				}
			}
	}
	
	$cmarks=$i=0;
foreach($users as $user){
    $k=get_post_meta($course_id,$user,true);
    if($k > 2 && $k<101){
      $cmarks += $k;
      $i++;
    }
}
if($i==0)$i=1;
	$avg = round(($cmarks/$i));

  

	if($avg && $flag !=1){
		update_post_meta($course_id,'average',$avg);
	}else{
		$flag=1;
	}
}

	if(!$flag){
		echo '<p>'.__('Statistics successfully calculated. Reloading...','vibe').'</p>';
	}else{
		echo '<p>'.__('Unable to calculate Average.','vibe').'</p>';
	}

	die();
}

add_action( 'wp_ajax_course_stats_user', 'course_stats_user' ); // RESETS QUIZ FOR USER
function course_stats_user(){
	$course_id = $_POST['id'];
    $user_id = $_POST['user'];

    echo '<a class="show_side link right" data-side=".course_stats_user">'.__('SHOW STATS','vibe').'</a><div class="course_stats_user"><a class="hide_parent link right">'.__('HIDE','vibe').'</a>';

    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'vibe_security') ){
        echo '<div id="message" class="info notice"><p>'.__('Security check failed !','vibe').'</p></div>';
        die();
    }

    if ( !isset($user_id) || !$user_id){
    	echo '<div id="message" class="info notice"><p>'.__('Incorrect User selected.','vibe').'</p></div>';
        die();
    }


    $start=get_user_meta($user_id,$course_id,true);
	
	$being=get_post_meta($course_id,$user_id,true);

	if(isset($being) && $being !=''){
		if(!$being){
			echo '<p>'.__('This User has not started the course.','vibe').'</p>';
		}else if($being > 2 && $being < 100){
			echo '<p>'.__('This User has completed the course.','vibe').'</p>';
			echo '<h4>'.__('Student Score for Course ','vibe').' : <strong>'.$being.' out of 100</strong></h4>';

      $course_curriculum=vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));
      $complete=$total=count($course_curriculum);

		}else{
			$total=0;
			$complete=0;

			echo '<h6>';
			_e('Course Started : ');
			echo '<span>'.tofriendlytime((time()-$start)).' ago</span></h6>';

			$course_curriculum=vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));

			$curriculum = '<div class="curriculum_check"><h6>'.__('Curriculum :','vibe').'</h6><ul>';
			$quiz ='<h5>Quizes</h5>';
			foreach($course_curriculum as $c){
				if(is_numeric($c)){
					$total++;
					$check=get_user_meta($user_id,$c,true);
					if(isset($check) && $check !=''){
						$complete++;
						if(get_post_type($c) == 'quiz'){
							$marks = get_post_meta($c,$user_id,true);

							$curriculum .= '<li><span class="done"></span> '.get_the_title($c).' <strong>'.(($marks)?'Marks Obtained : '.$marks:'Under Evaluation').'</strong></li>';
						}else
							$curriculum .= '<li><span class="done"></span> '.get_the_title($c).'</li>';

					}else{
						$curriculum .= '<li><span></span> '.get_the_title($c).'</li>';
					}
				}else{
					$curriculum .= '<li><h5>'.$c.'</h5></li>';
				}
			}
			$curriculum .= '</ul></div>';
		}
	}

	echo '<strong>'.__('Units Completed ').$complete.' out of '.$total.'</strong>';
	echo '<div class="complete_course"><input type="text" class="dial" data-max="'.$total.'" value="'.$complete.'"></div>';
	echo $curriculum;
    echo '</div>';
	die();
}


add_action( 'wp_ajax_remove_user_course', 'remove_user_course' ); // RESETS QUIZ FOR USER
function remove_user_course(){
	$course_id = $_POST['id'];
    $user_id = $_POST['user'];

    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'vibe_security') ){
        echo '<p>Security check failed !</p>';
        die();
    }

    if ( !isset($user_id) || !$user_id){
        echo '<p> Incorrect User selected.</p>';
        die();
    }

    if(delete_user_meta($user_id,$course_id)){
			delete_post_meta($course_id,$user_id);
			echo '<p>'.__('User removed from the Course','vibe').'</p>';

      bp_course_record_activity(array(
      'action' => 'Student '.bp_core_get_userlink($user_id).' removed from course '.get_the_title($course_id),
      'content' => 'Student '.bp_core_get_userlink($user_id).' removed from the course '.get_the_title($course_id),
      'type' => 'remove_from_course',
      'primary_link' => get_permalink($course_id),
      'item_id' => $course_id,
      'secondary_item_id' => $user_id
    ));

	}else{
		echo '<p>'.__('There was issue in removing this user from the Course. Please contact admin.','vibe').'</p>';
	}
	die();
}


add_action( 'wp_ajax_reset_course_user', 'reset_course_user' ); // RESETS COURSE FOR USER
function reset_course_user(){
	$course_id = $_POST['id'];
    $user_id = $_POST['user'];

    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'vibe_security') ){
        echo '<p>Security check failed !</p>';
        die();
    }

    if ( !isset($user_id) || !$user_id){
        echo '<p> Incorrect User selected.</p>';
        die();
    }
      
      //delete_user_meta($user_id,$course_id) // DELETE ONLY IF USER SUBSCRIPTION EXPIRED
    
    if(update_post_meta($course_id,$user_id,0)){  // Necessary for continue course
			 
			$course_curriculum=vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));

			foreach($course_curriculum as $c){
				if(is_numeric($c)){
					delete_user_meta($user_id,$c);
					
					if(get_post_type($c) == 'quiz'){
						delete_post_meta($c,$user_id);
						$questions = vibe_sanitize(get_post_meta($c,'vibe_quiz_questions',false));
				      	foreach($questions['ques'] as $question){
				        	global $wpdb;
                  if(isset($question) && $question !='' && is_numeric($question))
				        	$wpdb->query("UPDATE $wpdb->comments SET comment_approved='trash' WHERE comment_post_ID=$question");
				      	}
					}
				}
				
	      	}
			echo '<p>'.__('Course Reset for User','vibe').'</p>';
      bp_course_record_activity(array(
      'action' => 'Course '.get_the_title($course_id).' reset for student '.bp_core_get_userlink($user_id),
      'content' => 'Course '.get_the_title($course_id).' reset for student '.bp_core_get_userlink($user_id),
      'type' => 'reset_course',
      'primary_link' => get_permalink($course_id),
      'item_id' => $course_id,
      'secondary_item_id' => $user_id
    ));
	}else{
		echo '<p>'.__('There was issue in resetting this course for the user. Please contact admin.','vibe').'</p>';
	}
	die();
}

add_action( 'wp_ajax_reset_quiz', 'reset_quiz' ); // RESETS QUIZ FOR USER
function reset_quiz(){

    $quiz_id = $_POST['id'];
    $user_id = $_POST['user'];

    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'vibe_quiz') ){
        echo '<p>Security check failed !</p>';
        die();
    }

    if ( !isset($user_id) || !$user_id){
        echo '<p> Incorrect User selected.</p>';
        die();
    }

    if(delete_user_meta($user_id,$quiz_id)){

      delete_post_meta($quiz_id,$user_id); // Optional validates that user can retake the quiz

      $questions = vibe_sanitize(get_post_meta($quiz_id,'vibe_quiz_questions',false));
      foreach($questions['ques'] as $question){
        global $wpdb;
        $wpdb->query("UPDATE $wpdb->comments SET comment_approved='trash' WHERE comment_post_ID=$question");
      }
      echo '<p>'.__('Quiz Reset for Selected User','vibe').'</p>';
    }else{
      echo '<p>'.__('Could not find Quiz results for User. Contact Admin.','vibe').'</p>';
    }
	
    bp_course_record_activity(array(
      'action' => 'Instructor Reseted the Quiz for User '.bp_core_get_userlink( $user_id ),
      'content' => 'Quiz '.get_the_title($quiz_id).' was reset by the Instructor for user'.bp_core_get_userlink( $user_id ),
      'type' => 'reset_quiz',
      'primary_link' => get_permalink($quiz_id),
      'item_id' => $quiz_id
      ));
    die();
}


add_action( 'wp_ajax_give_marks', 'give_marks' ); // RESETS QUIZ FOR USER
function give_marks(){
    $answer_id=intval($_POST['aid']);
    $value=intval($_POST['aval']);
    update_comment_meta( $answer_id, 'marks',$value);
    die();
}

add_action( 'wp_ajax_complete_course_marks', 'complete_course_marks' ); // RESETS QUIZ FOR USER
function complete_course_marks(){
    $user_id=intval($_POST['user']);
    $course_id=intval($_POST['course']);
    $marks=intval($_POST['marks']);

    $badge_per = get_post_meta($course_id,'vibe_course_badge_percentage',true);
    $passing_per = get_post_meta($course_id,'vibe_course_passing_percentage',true);

    if(isset($badge_per) && $badge_per && $marks > $badge_per){
        $badges = array();
        $badges= vibe_sanitize(get_user_meta($user_id,'badges',false));
        $badges[]=$course_id;
        update_user_meta($user_id,'badges',$badges);

        bp_course_record_activity(array(
          'action' => 'Student got a Badge in the course '.get_the_title($course_id),
          'content' => 'Student '.bp_core_get_userlink($user_id).' got a badge in the course '.get_the_title($course_id),
          'type' => 'student_badge',
          'item_id' => $course_id,
          'primary_link'=>get_permalink($course_id),
          'secondary_item_id'=>$user_id
        )); 
    }

    if(isset($passing_per) && $passing_per && $marks > $passing_per){
        $pass = array();
        $pass=vibe_sanitize(get_user_meta($user_id,'certificates',false));
        $pass[]=$course_id;
        update_user_meta($user_id,'certificates',$pass);

        bp_course_record_activity(array(
          'action' => 'Student got a Certificate in the course '.get_the_title($course_id),
          'content' => 'Student '.bp_core_get_userlink($user_id).' got a caertificate in the course '.get_the_title($course_id),
          'type' => 'student_certificate',
          'item_id' => $course_id,
          'primary_link'=>get_permalink($course_id),
          'secondary_item_id'=>$user_id
        )); 
    }
    if(update_post_meta( $course_id,$user_id,$marks)){
      $message = __('You\'ve obtained ','vibe').$marks.' out of 100 in Course : <a href="'.get_permalink($course_id).'">'.get_the_title($course_id).'</a>';
      messages_new_message( array('sender_id' => get_current_user_id(), 'subject' => __('Course results available','vibe'), 'content' => $message,   'recipients' => $user_id ) );
      echo __('COURSE MARKED COMPLETE','vibe');
    }else{
      echo __('FAILED TO MARK COURSE, CONTACT ADMIN','vibe');
    }

    bp_course_record_activity(array(
      'action' => 'Instructor evaluated Course for Student',
      'content' => 'Student '.bp_core_get_userlink( $user_id ).' got '.$marks.' out of 100 in course '.get_the_title($course_id),
      'primary_link' => get_permalink($course_id),
      'type' => 'course_evaluated',
      'item_id' => $course_id,
      ));

    die();
}



add_action( 'wp_ajax_save_quiz_marks', 'save_quiz_marks' ); // RESETS QUIZ FOR USER
function save_quiz_marks(){
    $quiz_id=intval($_POST['quiz_id']);
    $user_id=intval($_POST['user_id']);
    $marks=intval($_POST['marks']);

    $ques = vibe_sanitize(get_post_meta($quiz_id,'vibe_quiz_questions',false));
    $max= array_sum($ques['marks']);

    
    update_post_meta( $quiz_id, $user_id,$marks);
    
    $message = __('You\'ve obtained ','vibe').$marks.' out of '.$max.' in Quiz : <a href="'.trailingslashit( bp_core_get_user_domain( $user_id ) . bp_get_course_slug()) . 'course-results/?action='.$quiz_id .'">'.get_the_title($quiz_id).'</a>';
    messages_new_message( array('sender_id' => get_current_user_id(), 'subject' => __('Quiz results available','vibe'), 'content' => $message,   'recipients' => $user_id ) );
    
    bp_course_record_activity(array(
      'action' => 'Instructor evaluated Quiz for student '.bp_core_get_userlink( $user_id ),
      'type' => 'quiz_evaluated',
      'content' => 'Student '.bp_core_get_userlink( $user_id ).' got '.$marks.' out of '.$max.' in Quiz '.get_the_title($course_id),
      'primary_link' => trailingslashit( bp_core_get_user_domain( $user_id ) . bp_get_course_slug()) . 'course-results/?action='.$quiz_id ,
      'item_id' => $quiz_id,
      ));

    die();
}

add_action( 'wp_ajax_evaluate_course', 'evaluate_course' ); // RESETS QUIZ FOR USER
function evaluate_course(){
    
    $course_id=intval($_POST['id']);
    $user_id=intval($_POST['user']);

    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],$course_id) ){
        echo '<p>Security check failed !</p>';
        die();
    }

    if ( !isset($user_id) || !$user_id){
        echo '<p> Incorrect User selected.</p>';
        die();
    }
    $sum=$max_sum=0;
    $curriculum=vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));
     echo '<ul class="course_curriculum">';
    foreach($curriculum as $c){
      if(is_numeric($c)){
        if(get_post_type($c) == 'quiz'){
            $status = get_user_meta($user_id,$c,true);
            $marks=get_post_meta($c,$user_id,true);
            $sum +=$marks;

            $qmax=vibe_sanitize(get_post_meta($c,'vibe_quiz_questions',false));

            $max=array_sum($qmax['marks']);
            $max_sum +=$max;
            echo '<li>
                  <strong>'.get_the_title($c).' <span>'.((isset($status) && $status !='')?'MARKS: '.$marks.' out of '.$max:'PENDING').'</span></strong>
                  </li>';
        }else{
            $status = get_user_meta($user_id,$c,true);
            echo '<li>
                  <strong>'.get_the_title($c).' <span>'.((isset($status) && $status !='')?'<i class="icon-check"></i> DONE':'<i class="icon-alarm-1"></i> PENDING').'</span></strong>
                  </li>';
        } 
      }else{

      }
    }     
    echo '</ul>';
    echo '<div id="total_marks">'.__('Total','vibe').' <strong><span>'.$sum.'</span> / '.$max_sum.'</strong> </div>';
    echo '<div id="course_marks">'.__('Course Percentage (Out of 100)','vibe').' <strong><span><input type="number" name="course_marks" id="course_marks_field" class="form_field" value="0" placegolder="Course Percentage out of 100" /></span></div>';
    echo '<a href="#" id="course_complete" class="button full" data-course="'.$course_id.'" data-user="'.$user_id.'">'.__('Mark Course Complete','vibe').'</a>';
  die();
}


add_action( 'wp_ajax_evaluate_quiz', 'evaluate_quiz' ); // RESETS QUIZ FOR USER
function evaluate_quiz(){

    $quiz_id=intval($_POST['id']);
    $user_id=intval($_POST['user']);

    if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'vibe_quiz') ){
        echo '<p>Security check failed !</p>';
        die();
    }

    if ( !isset($user_id) || !$user_id){
        echo '<p> Incorrect User selected.</p>';
        die();
    }

  $questions = vibe_sanitize(get_post_meta($quiz_id,'vibe_quiz_questions',false));
  
  if(count($questions)):

    echo '<ul class="quiz_questions">';
    $sum=$max_sum=0;
    foreach($questions['ques'] as $key=>$question){
      if(isset($question) && $question){
      $q=get_post($question);
      echo '<li>
          <div class="q">'.apply_filters('the_content',$q->post_content).'</div>';
      $comments_query = new WP_Comment_Query;
      $comments = $comments_query->query( array('post_id'=> $question,'user_id'=>$user_id,'number'=>1,'status'=>'approve') );   
      echo '<strong>';
      _e('Marked Answer :','vibe');
      echo '</strong>';

      $correct_answer=get_post_meta($question,'vibe_question_answer',true);


      foreach($comments as $comment){ // This loop runs only once
        $type = get_post_meta($question,'vibe_question_type',true);

          switch($type){
            case 'single': 
              $options = vibe_sanitize(get_post_meta($question,'vibe_question_options',false));
              
              echo $options[(intval($comment->comment_content)-1)]; // Reseting for the array
              if(isset($correct_answer) && $correct_answer !=''){
                $ans=$options[(intval($correct_answer)-1)];

              }
            break;  

            case 'multiple': 
              $options = vibe_sanitize(get_post_meta($question,'vibe_question_options',false));
              $ans=explode(',',$comment->comment_content);

              foreach($ans as $an){
                echo $option[intval($an)-1].' ';
              }

              $cans = explode(',',$correct_answer);
              $ans='';
              foreach($cans as $can){
                $ans .= $option[intval($can)-1].', ';
              }
            break;
            case 'smalltext': 
                echo $comment->comment_content;
                $ans = $correct_answer;
            break;
            case 'largetext': 
                echo apply_filters('the_content',$comment->comment_content);
                $ans = $correct_answer;
            break;
        }
        $cid=$comment->comment_ID;
        $marks=get_comment_meta( $comment->comment_ID, 'marks', true );
      }

      if(isset($correct_answer) && $correct_answer !=''){
        echo '<strong>';
        _e('Correct Answer :','vibe');
        echo '<span>'.$ans.'</span></strong>';


      }
      

    

      if(isset($marks) && $marks !=''){
          echo '<span class="marking">'.__('Marks Obtained','vibe').' <input type="text" id="'.$cid.'" class="form_field small question_marks" value="'.$marks.'" placeholder="Give marks" />
                <a href="#" class="give_marks button" data-ans-id="'.$cid.'">'.__('Update Marks','vibe').'</a>';

          $sum = $sum+$marks;
      }else{
        echo '<span class="marking">'.__('Marks Obtained','vibe').' <input type="text" id="'.$cid.'" class="form_field small question_marks" value="" placeholder="Give marks" />
        <a href="#" class="give_marks button" data-ans-id="'.$cid.'">'.__('Give Marks','vibe').'</a>';
      }
      $max_sum=$max_sum+intval($questions['marks'][$key]);
      echo '<span> Total Marks : '.$questions['marks'][$key].'</span>';
      echo '</li>';

      } // IF question check
    } 
    echo '</ul>';
    echo '<div id="total_marks">'.__('Total','vibe').' <strong><span>'.$sum.'</span> / '.$max_sum.'</strong> </div>';
    echo '<a href="#" id="mark_complete" class="button full" data-quiz="'.$quiz_id.'" data-user="'.$user_id.'">'.__('Mark Quiz as Checked','vibe').'</a>';
    endif;

    die();
}



add_action( 'wp_ajax_send_bulk_message', 'send_bulk_message' );
function send_bulk_message(){

    
    if ( isset($_POST['security']) && wp_verify_nonce($_POST['security'],'security') ){
        echo 'Security check failed !';
        die();
    }
    $members = json_decode(stripslashes($_POST['members']));

    $sender = $_POST['sender'];
    $subject=stripslashes($_POST['subject']);
    if(!isset($subject)){
      echo 'Set a Subject for the message';
      die();  
    }
    $message=stripslashes($_POST['message']);
    if(!isset($message)){
      echo 'Set a Subject for the message';
      die();  
    }
    $sent=0;
    if(count($members) > 0){
      foreach($members as $member){
          if( messages_new_message( array('sender_id' => $sender, 'subject' => $subject, 'content' => $message,   'recipients' => $member ) ) ){
            $sent++;
          }
      }
      echo 'Messages Sent to '.$sent.' members';
    }else{
      echo 'Please select members';
    }

    bp_course_record_activity(array(
      'action' => 'Instructor sent Bulk message to students : '.$subject,
      'content' => 'Bulk Message sent to students '.$message,
      'type' => 'bulk_action',
      'item_id' => $sender,
      ));

    die();
}



add_action('wp_ajax_unit_traverse', 'unit_traverse');
add_action( 'wp_ajax_nopriv_unit_traverse', 'unit_traverse' );

function unit_traverse(){
  $unit_id= $_POST['id'];
  $course_id = $_POST['course_id'];
  if ( !isset($_POST['security']) || !wp_verify_nonce($_POST['security'],'security') ){
     _e('Security check Failed. Contact Administrator.','vibe');
     die();
  }

  // Check if user has taken the course
  $user_id = get_current_user_id();
  $coursetaken=get_user_meta($user_id,$course_id,true);
  if(isset($coursetaken) && $coursetaken){
      
      $course_curriculum=vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));
      

        $units=array();
          foreach($course_curriculum as $key=>$curriculum){
            if(is_numeric($curriculum)){
                $units[]=$curriculum;
            }
          }

      // Drip Feed Check  
      //     
      $drip_enable=get_post_meta($course_id,'vibe_course_drip',true);

      
      if(isset($drip_enable) && $drip_enable && $drip_enable !='H'){

          $drip_duration = get_post_meta($course_id,'vibe_course_drip_duration',true);
          
          $unitkey = array_search($unit_id,$units);

          if($unitkey == 0){
            $pre_unit_time=get_post_meta($units[$unitkey],$user_id,true);
            if(!isset($pre_unit_time) || $pre_unit_time ==''){
              add_post_meta($units[$unitkey],$user_id,time());
            }
          }else{
             $pre_unit_time=get_post_meta($units[($unitkey-1)],$user_id,true);

             if(isset($pre_unit_time) && $pre_unit_time){

              //echo (($pre_unit_time + ($unitkey)*86400) - time());

               if(($pre_unit_time + ($unitkey)*86400) > time()){
                      echo '<div class="message"><p>'.__('Unit will be available in ','vibe').tofriendlytime(($pre_unit_time + ($unitkey)*86400)-time()).'</p></div>';
                      die();
                  }else{
                      $pre_unit_time=get_post_meta($units[$unitkey],$user_id,true);
                      if(!isset($pre_unit_time) || $pre_unit_time ==''){
                        add_post_meta($units[$unitkey],$user_id,time());

                        bp_course_record_activity(array(
                          'action' => 'Student started unit '.get_the_title($unit_id),
                          'content' => 'Student started the unit '.get_the_title($unit_id).' in course '.get_the_title($course_id),
                          'type' => 'unit',
                          'primary_link' => get_permalink($unit_id),
                          'item_id' => $unit_id,
                          'secondary_item_id' => $user_id
                        ));
                      }
                  } 
              }else{
                  echo '<div class="message"><p>'.__('Unit can not be accessed.','vibe').'</p></div>';
                  die();
              }    
            }
          }  
        
      

      // END Drip Feed Check  
      
      echo '<div id="unit" class="unit_title" data-unit="'.$unit_id.'">';
      the_unit_tags($unit_id);
      the_unit_instructor($unit_id);
      $minutes=0;
      $minutes = get_post_meta($unit_id,'vibe_duration',true);
      if($minutes){
        if($minutes > 60){
          $hours = intval($minutes/60);
          $minutes = $minutes - $hours*60;
        }
      
      do_action('wplms_course_unit_meta');
      
      echo '<span><i class="icon-clock"></i> '.(isset($hours)?$hours.' Hours':'').' '.$minutes.' minutes</span>';
      }
      echo '<div class="clear"></div>';
      echo '<h1>'.get_the_title($unit_id).'</h1>';
      echo '<h3>';
        the_sub_title($unit_id);
      echo '</h3></div>';
      the_unit($unit_id);  
      
      

              $k=array_search($unit_id,$units);
              $done_flag=get_user_meta($user_id,$unit_id,true);

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
          
        }
        die();
}  

?>