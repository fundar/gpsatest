<?php
global $post;
$id= get_the_ID();


if(isset($_REQUEST['error'])){ 
	switch($_REQUEST['error']){
		case 'precourse':
			$pre=get_post_meta($id,'vibe_pre_course',true);
			echo '<div id="message" class="notice"><p>'.__('Requires completition of course ','vibe').get_the_title($pre).'</p></div>';
		break;
	}
}

if(have_posts()):
while(have_posts()):the_post();
?>

<div class="course_title">
	<?php vibe_breadcrumbs(); ?>
	<h1><?php the_title(); ?></h1>
	<h6><?php the_excerpt(); ?></h6>
</div>
<div class="students_undertaking">
	<?php
	$students_undertaking=array();
	$students_undertaking = bp_course_get_students_undertaking();
	$students=get_post_meta(get_the_ID(),'vibe_students',true);

	echo '<strong>'.$students.' STUDENTS ENROLLED</strong>';

	echo '<ul>';
	$i=0;
	foreach($students_undertaking as $student){
		$i++;
		echo '<li>'.get_avatar($student).'</li>';
		if($i>5)
			break;
	}
	echo '</ul>';
	?>
</div>

<div class="course_description">
	<div class="small_desc">
	<?php $content=get_the_content(); 
		echo substr(apply_filters('the_content',$content),0,1200).' <a href="#" id="more_desc" class="link">READ MORE</a>';
		
	?>
	</div>
	<div class="full_desc">
		<?php
			echo substr(apply_filters('the_content',$content),1200,-1);
			echo '<a href="#" id="less_desc" class="link">LESS</a>';
		?>
	</div>
</div>


<div class="course_reviews">
<?php
	 comments_template('/course-review.php',true);
?>
</div>

<?php
endwhile;
endif;
?>