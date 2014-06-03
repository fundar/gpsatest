<?php 
/**
 * Theme Sidebar Admin Page Output
 *
 * This file is responsible for generating the admin 
 * page output for the custom sidebar generator. It
 * should only be included from within a function.
 * 
 * @package 	WordPress
 * @subpackage 	Custom_Theme_Sidebars
 * @author 		Sunny Johal - Titanium Themes
 * @copyright 	Copyright (c) 2014, Titanium Themes
 * @version 	1.4
 * 
 */

/**
 * Check User Permissions and Theme Support
 * 
 * Checks if the user has the required privileges and also
 * checks if the current activated theme has support for
 * dynamic sidebars. It will die if either of these 
 * conditions are not met.
 *
 * @link http://codex.wordpress.org/Function_Reference/current_user_can 			current_user_can()
 * @link http://codex.wordpress.org/Function_Reference/current_theme_supports		current_theme_supports()
 * @link http://codex.wordpress.org/Function_Reference/wp_die 				    	wp_die()
 *
 * @since 1.0
 * @version  1.4
 * 
 */
	if ( ! current_user_can('edit_theme_options') )
		wp_die( __( 'Cheatin&#8217; uh?' ) );

	if ( ! current_theme_supports( 'widgets' ) ) 
		wp_die( __( 'This theme does not support widgets, please use a theme that supports widgets in order to enable the sidebar generator.', 'theme-translate' ) );

/**
 * Include the WordPress Nav Menu Functions
 *
 * Includes the nav-menu.php file so that we have access to
 * the nav menu functions. We need these functions in order
 * to set up.
 *
 * @uses  global $wp_meta_boxes
 * 
 * @since 1.0
 * @version  1.4
 * 
 */
	require_once ( ABSPATH . 'wp-admin/includes/nav-menu.php' );
	wp_nav_menu_setup();
	global $wp_meta_boxes;

/**
 * Set Up URL Variables
 *
 * Declares and sets up all of the variables that are 
 * necessary to display the correct options screen on 
 * the admin page.
 *
 * @link http://codex.wordpress.org/Function_Reference/esc_url 				esc_url()
 * @link http://codex.wordpress.org/Function_Reference/add_query_arg 		add_query_arg()
 *
 * @since 1.0
 * @version  1.4
 * 
 */
	// Declare URL Variables
	$admin_page_name = 'custom_theme_sidebars';
	$admin_url       = esc_url( 
							add_query_arg( 
								array( 
									'page' => $admin_page_name 
								), 
								admin_url( 'themes.php' ) 
							) 
						);
	$manage_url      = esc_url( 
							add_query_arg( 
								array( 
									'screen' => 'sidebar_replacements' 
								), 
								$admin_url 
							) 
						);
	$create_url      = esc_url( 
							add_query_arg( 
								array( 
									'action' => 'create' 
								), 
								$admin_url 
							) 
						);
	$manage_screen   = ( isset( $_GET['screen'] ) && 'sidebar_replacements' == $_GET['screen'] ) ? true : false;

