<?php

/**
 * The -functions.php file is a good place to store miscellaneous functions needed by your plugin.
 *
 * @package BuddyPress_Course_Component
 * @since 1.6
 */

/**
 * bp_course_load_template_filter()
 *
 * You can define a custom load template filter for your component. This will allow
 * you to store and load template files from your plugin directory.
 *
 * This will also allow users to override these templates in their active theme and
 * replace the ones that are stored in the plugin directory.
 *
 * If you're not interested in using template files, then you don't need this function.
 *
 * This will become clearer in the function bp_course_screen_one() when you want to load
 * a template file.
 */
function bp_course_load_template_filter( $found_template, $templates ) {
	global $bp;

	/**
	 * Only filter the template location when we're on the course component pages.
	 */
	if ( $bp->current_component != $bp->course->slug )
		return $found_template;

	foreach ( (array) $templates as $template ) {
		if ( file_exists( STYLESHEETPATH . '/' . $template ) )
			$filtered_templates[] = STYLESHEETPATH . '/' . $template;
		else
			$filtered_templates[] = dirname( __FILE__ ) . '/templates/' . $template;
	}

	$found_template = $filtered_templates[0];

	return apply_filters( 'bp_course_load_template_filter', $found_template );
}
add_filter( 'bp_located_template', 'bp_course_load_template_filter', 10, 2 );


function bp_user_can_create_course() { 
		        // Bail early if super admin 
		        if ( is_super_admin() ) 
		                return true; 

		        if ( current_user_can('edit_posts') ) 
		                return true;     
	
		        // Get group creation option, default to 0 (allowed) 
	        $restricted = (int) get_site_option( 'bp_restrict_course_creation', 0 ); 
		 
		        // Allow by default 
		        $can_create = true; 
		 
		        // Are regular users restricted? 
		        if ( $restricted ) 
		                $can_create = false; 
	
	return apply_filters( 'bp_user_can_create_course', $can_create ); 
} 
/**
 * bp_course_nav_menu()
 * Navigation menu for BuddyPress course
 */

function bp_course_nav_menu(){
    $defaults = array(
      'Home' => array(
                        'id' => 'home',
                        'label'=>__('Home','vibe'),
                        'action' => '',
                        'link'=>bp_get_course_permalink(),
                    ),
      'curriculum' => array(
                        'id' => 'curriculum',
                        'label'=>__('Curriculum','vibe'),
                        'action' => 'curriculum',
                        'link'=>bp_get_course_permalink(),
                    ),
      'members' => array(
                        'id' => 'members',
                        'label'=>__('Members','vibe'),
                        'action' => 'members',
                        'link'=>bp_get_course_permalink(),
                    ),
      );

    $nav_menu = apply_filters('wplms_course_nav_menu',$defaults);

    if(is_array($nav_menu))
      foreach($nav_menu as $menu_item){
          echo '<li id="'.$menu_item['id'].'" class=""><a href="'.$menu_item['link'].''.(isset($menu_item['action'])?'?action='.$menu_item['action']:'').'">'.$menu_item['label'].'</a></li>';
      }
}
/**
 * bp_course_remove_data()
 *
 * It's always wise to clean up after a user is deleted. This stops the database from filling up with
 * redundant information.
 */
function bp_course_remove_data( $user_id ) {
	/* You'll want to run a function here that will delete all information from any component tables
	   for this $user_id */

	/* Remember to remove usermeta for this component for the user being deleted */
	delete_user_meta( $user_id, 'bp_course_some_setting' );

	do_action( 'bp_course_remove_data', $user_id );
}
add_action( 'wpmu_delete_user', 'bp_course_remove_data', 1 );
add_action( 'delete_user', 'bp_course_remove_data', 1 );


function bp_directory_course_search_form() {

	$default_search_value = bp_get_search_default_text( BP_COURSE_SLUG );
	$search_value         = !empty( $_REQUEST['s'] ) ? stripslashes( $_REQUEST['s'] ) : $default_search_value;

	$search_form_html = '<form action="" method="get" id="search-course-form">
		<label><input type="text" name="s" id="groups_search" placeholder="'. esc_attr( $search_value ) .'" /></label>
		<input type="submit" id="course_search_submit" name="course_search_submit" value="'. __( 'Search', 'vibe' ) .'" />
	</form>';

	echo apply_filters( 'bp_directory_course_search_form', $search_form_html );

}


