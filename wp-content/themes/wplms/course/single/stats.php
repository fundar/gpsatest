<?php


		$course_id=get_the_ID();
		$students=get_post_meta($course_id,'vibe_students',true);

		$avg=get_post_meta($course_id,'average',true);
		$pass=get_post_meta($course_id,'pass',true);
		$badge=get_post_meta($course_id,'badge',true);


		echo '<div class="course_grade">
				<ul>
					<li>'.__('Total Number of Students who took this course','vibe').' <strong>'.$students.'</strong></li>
					<li>'.__('Average Percentage obtained by Students','vibe').' <strong>'.$avg.' <span>'.__('out of 100','vibe').'</span></strong></li>
					<li>'.__('Number of Students who got a Badge','vibe').' <strong>'.$badge.'</strong></li>
					<li>'.__('Number of Passed Students','vibe').' <strong>'.$pass.'</strong></li>
					<li>'.__('Number of Students who did not pass ','vibe').' <strong>'.($students-$pass).'</strong></li>
				</ul>
			</div>';
		echo '<div id="average">'.__('Average Marks obtained by Students','vibe').'<input type="text" class="dial" data-max="100" value="'.$avg.'"></div>';
		echo '<div id="pass">'.__('Number of Passed Students','vibe').' <input type="text" class="dial" data-max="'.$students.'" value="'.$pass.'"></div>';	
		echo '<div id="badge">'.__('Number of Students who got a Badge','vibe').'<input type="text" class="dial" data-max="'.$students.'" value="'.$badge.'"></div>';

		
		
		
		$curriculum=vibe_sanitize(get_post_meta(get_the_ID(),'vibe_course_curriculum',false));
		foreach($curriculum as $c){
			if(is_numeric($c)){
				if(get_post_type($c) == 'quiz'){
					$qavg=get_post_meta($c,'average',true);

					$ques = vibe_sanitize(get_post_meta($c,'vibe_quiz_questions',false));
					$qmax= array_sum($ques['marks']);

					echo '<div class="course_quiz">
							<h5>'.__('Average Marks in Quiz ','vibe').' '.get_the_title($c).'</h5>
							<input type="text" class="dial" data-max="'.$qmax.'" value="'.$qavg.'">
						</div>';			
				}
			}
		}
		

		echo '<div class="calculate_panel"><strong>'.__('Calculate :','vibe').'</strong>';
			echo '<a href="#" id="calculate_avg_course" data-courseid="'.get_the_ID().'" class="tip" title="'.__('Calculate Statistics for Course','vibe').'"> <i class="icon-calculator"></i> </a>';
			wp_nonce_field('vibe_security','security'); // Just random text to verify
		echo '</div>';
		
?>