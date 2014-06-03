<?php

add_action( 'widgets_init', 'vibe_bp_widgets' );

function vibe_bp_widgets() {
    register_widget('vibe_bp_login');
}


/* Creates the widget itself */

if ( !class_exists('vibe_bp_login') ) {
	class vibe_bp_login extends WP_Widget {
	
		function vibe_bp_login() {
			$widget_ops = array( 'classname' => 'vibe-bp-login', 'description' => __( 'Vibe BuddyPress Login', 'vibe' ) );
			$this->WP_Widget( 'vibe_bp_login', __( 'Vibe BuddyPress Login Widget','vibe' ), $widget_ops);
		}
		
		function widget( $args, $instance ) {
			extract( $args );
			
			echo $before_widget;
			
			
			if ( is_user_logged_in() ) :
				do_action( 'bp_before_sidebar_me' ); ?>
				<div id="sidebar-me">
					<div id="bpavatar">
						<?php bp_loggedin_user_avatar( 'type=full' ); ?>
					</div>
					<ul>
						<li id="username"><a href="<?php bp_loggedin_user_link(); ?>"><?php bp_loggedin_user_fullname(); ?></a></li>
						<li><a href="<?php echo bp_loggedin_user_domain() . BP_XPROFILE_SLUG ?>/" title="<?php _e('View profile','vibe'); ?>"><?php _e('View profile','vibe'); ?></a></li>
						<li id="vbplogout"><a href="<?php echo wp_logout_url( get_permalink() ); ?>" rel="nofollow" class="logout" title="<?php _e( 'Log Out','vibe' ); ?>"><i class="icon-close-off-2"></i> <?php _e('LOGOUT','vibe'); ?></a></li>
					</ul>	
					<ul>
						<li><a href="<?php echo bp_loggedin_user_domain()   ?>course/"><i class="icon-book-open-1"></i> <?php _e('Courses','vibe'); ?></a></li>	
						<li><a href="<?php echo bp_loggedin_user_domain()  ?>course/course-stats/"><i class="icon-analytics-chart-graph"></i> <?php _e('Stats','vibe'); ?></a></li>	
						
						<?php 
						if ( bp_is_active( 'messages' ) ) : ?>
						<li><a href="<?php echo bp_loggedin_user_domain() . BP_MESSAGES_SLUG ?>/"><i class="icon-letter-mail-1"></i> <?php _e('Inbox','vibe'); if (messages_get_unread_count()) : echo " <span>" . messages_get_unread_count() . "</span>"; endif; ?></a></li>
						<li><a href="<?php echo bp_loggedin_user_domain() . BP_NOTIFICATIONS_SLUG ?>/"><i class="icon-exclamation"></i> <?php _e('Notifications','vibe'); ?>
						<?php $n=vbp_current_user_notification_count(); if ($n) : echo " <span>" . $n . "</span>"; endif; ?></a></li>
						<?php endif;
						
						if ( bp_is_active( 'groups' ) ) : ?>
						<li><a href="<?php echo bp_loggedin_user_domain() . BP_GROUPS_SLUG ?>/"><i class="icon-myspace-alt"></i> <?php _e('Groups','vibe'); ?></a></li>
						<?php endif; ?>
						
						<li><a href="<?php echo home_url(); ?>/bookmarks/"><i class="icon-bookmark"></i> Bookmarks</a></li>	
					</ul>
				
				<?php
				do_action( 'bp_sidebar_me' ); ?>
				</div>
				<?php do_action( 'bp_after_sidebar_me' );
			
			/***** If the user is not logged in, show the log form and account creation link *****/
			
			else :
				if(!isset($user_login))$user_login='';
				do_action( 'bp_before_sidebar_login_form' ); ?>
				
				
				<form name="login-form" id="vbp-login-form" class="standard-form" action="<?php echo wp_login_url( get_permalink() ); ?>" method="post">
					<label><?php _e( 'Username', 'vibe' ); ?><br />
					<input type="text" name="log" id="side-user-login" class="input" value="<?php echo esc_attr( stripslashes( $user_login ) ); ?>" /></label>
					
					<label><?php _e( 'Password', 'vibe' ); ?><br />
					<input type="password" name="pwd" id="sidebar-user-pass" class="input" value="" /></label>
					
					<p class=""><label><input name="rememberme" type="checkbox" id="sidebar-rememberme" value="forever" /><?php _e( 'Remember Me', 'vibe' ); ?></label></p>
					
					<?php do_action( 'bp_sidebar_login_form' ); ?>
					<input type="submit" name="wp-submit" id="sidebar-wp-submit" value="<?php _e( 'Log In','vibe' ); ?>" tabindex="100" />
					<input type="hidden" name="testcookie" value="1" />
					<?php if ( bp_get_signup_allowed() ) :
						printf( __( '<a href="%s" class="vbpregister" title="Create an account">Sign Up</a> ', 'vibe' ), site_url( BP_REGISTER_SLUG . '/' ) );
					endif; ?>
				</form>
				
				
				<?php do_action( 'bp_after_sidebar_login_form' );
			endif;
			
			echo $after_widget;
		}
		
		/* Updates the widget */
		
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			return $instance;
		}
		
		/* Creates the widget options form */
		
		function form( $instance ) {
			
		}
	
	} 
} 