function the_course_button(){
   global $post;
   $take_course_page=get_permalink(vibe_get_option('take_course_page'));
   $user_id = get_current_user_id();
   $course_id=get_the_ID();
   $coursetaken=get_user_meta($user_id,$course_id,true);

   do_action('wplms_the_course_button',$course_id,$user_id);
   // Free Course
   $free_course= get_post_meta($course_id,'vibe_course_free',true);
   if(isset($free_course) && $free_course && $free_course !='H' && is_user_logged_in()){
      $duration=get_post_meta($course_id,'vibe_duration',true);
      $new_duration = time()+86400*$duration;

      $new_duration = apply_filters('wplms_free_course_check',$new_duration);

      update_user_meta($user_id,$course_id,$new_duration);
      $coursetaken = $new_duration;      
   }

   if(isset($coursetaken) && $coursetaken && is_user_logged_in()){   // COURSE IS TAKEN & USER IS LOGGED IN
     
       
         if($coursetaken > time()){  // COURSE ACTIVE


          $course_user=get_post_meta($course_id,$user_id,true); // Validates that a user has taken this course

         

            if((isset($course_user) && $course_user !='') || (isset($free_course) && $free_course && $free_course !='H' && is_user_logged_in())){ // COURSE PURCHASED SECONDARY VALIDATION

             echo '<form action="'.$take_course_page.'" method="post">';
             if(!$course_user){ // COURSE NOT STARTED
                  echo '<input type="submit" class="course_button full button" value="'.__('START COURSE','vibe').'">'; 
                  wp_nonce_field('start_course','start_course');
             }else{  // COURSE STARTED
              
                switch($course_user){
                  case 1:
                    echo '<input type="submit" class="course_button full button" value="'.__('CONTINUE COURSE','vibe').'">';
                    wp_nonce_field('continue_course','continue_course');
                  break;
                  case 2:
                    echo '<a href="#" class="full button">'.__('COURSE UNDER EVALUATION','vibe').'</a>';
                  break;
                  default:
                    echo '<a href="#" class="full button">'.__('COURSE FINISHED','vibe').'</a>';
                  break;
                }
             }  
                
             
             echo  '<input type="hidden" name="course_id" value="'.get_the_ID().'" />';
             
             echo  '</form>'; 
            }else{ 
                  $pid=get_post_meta(get_the_ID(),'vibe_product',true); // SOME ISSUE IN PROCESS BUT STILL DISPLAYING THIS FOR NO REASON.
                  echo '<a href="'.get_permalink($pid).'" class="course_button full button">'.__('COURSE ENABLED','vibe').'</a><span>CONTACT ADMIN TO ENABLE</span>';   
            }
      }else{ 
              $pid=get_post_meta(get_the_ID(),'vibe_product',true);
              $pid=apply_filters('wplms_course_product_id',$pid,get_the_ID());
               echo '<a href="'.get_permalink($pid).'" class="course_button full button">'.__('COURSE EXPIRED','vibe').'</a>';   
      }
    
   }else{
      $pid=get_post_meta(get_the_ID(),'vibe_product',true);

      $pid=apply_filters('wplms_course_product_id',$pid,get_the_ID());

      echo '<a href="'.get_permalink($pid).'" class="course_button full button">'.__('TAKE THIS COURSE','vibe').'</a>'; 
   }
}


function the_course_details($args=NULL){
  echo get_the_course_details($args);
}

function get_the_course_details($args=NULL){
  $defaults=array(
    'course_id' =>get_the_ID(),
    );
  $r = wp_parse_args( $args, $defaults );
  extract( $r, EXTR_SKIP );


   global $post;
   $return ='<div class="course_details"><ul>';

   $return .= '<li><i class="icon-wallet-money"></i> <h5 class="credits">';
    $return .= bp_course_get_course_credits('course_id='.$course_id);
    $return .=  '</h5></li>';

    $precourse=get_post_meta($course_id,'vibe_pre_course',true);

    if(isset($precourse) && $precourse!='')
        $return .= '<li><i class="icon-clipboard-1"></i> * REQUIRES <a href="'.get_permalink($precourse).'">'.get_the_title($precourse).'</a></li>'; 
           
   $return .= '<li><i class="icon-clock"></i>'.get_the_course_time('course_id='.$course_id).'</li>'; 
   $badge=get_post_meta($course_id,'vibe_course_badge',true);
   
   if(isset($badge) && $badge !='')
      $return .=  '<li><i class="icon-award-stroke"></i> '.__('Course Badge','vibe').'</li>';

   $certificate=get_post_meta($course_id,'vibe_course_certificate',true);
   if(isset($certificate) && $certificate !='')
      $return .=  '<li><i class="icon-certificate-file"></i>  '.__('Course Certificate','vibe').'</li>';

    do_action('wplms_single_course_details');
    
   $return .=  '</ul></div>';

   return $return;
}

