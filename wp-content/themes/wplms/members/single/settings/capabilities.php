<?php

/**
 * BuddyPress Delete Account
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

get_header( 'buddypress' ); ?>

<section id="content">
	<div id="buddypress">
	    <div class="container">
	        <div class="row">
	            <div class="col-md-3 col-sm-3">
	             <?php do_action( 'bp_before_member_settings_template' ); ?>
	                <div class="pagetitle">
	                	<div id="item-header" role="complementary">
						<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>
						</div><!-- #item-header -->
					</div>
					<div id="item-nav">
						<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
							<ul>

							<?php bp_get_displayed_user_nav(); ?>

							<?php do_action( 'bp_member_options_nav' ); ?>

						
							</ul>
						</div>
					</div><!-- #item-nav -->
			</div>	

			<div class="col-md-6 col-sm-6">
					<div class="padder">
						<div id="item-body">

				<?php do_action( 'bp_before_member_body' ); ?>

				<div class="item-list-tabs no-ajax" id="subnav">
					<ul>

						<?php bp_get_options_nav(); ?>

						<?php do_action( 'bp_member_plugin_options_nav' ); ?>

					</ul>
				</div><!-- .item-list-tabs -->

				<h3><?php _e( 'Capabilities', 'vibe' ); ?></h3>

				<form action="<?php echo bp_displayed_user_domain() . bp_get_settings_slug() . '/capabilities/'; ?>" name="account-capabilities-form" id="account-capabilities-form" class="standard-form" method="post">

					<?php do_action( 'bp_members_capabilities_account_before_submit' ); ?>

					<label>
						<input type="checkbox" name="user-spammer" id="user-spammer" value="1" <?php checked( bp_is_user_spammer( bp_displayed_user_id() ) ); ?> />
						 <?php _e( 'This user is a spammer.', 'vibe' ); ?>
					</label>

					<div class="submit">
						<input type="submit" value="<?php _e( 'Save', 'vibe' ); ?>" id="capabilities-submit" name="capabilities-submit" />
					</div>

					<?php do_action( 'bp_members_capabilities_account_after_submit' ); ?>

					<?php wp_nonce_field( 'capabilities' ); ?>

				</form>

				<?php do_action( 'bp_after_member_body' ); ?>

			</div><!-- #item-body -->

			<?php do_action( 'bp_after_member_settings_template' ); ?>

		</div><!-- .padder -->
	</div><!-- #content -->
	<div class="col-md-3 col-sm-3">
		<?php get_sidebar( 'buddypress' ); ?>
	</div>
	</div><!-- row -->
	</div><!-- container -->
	</div><!-- buddypress -->
</section>	
<?php get_footer( 'buddypress' ); ?>