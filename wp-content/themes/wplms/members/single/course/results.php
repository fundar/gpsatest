<?php do_action( 'bp_before_course_results' ); ?>

<?php 
$user_id=get_current_user_id();
global $wpdb,$bp;

if(isset($_GET['action']) && !is_numeric($_GET['action'])){
		echo '<div id="message"><p>'.__('Invalid Results','vibe').'</p></div>';
}else{

if(isset($_GET['action']) && $_GET['action']){ // Check Action
	
	$id=intval($_GET['action']); 
	$post_type = get_post_type($id);

	switch($post_type){
		case 'wplms-assignment':
			$assignment_id = $id;
			$assignment_post=get_post($assignment_id);
    		$assignment_marks = get_post_meta($assignment_id,$user_id,true);
    		$total_assignment_marks = get_post_meta($assignment_id,'vibe_assignment_marks',true);

		    echo '<div class="assignment_content">';
		    echo apply_filters('the_content',$assignment_post->post_content);

		    echo '<h3 class="heading">'.__('My Submission','vibe').'</h3>';
			$answers=get_comments(array(
		      'post_id' => $assignment_id,
		      'status' => 'approve',
		      'number' => 1,
		      'user_id' => $user_id
		      ));

		    if(isset($answers) && is_array($answers) && count($answers)){
		        $answer = end($answers);
		        echo $answer->comment_content;
		        $attachment_id=get_comment_meta($answer->comment_ID, 'attachmentId',true);
		        if(isset($attachment_id) && $attachment_id)
		        echo '<div class="download_attachment"><a href="'.wp_get_attachment_url($attachment_id).'" target="_blank"><i class="icon-download-3"></i> '.__('Download Attachment','vibe').'</a></div>';
		    }

		    $table_name=$bp->activity->table_name;
		    $meta_table_name=$bp->activity->table_name_meta;
		    $remarkmessage = $wpdb->get_results($wpdb->prepare( "
		    							SELECT meta_value FROM {$meta_table_name} as meta
		    							WHERE meta_key = 'remarks'
		    							AND meta.activity_id IN (SELECT activity.id FROM {$table_name} AS activity
										WHERE 	activity.component 	= 'course'
										AND 	activity.type 	= 'evaluate_assignment'
										AND 	item_id = %d
										AND 	secondary_item_id = %d
										ORDER BY date_recorded DESC)
									" ,$assignment_id,$user_id));
		    $remarks=$remarkmessage[0]->meta_value;
		    if(isset($remarks)){
		    	echo '<a href="'.trailingslashit( bp_loggedin_user_domain() . $bp->messages->slug . '/view/' . $remarks ).'" class="button right small">'.__('See Instructor Remarks','vibe').'</a><span class="clearfix"></span>';
		    }
		    
		    echo '<div id="total_marks">'.__('Marks Obtained','vibe').' <strong><span>'.$assignment_marks.'</span> / '.$total_assignment_marks.'</strong> </div>';
		    echo '</div>';
		break;
		case 'quiz':
			$quiz_id = $id;
			$retakes=get_post_meta($quiz_id,'vibe_quiz_retakes',true);
			$course=get_post_meta($quiz_id,'vibe_quiz_course',true);
			
			$questions = vibe_sanitize(get_post_meta($quiz_id,'quiz_questions'.$user_id,false));
		    if(!isset($questions) || !is_array($questions)) // Fallback for Older versions
		    $questions = vibe_sanitize(get_post_meta($quiz_id,'vibe_quiz_questions',false));


			$sum=$total_sum=0;
			echo '<div class="quiz_result"><h3 class="heading">'.get_the_title($quiz_id).((isset($course) && is_numeric($course))?'<a href="'.get_permalink($course).'" class="small_link">( &larr; '.__('BACK TO COURSE','vibe').' )</a>':'').'<span class="right">'.social_sharing().' <a class="print_results"><i class="icon-printer-1"></i></a></span></h3>';
			if(count($questions)){

				echo '<ul class="quiz_questions">';

				foreach($questions['ques'] as $key=>$question){
					if(isset($question) && is_numeric($question)){
					$q=get_post($question);
					echo '<li>
							<div class="q">'.apply_filters('the_content',$q->post_content).'</div>';
					$comments_query = new WP_Comment_Query;

					$comments = $comments_query->query( array('post_id'=> $question,'user_id'=>$user_id,'number'=>1,'status'=>'approve') );		

					echo '<strong>';
					_e('Marked Answer :','vibe');
					echo '</strong>';

					$correct_answer=get_post_meta($question,'vibe_question_answer',true);
					$marks=0;
					foreach($comments as $comment){ // This loop runs only once
						$type = get_post_meta($question,'vibe_question_type',true);

					    switch($type){
					      case 'truefalse': 
					      	$options = array( 0 => __('FALSE','vibe'),1 =>__('TRUE','vibe'));
					      	
					        echo $options[(intval($comment->comment_content))]; // Reseting for the array
					        if(isset($correct_answer) && $correct_answer !=''){
					        	$ans=$options[(intval($correct_answer))];
					        }
					      break;  	
					      case 'single':
					      case 'select':
					      	$options = vibe_sanitize(get_post_meta($question,'vibe_question_options',false));
					      	
					        echo $options[(intval($comment->comment_content)-1)]; // Reseting for the array
					        if(isset($correct_answer) && $correct_answer !=''){
					        	$ans=$options[(intval($correct_answer)-1)];
					        }
					      break;  
					      case 'sort': 
					      case 'match': 
					      case 'multiple': 
			              $options = vibe_sanitize(get_post_meta($question,'vibe_question_options',false));
			              $ans=explode(',',$comment->comment_content);

			              foreach($ans as $an){
			                echo $options[intval($an)-1].' ';
			              }

			              $cans = explode(',',$correct_answer);
			              $ans='';
			              foreach($cans as $can){
			                $ans .= $options[intval($can)-1].', ';
			              }
			            break;
					      case 'fillblank':
					      case 'smalltext': 
					        	echo $comment->comment_content;
					        	$ans = $correct_answer;
					      break;
					      case 'largetext': 
					        	echo apply_filters('the_content',$comment->comment_content);
					        	$ans = $correct_answer;
					      break;
						}

						$marks=get_comment_meta( $comment->comment_ID, 'marks', true );
					}// END- COMMENTS-FOR
					
					if(isset($correct_answer) && $correct_answer !='' && isset($marks) && $marks !=''){
						$explaination = get_post_meta($question,'vibe_question_explaination',true);
						echo '<strong>';
						_e('Correct Answer :','vibe');
						echo '<span>'.$ans.' '.((isset($explaination) && $explaination && strlen($explaination) > 5)?'<a class="show_explaination tip" title="'.__('View answer explaination','vibe').'"></a>':'').'</span></strong>';
					}
						
					
					$total_sum=$total_sum+intval($questions['marks'][$key]);
					echo '<span> '.__('Total Marks :','vibe').' '.$questions['marks'][$key].'</span>';

					if(isset($marks) && $marks !=''){
						if($marks){
							echo '<span>'.__('MARKS OBTAINED','vibe').' <i class="icon-check"></i> '.$marks.'</span>';
							$sum = $sum+intval($marks);
						}else{
							echo '<span>'.__('MARKS OBTAINED','vibe').' <i class="icon-x"></i> '.$marks.'</span>';
						}
					}else{
						echo '<span>'.__('Marks Obtained','vibe').' <i class="icon-alarm"></i></span>';
					}
					if(isset($explaination) && $explaination && strlen($explaination) > 5){
						echo '<div class="explaination">'.$explaination.'</div>';
					}
					
					echo '</li>';
					} // IF question check
				}	// END FOR LOOP

				echo '</ul>';
				echo '<div id="total_marks">'.__('Total Marks','vibe').' <strong><span>'.$sum.'</span> / '.$total_sum.'</strong> </div></div>';
				do_action('wplms_quiz_results_extras');
				
				if(isset($retakes) && $retakes){
					global $bp;
					$table_name=$bp->activity->table_name;
					$quiz_retakes = $wpdb->get_results($wpdb->prepare( "
										SELECT activity.content FROM {$table_name} AS activity
										WHERE 	activity.component 	= 'course'
										AND 	activity.type 	= 'retake_quiz'
										AND 	user_id = %d
										AND 	item_id = %d
										ORDER BY date_recorded DESC
									" ,$user_id,$quiz_id));

					if(($retakes - count($quiz_retakes)) > 0){
						echo '<form method="post" class="quiz_retake_form" action="'.get_permalink($quiz_id).'">';
						echo '<input type="submit" name="initiate_retake" value="'.__('RETAKE QUIZ','vibe').'" />';
						echo '<p>'.__('Number of retakes available ','vibe').' : <strong>'.count($quiz_retakes).__(' out of ','vibe').$retakes.'</strong></p>';
						wp_nonce_field('retake'.$user_id,'security');
						echo '</form>';
					}

					echo '<h4 id="prev_results"><a href="#">'.__('Previous Results for Quiz ','vibe').'</a></h4>';
					$quiz_results = $wpdb->get_results($wpdb->prepare( "
										SELECT activity.content FROM {$table_name} AS activity
										WHERE 	activity.component 	= 'course'
										AND 	activity.type 	= 'evaluate_quiz'
										AND 	user_id = %d
										AND 	item_id = %d
										ORDER BY date_recorded DESC
									" ,$user_id,$quiz_id));

					if(count($quiz_results) > 0){
						echo '<ul class="prev_quiz_results">';
						foreach($quiz_results as $content){
							echo '<li>'.$content->content.'</li>';
							}
							echo '</ul>';
						}else{
							echo '<div id="message"><p>'.__('Results not Available','vibe').'</p></div>';
						}
					} // END Retakes
				}//END Questions	
			break;
			}	

		}else{

		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
		$the_quiz=new WP_QUERY(array(
			'post_type'=>'quiz',
			'paged' => $paged,
			'meta_query'=>array(
				array(
					'key' => $user_id,
					'compare' => 'EXISTS'
					),
				),
			));

			if($the_quiz->have_posts()){
		?>
		<h3 class="heading"><?php _e('Quiz Results','vibe'); ?></h3>
		<ul class="quiz_results">
		<?php
			while($the_quiz->have_posts()) : $the_quiz->the_post();
			$value = get_post_meta(get_the_ID(),$user_id,true);
			$questions = vibe_sanitize(get_post_meta(get_the_ID(),'vibe_quiz_questions',false));
			if(is_Array($questions['marks']) && isset($questions['marks']))
				$max = array_sum($questions['marks']);
			else
				$max = 0; 
		?>
		<li><i class="icon-task"></i>
			<a href="?action=<?php echo get_the_ID(); ?>"><?php the_title(); ?></a>
			<span><?php	
			if($value > 0){
				echo '<i class="icon-check"></i> '.__('Results Available','vibe');
			}else{
				echo '<i class="icon-alarm"></i> '.__('Results Awaited','vibe');
			}
			?></span>
			<span><?php
			$newtime=get_user_meta($user_id,get_the_ID(),true);
			$diff=time()-$newtime;

			echo '<i class="icon-clock"></i> '.__('Submitted ','vibe').tofriendlytime($diff) .__(' ago','vibe');

			?></span>
			<?php
			if($value > 0)
				echo '<span><strong>'.$value.' / '.$max.'</strong></span>';
			?>
		</li>

		<?php
			endwhile;
			wp_reset_query();
			?>
			</ul>
			<?php
		  	
		  	}

		  	//ASSIGNMENTS	
		  	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
			$the_assignment=new WP_QUERY(array(
				'post_type'=>'wplms-assignment',
				'paged' => $paged,
				'meta_query'=>array(
					array(
						'key' => $user_id,
						'compare' => 'EXISTS'
						),
					),
				));

			if($the_assignment->have_posts()){
			?>
			<h3 class="heading"><?php _e('Assignment Results','vibe'); ?></h3>
			<ul class="quiz_results">	
		  	<?php
			while($the_assignment->have_posts()) : $the_assignment->the_post();
			$value = get_post_meta(get_the_ID(),$user_id,true);
			$max = get_post_meta(get_the_ID(),'vibe_assignment_marks',true);
			?>
			<li><i class="icon-task"></i>
				<a href="?action=<?php echo get_the_ID(); ?>"><?php the_title(); ?></a>
				<span><?php	
				if($value > 0){
					echo '<i class="icon-check"></i> '.__('Results Available','vibe');
				}else{
					echo '<i class="icon-alarm"></i> '.__('Results Awaited','vibe');
				}
				?></span>
				<span><?php
				$newtime=get_user_meta($user_id,get_the_ID(),true);
				$diff=time()-$newtime;

				echo '<i class="icon-clock"></i> '.__('Submitted ','vibe').tofriendlytime($diff) .__(' ago','vibe');

				?></span>
				<?php
				if($value > 0)
					echo '<span><strong>'.$value.' / '.$max.'</strong></span>';
				?>
			</li>
		  	<?php
		  	endwhile;
		  	?>
		  	</ul>
		  	<?php
				wp_reset_query();
		  		}// End Assignment -> Have posts
			}
}//END ELSE
?>
<?php do_action( 'bp_after_course_results' ); ?>