function take_course_page(){

}




if(!function_exists('the_question')){
  function the_question(){
    global $post;
    echo '<div id="question" data-ques="'.get_the_ID().'">';
    echo '<div class="question">';
    the_content();
    echo '</div>';

    $type = get_post_meta(get_the_ID(),'vibe_question_type',true);

    switch($type){
      case 'single': 
        the_options('single');
      break;  
      case 'multiple': 
        the_options('multiple');
      break;
      case 'smalltext': 
        the_text();
      break;
      case 'largetext': 
        the_textarea();
      break;
    }
    echo '</div>';
  }
}

if(!function_exists('the_options')){
  function the_options($type){
      global $post;
      $options = vibe_sanitize(get_post_meta(get_the_ID(),'vibe_question_options',false));
    

    if(isset($options) || $options){

      

        $user_id = get_current_user_id();

        $answers=get_comments(array(
          'post_id' => get_the_ID(),
          'status' => 'approve',
          'user_id' => $user_id
          ));
        if(isset($answers) && is_array($answers) && count($answers)){
            $answer = reset($answers);
            $content = explode(',',$answer->comment_content);
        }else{
            $content=array();
        }
    

      echo '<ul class="question_options">';
      if($type=='single'){
        foreach($options as $key=>$value){
          $k=$key+1;
          echo '<li>
                    <input type="radio" id="'.$post->post_name.$key.'" name="'.$post->post_name.'" value="'.$k.'" '.(in_array($k,$content)?'checked':'').'/>
                    <label for="'.$post->post_name.$key.'"><span></span> '.$value.'</label>
                </li>';
        }
      }else{
        foreach($options as $key=>$value){
          $k=$key+1;
          echo '<li>
                    <input type="checkbox" id="'.$post->post_name.$key.'" name="'.$post->post_name.$key.'" value="'.$k.'" '.(in_array($k,$content)?'checked':'').'/>
                    <label for="'.$post->post_name.$key.'"><span></span> '.$value.'</label>
                </li>';
        }
      }  
      echo '</ul>';

      global $withcomments;
      $withcomments = true;
      comments_template('/answer-options.php',true);

    }


  }
}

if(!function_exists('the_text')){
  function the_text(){
      global $post;
      echo '<div class="single_text">';
        global $withcomments;
        $withcomments = true;
       comments_template('/answer-text.php');
       echo '</div>';
  }
}

if(!function_exists('the_textarea')){
  function the_textarea(){
      echo '<div class="essay_text">';
        global $withcomments;
      $withcomments = true;
       comments_template('/answer-essay.php');
       echo '</div>';
  }
}

if(!function_exists('the_question_tags')){
  function the_question_tags($before,$saperator,$after){
    global $post;
      echo get_the_term_list($post->ID,'question-tag',$before,$saperator,$after);
       
  }
}


