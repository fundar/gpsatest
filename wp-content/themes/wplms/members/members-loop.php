<?php

/**
 * BuddyPress - Members Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_dtheme_object_filter()
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

$loop_number=vibe_get_option('loop_number');
isset($loop_number)?$loop_number:$loop_number=5;

?>

<?php do_action( 'bp_before_members_loop' ); ?>



<?php if ( bp_has_members( bp_ajax_querystring( 'members' ).'&per_page='.$loop_number ) ) : ?>

	<div id="pag-top" class="pagination">

		<div class="pag-count" id="member-dir-count-top">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-dir-pag-top">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php do_action( 'bp_before_directory_members_list' );  ?>

	<ul id="members-list" class="item-list" role="main">

	<?php while ( bp_members() ) : bp_the_member(); 

		global $members_template; 
		$user_id = $members_template->member->id;
		$role = 'student';
		if(user_can($user_id,'edit_posts'))
			$role = 'instructor';
		if(user_can($user_id,'manage_options'))
			$role = 'administrator';
	?>

		<li <?php echo 'class="role-'.$role.'"'; ?>>
			<div class="item-avatar">
				<a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar(); ?></a>
			</div>

			<div class="item">
				<div class="item-title">
					<?php

					echo '<span>'.$role.'</span>';
					?>
					<a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a>
					<?php
					if($role == 'student'){
						$field = vibe_get_option('student_field');

						if(!isset($field) || $field =='')
							$field = 'Location';


					if(bp_is_active('xprofile'))
						echo '<span>'.bp_get_profile_field_data( array('user_id'=>$user_id,'field'=>$field )).'</span>';
					}else if($role == 'instructor'){
						$field = vibe_get_option('instructor_field');

						if(!isset($field) || $field =='')
							$field = 'Expertise';
						if(bp_is_active('xprofile'))
						echo '<span>'.bp_get_profile_field_data( array('user_id'=>$user_id,'field'=>$field )).'</span>';
					}

					$members_activity=vibe_get_option('members_activity');
					if(isset($members_activity) && $members_activity){
					?>
					<?php if ( bp_get_member_latest_update() ) : ?>

						<span class="update"> <?php bp_member_latest_update(); ?></span>

					<?php endif; 

					?>

				</div>

				<div class="item-meta"><span class="activity"><?php bp_member_last_active(); ?></span>
				<?php } ?>
				</div>

				<?php do_action( 'bp_directory_members_item' ); ?>

				<?php
				 /***
				  * If you want to show specific profile fields here you can,
				  * but it'll add an extra query for each member in the loop
				  * (only one regardless of the number of fields you show):
				  *
				  * bp_member_profile_data( 'field=the field name' );
				  */
				?>
			</div>

			<div class="action">

				<?php do_action( 'bp_directory_members_actions' ); ?>

			</div>

			<div class="clear"></div>
		</li>

	<?php endwhile; ?>

	</ul>

	<?php do_action( 'bp_after_directory_members_list' ); ?>

	<?php bp_member_hidden_fields(); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="member-dir-count-bottom">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-dir-pag-bottom">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( "Sorry, no members were found.", 'vibe' ); ?></p>
	</div>

<?php endif; ?>

<?php do_action( 'bp_after_members_loop' ); ?>