/**
 * Get and Initialise Custom Sidebars
 *
 * Get all sidebar instances so that they can be used
 * throughout the admin pages.
 * 
 * @link http://codex.wordpress.org/Function_Reference/have_post            have_post()
 * @link http://codex.wordpress.org/Function_Reference/the_post             the_post()
 * @link http://codex.wordpress.org/Function_Reference/get_post_meta        get_post_meta()
 * @link http://codex.wordpress.org/Function_Reference/get_the_ID           get_the_ID()
 * @link http://codex.wordpress.org/Function_Reference/get_the_title        get_the_title()
 * @link http://codex.wordpress.org/Function_Reference/wp_reset_postdata    wp_reset_postdata()
 *
 * @uses master_get_all_sidebar_instances()    defined in includes/theme-sidebar-functions.php
 *
 * @since 1.0
 * @version  1.4
 * 
 */
	$sidebar_instances = master_get_all_sidebar_instances();
	$custom_sidebars   = array();
	$no_sidebars       = true;
	$first_sidebar     = false;
	
	if ( $sidebar_instances ) {

		$no_sidebars = false;

		/**
		 * Get all custom sidebars and initialise the first custom
		 * sidebar as the one to edit on this screen. This will be
		 * the sidebar to edit if no other sidebar id has been 
		 * passed in the URL.
		 * 
		 */
		$count = 0;
		$current_sidebar_id;

		while ( $sidebar_instances->have_posts() ) {
			// Loop through the post
			$sidebar_instances->the_post();
			
			// Add this sidebar to the $custom_sidebars array
			$id                     = get_post_meta( get_the_ID(), 'sidebar_id', true );
			$custom_sidebars[ $id ] = get_the_title();

			// Set curent sidebar id to the first sidebar
			if( 0 == $count ) {
				$current_sidebar_id = $id;
				$first_sidebar      = master_get_sidebar_instance($id);
			}

			$count++;
		}

		// Restore original Post Data
		wp_reset_postdata();

	}

	// Update current sidebar id if it is passed in the URL
	if ( isset( $_GET['sidebar'] ) ) {
		$current_sidebar_id = $_GET['sidebar'];
	}

/**
 * Get and Initialise Theme Default Sidebars
 *
 * Get all theme default registered sidebars so that 
 * they can be used throughout the admin pages.
 * 
 * @uses master_get_ordered_theme_custom_sidebars()    defined in includes/theme-sidebar-functions.php
 * @uses master_get_theme_default_sidebars()           defined in includes/theme-sidebar-functions.php
 *
 * @since 1.0
 * @version  1.4
 * 
 */
	$default_sidebars = master_get_ordered_theme_custom_sidebars( 'name', master_get_theme_default_sidebars() );
	
/**
 * Output Admin Page HTML
 *
 * Generate and output all of the required HTML
 * in order to enable the admin options page
 * functionality.
 *
 * @since 1.0
 * @version  1.4
 * 
 */
