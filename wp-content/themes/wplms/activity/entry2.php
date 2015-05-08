<?php

/**
 * BuddyPress - Activity Stream (Single Item)
 *
 * This template is used by activity-loop.php and AJAX functions to show
 * each activity.
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

?>

<?php do_action( 'bp_before_activity_entry' ); ?>
<section id="activitytitle">
    <div class="container">
        <div class="row">
			
<li class="<?php bp_activity_css_class(); ?>" id="activity-<?php bp_activity_id(); ?>">
	<div class="activity-avatar">
		<a href="<?php bp_activity_user_link(); ?>">

			<?php bp_activity_avatar(); ?>

		</a>
                            <?php do_action( 'showcountry'); ?>

	</div>

	<div class="activity-content">

		<div class="activity-header">

			<?php bp_activity_action(); ?>

		</div>

		<?php if ( 'activity_comment' == bp_get_activity_type() ) : ?>

			<div class="activity-inreplyto">
				<strong><?php _e( 'In reply to: ', 'vibe' ); ?></strong><?php bp_activity_parent_content(); ?> <a href="<?php bp_activity_thread_permalink(); ?>" class="view" title="<?php _e( 'View Thread / Permalink', 'vibe' ); ?>"><?php _e( 'View', 'vibe' ); ?></a>
			</div>

		<?php endif; ?>

		<?php if ( bp_activity_has_content() ) : ?>

			<div class="activity-inner">

				<?php bp_activity_content_body(); ?>

			</div>
			<div class="read_more">
				<a href="http://gpsaknowledge.org/networking/" rel="nofollow">[Read more]</a>
			</div>
		<?php endif; ?>

	
		
	</div>

	
</li>

		</div>
    </div>
</section>
<?php do_action( 'bp_after_activity_entry' ); ?>