if(!function_exists('the_quiz')){
  function the_quiz($args=NULL){

    $defaults=array(
    'quiz_id' =>get_the_ID(),
    'ques_id'=> ''
    );
  $r = wp_parse_args( $args, $defaults );
  extract( $r, EXTR_SKIP );

    $user_id = get_current_user_id();
    $questions=vibe_sanitize(get_post_meta($quiz_id,'vibe_quiz_questions',false));
    $key=array_search($ques_id,$questions['ques']);

    

    if($ques_id){
      $the_query = new WP_Query(array(
        'post_type'=>'question',
        'p'=>$ques_id
        ));
      while ( $the_query->have_posts() ) : $the_query->the_post(); 
        the_question();

        if($key == 0){ // FIRST QUESTION

          echo '<a href="#" class="ques_link right quiz_question" data-quiz="'.$quiz_id.'" data-qid="'.$questions['ques'][($key+1)].'">'.__('Next Question','vibe').'</a>';

        }elseif($key == (count($questions['ques'])-1)){ // LAST QUESTION

          echo '<a href="#" class="ques_link left quiz_question" data-quiz="'.$quiz_id.'" data-qid="'.$questions['ques'][($key-1)].'">'.__('Previous Question','vibe').'</a>';

        }else{
          echo '<a href="#" class="ques_link left quiz_question" data-quiz="'.$quiz_id.'" data-qid="'.$questions['ques'][($key-1)].'">'.__('Previous Question','vibe').'</a>';
          echo '<a href="#" class="ques_link right quiz_question" data-quiz="'.$quiz_id.'" data-qid="'.$questions['ques'][($key+1)].'">'.__('Next Question','vibe').'</a>';
        }
      endwhile;
      wp_reset_postdata();
    }else{
        
        $quiz_taken=get_user_meta($user_id,$quiz_id,true);

        if(isset($quiz_taken) && $quiz_taken && ($quiz_taken < time())){
          
          $message=get_post_meta($quiz_id,'vibe_quiz_message',true);
       
          echo $message;
        }else{
          the_content();
        }
    } 
  }
}

if(!function_exists('the_quiz_timer')){
  function the_quiz_timer($start){
    global $post;

      $user_id = get_current_user_id();
      $quiztaken=get_user_meta($user_id,get_the_ID(),true);

      

      if(!isset($quiztaken) || !$quiztaken){
          
          $minutes=intval(get_post_meta($post->ID,'vibe_duration',true));

          if(!$minutes) {$minutes=1; echo "Duration not Set";}

          $minutes= $minutes*60;

      }else{
          if($quiztaken>time())
            $minutes=$quiztaken-time();
          else{
            $minutes=1;
          }
      } 
      
      

      echo '<div class="quiz_timer '.(($start)?'start':'').'" data-time="'.$minutes.'">
      <span class="timer" data-timer="'.$minutes.'"></span>
      <span class="countdown">'.minutes_to_hms($minutes).'</span>
      <span>'.__('Time Remaining','vibe').'</span>
      <span><strong>'.__('Mins','vibe').'</strong> '.__('Secs','vibe').'</span>
      </div>';
       
  }
}

if(!function_exists('the_quiz_timeline')){
  function the_quiz_timeline($id=NULL){
    global $post;
    $quiz_id =$post->ID;
    $user_id = get_current_user_id();

    $questions = vibe_sanitize(get_post_meta($post->ID,'vibe_quiz_questions',false));


    $quess=$questions['ques'];
    $marks=$questions['marks'];

    if(isset($quess) && is_array($quess)){
      echo '<div class="quiz_timeline"><ul>';
      
      //Randomise questions
      //shuffle($quess);
        foreach($quess as $i => $ques){
          $class='';


          $answers=get_comments(array(
            'post_id' => $ques,
            'status' => 'approve',
            'user_id' => $user_id,
            'count' => true,
            ));
          if($answers){
              $class="done";
          }


          if(isset($ques) && $ques){
            if(isset($id) && $ques == $id){
              $class="active";
            }
            echo '<li id="ques'.$ques.'" class="'.$class.'"><span></span> <a href="#" data-quiz="'.$quiz_id.'" data-qid="'.$ques.'" class="'.(is_user_logged_in()?'quiz_question':'').'">'.__('QUESTION','vibe').' '.($i+1).'<span>'.$marks[$i].'</span></a></li>';
          }
        }   
      echo '</ul></div>';  
    }   
  }
}

if(!function_exists('minutes_to_hms')){
  function minutes_to_hms($sec){
    if($sec > 60){
        $minutes = floor($sec/60);
        $secs = $sec%60;
        if($secs < 10) $secs = '0'.$secs;
        return $minutes.':'.$secs;
    }else{
      $secs = $sec;
      return '00:'.$secs;
    }
  }
}

if(!function_exists('tofriendlytime')){
  function tofriendlytime($seconds) {
  $measures = array(
    'year'=>365*30*24*60*60,
    'month'=>30*24*60*60,
    'week'=>7*24*60*60,
    'day'=>24*60*60,
    'hour'=>60*60,
    'minute'=>60,
    'second'=>1,
    );
  foreach ($measures as $label=>$amount) {
    if ($seconds >= $amount) {  
      $howMany = floor($seconds / $amount);
      return apply_filters('wplms_friendly_time',$howMany." ".$label.($howMany > 1 ? "s" : ""),$seconds);
    }
  } 
  return "right now";
} 
}

