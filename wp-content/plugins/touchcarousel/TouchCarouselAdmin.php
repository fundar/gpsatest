<?php


class TouchCarouselAdmin {
	/* 
	 * Carousel skins:
	 * 'skinClass' => 'Skin name in admin'
	 * 
	 * Put new skins in:
	 * touchcarousel/skinClass/skinClass.css
	 * 
	 * */
	var $skins = array(
		'black-and-white' => 'Black & white, inside',			
		'grey-blue' => 'Blue & grey, outside',
		'minimal-light' => 'White, minimal inside',
		'three-d' => 'Grey 3D, top-right',
		'white-inside' => 'White, light, inside'
	);
	
	
	var $main;	
	var $name = "";
	var $url = "";
	var $path = "";
	var $current_loop_post;
	var $all_post_types;
	var $all_vars;
	var $options;

	var $styles_to_load = array();
	var $scripts_to_load = array();
	var $custom_css_to_load = array();
	
	function __construct($main) {
		$this->main = $main;
		$this->init();		
		return $this;		
	}
	
	function init() {
		$this->path = dirname( __FILE__ );
		$this->name = basename( $this->path );
		$this->url = plugins_url( "/{$this->name}/" );
		
		load_plugin_textdomain( 'touchcarousel', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		add_theme_support('post-thumbnails');

		if( is_admin() ) {
			add_action('wp_ajax_touchcarouselSave', array(&$this, 'save_carousel') );
			add_action('wp_ajax_touchcarouselUpdateTaxonomies', array(&$this, 'update_taxonomies') );
			add_action('wp_ajax_touchcarouselShowVariables', array(&$this, 'show_variables') );
			register_activation_hook( $this->main , array(&$this, 'activate') );

			add_action( 'admin_init', array(&$this, 'admin_init') );
			add_action( 'admin_menu', array(&$this, 'admin_menu') );	
		} else {			
			add_action('wp_enqueue_scripts', array(&$this, 'frontend_styles_and_scripts'));		
			add_shortcode('touchcarousel', array(&$this, 'shortcode') );
		}
	}
	
	// Variables popup cotent, admin ajax call
	function show_variables() {
		if (!is_admin() && !current_user_can("manage_options") || !wp_verify_nonce( $_POST['touchcarousel_ajax_nonce'], 'touchcarousel_ajax_nonce' ) ) 
			die ( 'tc-oops1' );
		
		if($_POST['action'] == 'touchcarouselShowVariables') {
			$id = intval($_POST['id']);
			include($this->path . '/pages/touchcarousel-variables.php');
		}
		die();
	}
	
	// Taxonomies list, admin ajax call
	function update_taxonomies() {
		if (!is_admin() && !current_user_can("manage_options") || !wp_verify_nonce( $_POST['touchcarousel_ajax_nonce'], 'touchcarousel_ajax_nonce' ) ) 
			die ( 'tc-oops2' );	
		
		
		if($_POST['action'] == 'touchcarouselUpdateTaxonomies') {
			$taxonomies= get_object_taxonomies(array( 'post_type' => $_POST['post_type'] ), 'objects');
			$out = "";		

			$id = intval($_POST['id']);
			if ($id > 0) {
				global $wpdb;
				$carousels_table = $wpdb->prefix . 'touchcarousels';				
				$slider_row = $wpdb->get_row("SELECT * FROM $carousels_table WHERE id = $id", ARRAY_A);
				$selected_array = (array)json_decode (stripslashes($slider_row['post_categories']));
			}
			
			if($taxonomies) {
						
				foreach ($taxonomies  as $taxonomy ) {
					$terms = get_terms($taxonomy->name);
					if ($terms) {
						
						$taxonomy_name = $taxonomy->name;
						$out .= "<optgroup id=\"{$taxonomy_name}\" label=\"{$taxonomy->labels->name}\"> \n";
						if($selected_array) {
							$selected_value = $selected_array[$taxonomy_name];
						}
						
						foreach ($terms as $term) {
							$selected = "";
							if($selected_value && in_array($term->slug, $selected_value)) {
								$selected = "selected=\"selected\"";
							}
							$out .=   "<option value=\"{$term->slug}\" $selected>{$term->name}</option>\n";
						
						}
						$out .=  "</optgroup>";
					}
				}	
			}
			echo $out;
		}
		die();
	}
	
	/**
	* Activate TouchCarousel
	*/
	function activate() {
	
		global $wpdb;
		if (function_exists('is_multisite') && is_multisite()) 
		{
		
			// check if it is a network activation - if so, run the activation function for each blog id
			if (isset($_GET['networkwide']) && ($_GET['networkwide'] == 1)) 
			{
	        
				$old_blog = $wpdb->blogid;
			
				// Get all blog ids
				$blogids = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs"));
				foreach ($blogids as $blog_id) {

					switch_to_blog($blog_id);
					$this->activate_db();
			
				}
			
				switch_to_blog($old_blog);
				return;
			}	
	
		} 
		
		$this->activate_db();
		
	}


	/**
	* Create Database if needed
	*/

	function activate_db() {
	
		global $wpdb;

		$table_name = $wpdb->prefix . 'touchcarousels';
	
		if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) 
		{
		
			$sql = "CREATE TABLE {$table_name} (
		
						  id 				mediumint(9) NOT NULL AUTO_INCREMENT,					  
						  name 				tinytext NOT NULL,
						  skin 				tinytext NOT NULL,
						  preload_skin 		tinyint(1) NOT NULL,
						  width 			tinytext NOT NULL,
						  height 			tinytext NOT NULL,
						  max_posts 		smallint NOT NULL,
						  post_type 		varchar(100) NOT NULL,
						  post_categories 	text NOT NULL,
						  post_orderby 		varchar(20) NOT NULL,
						  layout_name 		varchar(100) NOT NULL,
						  layout_code 		text NOT NULL,
						  layout_css 		text NOT NULL,
						  js_settings 		text NOT NULL,
						  post_relation 	varchar(20) NOT NULL,
						  css_classes 		text NOT NULL,

						  PRIMARY KEY (id)

						);";	
	
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);			

		}

	}	
	


	/**
	* TouchCarousel shortcode
	*/
	function shortcode($atts, $content = null) {
		extract(shortcode_atts(array(
				"id" => '-1'
		), $atts));
		return do_shortcode($this->get_carousel($id, false));
	}	
	/**
	 * Admin menu item
	 */
	function admin_menu() {		
		$main_page = add_menu_page( 'touchcarousel', 'TouchCarousel', 'manage_options', 'touchcarousel', array(&$this, 'admin_page'), plugins_url('/touchcarousel/img/touchcarousel-admin-icons.png') );
	
		add_action( 'admin_print_styles-' . $main_page, array(&$this, 'admin_page_styles') );		
		add_action( 'admin_print_scripts-'. $main_page, array(&$this, 'admin_page_scripts') );		
	}
	
	function admin_init() {
		// register styles and scripts used in admin
		wp_register_style( 'touchcarousel-admin-css', $this->url . 'css/touchcarousel-admin.css' );		
		wp_register_style( 'touchcarousel-frontend-css', $this->url .'touchcarousel/touchcarousel.css' );		
		
		wp_register_style( 'touchcarousel-jquery-ui-css', $this->url . 'css/jquery-ui.css' );
		
		wp_register_style( 'jquery-qtip-css', $this->url .'js/qtip/jquery.qtip.css' );
		wp_register_style( 'jquery-colorbox-css', $this->url .'js/colorbox/colorbox.css' );
		
		wp_register_script( 'textchange', $this->url .'js/jquery.textchange.min.js' );
		wp_register_script( 'touchcarousel-admin-js', $this->url .'js/touchcarousel-admin.js' );
		wp_register_script( 'jquery-url-parser', $this->url .'js/jquery.url.min.js' );
		wp_register_script( 'my-form2object', $this->url .'js/form2object.js' );
		
		wp_register_script( 'jquery-qtip-js', $this->url .'js/qtip/jquery.qtip.min.js' );
		wp_register_script( 'jquery-colorbox-js', $this->url .'js/colorbox/jquery.colorbox-min.js' );
		
		
		wp_register_script( 'touchcarousel-js', $this->url .'touchcarousel/jquery.touchcarousel.min.js' );
				
		wp_register_script( 'ui-dropdownchecklist', $this->url .'js/dropdown-checklist/ui.dropdownchecklist.js',array("jquery-ui-core"),"1.0",false);
	}
	/**
	 * Add CSS styles only to touchcarousel admin pages
	 */
	function admin_page_styles() {				
		/* wp_enqueue_style( 'touchcarousel-jquery-ui-css' ); */
		wp_enqueue_style( 'jquery-qtip-css' );
		wp_enqueue_style( 'jquery-colorbox-css' );
		wp_enqueue_style( 'thickbox' );
		
		wp_enqueue_style( 'touchcarousel-admin-css' );
		wp_enqueue_style( 'touchcarousel-frontend-css' ); 
		
		foreach($this->skins as $skin => $skinName) {
			wp_register_style( 'touchcarousel-skin-' . $skin, $this->url . 'touchcarousel/' . $skin . '-skin/' . $skin . '-skin.css' );
			wp_enqueue_style( 'touchcarousel-skin-' . $skin );
		}
	}

	/**
	 * Add scripts only to touchcarousel admin pages
	 */
	function admin_page_scripts() {
	
		wp_enqueue_script('jquery');
		
		// edit carousel page only scripts
		if (isset($_GET['action']) ) {			
			$this->current_action = $_GET['action'];
			if($this->current_action == 'edit' || $this->current_action == 'add_new') {
				wp_enqueue_script('tc-jquery-ui-core', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.min.js');
				wp_enqueue_script('ui-dropdownchecklist');	
				wp_enqueue_script( 'textchange' );
			}
		}
		
			
		wp_enqueue_script( 'my-form2object' );
		wp_enqueue_script( 'jquery-qtip-js' );
		wp_enqueue_script( 'jquery-colorbox-js' );
		wp_enqueue_script( 'touchcarousel-admin-js' );
		wp_enqueue_script( 'touchcarousel-js' );
		
		global $blog_id;
		wp_localize_script( 'touchcarousel-admin-js', 'touchcarousel_ajax_vars', array(
			'pluginurl' => $this->url,
			'admin_edit_url' => admin_url('admin.php?page=touchcarousel&action=edit&id='), 
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'touchcarousel_ajax_nonce' => wp_create_nonce( 'touchcarousel_ajax_nonce' ),							
			'saveText' => __( 'Save Carousel', 'touchcarousel' ),
			'createText' => __( 'Create Carousel', 'touchcarousel' ),
			'deleteDialogText' => __( 'Delete carousel permanently?', 'touchcarousel' ),
			'savingText' => __( 'Saving...', 'touchcarousel' ),
			'savedText' => __( 'Saved', 'touchcarousel' ),
			'unsavedText' => __( 'Unsaved', 'touchcarousel' ),
			
			'layoutText' => __( 'Layout', 'touchcarousel' ),
			'customLayoutText' => __( 'Custom layout', 'touchcarousel' ),
			'autoText' => __("All", 'touchcarousel'),
			'emptyTaxonomiesText' => __("No taxonomies found for selected post type", 'touchcarousel')
		
		));	
	}
	
	// Frontend JS and CSS files are loaded here
	function frontend_styles_and_scripts() {		
		if(!is_admin()) {
			global $posts;
			global $wpdb;
			$carousels_table = $wpdb->prefix . 'touchcarousels';
			
			// find all all sliders where preload_skins is true
			$slider_skins_preload = $wpdb->get_results("SELECT skin, layout_css FROM " . $carousels_table . " WHERE preload_skin=1", ARRAY_A);	
			
			if($slider_skins_preload) {
				foreach($slider_skins_preload as $slider_skin) {
					if (!in_array($slider_skin['skin'], $this->styles_to_load)) {
						array_push($this->styles_to_load, $slider_skin['skin']);
					}
					if(!in_array($slider_skin['layout_css'], $this->custom_css_to_load)) {
						array_push($this->custom_css_to_load, $slider_skin['layout_css']);
					}
				}
			}
			
			// search for shortcode in curr page
			$matches = array();
			$pattern = get_shortcode_regex();
			
			
			// find shortcode in current post
			if (isset($posts) && !empty($posts)) {
				foreach($posts as $post) {
					preg_match_all('/' . $pattern . '/s', $post->post_content, $matches);
					foreach($matches[2] as $key => $value) {
						
						if($value == 'touchcarousel') {							
							
							$atts = explode(" ", $matches[3][$key]);
								
							foreach($atts as $att) {
								$a = explode("=", $att);
								if($a[0] == 'id' || $a[0] == 'ID') {
									$id = str_replace(array("\"", "'"), "", $a[1]);
									
									$id = intval($id);
									if ($id < 0)
										die ('Incorrect ID 1006');									
								
									$slider_row =  $wpdb->get_row("SELECT * FROM $carousels_table WHERE id = $id", ARRAY_A);
									if(!in_array($slider_row['skin'], $this->styles_to_load)) {	
										array_push($this->styles_to_load, $slider_row['skin']);
									}
									if(!in_array($slider_row['layout_css'], $this->custom_css_to_load)) {
										array_push($this->custom_css_to_load, $slider_row['layout_css']);
									}
								}
								
							}							
						}
					}
				}
			} 
			
			if(count($this->styles_to_load) > 0) {
				wp_enqueue_script( 'jquery' );
				
				wp_register_script( 'touchcarousel-js', $this->url .'touchcarousel/jquery.touchcarousel.min.js',array("jquery"),"1.0",false);
				wp_enqueue_script( 'touchcarousel-js' );
				
				wp_register_style( 'touchcarousel-frontend-css', $this->url .'touchcarousel/touchcarousel.css' );
				wp_enqueue_style( 'touchcarousel-frontend-css' );
				
				
				// Load only needed skins
				foreach($this->styles_to_load as $skin) {
					wp_register_style( 'touchcarousel-skin-' . $skin, $this->url . 'touchcarousel/' . $skin . '-skin/' . $skin . '-skin.css' );
					wp_enqueue_style( 'touchcarousel-skin-' . $skin );
				}
				
				
				add_action( 'wp_head', array(&$this, 'frontend_custom_css') );
			}
			
		}
	}
	// Adding layout CSS
	function frontend_custom_css() {
		?>
		<style type="text/css">
		<?php
			foreach($this->custom_css_to_load as $style) {
				echo stripslashes($style)."\n";
			}
		?>
		</style>
		<?php
	}
	
	// helpers...
	function get_carousel_post_types() {
		if(!$this->all_post_types) {
			$post_types = get_post_types(array(
				'_builtin' => false
			));
			$post_types = array("post" => "post", "page" => "page") + $post_types;
			$this->all_post_types = $post_types;
		}
		return $this->all_post_types;
	}
	// helpers...
	function get_carousel_singular_post_type_name( $type ) 
	{
		$posttype_obj 	= get_post_type_object($type);
		$label 			= $posttype_obj->labels->singular_name;
		return $label;
	}
	
	
	function admin_page() {
		if(!is_admin() ||  !current_user_can("manage_options"))
			die( 'tc-oops3' );
		
		if (isset($_GET['action']) ) {			
			$this->current_action = $_GET['action'];		
		} else {
			$this->current_action = 0;
		}
		if (isset($_GET['id']) ) {
			$this->current_id = intval($_GET['id']);
			if ($this->current_id < 0) 
				die ('tc-oops4' . $this->current_id );			
		} else {
			$this->current_id = -1;
		}
		
		global $wpdb;
		if($this->current_action && ($this->current_action == 'delete' || 
			$this->current_action == 'edit' || 
			$this->current_action == 'duplicate' || 
			$this->current_action == 'add_new')) {			
			
			$carousels_table = $wpdb->prefix . 'touchcarousels';	
			if($this->current_id >= 0) {
				$slider_row = $wpdb->get_row("SELECT * FROM $carousels_table WHERE id = $this->current_id", ARRAY_A);	

			} else {
				$slider_row = 0;
			}	
				
			
			
			if($this->current_action == 'edit') {				
				include_once($this->path . '/pages/touchcarousel-edit-carousel-page.php');
			} else if($this->current_action == 'create') {
				include_once($this->path . '/pages/touchcarousel-edit-carousel-page.php');
			}
			else if ($this->current_action == 'delete') {
				if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'touchcarousel_delete_nonce') ) 
					die( 'tc-oops5' );				
							
				$wpdb->query( "DELETE FROM " . $carousels_table . " WHERE id = $this->current_id" );					
				include_once($this->path . '/pages/touchcarousel-carousels-page.php');
				
			} else if ($this->current_action == 'duplicate') {
				if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'touchcarousel_duplicate_nonce') )
					die( 'tc-oops6' );				
				
			
				$wpdb->insert(
					$carousels_table,
					array(
						'name'=>$slider_row['name'],
						
						'skin'=>$slider_row['skin'],
						'preload_skin'=>$slider_row['preload_skin'],
						
						'width'=>$slider_row['width'],
						'height'=>$slider_row['height'],
						
						'max_posts'=>$slider_row['max_posts'],
						'post_type'=>$slider_row['post_type'],
						'post_categories'=>$slider_row['post_categories'],
						'post_orderby'=>$slider_row['post_orderby'],
						
						'layout_name'=>$slider_row['layout_name'],
						'layout_code'=>$slider_row['layout_code'],
						'layout_css'=>$$slider_row['layout_css'],
						
						'js_settings'=>$slider_row['js_settings'],
						'post_relation' => $slider_row['post_relation'],
						'css_classes' => $slider_row['css_classes']
					),
					array(
						'%s',
						
						'%s',
						'%d',

						'%s',
						'%s',

						'%d',
						'%s',
						'%s',
						'%s',
						
						'%s',
						'%s',
						'%s',
						
						'%s',
						'%s',
						'%s'
					)
				);	
				
				include_once($this->path . '/pages/touchcarousel-carousels-page.php');
			} else if($this->current_action == 'add_new') {
				include_once($this->path . '/pages/touchcarousel-edit-carousel-page.php');
			} 
		} else {
			include_once( $this->path . '/pages/touchcarousel-carousels-page.php' );			
		}		
		
	}	
	function get_variable_from_array($variable_name, $arr) {
		if ( array_key_exists($variable_name, $arr) ) {
			if( ($arr instanceof stdClass)) {
				$arr = (array)$arr;
			}
			
			$new_value = $arr[$variable_name];
			
			
			$counter = 10;
			while(is_array($new_value)) {
				$new_value = $new_value[0];
				
				if($counter <= 0) {
					break;
				}
				$counter--;
			}
		} else {
			$new_value = false;
		}
		return $new_value;
	}
	
	/*
	 * Resize images dynamically using wp built in functions
	 * Victor Teixeira
	 *
	 * php 5.2+
	 *
	 * Exemplo de uso:
	 *
	 * <?php
	 * $thumb = get_post_thumbnail_id();
	 * $image = vt_resize( $thumb, '', 140, 110, true );
	 * ?>
	 * <img src="<?php echo $image[url]; ?>" width="<?php echo $image[width]; ?>" height="<?php echo $image[height]; ?>" />
	 *
	 * @param int $attach_id
	 * @param string $img_url
	 * @param int $width
	 * @param int $height
	 * @param bool $crop
	 * @return array
	 */
	
	function vt_resize( $attach_id = null, $img_url = null, $width, $height, $crop = false ) {

		// this is an attachment, so we have the ID
		if ( $attach_id ) {
			
			$image_src = wp_get_attachment_image_src( $attach_id, 'full' );
			$file_path = get_attached_file( $attach_id );

		// this is not an attachment, let's use the image url
		} else if ( $img_url ) {

			$file_path = parse_url( $img_url );
			$file_path = $_SERVER['DOCUMENT_ROOT'] . $file_path['path'];

			//$file_path = ltrim( $file_path['path'], '/' );
			//$file_path = rtrim( ABSPATH, '/' ).$file_path['path'];

			$orig_size = getimagesize( $file_path );

			$image_src[0] = $img_url;
			$image_src[1] = $orig_size[0];
			$image_src[2] = $orig_size[1];
		}

		$file_info = pathinfo( $file_path );

		
		// check if file exists
		$base_file = $file_info['dirname'].'/'.$file_info['filename'].'.'.$file_info['extension'];
		
		
		
		if ( !file_exists($base_file) )
		 return;

		$extension = '.'. $file_info['extension'];

		// the image path without the extension
		$no_ext_path = $file_info['dirname'].'/'.$file_info['filename'];

		$cropped_img_path = $no_ext_path.'-'.$width.'x'.$height.$extension;



		
		
		
		// checking if the file size is larger than the target size
		// if it is smaller or the same size, stop right here and return
		
		if ( $image_src[1] > $width ) {
			
			// the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)
			if ( file_exists( $cropped_img_path ) ) {
				
				$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );

				$vt_image = array (
					'url' => $cropped_img_url,
					'width' => $width,
					'height' => $height
				);

				return $vt_image;
			}

			// $crop = false or no height set
			if ( $crop == false OR !$height ) {

				// calculate the size proportionaly
				$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
				$resized_img_path = $no_ext_path.'-'.$proportional_size[0].'x'.$proportional_size[1].$extension;

				// checking if the file already exists
				if ( file_exists( $resized_img_path ) ) {

					$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );

					$vt_image = array (
						'url' => $resized_img_url,
						'width' => $proportional_size[0],
						'height' => $proportional_size[1]
					);

					return $vt_image;
				}
			}

			// check if image width is smaller than set width
			$img_size = getimagesize( $file_path );
			if ( $img_size[0] <= $width ) $width = $img_size[0];
			
			// Check if GD Library installed
			if (!function_exists ('imagecreatetruecolor')) {
			    echo 'GD Library Error: imagecreatetruecolor does not exist - please contact your webhost and ask them to install the GD library';
			    return;
			}

			// no cache files - let's finally resize it
			$new_img_path = image_resize( $file_path, $width, $height, $crop );			
			$new_img_size = getimagesize( $new_img_path );
			$new_img = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );

			// resized output
			$vt_image = array (
				'url' => $new_img,
				'width' => $new_img_size[0],
				'height' => $new_img_size[1]
			);

			return $vt_image;
		}

		// default output - without resizing
		$vt_image = array (
			'url' => $image_src[0],
			'width' => $width,
			'height' => $height
		);

		return $vt_image;
	}	
	
	// Find and replace with content all [tco] vars
	function format_variables($match) {
		$variable_name = $this->getTextBetweenTags($match[0], 'tco');
		
		if($variable_name) {
			$curr_post = $this->current_loop_post;
			
			switch (strtolower($variable_name)) :
				case "title" :
					return $curr_post->post_title;
					break;
					
				case "excerpt" :
					$limit = intval($this->getAttributeValue('length', $match[0]));
					
					if($limit > 0) {
						$excerpt = explode(' ', get_the_excerpt(), $limit);
				        if (count($excerpt)>=$limit) {
				        	array_pop($excerpt);
				        	$excerpt = implode(" ",$excerpt).'…';
				        } else {
				        	$excerpt = implode(" ",$excerpt);
				        } 
				    	return preg_replace('`\[[^\]]*\]`','',$excerpt);
					} else {
						return apply_filters('the_excerpt', $curr_post->post_excerpt);
					}
					
					break;
					
				case "content" :
					return apply_filters('the_content', $curr_post->post_content);
					break;
					
				case "permalink" :
					return get_permalink($curr_post->ID);
					break;
					
				case "date" : 
					return get_the_date(get_option('date_format'));
					break;
				case "time" : 
					return get_the_time(get_option('time_format'));
					break;
					
				case "thumbnail" :
					
					$m = $match[0];
					$wp_size = $this->getAttributeValue('wp-size', $m);
					
					if($wp_size) {
						return  get_the_post_thumbnail($curr_post->ID, $wp_size);
					} else {
						$thumb = get_post_thumbnail_id($curr_post->ID); 
		 				
		 				if($thumb) {
		 					$width = (int)$this->getAttributeValue('width', $m);
							$height = (int)$this->getAttributeValue('height', $m);
		 					$image_data = $this->vt_resize( $thumb, '', $width, $height, true );
		 					return "<img src=\"{$image_data['url']}\" width=\"{$image_data['width']}\" height=\"{$image_data['height']}\" />";
		 				} else {
		 					return "";
		 				}
					}
					break;
					
					
				case "comments-popup-link" : 
					ob_start(); 
					comments_popup_link();
					$new_value = ob_get_contents(); 
					ob_end_clean();
					return $new_value;
					break;
				case "comments-url" :
					return  get_comments_link($curr_post->ID);
					break;
					
				case "author-name" :
					return  get_the_author_meta( 'display_name', $curr_post->post_author );
					break;
				case "author-url" :
					return  get_author_posts_url( $curr_post->post_author );
					break;
					
				
				case "tags" :
					return  get_the_tag_list('',', ','');
					break;
				case "categories" : 
					return  get_the_category_list(", ", '', $curr_post->ID);
					break;
			endswitch;	
			
			
			
			if(!$new_value) {
				$new_value = $this->get_variable_from_array($variable_name, get_post_custom( $curr_post->ID ));
			} // meta fields
			
		} // if variable name defined
		return $new_value;
	}
	
	// helpers...
	function getTextBetweenTags($string, $tagname) {
	    $pattern = "/\[$tagname ?.*\](.*)\[\/$tagname\]/";
	    preg_match($pattern, $string, $matches);
	    return $matches[1];
	}
	function getAttributeValue($attr, $input_string){
		$count = preg_match('/'.$attr.'=(["\'])(.*?)\1/', $input_string, $match);
		if ($count === false) {
			return false;
		}
		else {
			if($match)
				return $match[2];
			else
				return false;
		}
			
	}
	
	
	/* returns carousel html and js embed code */
	function get_carousel($id, $get_first_post) 
	{
	
		$id = intval($id);
		if ($id <= 0)
			die ('tc-oops7');	
		
		global $wpdb;		
		$carousels_table = $wpdb->prefix . 'touchcarousels';
		$slider_row = $wpdb->get_row("SELECT * FROM $carousels_table WHERE id = $id", ARRAY_A);		
		
		if(!$slider_row) {
			return "<p>Oops, TouchCarousel with ID $id not found.</p>";
		}
		
		$carousel_html = '';
		$skin_name = $slider_row['skin'];
		$css_classes = $slider_row['css_classes'];
		$carousel_html .= "<div id=\"touchcarousel-$id\" class=\"touchcarousel $skin_name $css_classes\" style=\"width:{$slider_row['width']};  height:{$slider_row['height']}; \">\n";
		$carousel_html .= "\t<ul class=\"touchcarousel-container\">\n";
	
		global $post;
		$args = array(
		    'numberposts'     => intval($slider_row['max_posts']),
		    'posts_per_page'  => intval($slider_row['max_posts']),
		    'offset'          => 0,
		    'cat'             => $slider_row['post_categories'],
		    'orderby'         => $slider_row['post_orderby'],
		    'order'           => 'DESC',
		    'include'         => '',
		    'exclude'         => '',
		    'meta_key'        => '',
		    'meta_value'      => '',
		    'post_type'       => $slider_row['post_type'],
		    'post_mime_type'  => '',
		    'post_parent'     => '',
		    'post_status'     => 'publish' );
		    
	    
	    $post_taxonomies_arr = (array)json_decode (stripslashes($slider_row['post_categories']));
	    $taxonomies_query_arr = array();
	    
	    $taxonomies_query_arr['relation'] = $slider_row['post_relation'];
	    
	    $count = 0;
	    foreach ($post_taxonomies_arr as  $key => $taxonomy ) {
	    	$taxonomies_query_arr[$count]['taxonomy'] = $key;
	    	$taxonomies_query_arr[$count]['terms'] = $taxonomy;
	    	$taxonomies_query_arr[$count]['field'] = 'slug';
	    	$count++;
	    }
	  
		$args['tax_query'] = $taxonomies_query_arr;
    
		$the_query = new WP_Query( $args );
		
		if($get_first_post) {
			$the_query->have_posts();
			$the_query->the_post();
			$this->current_loop_post = $post;
	    	return $post;
	    }
	    
	   
	    if($the_query->have_posts()) {
	    	$layout_code = stripslashes($slider_row['layout_code']);
	    	while ( $the_query->have_posts() ) : 
				$the_query->the_post();
				$this->current_loop_post = $post;
				$carousel_html .= "\t\t<li class=\"touchcarousel-item\">\n";
				$carousel_html .= preg_replace_callback ("/\[tco.*?\](.*?)\[\/tco\]/", array($this, 'format_variables'), $layout_code);
				$carousel_html .= "\t\t</li>\n";
			endwhile;

			$carousel_html .= "\t</ul>\n";
			$carousel_html .= "</div>";
			
	 		$slider_settings =  stripslashes($slider_row['js_settings']);
			
			$carousel_js = "";
			$carousel_js .= "<script type=\"text/javascript\">\n";
			$carousel_js .= "jQuery(document).ready(function($) {";
			$carousel_js .= "$(\"#touchcarousel-$id\").touchCarousel(";			
			$carousel_js .= $slider_settings;		
			$carousel_js .= ");";
			$carousel_js .= "});\n";
			$carousel_js .= "</script>";
			
			$carousel_html .= $carousel_js;


	    } else {
	    	$carousel_html = "<p class=\"tc-posts-not-found\">". __('TouchCarousel Warning: No posts found with selected settings.', 'touchcarousel') ."</p>";
	    }
		
		
		
		
		wp_reset_postdata();
		wp_reset_query();
		
		
		return stripslashes($carousel_html);
	}
	
	/* save carousel to database or create new */
	function save_carousel() {
		if ( !current_user_can("manage_options") || !wp_verify_nonce( $_POST['touchcarousel_ajax_nonce'], 'touchcarousel_ajax_nonce' ) ) 
			return 'no permissions';	

		
		if($_POST['action'] == 'touchcarouselSave') {	
			global $wpdb;
			$carousels_table = $wpdb->prefix . 'touchcarousels';
			
			if (isset($_POST['id']) ) {
				$id = intval($_POST['id']);				
			}
			
			// insert new carousel
			
			if( $id <= 0 ) {
				$wpdb->insert(
					$carousels_table,
					array(
						'name'=>$_POST['name'],
						
						'skin'=>$_POST['skin'],
						'preload_skin'=>$_POST['preload_skin'],
						
						'width'=>$_POST['width'],
						'height'=>$_POST['height'],
						
						'max_posts'=>$_POST['max_posts'],
						'post_type'=>$_POST['post_type'],
						'post_categories'=>$_POST['post_categories'],
						'post_orderby'=>$_POST['post_orderby'],
						
						'layout_name'=>$_POST['layout_name'],
						'layout_code'=>$_POST['layout_code'],
						'layout_css'=>$_POST['layout_css'],
						
						'js_settings'=>$_POST['js_settings'],
						'post_relation' => $_POST['post_relation'],
						'css_classes' => $_POST['css_classes']
											
					),
					array(
						'%s',
						
						'%s',
						'%d',

						'%s',
						'%s',

						'%d',
						'%s',
						'%s',
						'%s',
						
						'%s',
						'%s',
						'%s',
						
						'%s',
						'%s',
						'%s'
				
					)
				);
				$insert_id = $wpdb->insert_id;
				echo $insert_id;
			} else { // update existing slider
				$wpdb->update(
					$carousels_table,
					array(
						'name'=>$_POST['name'],
						
						'skin'=>$_POST['skin'],
						'preload_skin'=>$_POST['preload_skin'],
						
						'width'=>$_POST['width'],
						'height'=>$_POST['height'],
						
						'max_posts'=>$_POST['max_posts'],
						'post_type'=>$_POST['post_type'],
						'post_categories'=>$_POST['post_categories'],
						'post_orderby'=>$_POST['post_orderby'],
						
						'layout_name'=>$_POST['layout_name'],
						'layout_code'=>$_POST['layout_code'],
						'layout_css'=>$_POST['layout_css'],
						
						'js_settings'=>$_POST['js_settings'],
						'post_relation' => $_POST['post_relation'],
						'css_classes' => $_POST['css_classes']			
					),
					array( 'id' => $id ),
					array(
						'%s',
						
						'%s',
						'%d',
						
						'%s',
						'%s',

						'%d',
						'%s',
						'%s',
						'%s',
						
						'%s',
						'%s',
						'%s',
						
						'%s',
						'%s',
						'%s'					
					),
					array( 
						'%d' 
					)
				);				
				echo $id;				
			}
			
		}
		die();
	}	
}
?>