<?php

/**
 * BuddyPress - Groups Directory
 *
 * @package BuddyPress
 * @subpackage bp-default
 */
$flag=1;

$capability=vibe_get_option('group_create');
if(isset($capability)){
	$flag=0;
	switch($capability){
                case 1: 
			$flag=1;
		break;
		case 2: 
			if(current_user_can('edit_posts'))
			$flag=1;
		break;
		case 3:
			if(current_user_can('manage_options'))
			$flag=1;
		break;
	}

}


get_header( 'buddypress' ); ?>

<?php do_action( 'bp_before_directory_groups_page' ); ?>

<section id="grouptitle">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-8">
                <div class="pagetitle">
                    <h1><?php the_title(); ?></h1>
                    <?php the_sub_title(); ?>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
            	<?php if ( is_user_logged_in() && bp_user_can_create_groups() && $flag ) : ?> 
					&nbsp;
					<a class="button create-group-button full" href="<?php echo trailingslashit( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/create' ); ?>"><?php _e( 'Create a Group', 'vibe' ); ?></a>
				<?php endif; ?>
            </div>
        </div>
    </div>
</section>
	
<section id="content">
	<div id="buddypress">
	    <div class="container">
	    	<div class="padder">
				<?php do_action( 'bp_before_directory_groups' ); ?>

					<form action="" method="post" id="groups-directory-form" class="dir-form">
						<div class="row">
							<div class="col-md-12">
								<?php do_action( 'bp_before_directory_groups_content' ); ?>
								<?php do_action( 'template_notices' ); ?>
							</div>
						</div>	
						<div class="row">
							<div class="col-md-9 col-sm-8">
								<div class="item-list-tabs" role="navigation">
									<ul>
										<li class="selected" id="groups-all"><a href="<?php echo trailingslashit( bp_get_root_domain() . '/' . bp_get_groups_root_slug() ); ?>"><?php printf( __( 'All Groups <span>%s</span>', 'vibe' ), bp_get_total_group_count() ); ?></a></li>

										<?php if ( is_user_logged_in() && bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) : ?>

											<li id="groups-personal"><a href="<?php echo trailingslashit( bp_loggedin_user_domain() . bp_get_groups_slug() . '/my-groups' ); ?>"><?php printf( __( 'My Groups <span>%s</span>', 'vibe' ), bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ); ?></a></li>

										<?php endif; ?>

										<?php do_action( 'bp_groups_directory_group_filter' ); ?>

									</ul>
								</div><!-- .item-list-tabs -->
								<div class="item-list-tabs" id="subnav" role="navigation">
									<ul>

										<?php do_action( 'bp_groups_directory_group_types' ); ?>
										<li>
											<div id="group-dir-search" class="dir-search" role="search">
												<?php bp_directory_groups_search_form(); ?>
											</div><!-- #group-dir-search -->
										</li>
										<li id="groups-order-select" class="last filter">

											<label for="groups-order-by"><?php _e( 'Order By:', 'vibe' ); ?></label>
											<select id="groups-order-by">
												<option value="active"><?php _e( 'Last Active', 'vibe' ); ?></option>
												<option value="popular"><?php _e( 'Most Members', 'vibe' ); ?></option>
												<option value="newest"><?php _e( 'Newly Created', 'vibe' ); ?></option>
												<option value="alphabetical"><?php _e( 'Alphabetical', 'vibe' ); ?></option>

												<?php do_action( 'bp_groups_directory_order_options' ); ?>

											</select>
										</li>
									</ul>
								</div>

								<div id="groups-dir-list" class="groups dir-list">

									<?php locate_template( array( 'groups/groups-loop.php' ), true ); ?>

								</div><!-- #groups-dir-list -->

								<?php do_action( 'bp_directory_groups_content' ); ?>

								<?php wp_nonce_field( 'directory_groups', '_wpnonce-groups-filter' ); ?>

								<?php do_action( 'bp_after_directory_groups_content' ); ?>
							</div>	
							<div class="col-md-3 col-sm-4">
								<div class="buddysidebar">
									<?php
								 		$sidebar = apply_filters('wplms_sidebar','buddypress',get_the_ID());
						                if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar($sidebar) ) : ?>
					               	<?php endif; ?>
								</div>
							</div>
						</div>
					</form><!-- #groups-directory-form -->

						<?php do_action( 'bp_after_directory_groups' ); ?>

					</div><!-- .padder -->
				</div><!-- #container -->
			</div>
</section>
</div> <!-- Extra Global div in header -->									
<?php do_action( 'bp_after_directory_groups_page' ); ?>

<?php get_footer( 'buddypress' ); ?>