add_action('wp_ajax_submit_quiz', 'submit_quiz');
if(!function_exists('submit_quiz')){
  function submit_quiz(){
    $id= $_POST['id'];
    $user_id = get_current_user_id();
    update_user_meta($user_id,$id,time());
    update_post_meta($id,$user_id,0);
    echo $message;

    bp_course_quiz_auto_submit($id,$user_id);
    die();
  }
}

//BEGIN QUIZ
add_action('wp_ajax_begin_quiz', 'begin_quiz'); // Only for LoggedIn Users
if(!function_exists('begin_quiz')){
  function begin_quiz(){
      $id= $_POST['id'];
      if ( isset($_POST['start_quiz']) && wp_verify_nonce($_POST['start_quiz'],'start_quiz') ){

        $user_id = get_current_user_id();
        $quiztaken=get_user_meta($user_id,$id,true);
        

         if(!isset($quiztaken) || !$quiztaken){
            
            $quiz_duration = get_post_meta($id,'vibe_duration',true) * 60; // Quiz duration in seconds
            $expire=time()+$quiz_duration;
            add_user_meta($user_id,$id,$expire);
            $quiz_questions = vibe_sanitize(get_post_meta($id,'vibe_quiz_questions',false));
            the_quiz('quiz_id='.$id.'&ques_id='.$quiz_questions['ques'][0]);

            bp_course_record_activity(array(
              'action' => 'Student started a quiz',
              'content' => 'Student '.bp_core_get_userlink($user_id).' started the quiz '.get_the_title($id),
              'type' => 'start_quiz',
              'primary_link' => get_permalink($id),
              'item_id' => $id,
              'secondary_item_id' => $user_id
            ));

         }else{

          if($quiztaken > time()){
            $quiz_questions = vibe_sanitize(get_post_meta($id,'vibe_quiz_questions',false));

            the_quiz('quiz_id='.$id.'&ques_id='.$quiz_questions['ques'][0]);

          }else{
            echo '<div class="message error"><h3>'.__('Quiz Timed Out .','vibe').'</h3>'; 
            echo '<p>'.__('If you want to attempt again, Contact Instructor to reset the quiz.','vibe').'</p></div>';
          }
          
         }

     }else{
        echo '<h3>'.__('Quiz Already Attempted.','vibe').'</h3>'; 
        echo '<p>'.__('Security Check Failed. Contact Site Admin.','vibe').'</p>'; 
     }
     die();
  }  
}


//BEGIN QUIZ
add_action('wp_ajax_quiz_question', 'quiz_question'); // Only for LoggedIn Users
if(!function_exists('quiz_question')){
  function quiz_question(){
      
      $quiz_id= $_POST['quiz_id'];
      $ques_id= $_POST['ques_id'];

      

      if ( isset($_POST['start_quiz']) && wp_verify_nonce($_POST['start_quiz'],'start_quiz') ){ // Same NONCE just for validation

        $user_id = get_current_user_id();
        $quiztaken=get_user_meta($user_id,$quiz_id,true);
        

         if(isset($quiztaken) && $quiztaken){
            if($quiztaken > time()){
                the_quiz('quiz_id='.$quiz_id.'&ques_id='.$ques_id);  
            }else{
              echo '<div class="message error"><h3>'.__('Quiz Timed Out .','vibe').'</h3>'; 
        echo '<p>'.__('If you want to attempt again, Contact Instructor to reset the quiz.','vibe').'</p></div>';
            }
            
         }else{
            echo '<div class="message info"><h3>'.__('Start Quiz to begin quiz.','vibe').'</h3>'; 
            echo '<p>'.__('Click "Start Quiz" button to start the Quiz.','vibe').'</p></div>';
         }

     }else{
                echo '<div class="message error"><h3>'.__('Security Check Failed .','vibe').'</h3>'; 
                echo '<p>'.__('Contact Site Admin.','vibe').'</p></div>';
     }
     die();
  }  
}

