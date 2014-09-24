<?php get_header( 'buddypress' ); ?>

<section id="memberstitle">
    <div class="container">
        <div class="row">
             <div class="col-md-9 col-sm-8">
                <div class="pagetitle">
                   	<h1><?php single_cat_title(); ?></h1>
                    <h5><?php echo category_description(); ?></h5>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
                <?php vibe_breadcrumbs(); ?> 
            </div>
        </div>
    </div>
</section>
<section id="content">
	<div id="buddypress">
    <div class="container">
		<div class="padder">
		<?php do_action( 'bp_before_directory_course' ); ?>	
		<div class="row">
			<div class="col-md-9 col-sm-8">
				<div class="content padding_adjusted">
				<?php
					if ( have_posts() ) : while ( have_posts() ) : the_post();

					echo '<div class="col-md-4 col-sm-6 clear3">'.thumbnail_generator($post,'course','3','0',true,true).'</div>';
				
					endwhile;
					pagination();
					endif;
				?>
				</div>
			</div>	
			<div class="col-md-3 col-sm-3">
				<?php
                    $sidebar = apply_filters('wplms_sidebar','buddypress',get_the_ID());
                    if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar($sidebar) ) : ?>
                <?php endif; ?>
			</div>
		</div>	
		<?php do_action( 'bp_after_directory_course' ); ?>

		</div><!-- .padder -->
	
	<?php do_action( 'bp_after_directory_course_page' ); ?>
</div><!-- #content -->
</div>
</section>

<?php get_footer( 'buddypress' ); ?>