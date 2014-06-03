<?php 

require_once 'class-wonderplugin-carousel-list-table.php';
require_once 'class-wonderplugin-carousel-creator.php';

class WonderPlugin_Carousel_View {

	private $controller;
	private $list_table;
	private $creator;
	
	function __construct($controller) {
		
		$this->controller = $controller;
	}
	
	function add_metaboxes() {
		add_meta_box('overview_features', __('WonderPlugin Carousel Features', 'wonderplugin_carousel'), array($this, 'show_features'), 'wonderplugin_carousel_overview', 'features', '');
		add_meta_box('overview_upgrade', __('Upgrade to Commercial Version', 'wonderplugin_carousel'), array($this, 'show_upgrade_to_commercial'), 'wonderplugin_carousel_overview', 'upgrade', '');
		add_meta_box('overview_news', __('WonderPlugin News', 'wonderplugin_carousel'), array($this, 'show_news'), 'wonderplugin_carousel_overview', 'news', '');
		add_meta_box('overview_contact', __('Contact Us', 'wonderplugin_carousel'), array($this, 'show_contact'), 'wonderplugin_carousel_overview', 'contact', '');
	}
	
	function show_upgrade_to_commercial() {
		?>
		<ul class="wonderplugin-feature-list">
			<li>Use on commercial websites</li>
			<li>Remove the wonderplugin.com watermark</li>
			<li>Priority techincal support</li>
			<li><a href="http://www.wonderplugin.com/order/?product=carousel" target="_blank">Upgrade to Commercial Version</a></li>
		</ul>
		<?php
	}
	
	function show_news() {
		
		include_once( ABSPATH . WPINC . '/feed.php' );
		
		$rss = fetch_feed( 'http://www.wonderplugin.com/feed/' );
		
		$maxitems = 0;
		if ( ! is_wp_error( $rss ) )
		{
			$maxitems = $rss->get_item_quantity( 5 );
			$rss_items = $rss->get_items( 0, $maxitems );
		}
		?>
		
		<ul class="wonderplugin-feature-list">
		    <?php if ( $maxitems > 0 ) {
		        foreach ( $rss_items as $item )
		        {
		        	?>
		        	<li>
		                <a href="<?php echo esc_url( $item->get_permalink() ); ?>" target="_blank" 
		                    title="<?php printf( __( 'Posted %s', 'wonderplugin_carousel' ), $item->get_date('j F Y | g:i a') ); ?>">
		                    <?php echo esc_html( $item->get_title() ); ?>
		                </a>
		                <p><?php echo $item->get_description(); ?></p>
		            </li>
		        	<?php 
		        }
		    } ?>
		</ul>
		<?php
	}
	
	function show_features() {
		?>
		<ul class="wonderplugin-feature-list">
			<li>Support images, YouTube, Vimeo and MP4/WebM videos</li>
			<li>Works on mobile, tablets and all major web browsers, including iPhone, iPad, Android, Firefox, Safari, Chrome, Internet Explorer 7/8/9/10/11 and Opera</li>
			<li>Built-in lightbox effect</li>
			<li>Pre-defined professional skins</li>
			<li>Fully responsive</li>
			<li>Easy-to-use wizard style user interface</li>
			<li>Instantly preview</li>
			<li>Provide shortcode and PHP code to insert the carousel to pages, posts or templates</li>
		</ul>
		<?php
	}
	
	function show_contact() {
		?>
		<p>Priority technical support is available for Commercial Version users at support@wonderplugin.com. Please include your license information, WordPress version, link to your carousel, all related error messages in your email.</p> 
		<?php
	}
	