add_action('wp_ajax_continue_quiz', 'continue_quiz'); // Only for LoggedIn Users
if(!function_exists('continue_quiz')){
  function continue_quiz(){
      $user_id = get_current_user_id();
      $quiztaken=get_user_meta($user_id,get_the_ID(),true);
      
      if ( isset($_POST['start_quiz']) && wp_verify_nonce($_POST['start_quiz'],'start_quiz') ){ // Same NONCE just for validation
       if(isset($quiztaken) && $quiztaken && $quiztaken > time()){
          $questions = vibe_sanitize(get_post_meta($id,'vibe_quiz_questions',false));
          the_quiz('quiz_id='.get_the_ID().'&ques_id='.$questions['ques'][0]);  
          //the_quiz();
          
       }else{
         echo '<div class="message error"><h3>'.__('Quiz Timed Out .','vibe').'</h3>'; 
        echo '<p>'.__('If you want to attempt again, Contact Instructor to reset the quiz.','vibe').'</p></div>';
       }
     }else{
          echo '<div class="message error"><h3>'.__('Quiz Already Attempted .','vibe').'</h3>'; 
          echo '<p>'.__('If you want to attempt again, Contact Instructor to reset the quiz.','vibe').'</p></div>'; 
     }
      die();
  }  
}


if(!function_exists('the_course_timeline')){
  function the_course_timeline($id=NULL){
    global $post;
    $quiz_id =$post->ID;
    $user_id = get_current_user_id();

    $questions = vibe_sanitize(get_post_meta($post->ID,'vibe_quiz_questions',false));


    $quess=$questions['ques'];
    $marks=$questions['marks'];

    if(isset($quess) && is_array($quess)){
      echo '<div class="quiz_timeline"><ul>';
      
        foreach($quess as $i => $ques){
          $class='';


          $answers=get_comments(array(
            'post_id' => $ques,
            'status' => 'approve',
            'user_id' => $user_id,
            'count' => true,
            ));
          if($answers){
              $class="done";
          }


          if(isset($ques) && $ques){
            if(isset($id) && $ques == $id){
              $class="active";
            }
            echo '<li id="ques'.$ques.'" class="'.$class.'"><span></span> <a href="#" data-quiz="'.$quiz_id.'" data-qid="'.$ques.'" class="quiz_question">'.get_the_title($ques).' <span>'.$marks[$i].'</span></a></li>';
          }
        }   
      echo '</ul></div>';  
    }   
  }
}

function the_unit($id=NULL){
  if(!isset($id))
    return;
  

  do_action('wplms_before_every_unit',$id);

  $the_query = new WP_Query( 'post_type=unit&p='.$id );
  
  while ( $the_query->have_posts() ):$the_query->the_post();
  

  echo '<div class="main_unit_content">';

  the_content();
  wp_link_pages('before=<div class="unit-page-links page-links"><div class="page-link">&link_before=<span>&link_after=</span>&after=</div></div>');

  echo '</div>';
  endwhile;
  wp_reset_postdata();

  do_action('wplms_after_every_unit',$id);

  $attachments =& get_children( 'post_type=attachment&output=ARRAY_N&orderby=menu_order&order=ASC&post_parent='.$id);
  if($attachments && count($attachments)){
    $att= '';

    $count=0;
  foreach( $attachments as $attachmentsID => $attachmentsPost ){
  
  $type=get_post_mime_type($attachmentsID);

  if($type != 'image/jpeg' && $type != 'image/png' && $type != 'image/gif'){
      
      if($type == 'application/zip')
        $type='icon-compressed-zip-file';
      else if($type == 'video/mpeg' || $type== 'video/mp4' || $type== 'video/quicktime')
        $type='icon-movie-play-file-1';
      else if($type == 'text/csv' || $type== 'text/plain' || $type== 'text/xml')
        $type='icon-document-file-1';
      else if($type == 'audio/mp3' || $type== 'audio/ogg' || $type== 'audio/wmv')
        $type='icon-music-file-1';
      else if($type == 'application/pdf')
        $type='icon-text-document';
      else
        $type='icon-file';

      $count++;

      $att .='<li><i class="'.$type.'"></i>'.wp_get_attachment_link($attachmentsID).'</li>';
    }
  }
    if($count){
      echo '<div class="unitattachments"><h4>'.__('Attachments','vibe').'<span><i class="icon-download-3"></i>'.$count.'</span></h4><ul id="attachments">';
      echo $att;
     echo '</ul></div>';
    }
  }

  $forum=get_post_meta($id,'vibe_forum',true);
  if(isset($forum) && $forum){
    echo '<div class="unitforum"><a href="'.get_permalink($forum).'">'.__('Have Questions ? Ask in the Unit Forums','vibe').'</a></div>';
  }
}