?>
<div class="wrap">
	<!-- Screen Navigation -->
	<?php screen_icon(); ?>
	<h2 class="nav-tab-wrapper">
		<a href="<?php echo $admin_url; ?>" class="nav-tab <?php if ( ! isset( $_GET['screen'] ) || isset( $_GET['screen'] ) && 'sidebar_replacements' != $_GET['screen'] ) echo ' nav-tab-active' ?>"> 
			<?php esc_html_e( 'Edit Sidebars', 'theme-translate' ); ?>
		</a>
		<a href="<?php echo $manage_url; ?>" class="nav-tab<?php if ( $manage_screen ) echo ' nav-tab-active'; ?>">
			<?php esc_html_e( 'Manage Sidebar Replacements', 'theme-translate' ); ?>
		</a>
	</h2>
	
	<?php 
		/**
		 * MANAGE SIDEBAR REPLACEMENTS SCREEN
		 * ==================================
		 * 
		 * Generate and output all of the required HTML
		 * for the Manage Sidebar Replacements Screen.
		 *
		 * @since 1.0
		 * @version  1.4
		 * 
		 */
	?>	
	<?php if ( $manage_screen ) : ?>
		
		<!-- Manage Sidebars Replacements Overview Screen -->

		<div id="sidebar-replacements-wrap">
			<?php do_action( 'before_sidebar_replacements_form' ); ?>
			<form autocomplete="off" method="post" action="<?php echo esc_url( add_query_arg( array( 'action' => 'sidebar_replacements' ), $admin_url ) ); ?>">
				
				<?php 
					/**
					 * Output New Sidebar Dialog Message
					 * 
					 * If there are no sidebars output a dialog message
					 * to prompt the user to create a new custom sidebar.
					 * 
					 */
				?>
				<?php if ( $no_sidebars ) : ?>
					<div class="manage-sidebars manage-menus no-sidebars">
						<label>Create a new sidebar for your theme:</label>
						<?php submit_button( __( 'Create a New Sidebar', 'theme-translate'), 'secondary', 'create_new_sidebar', false, array( 'data-create-sidebar-url' => $create_url ) ); ?>	
					</div>

				<?php 
					/**
					 * Output Custom Sidebar Table
					 * 
					 * If there are existing sidebars output a table that
					 * displays all custom sidebar instances along with
					 * their corresponding replacements.
					 * 
					 */
				?>				
				<?php else : ?>

					<div class="manage-sidebars manage-menus sidebar-dialog no-sidebars">
						<label class="manage-label"><?php _e( 'Manage your sidebar replacements here or:', 'theme-translate' ); ?></label>
						<label class="new-label"><?php _e( 'Create a new sidebar for your theme:', 'theme-translate' ); ?></label>
						<?php submit_button( __( 'Create a New Sidebar', 'theme-translate'), 'secondary', 'create_new_sidebar', false, array( 'data-create-sidebar-url' => $create_url ) ); ?>				
					</div>					
					
					<!-- Sidebar Replacements Table -->
					<table id="sidebar-replacements-table" class="widefat fixed" cellspacing="0">
					<thead>
						<tr>
							<th class="manage-column column-sidebars"><?php _e( 'Sidebar Name', 'theme-translate' ); ?></th>
							<th class="manage-column column-sidebars-widget-replacement"><?php _e( 'Default Sidebar To Replace', 'theme-translate' ) ?></th>
						</tr>
						<tbody class="sidebar-replacements">
								
							<?php while ( $sidebar_instances->have_posts() ) : $sidebar_instances->the_post(); ?>
								<?php 
									$sidebar_replacement_id = get_post_meta( get_the_ID(), 'sidebar_replacement_id', true );
									$sidebar_id             = get_post_meta( get_the_ID(), 'sidebar_id', true );
									$selected_option        = false;
								?>
								<tr class="sidebar-replacements-row">
									<td class="sidebar-replacement-title">
										<strong><?php the_title(); ?></strong>
									</td>
									<td id="" class="default-widget-areas">
										<!-- Replacement Sidebar Select -->
										<select data-sidebar-reference="<?php echo $sidebar_id; ?>" name="" id="">
											<?php foreach ( master_get_theme_default_sidebars() as $sidebar ) : ?>
													<option value="<?php echo $sidebar['id']; ?>" <?php if ( $sidebar_replacement_id == $sidebar['id'] ) : $selected_option = true; ?>selected<?php endif; ?>>
														<?php echo $sidebar['name'] ; ?>
													</option>
											<?php endforeach; ?>
											<?php if ( ! $selected_option ) : ?>
												<option value="0" selected>&mdash; <?php _e('Select a Sidebar', 'theme-translate') ?> &mdash;</option>
											<?php else : ?>
												<option value="0">&mdash; <?php _e('Deactivate Sidebar', 'theme-translate') ?> &mdash;</option>
											<?php endif; ?>
										</select>
										
										<?php 
											/**
											 * Build Edit Link URL
											 * 
											 * Generate a unique edit URL for each custom
											 * sidebar.
											 * 
											 */
											$edit_link = esc_url( 
															add_query_arg( 
																array( 
																	'page'    => $admin_page_name,
																	'action'  => 'edit',
																	'sidebar' => $sidebar_id
																), 
																admin_url( 'themes.php' ) 
															) 
														);
										?>
										<!-- Edit/Delete Link -->
										<span class="sidebar-edit-link">
											<a href="<?php echo $edit_link; ?>" data-sidebar-reference="<?php echo $sidebar_id; ?>"><?php _e( 'Edit', 'theme-translate' ); ?></a>
										</span> | 
										<span class="sidebar-delete-link">
											<a href="#" data-sidebar-reference="<?php echo $sidebar_id; ?>"><?php _e( 'Delete', 'theme-translate' ); ?></a>
										</span>
										<span class="spinner"></span>
										</td>
								</tr>
								
							<?php 
								endwhile;
								wp_reset_postdata();
							?>
						</tbody>
					</thead>
				</table><!-- END #sidebar-replacements-table -->
					<?php 
						/**
						 * Create Delete All Sidebars Button
						 *
						 * Creates a button that will delete all sidebars.
						 */
					?>

					<a href="#" id="delete_all_sidebars"><?php _e( 'Delete All Sidebars', 'theme-translate' ) ?></a>

				<?php endif; ?>
				
				<?php 
					/**
					 * Create Sidebar Nonce Fields for Security
					 * 
					 * This ensures that the request to modify sidebars 
					 * was an intentional request from the user. Used in
					 * the Ajax Reequest for validation.
					 *
					 * @link http://codex.wordpress.org/Function_Reference/wp_nonce_field 	wp_nonce_field()
					 * 
					 */
				?>
				<?php wp_nonce_field( 'master_delete_sidebar_instance', 'master_sidebar_delete_sidebar_instance_nonce' ); ?>
				<?php wp_nonce_field( 'master_edit_sidebar_instance', 'master_sidebar_edit_sidebar_instance_nonce' ); ?>
				<?php wp_nonce_field( 'master_sidebar_quick_search', 'master_sidebar_quick_search_nonce' ); ?>
				<?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
			</form><!-- END form -->
			<?php do_action( 'after_sidebar_replacements_form' ); ?>
		</div><!-- END #sidebar-replacements-wrap -->
	
	<?php 
		/**
		 * EDIT SIDEBAR REPLACEMENTS SCREEN
		 * ================================
		 * 
		 * Generate and output all of the required HTML
		 * for the Edit Sidebars Screen.
		 *
		 * @since 1.0
		 * @version 1.4
		 * 
		 */
	?>
	<?php else : ?>

		<?php 
				/**
				 * Get URL Parameters and Determine Action to take
				 *
				 * Get the parameters passed in the url and determine
				 * the action to take based on the values.
				 *
				 * @since 1.0
				 * @version  1.4
				 * 
				 */

					// Allowed actions 'create', 'edit', ( 'delete' gets handled by ajax )
					$action = isset( $_GET['action'] ) ? $_GET['action'] : false; 
					
					// The sidebar id of the current sidebar being edited - Note this is a string representation of '0', not an integer
					$sidebar_selected_id = isset( $_GET['sidebar'] ) ? $_GET['sidebar'] : '0';

					// Attempt to get a sidebar instance if it exists 
					$sidebar_instance = master_get_sidebar_instance( $sidebar_selected_id );

					// edit and and no sidebar but has first sidebar
					if ( 'edit' == $action ) {
						if ( ! isset( $_GET['sidebar'] ) && $first_sidebar ) {
							$sidebar_instance    = $first_sidebar;
							$sidebar_selected_id = get_post_meta( $sidebar_instance->ID, 'sidebar_id', true );
							$action              = 'edit';
						} 
					}
					

					/**
					 * Initialise screen action if no action has been set
					 * in the parameter.
					 */
					if ( ! $action ) {
						if ( $first_sidebar ) {
							$sidebar_instance    = $first_sidebar;
							$sidebar_selected_id = get_post_meta( $sidebar_instance->ID, 'sidebar_id', true );
							$action              = 'edit';
						} else {
							$action = 'create';
						}
					} else {
						/**
						 * PHP Switch to determine what action to take
						 * upon screen initialisation.
						 */
						switch ( $action ) {
							case 'edit':
								// Change action if we are creating a new menu
								if ( '0' == $sidebar_selected_id ) {
									 $action = 'create';
								} else {

									// Change action if the sidebar instance doesn't exist
									if ( ! $sidebar_instance ) {
										$action = 'create';
									}
								}

								break;
							case 'create':
								// The sidebar id of the current sidebar being edited - Note this is a string representation of '0', not an integer
								$sidebar_selected_id = '0';
								break;
						}						
					}


				/**
				 * Initialise Variables to use on this screen
				 *
				 * Now that the action has been determined the next
				 * stage is to initialise/set up the variables so 
				 * that they can be used on the page.
				 *
				 * @since  1.0
				 * @version  1.4
				 */
			
				$messages  = array();          // Container for any messages displayed to the user
				
				// Define Variables
				$sidebar_name        = '';
				$replacement_id      = '0';
				$sidebar_description = '';

				if ( 'edit' == $action ) {
					$sidebar_name        = $sidebar_instance->post_title;
					$replacement_id      = get_post_meta( $sidebar_instance->ID, 'sidebar_replacement_id', true );
					$sidebar_description = get_post_meta( $sidebar_instance->ID, 'sidebar_description', true );
				}
			
		?>

		<?php 
			/**
			 * Update Sidebar Message 
			 *
			 * Message to display to the user if this
			 * sidebar has been updated.
			 * 
			 */
		?>
		<?php if ( isset( $_GET['dialog'] ) ) : ?>
			<?php if ( 'updated' == $_GET['dialog'] ) : ?>
				<?php $updated_sidebar_name =  isset( $_GET['name'] ) ? $_GET['name'] : __( 'Sidebar', 'theme-translate' ); ?>
				<div class="updated below-h2" id="update_message">
					<p>
						<?php printf( __( '%1$s has been updated.', 'theme-translate' ), "<strong id='updated_sidebar_name'>{$updated_sidebar_name}</strong>" ); ?>
					</p>
				</div>
			<?php endif; ?>
		<?php endif; ?>
		<?php 
			/**
			 * Deleted Sidebar Dialog Message 
			 * 
			 * Checks if a sidebar has just been deleted and
			 * outputs a feedback to the message if it has.
			 * 
			 */
		?>
		<?php if ( isset( $_GET['dialog'] ) ) : ?>
			<?php if ( $_GET['dialog'] == 'deleted' ) : ?>
				<?php $deleted_sidebar_name = isset( $_GET['name'] ) ? $_GET['name'] : __( 'Sidebar', 'theme-translate' ); ?>
				<div class="updated below-h2" id="delete_message">
					<p><?php printf( __( '%1$s has been deleted.', 'theme-translate' ), "<strong>{$deleted_sidebar_name}</strong>" ) ?></p>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php 
			/**
			 * Manage Sidebars Dialog Message 
			 * 
			 * Checks if there are any existing sidebars and
			 * outputs a contextual user message depending on
			 * the outcome.
			 * 
			 */
		?>
		<div class="manage-sidebars manage-menus">
			<form autocomplete="off" id="" action="" method="get" enctype="multipart/form-data">
				<?php if ( ! empty( $custom_sidebars ) ) : ?>
					<input type="hidden" name="page" value="<?php echo $admin_page_name; ?>">
					<input name="action" type="hidden" value="edit">
					<label class="selected-menu" for="menu"><?php _e('Select a sidebar to edit:', 'theme-translate'); ?></label>
					<select autocomplete="off" name="sidebar" id="sidebar">
						<?php foreach ( $custom_sidebars as $custom_sidebar_id => $custom_sidebar_name ) : ?>
							<option value="<?php echo $custom_sidebar_id; ?>" <?php if( $custom_sidebar_id == $sidebar_selected_id ) : ?>selected<?php endif; ?>><?php echo $custom_sidebar_name; ?></option>
						<?php endforeach; ?>
					</select>
					<?php submit_button( __( 'Select', 'theme-translate' ), 'secondary', '', false ); ?>
					<span class="add-new-menu-action">
						or <a href="<?php echo $create_url; ?>">create a new sidebar</a>.			
					</span><!-- /add-new-menu-action -->
				<?php elseif ( 'create' == $action ) : ?>
					<label><?php _e( 'Create a new Sidebar.', 'theme-translate' ); ?></label>
				<?php endif; ?>	
			</form>
		</div>
	
		<!-- Sidebar Management Options -->
		<div id="sidebars-frame">
			<?php 
				/**
				 * Output Accordion Metabox
				 *
				 * Generates the same accordion metabox used by WordPress 
				 * to generate the metabox for nav-menus. This outputs all
				 * of the available pages, posts, categories, taxonomies,
				 * post types, templates that are avaliable.
				 *
				 * @link http://codex.wordpress.org/Function_Reference/wp_nonce_field 	wp_nonce_field()
				 * 
				 * @uses  master_do_accordion_sections() 	defined in includes/theme-sidebar-functions.php
				 * 
				 */
			?>
			<div id="sidebar-all-pages-column" class="metabox-holder <?php if ( 'create' == $action ) : ?>metabox-holder-disabled <?php endif; ?>">
				<div class="clear"></div>
				<form id="sidebar-meta" action="" class="sidebar-meta" method="post" enctype="multipart/form-data">
					<?php wp_nonce_field( 'add-menu_item', 'menu-settings-column-nonce' ); ?>
					<?php wp_nonce_field( 'master_add_sidebar_item', 'master_sidebar_settings_column_nonce' ); ?>
					<?php master_do_accordion_sections(); ?>
				</form><!-- END #sidebar-meta -->
			</div><!-- END #sidebar-all-pages-column -->
			
			<?php 
				/**
				 * Output Sidebar Options Pane
				 *
				 * Generates the markup required in order to either
				 * edit an existing sidebar or create a new one.
				 * 
				 */
			?>
			<div id="sidebar-management-liquid">
				<div id="sidebar-management">
					<form autocomplete="off" id="update-sidebar" enctype="multipart/form-data" method="post" action="">
						<div class="sidebar-edit">
							<div id="sidebar-header">
								<div class="major-publishing-actions">
									<label for="custom-sidebar-name" class="custom-sidebar-name-label howto open-label">
										<span><?php _e( 'Sidebar Name', 'theme-translate' ); ?></span>
										<input type="text" value="<?php echo $sidebar_name; ?>" title="<?php _e( 'Enter sidebar name here', 'theme-translate' ) ?>" class="custom-sidebar-name regular-text menu-item-textbox input-with-default-title" id="custom-sidebar-name" name="custom-sidebar-name">
									</label>
									<div class="publishing-action">
										<span class="spinner"></span>
										<?php if ( 'create' == $action ) : ?>
											
										<?php 
											/**
											 * Build Edit Redirect Link URL
											 * 
											 * Generate the first part of the URL and store it
											 * in a data attribute. This URL will have the rest
											 * of the query variables appended to it via AJAX.
											 *
											 * @since 1.0
											 * @version 1.4
											 * 
											 */
											$edit_redirect_link = esc_url( 
															add_query_arg( 
																array( 
																	'page'    => $admin_page_name,
																	'action'  => 'edit'
																), 
																admin_url( 'themes.php' ) 
															) 
														);

											// Create submit button
											submit_button( 
												__( 'Create Sidebar', 'theme-translate'), 
												'primary', 
												'submit', 
												false, 
												array( 
													'id'                => 'create_sidebar_header',
													'data-redirect-url' => $edit_redirect_link
												) 
											); 
										?>
										
										<?php else : ?>
											
											<?php
												/**
												 * Build Save Redirect Link URL
												 * 
												 * Generate the first part of the URL and store it
												 * in a data attribute. This URL will have the rest
												 * of the query variables appended to it via AJAX.
												 *
												 * @since 1.0
												 * @version 1.4
												 * 
												 */
												$save_redirect_link = esc_url( 
																		add_query_arg( 
																			array( 
																				'page'    => $admin_page_name,
																				'action'  => 'edit',
																				'dialog'  => 'updated',
																				'sidebar' => $sidebar_selected_id
																			), 
																			admin_url( 'themes.php' ) 
																		) 
																	); 

												submit_button( 
													__( 'Save Sidebar', 'theme-translate'), 
													'primary', 
													'submit', 
													false, 
													array( 
														'id' => 'save_sidebar_header', 
														'data-sidebar-id' => $sidebar_selected_id,
														'data-redirect-url' => $save_redirect_link
													) 
												); 
											?>
										
										<?php endif; ?>
									</div><!-- END .publishing-action -->
									<div class="clear"></div>
								</div><!-- END .major-publishing-actions -->
							</div><!-- END #sidebar-header -->
							<div id="post-body">
								<div id="post-body-content">
									<?php if ( 'create' == $action ) : ?>
										<p class="post-body-plain"><?php _e( 'Give your sidebar a name above, then click Create Sidebar.', 'theme-translate' ) ?></p>
									<?php else: ?>

										<h3>Sidebar Replacement Pages</h3>
										
										<!-- Drag Instructions -->
										<?php if ( 'edit' == $action ) : ?>
											<div class="drag-instructions post-body-plain" style="display:none;">
												<p>
													<?php _e( "Drag each item into the order you prefer. Click the arrow on the right of the item to reveal additional configuration options. Please ensure that any items added to this sidebar contain the default 'Sidebar to Replace' widget area selected in the sidebar properties below.", 'theme-translate' ); ?>
												</p>
											</div>
											<div id="sidebar-instructions" class="post-body-plain" style="display:none;">
												<p>
													<?php _e( "Add items from the column on the left. Please ensure that any items added to this sidebar contain the default 'Sidebar to Replace' widget area selected in the sidebar properties below.", 'theme-translate' ); ?>
												</p>
											</div>
										<?php endif; ?>

										<!-- Sidebar Item Replacements -->
										<ul class="sidebar nav-menus-php" id="sidebar-to-edit">
											<?php 
												/**
												 * Load Sidebar Attachments
												 *
												 * Load any saved sidebar attachments if they
												 * exist. Only applicable in edit mode. 
												 *
												 * @uses  master_get_sidebar_attachment_markup()  defined in includes/theme-sidebar-admin-page-functions.php
												 *
												 * @since 1.0
												 * @version 1.4
												 * 
												 */
											?>
											<?php if ( 'edit' == $action ) : ?>
												<?php echo master_get_sidebar_attachment_markup( $sidebar_selected_id ); ?>
											<?php endif; ?>
										</ul>


										<div class="sidebar-settings menu-settings">
											<h3><?php _e('Sidebar Properties', 'theme-translate') ?></h3>
											<div id="sidebar-properties-instructions">
												<p>Edit Information below</p>
											</div>
											<dl>
												<dt class="howto"><?php _e('Sidebar to Replace', 'theme-translate') ?></dt>
												<dd>
													<?php if( $default_sidebars ) : ?>
													<select name="" id="sidebar_replacement_id">
														<?php foreach ( $default_sidebars as $sidebar ) : ?>
															<option value="<?php echo $sidebar['id']; ?>" <?php if ( $sidebar['id'] == $replacement_id ) : ?>selected<?php endif; ?> ><?php echo $sidebar['name']; ?></option>
														<?php endforeach; ?>
															<option value="0" <?php if ( '0' == $replacement_id ) : ?>selected<?php endif; ?> >
																<?php if ( '0' == $replacement_id ) : ?>
																	&mdash; <?php _e( 'Select a Sidebar', 'theme-translate' ); ?> &mdash;
																<?php else : ?>
																	&mdash; <?php _e( 'Deactivate Sidebar', 'theme-translate' ); ?> &mdash;
																<?php endif; ?>
															</option>
													</select>
													<?php endif; ?>
												</dd>
												<div class="clear"></div>
											</dl>
											<dl>
												<dt class="howto"><?php _e('Sidebar Description', 'theme-translate') ?></dt>
												<dd>
													<textarea id="sidebar_description" title="<?php _e( 'Enter sidebar description here', 'theme-translate' ) ?>" class="custom-sidebar-name regular-text menu-item-textbox input-with-default-title"><?php echo $sidebar_description; ?></textarea>
												</dd>
												<div class="clear"></div>
											</dl>																				
										</div>
									<?php endif; ?>
								</div><!-- END #post-body-content -->
							</div><!-- END #post-body -->
							<div id="sidebar-footer">
								<div class="major-publishing-actions">
									<?php 
										/**
										 * Build Delete Link URL
										 * 
										 * Generate a unique edit URL for each custom
										 * sidebar.
										 * 
										 */
										$delete_link = '';
										$delete_link = esc_url( 
															add_query_arg( 
																array( 
																	'page'    => $admin_page_name,
																	'action'  => 'edit',
																	'dialog'  => 'deleted',
																	'name'    =>  str_replace ( ' ', '+', $sidebar_name )
																), 
																admin_url( 'themes.php' ) 
															) 
														);
									?>
									<span class="delete-action">
										<?php if( 'create' == $action ) : ?>
											<?php $delete_link = $admin_url; ?>									
										<?php endif; ?>										
										<a data-redirect-url="<?php echo $delete_link; ?>" data-sidebar-id="<?php echo $sidebar_selected_id; ?>" id="delete_sidebar" href="#" class="submitdelete deletion menu-delete">
											<?php _e( 'Delete Sidebar', 'theme-translate' ); ?>
										</a>


									</span>
									<div class="publishing-action">
										<span class="spinner"></span>
										<?php if ( 'create' == $action ) : ?>
										<?php 
											/**
											 * Build Edit Redirect Link URL
											 * 
											 * Generate the first part of the URL and store it
											 * in a data attribute. This URL will have the rest
											 * of the query variables appended to it via AJAX.
											 *
											 * @since 1.0
											 * @version 1.4
											 * 
											 */
											$edit_redirect_link = esc_url( 
															add_query_arg( 
																array( 
																	'page'    => $admin_page_name,
																	'action'  => 'edit'
																), 
																admin_url( 'themes.php' ) 
															) 
														);

											// Create submit button
											submit_button( 
												__( 'Create Sidebar', 'theme-translate'), 
												'primary', 
												'submit', 
												false, 
												array( 
													'id'                => 'create_sidebar_footer',
													'data-redirect-url' => $edit_redirect_link
												) 
											); 
										?>										
										<?php else : ?>
											<?php 
												/**
												 * Build Save Redirect Link URL
												 * 
												 * Generate the first part of the URL and store it
												 * in a data attribute. This URL will have the rest
												 * of the query variables appended to it via AJAX.
												 *
												 * @since 1.0
												 * @version 1.4
												 * 
												 */
												$save_redirect_link = esc_url( 
																		add_query_arg( 
																			array( 
																				'page'    => $admin_page_name,
																				'action'  => 'edit',
																				'dialog'  => 'updated',
																				'sidebar' => $sidebar_selected_id
																			), 
																			admin_url( 'themes.php' ) 
																		) 
																	); 

												submit_button( 
													__( 'Save Sidebar', 'theme-translate'), 
													'primary', 
													'submit', 
													false, 
													array( 
														'id'              => 'save_sidebar_footer',
														'data-sidebar-id' => $sidebar_selected_id,
														'data-redirect-url' => $save_redirect_link 
													) 
												); ?>
										
										<?php endif; ?>
									</div><!-- END .publishing-action -->
									<div class="clear"></div>
								</div><!-- END .major-publishing-actions -->
							</div>
						</div><!-- END .sidebar-edit -->
						<?php 
							/**
							 * Create Sidebar Nonce Fields for Security
							 * 
							 * This ensures that the request to modify sidebars 
							 * was an intentional request from the user. Used in
							 * the Ajax Reequest for validation.
							 *
							 * @link http://codex.wordpress.org/Function_Reference/wp_nonce_field 	wp_nonce_field()
							 * 
							 */
						?>
						<?php wp_nonce_field( 'master_delete_sidebar_instance', 'master_sidebar_delete_sidebar_instance_nonce' ); ?>
						<?php wp_nonce_field( 'master_edit_sidebar_instance', 'master_sidebar_edit_sidebar_instance_nonce' ); ?>
						<?php wp_nonce_field( 'master_sidebar_quick_search', 'master_sidebar_quick_search_nonce' ); ?>
						<?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
					</form><!-- END #update-sidebar -->
				</div><!-- END #sidebar-management -->
			</div><!-- END #sidebar-management-liquid -->
		</div><!-- END #sidebars-frame -->
	<?php endif; ?>
</div><!-- END .wrap -->

