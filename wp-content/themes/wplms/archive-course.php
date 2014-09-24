<?php get_header( 'buddypress' ); 


?>

<section id="memberstitle">
    <div class="container">
        <div class="row">
             <div class="col-md-9 col-sm-8">
                <div class="pagetitle">
                    <h1><?php _e('Course Directory','vibe'); ?></h1>
                    <h5><?php _e('All Courses by all instructors','vibe'); ?></h5>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
            	<?php 
            		do_action('wplms_be_instructor_button');	
				?>
            </div>
        </div>
    </div>
</section>
<section id="content">
	<div id="buddypress">
    <div class="container">

	<?php do_action( 'bp_before_directory_course_page' ); ?>

		<div class="padder">

		<?php do_action( 'bp_before_directory_course' ); ?>
		<div class="row">
			<div class="col-md-9 col-sm-8">
				<form action="" method="post" id="course-directory-form" class="dir-form">

					<?php do_action( 'bp_before_directory_course_content' ); ?>

					<?php do_action( 'template_notices' ); ?>

					<div class="item-list-tabs" role="navigation">
						<ul>

							
							<li class="selected" id="course-all"><a href="<?php echo trailingslashit( bp_get_root_domain() . '/' . bp_get_course_root_slug() ); ?>"><?php printf( __( 'All Courses <span>%s</span>', 'vibe' ), bp_course_get_total_course_count() ); ?></a></li>
							<?php
							if(is_user_logged_in()){
							?>
							<li id="my-course"><a href="<?php echo trailingslashit( bp_loggedin_user_domain() . bp_get_course_slug() . 'course' ); ?>"><?php printf( __( 'My Courses <span>%s</span>', 'vibe' ), bp_course_get_total_course_count_for_user(bp_loggedin_user_id()) ); ?></a></li>

							<?php 
							}
							bp_get_options_nav(); ?>

							<?php do_action( 'bp_course_directory_filter' ); ?>

						</ul>
					</div><!-- .item-list-tabs -->
					<div class="item-list-tabs" id="subnav" role="navigation">
						<ul>
							<?php do_action( 'bp_course_directory_course_types' ); ?>
							<li>
								<div id="group-dir-search" class="dir-search" role="search">
									<?php bp_directory_course_search_form(); ?>
								</div><!-- #group-dir-search -->
							</li>
							<li id="groups-order-select" class="last filter">

								<label for="groups-order-by"><?php _e( 'Order By:', 'vibe' ); ?></label>
								<select id="groups-order-by">
									<option value="alphabetical"><?php _e( 'Alphabetical', 'vibe' ); ?></option>
									<option value="newest"><?php _e( 'Newly Created', 'vibe' ); ?></option>
									<option value="popular"><?php _e( 'Most Members', 'vibe' ); ?></option>
									<option value="rating"><?php _e( 'Highest Rated', 'vibe' ); ?></option>
									
									<?php do_action( 'bp_course_directory_order_options' ); ?>

								</select>
							</li>
						</ul>
					</div>
					<div id="course-dir-list" class="course dir-list">

						<?php //bp_core_load_template( 'course/course-loop' );  
						//******* EXTREME BIG BIG ISSUE WITH BUDDYPRESS CONTROL NEVER RETUNRS; ?>


							<?php do_action( 'bp_before_course_loop' ); ?>



							<?php if ( bp_course_has_items( bp_ajax_querystring( 'course' ) ) ) : ?>
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
											<div class="item-instructor">
												<?php bp_course_credits(); ?>
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
									<p><?php _e( 'You have not subscribed to any courses.', 'vibe' ); ?></p>
								</div>

							<?php endif;  ?>

							<?php do_action( 'bp_after_course_loop' ); ?>


					</div><!-- #courses-dir-list -->

					<?php do_action( 'bp_directory_course_content' ); ?>

					<?php  wp_nonce_field( 'directory_course', '_wpnonce-course-filter' ); ?>

					<?php do_action( 'bp_after_directory_course_content' ); ?>


				</form><!-- #course-directory-form -->
			</div>	
			<div class="col-md-3 col-sm-3"><?php
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