if(!function_exists('the_unit_tags')){
  function the_unit_tags($id){
      echo get_the_term_list($id,'module-tag','<ul class="tags"><li>','</li><li>','</li></ul>');
  }
}

if(!function_exists('the_unit_instructor')){
  function the_unit_instructor($id){
      global $post,$bp;
      if(isset($id)){
        $author_id = get_post_field( 'post_author', $id );
      }else{
        $author_id = get_the_author_meta('ID');
      }
     
      echo '<div class="instructor">
      <a href="'.bp_core_get_user_domain($author_id).'" title="'.bp_core_get_user_displayname( $author_id) .'"> '.get_avatar($author_id).' <span><strong>'.__('Instructor','vibe').'</strong><br />'.bp_core_get_user_displayname( $author_id) .'</span></a>
      </div>';
       
  }
}



function the_course_time($args){
  echo '<strong>'.__('Time Remaining','vibe').' : <span>'.get_the_course_time($args).'</span></strong>';
}

function get_the_course_time($args){
  $defaults=array(
    'course_id' =>get_the_ID(),
    'user_id'=> get_current_user_id()
    );
  $r = wp_parse_args( $args, $defaults );
  extract( $r, EXTR_SKIP );



      $d=get_user_meta($user_id,$course_id,true);
      if(isset($d) && $d !='')
          return tofriendlytime(($d-time()));
      else{
          $d=get_post_meta($course_id,'vibe_duration',true);
          return tofriendlytime(($d*86400));
      }              
}

function bp_get_course_badge($id=NULL){
  if(!isset($id))
    $id=get_the_ID();

  $badge=get_post_meta($id,'vibe_course_badge',true);

  return $badge;
}
function bp_get_total_instructor_count(){
  $args =  array(
    'role' => 'Instructor',
    'count_total' => true
    );
  $users = new WP_User_Query($args);
  return count($users->results);
  
}

function bp_get_course_certificate($args){
  $defaults=array(
    'course_id' =>get_the_ID(),
    'user_id'=> get_current_user_id()
    );


  $r = wp_parse_args( $args, $defaults );
  extract( $r, EXTR_SKIP );

  $certificate_template_id=get_post_meta($course_id,'vibe_certificate_template',true);

  if(isset($certificate_template_id) && $certificate_template_id){
      $pid = $certificate_template_id;
  }else{
      $pid=vibe_get_option('certificate_page');
  }

  $url = get_permalink($pid).'?c='.$course_id.'&u='.$user_id;
  return $url;
}

function bp_course_quiz_auto_submit($quiz_id,$user_id){
    $quiz_auto_evaluate=get_post_meta($quiz_id,'vibe_quiz_auto_evaluate',true);

    if(isset($quiz_auto_evaluate) && $quiz_auto_evaluate && $quiz_auto_evaluate !='H'){ // Auto Evaluate Enabled
        $total_marks=0;
        $questions = vibe_sanitize(get_post_meta($quiz_id,'vibe_quiz_questions',false));
        if(count($questions)){
            $sum=$max_sum=0;
            foreach($questions['ques'] as $key=>$question){ // Grab all the Questions
              if(isset($question) && $question){
                  $type = get_post_meta($question,'vibe_question_type',true); 
                  if(isset($type) && ($type == 'single') ){
                    $correct_answer=get_post_meta($question,'vibe_question_answer',true);
                    $comments_query = new WP_Comment_Query;
                    
                    $comments = $comments_query->query( array('post_id'=> $question,'user_id'=>$user_id,'number'=>1,'status'=>'approve') );   
                    foreach($comments as $comment){
                      if($comment->comment_content == $correct_answer){
                        $marks=$questions['marks'][$key];
                        $total_marks = $total_marks+$marks;
                      }else{
                        $marks = 0;
                      }
                      update_comment_meta( $comment->comment_ID, 'marks', $marks );
                    }//END-For
                  }
              }
            }
            update_post_meta( $quiz_id, $user_id,$total_marks);
            bp_course_record_activity(array(
              'action' => 'Quiz Auto Evaluated',
              'content' => 'Quiz '.get_the_title($quiz_id).' auto evaluated for user '.bp_core_get_userlink($user_id).' with marks '.$total_marks,
              'type' => 'evaluate_quiz',
              'primary_link' => get_permalink($quiz_id),
              'item_id' => $quiz_id,
              'secondary_item_id' => $user_id
            ));
          }  
    }  
}
function bp_course_validate_certificate($args){
  $defaults=array(
    'course_id' =>get_the_ID(),
    'user_id'=> get_current_user_id()
    );
  $r = wp_parse_args( $args, $defaults );
  extract( $r, EXTR_SKIP );
  $meta = vibe_sanitize(get_user_meta($user_id,'badges',false));
  if(in_array($course_id,$meta)){
    return;
  }else{
    wp_die(__('Certificate not valid for user','vibe'));
  }
}


