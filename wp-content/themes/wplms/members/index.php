<?php

/**
 * BuddyPress - Members Directory
 *
 * @package BuddyPress
 * @subpackage bp-default
 */
$flag=1;
$members_view=vibe_get_option('members_view');

if(isset($members_view) && $members_view){
	$flag=0;
	switch($members_view){
		case 1:
			if(is_user_logged_in())$flag=1;
		break;
		case 2:
			if(current_user_can('edit_posts'))$flag=1;
		break;
		case 3:
			if(current_user_can('manage_options'))$flag=1;
		break;
	}
}

if(!$flag){
	$id=vibe_get_option('members_redirect');
	if(isset($id))
		wp_redirect(get_permalink($id));
	exit();
}
get_header( 'buddypress' ); ?>

<section id="memberstitle">
    <div class="container">
        <div class="row">
             <div class="col-md-9 col-sm-8">
                <div class="pagetitle">
                    <h1><?php the_title(); ?></h1>
                    <?php the_sub_title(); ?>
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
		<?php do_action( 'bp_before_directory_members_page' ); ?>
		<div class="padder">
			<?php do_action( 'bp_before_directory_members' ); ?>
				<form action="" method="post" id="members-directory-form" class="dir-form">
				<div class="row">	
					<?php do_action( 'bp_before_directory_members_tabs' ); ?>
					<div class="col-md-9 col-sm-8">	
						<div class="item-list-tabs" role="navigation">
							<ul>
								<li class="selected" id="members-all"><a href="<?php echo trailingslashit( bp_get_root_domain() . '/' . bp_get_members_root_slug() ); ?>"><?php printf( __( 'All Members <span>%s</span>', 'vibe' ), bp_get_total_member_count() ); ?></a></li>

								<?php if ( is_user_logged_in() && bp_is_active( 'friends' ) && bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>

									<li id="members-personal"><a href="<?php echo bp_loggedin_user_domain() . bp_get_friends_slug() . '/my-friends/' ?>"><?php printf( __( 'My Friends <span>%s</span>', 'vibe' ), bp_get_total_friend_count( bp_loggedin_user_id() ) ); ?></a></li>

								<?php endif; ?>
								
								<?php do_action( 'bp_members_directory_member_types' ); ?>

							</ul>
						</div><!-- .item-list-tabs -->
						<div class="item-list-tabs" id="subnav" role="navigation">
							<ul>
								<li>
									<div id="member-dir-search" class="dir-search" role="search">
										<?php bp_directory_members_search_form(); ?>
									</div><!-- #group-dir-search -->
								</li>
								<?php do_action( 'bp_members_directory_member_sub_types' ); ?>

								<li id="members-order-select" class="last filter">

									<label for="members-order-by"><?php _e( 'Order By:', 'vibe' ); ?></label>
									<select id="members-order-by">
										<option value="active"><?php _e( 'Last Active', 'vibe' ); ?></option>
										<option value="newest"><?php _e( 'Newest Registered', 'vibe' ); ?></option>

										<?php if ( bp_is_active( 'xprofile' ) ) : ?>

											<option value="alphabetical"><?php _e( 'Alphabetical', 'vibe' ); ?></option>

										<?php endif; ?>

										<?php do_action( 'bp_members_directory_order_options' ); ?>

									</select>
								</li>
							</ul>
						</div>

						<div id="members-dir-list" class="members dir-list">

							<?php locate_template( array( 'members/members-loop.php' ), true ); ?>

						</div><!-- #members-dir-list -->

						<?php do_action( 'bp_directory_members_content' ); ?>

						<?php wp_nonce_field( 'directory_members', '_wpnonce-member-filter' ); ?>

						<?php do_action( 'bp_after_directory_members_content' ); ?>
					</div>	
					<div class="col-md-3 col-sm-4">
						<?php
					 		$sidebar = apply_filters('wplms_sidebar','buddypress',get_the_ID());
			                if ( !function_exists('dynamic_sidebar')|| !dynamic_sidebar($sidebar) ) : ?>
		               	<?php endif; ?>
					</div>
				</div>	
			</form><!-- #members-directory-form -->
		<?php do_action( 'bp_after_directory_members' ); ?>
		</div><!-- .padder -->
	</div><!-- #content -->
	<?php do_action( 'bp_after_directory_members_page' ); ?>
	</div>
</section>	
</div>
<?php get_footer( 'buddypress' ); ?>