	function print_overview() {
		
		?>
		<div class="wrap">
		<div id="icon-wonderplugin-carousel" class="icon32"><br /></div>
		<div id="wondercarousellightbox_options" data-skinsfoldername=""  data-jsfolder="<?php echo WONDERPLUGIN_CAROUSEL_URL . 'engine/'; ?>" style="display:none;"></div>
			
		<h2><?php echo __( 'WonderPlugin Carousel', 'wonderplugin_carousel' ) . ( (WONDERPLUGIN_CAROUSEL_VERSION_TYPE == "C") ? " Commercial Version" : " Free Version") . " " . WONDERPLUGIN_CAROUSEL_VERSION; ?> </h2>
		 
		<div id="welcome-panel" class="welcome-panel">
			<div class="welcome-panel-content">
				<h3>WordPress Image and Video Carousel Plugin</h3>
				<div class="welcome-panel-column-container">
					<div class="welcome-panel-column">
						<h4>Get Started</h4>
						<a class="button button-primary button-hero" href="<?php echo admin_url('admin.php?page=wonderplugin_carousel_add_new'); ?>">Create A New Carousel</a>
					</div>
					<div class="welcome-panel-column welcome-panel-last">
						<h4>More Actions</h4>
						<ul>
							<li><a href="<?php echo admin_url('admin.php?page=wonderplugin_carousel_show_items'); ?>" class="welcome-icon welcome-widgets-menus">Manage Existing Carousels</a></li>
							<li><a href="http://www.wonderplugin.com/wordpress-carousel/help/" target="_blank" class="welcome-icon welcome-learn-more">Help Document</a></li>
							<?php  if (WONDERPLUGIN_CAROUSEL_VERSION_TYPE !== "C") { ?>
							<li><a href="http://www.wonderplugin.com/order/?product=carousel" target="_blank" class="welcome-icon welcome-view-site">Upgrade to Commercial Version</a></li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		<div id="dashboard-widgets-wrap">
			<div id="dashboard-widgets" class="metabox-holder columns-2">
	 
	                 <div id="postbox-container-1" class="postbox-container">
	                    <?php 
	                    do_meta_boxes( 'wonderplugin_carousel_overview', 'features', '' ); 
	                    do_meta_boxes( 'wonderplugin_carousel_overview', 'contact', '' ); 
	                    ?>
	                </div>
	 
	                <div id="postbox-container-2" class="postbox-container">
	                    <?php 
	                    if (WONDERPLUGIN_CAROUSEL_VERSION_TYPE != "C")
	                    	do_meta_boxes( 'wonderplugin_carousel_overview', 'upgrade', ''); 
	                    do_meta_boxes( 'wonderplugin_carousel_overview', 'news', ''); 
	                    ?>
	                </div>
	 
	        </div>
        </div>
            
		<?php
	}
	
	function print_items() {
		
		?>
		<div class="wrap">
		<div id="icon-wonderplugin-carousel" class="icon32"><br /></div>
		<div id="wondercarousellightbox_options" data-skinsfoldername=""  data-jsfolder="<?php echo WONDERPLUGIN_CAROUSEL_URL . 'engine/'; ?>" style="display:none;"></div>
			
		<h2><?php _e( 'Manage Carousels', 'wonderplugin_carousel' ); ?> <a href="<?php echo admin_url('admin.php?page=wonderplugin_carousel_add_new'); ?>" class="add-new-h2"> <?php _e( 'New Carousel', 'wonderplugin_carousel' ); ?></a> </h2>
		
		<?php $this->process_actions(); ?>
		
		<form id="carousel-list-table" method="post">
		<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
		<?php 
		
		if ( !is_object($this->list_table) )
			$this->list_table = new WonderPlugin_Carousel_List_Table($this);
		
		$this->list_table->list_data = $this->controller->get_list_data();
		$this->list_table->prepare_items();
		$this->list_table->display();		
		?>								
        </form>
        
		</div>
		<?php
	}
	
	function print_item()
	{
		if ( !isset( $_REQUEST['itemid'] ) )
			return;
		
		?>
		<div class="wrap">
		<div id="icon-wonderplugin-carousel" class="icon32"><br /></div>
		<div id="wondercarousellightbox_options" data-skinsfoldername=""  data-jsfolder="<?php echo WONDERPLUGIN_CAROUSEL_URL . 'engine/'; ?>" style="display:none;"></div>
					
		<h2><?php _e( 'View Carousel', 'wonderplugin_carousel' ); ?> <a href="<?php echo admin_url('admin.php?page=wonderplugin_carousel_edit_item') . '&itemid=' . $_REQUEST['itemid']; ?>" class="add-new-h2"> <?php _e( 'Edit Carousel', 'wonderplugin_carousel' ); ?>  </a> </h2>
		
		<div class="updated"><p style="text-align:center;">  <?php _e( 'To embed the carousel into your page, use shortcode', 'wonderplugin_carousel' ); ?> <strong><?php echo esc_attr('[wonderplugin_carousel id="' . $_REQUEST['itemid'] . '"]'); ?></strong></p></div>

		<div class="updated"><p style="text-align:center;">  <?php _e( 'To embed the carousel into your template, use php code', 'wonderplugin_carousel' ); ?> <strong><?php echo esc_attr('<?php echo do_shortcode(\'[wonderplugin_carousel id="' . $_REQUEST['itemid'] . '"]\'); ?>'); ?></strong></p></div>
		
		<?php
		echo $this->controller->generate_body_code( $_REQUEST['itemid'], true ); 
		?>	 
		
		</div>
		<?php
	}
	
	function process_actions()
	{
		
		if ( isset($_REQUEST['action']) && ($_REQUEST['action'] == 'delete') && isset( $_REQUEST['itemid'] ) )
		{
			$deleted = 0;
			
			if ( is_array( $_REQUEST['itemid'] ) )
			{
				foreach( $_REQUEST['itemid'] as $id)
				{
					$ret = $this->controller->delete_item($id);
					if ($ret > 0)
						$deleted += $ret;
				}
			}
			else
			{
				$deleted = $this->controller->delete_item( $_REQUEST['itemid'] );
			}
			
			if ($deleted > 0)
			{
				echo '<div class="updated"><p>';
				printf( _n('%d carousel deleted.', '%d carousels deleted.', $deleted), $deleted );
				echo '</p></div>';
			}
		}
		
		if ( isset($_REQUEST['action']) && ($_REQUEST['action'] == 'clone') && isset( $_REQUEST['itemid'] ) )
		{
			$cloned_id = $this->controller->clone_item( $_REQUEST['itemid'] );
			if ($cloned_id > 0)
			{
				echo '<div class="updated"><p>';
				printf( 'New carousel created with ID: %d', $cloned_id );
				echo '</p></div>';
			}
			else
			{
				echo '<div class="error"><p>';
				printf( 'The carousel cannot be cloned.' );
				echo '</p></div>';
			}
		}
	}

	function print_add_new() {
		
		?>
		<div class="wrap">
		<div id="icon-wonderplugin-carousel" class="icon32"><br /></div>
		<div id="wondercarousellightbox_options" data-skinsfoldername=""  data-jsfolder="<?php echo WONDERPLUGIN_CAROUSEL_URL . 'engine/'; ?>" style="display:none;"></div>
		
		<h2><?php _e( 'New Carousel', 'wonderplugin_carousel' ); ?> <a href="<?php echo admin_url('admin.php?page=wonderplugin_carousel_show_items'); ?>" class="add-new-h2"> <?php _e( 'Manage Carousels', 'wonderplugin_carousel' ); ?>  </a> </h2>
		
		<?php 
		$this->creator = new WonderPlugin_Carousel_Creator($this);		
		echo $this->creator->render( -1, null);
	}
	
	function print_edit_item()
	{
		if ( !isset( $_REQUEST['itemid'] ) )
			return;
	
		?>
		<div class="wrap">
		<div id="icon-wonderplugin-carousel" class="icon32"><br /></div>
		<div id="wondercarousellightbox_options" data-skinsfoldername=""  data-jsfolder="<?php echo WONDERPLUGIN_CAROUSEL_URL . 'engine/'; ?>" style="display:none;"></div>
			
		<h2><?php _e( 'Edit Carousel', 'wonderplugin_carousel' ); ?> <a href="<?php echo admin_url('admin.php?page=wonderplugin_carousel_show_item') . '&itemid=' . $_REQUEST['itemid']; ?>" class="add-new-h2"> <?php _e( 'View Carousel', 'wonderplugin_carousel' ); ?>  </a> </h2>
		
		<?php 
		$this->creator = new WonderPlugin_Carousel_Creator($this);
		echo $this->creator->render( $_REQUEST['itemid'], $this->controller->get_item_data( $_REQUEST['itemid'] ) );
	}
	
}