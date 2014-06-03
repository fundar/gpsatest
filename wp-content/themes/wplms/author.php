<?php get_header( 'buddypress' ); ?>

<section id="memberstitle">
    <div class="container">
        <div class="row">
             <div class="col-md-9 col-sm-8">
                <div class="pagetitle">
                   	<h1><?php _e('All Courses by ','vibe'); the_author_meta("user_firstname");?> <?php the_author_meta("user_lastname");?></h1>
                    <h5><?php echo get_the_author_meta('description'); ?></h5>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
				<a class="button create-group-button full" href="<?php echo bp_core_get_user_domain( get_the_author_meta('ID')); ?>"><?php echo bp_core_get_user_displayname(get_the_author_meta('ID')); _e( ' Profile', 'vibe' ); ?></a>
            </div>
        </div>
    </div>
</section>
<section id="content">
	<div id="buddypress">
    <div class="container">

		<div class="padder">

		<div class="row">
			<div class="col-md-9 col-sm-8">
			<?php
				if ( have_posts() ) : while ( have_posts() ) : the_post();
				global $post;
				echo '<div class="col-md-4 col-sm-6">'.thumbnail_generator($post,'course','3','0',true,true).'</div>';
			
				pagination();
				endwhile;
				endif;
			?>
			</div>	
			<div class="col-md-3 col-sm-4">
				<?php get_sidebar( 'buddypress' ); ?>
			</div>
		</div>	
		<?php do_action( 'bp_after_directory_course' ); ?>

		</div><!-- .padder -->
	
	<?php do_action( 'bp_after_directory_course_page' ); ?>
</div><!-- #content -->
</div>
</section>

<?php get_footer( 'buddypress' ); ?>