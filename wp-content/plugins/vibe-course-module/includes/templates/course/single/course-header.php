<?php

do_action( 'bp_before_course_header' );

?>

	<div id="item-header-avatar">
		<a href="<?php bp_course_permalink(); ?>" title="<?php bp_course_name(); ?>">

			<?php bp_course_avatar(); ?>

		</a>
	</div><!-- #item-header-avatar -->


<div id="item-header-content">
	<span class="highlight"><?php bp_course_type(); ?></span>
	<h3><a href="<?php bp_course_permalink(); ?>" title="<?php bp_course_name(); ?>"><?php bp_course_name(); ?></a></h3>
	 <!--span class="activity"><?php //printf( __( 'active %s', 'vibe' ), bp_get_course_last_active() ); ?></span-->

	<?php do_action( 'bp_before_course_header_meta' ); ?>

	<div id="item-meta">
		<?php bp_course_meta() ?>
											
		<div id="item-buttons">
			<?php bp_course_action() ?>
			<?php do_action( 'bp_course_header_actions' ); ?>

		</div><!-- #item-buttons -->

		<?php do_action( 'bp_course_header_meta' ); ?>

	</div>
</div><!-- #item-header-content -->

<div id="item-admins">

<h3><?php _e( 'Instructors', 'vibe' ); ?></h3>
	<div class="item-avatar">
	<?php 
	bp_course_instructor_avatar();
	?>
	</div>
	<?php
	bp_course_instructor();

	do_action( 'bp_after_course_menu_instructors' );
	?>
</div><!-- #item-actions -->

<?php
do_action( 'bp_after_course_header' );
?>