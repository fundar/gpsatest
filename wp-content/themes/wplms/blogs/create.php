<?php

/**
 * BuddyPress - Create Blog
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

get_header( 'buddypress' ); ?>


<?php do_action( 'bp_before_create_blog_content_template' ); ?>
<section id="blogtitle">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-8">
                <div class="pagetitle">
                    <h1><?php the_title(); ?></h1>
                    <?php the_sub_title(); ?>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
            	<?php if ( is_user_logged_in() && bp_user_can_create_groups() ) : ?> 
					&nbsp;
					<a class="button" href="<?php echo trailingslashit( bp_get_root_domain() . '/' . bp_get_blogs_root_slug() ); ?>"><?php _e( 'Site Directory', 'vibe' ); ?></a>
				<?php endif; ?>
            </div>
        </div>
    </div>
</section>
<section id="content">
	<div id="buddypress">
	    <div class="container">
	    	<div class="padder">
			<?php do_action( 'bp_before_create_blog_content' ); ?>
			<div class="row">	
				<div class="col-md-9 col-sm-8">	
					<?php do_action( 'template_notices' ); ?>		
					<?php if ( bp_blog_signup_enabled() ) : ?>

						<?php bp_show_blog_signup_form(); ?>

					<?php else: ?>

						<div id="message" class="info">
							<p><?php _e( 'Site registration is currently disabled', 'vibe' ); ?></p>
						</div>

					<?php endif; ?>

					<?php do_action( 'bp_after_create_blog_content' ); ?>
					
					<?php do_action( 'bp_after_create_blog_content_template' ); ?>
				</div>
				<div class="col-md-3 col-sm-4">
					<?php get_sidebar( 'buddypress' ); ?>
				</div>	
			</div>	
			</div><!-- .padder -->
			<?php do_action( 'bp_after_directory_blogs_content' ); ?>
		</div>	
	</div><!-- #content -->
</section>	
</div>

<?php get_footer( 'buddypress' ); ?>