function array_to_csv_download($array, $filename = "export.csv", $delimiter=";") {
    // open raw memory as file so no temp files needed, you might run out of memory though
    $f = fopen('php://memory', 'w'); 
    // loop over the input array
    foreach ($array as $line) { 
        // generate csv lines from the inner arrays
        fputcsv($f, $line, $delimiter); 
    }
    // rewrind the "file" with the csv lines
    fseek($f, 0);
    // tell the browser it's going to be a csv file
    header('Content-Type: application/csv');
    // tell the browser we want to save it instead of displaying it
    header('Content-Disposition: attachement; filename="'.$filename.'";');
    // make php send the generated csv lines to the browser
    fpassthru($f);
}


function bp_course_instructor_controls(){
  global $bp,$wpdb;
  $user_id=$bp->loggedin_user->id;
  $course_id = get_the_ID();

  $curriculum=vibe_sanitize(get_post_meta($course_id,'vibe_course_curriculum',false));
  $course_quizes=array();
  if(isset($curriculum))
    foreach($curriculum as $c){
      if(is_numeric($c)){
        if(get_post_type($c) == 'quiz'){
            $course_quizes[]=$c;
          }
      }
  }

  echo '<ul class="instructor_action_buttons">';

  $course_query = $wpdb->get_results($wpdb->prepare("SELECT COUNT(meta_key) as num FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_value = %d",$course_id,2));
  $num=0;
  if(isset($course_query) && $course_query !='')
    $num=$course_query[0]->num;
  else
    $num=0;

  echo '<li><a href="'.get_permalink($course_id).'/?action=admin&submissions" class="action_icon tip" title="'.__('Evaluate course submissions','vibe').'"><i class="icon-task"></i><span>'.$num.'</span></a></li>';  

  if(isset($course_quizes) && count($course_quizes)){
    if(count($course_quizes) > 1){
      $quiz_ids = join(',',$course_quizes);  
      $query = $wpdb->get_results($wpdb->prepare("SELECT COUNT(meta_key) as num FROM {$wpdb->postmeta} WHERE post_id IN {$quiz_ids} AND meta_value = %d",0));
    }else{
      $query = $wpdb->get_results($wpdb->prepare("SELECT COUNT(meta_key) as num FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_value = %d",$course_quizes,0));
    }
    
    if(isset($query) && $query !='')
      $num=$query[0]->num;
    else
      $num=0;

    echo '<li><a href="'.get_permalink($course_id).'/?action=admin&submissions" class="action_icon tip"  title="'.__('Evaluate Quiz submissions','vibe').'"><i class="icon-check-clipboard-1"></i><span>'.$num.'</span></a></li>';
  } 

  $n=get_post_meta($course_id,'vibe_students',true);
  if(isset($n) && $n !=''){$num=$n;}else{$num=0;}
  echo '<li><a href="'.get_permalink($course_id).'/?action=admin&members" class="action_icon tip"  title="'.__('Manage Students','vibe').'"><i class="icon-users"></i><span>'.$num.'</span></a></li>';
  echo '<li><a href="'.get_permalink($course_id).'/?action=admin&stats" class="action_icon tip"  title="'.__('See Stats','vibe').'"><i class="icon-analytics-chart-graph"></i></a></li>';
  echo '<li><a href="'.get_permalink($course_id).'/?action=admin&activity" class="action_icon tip"  title="'.__('See all Activity','vibe').'"><i class="icon-atom"></i></a></li>';
  echo '</ul>';
}
?>
