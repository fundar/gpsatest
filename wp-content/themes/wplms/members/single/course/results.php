<?php do_action( 'bp_before_course_results' ); ?>

<?php 
$user_id=get_current_user_id();

if(isset($_GET['action']) && $_GET['action']):
	$quiz_id=intval($_GET['action']);
	$questions = vibe_sanitize(get_post_meta($quiz_id,'vibe_quiz_questions',false));
	$sum=$total_sum=0;

	if(count($questions)):
		echo '<ul class="quiz_questions">';
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

				$marks=get_comment_meta( $comment->comment_ID, 'marks', true );
			}

			if(isset($correct_answer) && $correct_answer !='' && isset($marks) && $marks !=''){
				echo '<strong>';
				_e('Correct Answer :','vibe');
				echo '<span>'.$ans.'</span></strong>';


			}
			
			$total_sum=$total_sum+intval($questions['marks'][$key]);
			echo '<span> Total Marks : '.$questions['marks'][$key].'</span>';

			if(isset($marks) && $marks !=''){
				if($marks){
					echo '<span>'.__('MARKS OBTAINED','vibe').' <i class="icon-check"></i> '.$marks.'</span>';
					$sum = $sum+intval($marks);
				}else{
					echo '<span>'.__('MARKS OBTAINED','vibe').' <i class="icon-x"></i> '.$marks.'</span>';
				}
			}else{
				echo '<span>Marks Obtained <i class="icon-alarm"></i></span>';
			}
			echo '</li>';

			} // IF question check
		}	
		echo '</ul>';
		echo '<div id="total_marks">'.__('Total Marks','vibe').' <strong><span>'.$sum.'</span> / '.$total_sum.'</strong> </div>';

	endif;

else:

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

if($the_quiz->have_posts()):
	?>
<ul class="quiz_results">
<?php
	while($the_quiz->have_posts()) : $the_quiz->the_post();
	$value = get_post_meta(get_the_ID(),$user_id,true);
	$questions = vibe_sanitize(get_post_meta(get_the_ID(),'vibe_quiz_questions',false));
	
	$max = array_sum($questions['marks']);
?>
<li><i class="icon-task"></i>
	<a href="?action=<?php echo get_the_ID(); ?>"><?php the_title(); ?></a>
	<span><?php	
	if($value > 0){
		echo '<i class="icon-check"></i> Results Available';
	}else{
		echo '<i class="icon-alarm"></i> Results Awaited';
	}
	?></span>
	<span><?php
	$newtime=get_user_meta($user_id,get_the_ID(),true);
	$diff=time()-$newtime;

	echo '<i class="icon-clock"></i> Submitted '.tofriendlytime($diff) .' ago';

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
  endif;		
endif;	
?>
<?php do_action( 'bp_after_course_results' ); ?>