<?php

/**
 *
 * @package BuddyPress_Skeleton_Component
 * @since 1.6
 */


?>

<?php do_action( 'bp_before_course_loop' ); ?>



<?php 
$user_id=get_current_user_id();

if ( bp_course_has_items( bp_ajax_querystring( 'course' ).'&user='.$user_id.'&per_page=5' ) ) : ?>
<?php // global $items_template; var_dump( $items_template ) ?>
	<div id="pag-top" class="pagination">

		<div class="pag-count" id="course-dir-count-top">

			<?php bp_course_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="course-dir-pag-top">

			<?php bp_course_item_pagination(); ?>

		</div>

	</div>
	
	<?php do_action( 'bp_before_directory_course_list' ); ?>

	<ul id="course-list" class="item-list" role="main">

	<?php while ( bp_course_has_items() ) : bp_course_the_item(); ?>

		<li>
			<div class="item-avatar">
				<?php bp_course_avatar(); ?>

			</div>
			<div class="item">
				<div class="item-title"><?php bp_course_title() ?></div>
				<div class="item-meta"><?php bp_course_meta() ?></div>
				<div class="item-desc"><?php bp_course_desc() ?></div>
				<div class="item-credits">
					<?php 
					$live=get_post_meta($id,$user_id,true);
						if(isset($live) && $live !=''){
							echo '<strong>';
							switch($live){
								case 0:
									echo '<a href="'.get_permalink($id).'" class="button">'.__('Start Course','vibe').'</a>';
								break;
								case 1:
									echo '<a href="'.get_permalink($id).'" class="button">'.__('Continue Course','vibe').'</a>';
								break;
								default:
									echo '<a href="'.get_permalink($id).'" class="button">'.__('Course Finished','vibe').'</a>';
								break;
							}
							echo '</strong>';
						}else{
							bp_course_credits();		
						}
					 ?>
				</div>
				<div class="item-instructor">
					<?php bp_course_instructor_avatar(); ?>
					<?php bp_course_instructor(); ?>
				</div>
				<div class="item-action"><?php bp_course_action() ?></div>
				<?php do_action( 'bp_directory_course_item' ); ?>

			</div>

			<div class="clear"></div>
		</li>

	<?php endwhile; ?>

	</ul>

	<?php do_action( 'bp_after_directory_course_list' ); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="course-dir-count-bottom">

			<?php bp_course_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="course-dir-pag-bottom">

			<?php bp_course_item_pagination(); ?>

		</div>

	</div>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'You have not subscribed to any Course.', 'vibe' ); ?></p>
	</div>

<?php endif;  ?>


<?php do_action( 'bp_after_course_loop' ); ?>
