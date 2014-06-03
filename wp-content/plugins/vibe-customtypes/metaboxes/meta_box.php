<?php

function meta_box_find_field_type( $needle, $haystack ) {
    foreach ( $haystack as $item )
        if ( $item['type'] == $needle )
            return true;
    return false;
}

class custom_add_meta_box {
	
	var $id; // string meta box id
	var $title; // string title
	var $fields; // array fields
	var $page; // string|array post type to add meta box to
	var $js; // bool including javascript or not
	
    public function __construct( $id, $title, $fields, $page, $js ) {
		$this->id = $id;
		$this->title = $title;
		$this->fields = $fields;
		$this->page = $page;
		$this->js = $js;
		
		if(!is_array($this->page)) {
			$this->page = array($this->page);
		}
		
                add_action( 'admin_print_scripts-post-new.php',  array( $this, 'admin_enqueue_scripts' ) ); 
                add_action( 'admin_print_scripts-post.php',  array( $this, 'admin_enqueue_scripts' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'persistent_admin_scripts' ) );
                
		add_action( 'admin_head',  array( $this, 'admin_head' ) );
		add_action( 'admin_menu', array( $this, 'add_box' ) );
		add_action( 'save_post',  array( $this, 'save_box' ));
    }
	
	function admin_enqueue_scripts() {
		
        wp_enqueue_script( 'jquery-ui-datepicker', array( 'jquery', 'jquery-ui-core' ) );
		wp_enqueue_script( 'jquery-ui-slider', array( 'jquery', 'jquery-ui-core' ) );
		wp_enqueue_script( 'graph_box', VIBE_PLUGIN_URL . '/vibe-customtypes/metaboxes/js/jquery.flot.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'graph_resize_box', VIBE_PLUGIN_URL . '/vibe-customtypes/metaboxes/js/jquery.flot.resize.min.js', array( 'jquery' ) );
        wp_enqueue_script( 'meta_box-gmap','http://maps.google.com/maps/api/js?sensor=false');
        wp_register_style( 'jqueryui', VIBE_PLUGIN_URL . '/vibe-customtypes/metaboxes/css/jqueryui.css' );
	}
	
	function persistent_admin_scripts(){
		wp_enqueue_script( 'meta_box_js', VIBE_PLUGIN_URL . '/vibe-customtypes/metaboxes/js/scripts.js', array( 'jquery','iris','jquery-ui-core','jquery-ui-sortable','jquery-ui-slider','jquery-ui-datepicker' ) );
		wp_enqueue_style( 'meta_box_css', VIBE_PLUGIN_URL . '/vibe-customtypes/metaboxes/css/meta_box.css');
	}
	// scripts
	function admin_head() {
		global $post, $post_type;
		
		if (in_array($post_type, $this->page) && $this->js == true ) { 

			echo '<script type="text/javascript">
						jQuery(function() {';
			
			foreach ( $this->fields as $field ) {
				// date
				if( $field['type'] == 'date' )
					echo 'jQuery("#' . $field['id'] . '").datepicker({
							dateFormat: \'yy-mm-dd\'});';
				// slider
				if ( $field['type'] == 'slider' ) {
					$value = get_post_meta( $post->ID, $field['id'], true );
					if ( $value == '' ) $value = $field['min'];
					echo 'jQuery( "#' . $field['id'] . '-slider" ).slider({
								value: ' . $value . ',
								min: ' . $field['min'] . ',
								max: ' . $field['max'] . ',
								step: ' . $field['step'] . ',
								slide: function( event, ui ) {
									jQuery( "#' . $field['id'] . '" ).val( ui.value );
								}
							});';
				}
                                if ( $field['type'] == 'gmap' ) {
                                    global $vibe_options;
                                    $zoom=(isset($vibe_options['zoomlevel'])?$vibe_options['zoomlevel']:15);
                                    $value = get_post_meta( $post->ID, $field['id'], true );
                                    echo "if(typeof google != 'undefined'){
var geocoder = new google.maps.Geocoder();

function geocodePosition(pos) {
  geocoder.geocode({
    latLng: pos
  }, function(responses) {
    if (responses && responses.length > 0) {
        var address = '',city = '', state = '', zip = '', country = '', formattedAddress = '';
                     for (var i = 0; i < responses[0].address_components.length; i++) {
                          var addr = responses[0].address_components[i];
                          // check if this entry in address_components has a type of country
                          if (addr.types[0] == 'country'){
                              document.getElementById('country').value = addr.long_name;
                              country = addr.long_name;
                          }else if (addr.types[0] == 'postal_code'){       // Zip
                              document.getElementById('pincode').value = addr.short_name;
                              zip = addr.long_name;
                          }else if (addr.types[0] == ['administrative_area_level_1']){       // State
                              document.getElementById('state').value = addr.long_name;
                              state = addr.long_name;
                          }else if (addr.types[0] == ['locality']){       // City
                              document.getElementById('city').value = addr.long_name;
                              city = addr.long_name;
                              }
                      }
                      
      var staddr=responses[0].formatted_address;
      staddr=staddr.replace(country,'');staddr=staddr.replace(zip,'');staddr=staddr.replace(city,'');staddr=staddr.replace(state,'');
      staddr=staddr.replace(', ,','');
      document.getElementById('staddress').value = staddr;                
      updateMarkerAddress(responses[0].formatted_address);
    } else {
      updateMarkerAddress('Cannot determine address at this location.');
    }
  });
}

function updateMarkerStatus(str) { 
}

function updateMarkerPosition(latLng) {

  document.getElementById('latitude').value = latLng.lat();
  document.getElementById('longitude').value =  latLng.lng();

}

function updateMarkerAddress(str) {
    if(str != null)
    document.getElementById('address').innerHTML = str;
}

function initialize() {
var lat;var lng;
".(isset($value['latitude'])?"lat= ".$value['latitude']:'')." ;
lat = lat || +37.0625;
if(isNaN(lat))
 lat=37.0625;
 
".(isset($value['longitude'])?"lng= ".$value['longitude']:'')." ;
    
lng = lng || -95.677068;
if(isNaN(lng))
 lng= -95.677068;


  var latLng = new google.maps.LatLng(lat,lng);
  var map = new google.maps.Map(document.getElementById('mapCanvas'), {
    zoom: ".$zoom.",
    center: latLng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  });
  var marker = new google.maps.Marker({
    position: latLng,
    title: 'Property',
    map: map,
    draggable: true
  });
  
  // Update current position info.
  updateMarkerPosition(latLng);
  geocodePosition(latLng);
  
  // Add dragging event listeners.
  google.maps.event.addListener(marker, 'dragstart', function() {
    updateMarkerAddress('Dragging...');
  });
  
  google.maps.event.addListener(marker, 'drag', function() {
    updateMarkerStatus('Dragging...');
    updateMarkerPosition(marker.getPosition());
  });
  
  google.maps.event.addListener(marker, 'dragend', function() {
    updateMarkerStatus('Drag ended');
    geocodePosition(marker.getPosition());
    
  });
}
    
// Onload handler to fire off the app.
google.maps.event.addDomListener(window, 'load', initialize);
}";
                                }
			}
			
			echo '});
				</script>';
		};
	}
	
	function add_box() {
		foreach ($this->page as $page) {
			add_meta_box( $this->id, $this->title, array( $this, 'meta_box_callback' ), $page, 'normal', 'high');
		}
	}
	
	function meta_box_callback() {
		global $post, $post_type;
		// Use nonce for verification
		echo '<input type="hidden" name="' . $post_type . '_meta_box_nonce" value="' . wp_create_nonce( basename( __FILE__) ) . '" />';
		
		// Begin the field table and loop
		echo '<table class="form-table meta_box">';
		if(is_array($this->fields))
		foreach ( $this->fields as $field) {
			
			// get data for this field
			extract( $field );
			if ( !empty( $desc ) )
				$desc = '<span class="description">' . $desc . '</span>';
				
			// get value of this field if it exists for this post

				$meta = get_post_meta( $post->ID, $id, true);
			
			
			// begin a table row with
			echo '<tr>
					<th><label for="' . $id . '">' . $label . '</label></th>
					<td>';
					switch( $type ) {
                        case 'number':
                        
							echo '<input type="number" name="' . $id . '" id="' . $id . '" value="' . esc_attr( $meta ) . '" size="20" />
									<br />' . $desc;
						break;
						// text
						case 'text':
							echo '<input type="text" name="' . $id . '" id="' . $id . '" value="' . esc_attr( $meta ) . '" size="20" />
									<br />' . $desc;
						break;
						// textarea
						case 'textarea':
							echo '<textarea name="' . $id . '" id="' . $id . '" cols="60" rows="4">' . esc_attr( $meta ) . '</textarea>
									<br />' . $desc;
						break;
						// editor
						case 'editor':
							wp_editor(  $meta, $id , array(
							        'wpautop' => true,
							        'media_buttons' => true,
							        'teeny' => true,
							        'textarea_rows' => '4',
							        'textarea_cols' => '30',
							        'tinymce' => array(
							                        'theme_advanced_buttons1' => 'save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,cut,copy,paste,pastetext,pasteword,',
							                        'theme_advanced_buttons2' => "styleselect,formatselect,fontselect,fontsizeselect,",
							                        'theme_advanced_buttons3' => ",bullist,numlist,|,outdent,indent,blockquote,|,link,anchor,image,|,insertdate,forecolor,backcolor,|,tablecontrols,|,hr,|,fullscreen",
							                        'theme_advanced_buttons4' => "",
							                        'theme_advanced_text_colors' => '0f3156,636466,0486d3',
							                    ),
							        'quicktags' => array(
							            'buttons' => 'b,i,ul,ol,li,link,close'
							        )
							    ) );
							echo '<br />' . $desc;
						break;

                                                // color
                        case 'color':
							echo '<input type="text" name="' . $id . '" id="' . $id . '" value="' . esc_attr( $meta ) . '" size="10" class="color" />
									<br />' . $desc;
						break;  
						case 'checkbox':
                                                       if(!isset($std))$std=0;
                                                        if(!isset($meta)){$meta=$std;}
							echo '<div class="checkbox_button"></div>
                                                              <input type="checkbox" name="' . $id . '" id="' . $id . '" ' . checked( esc_attr( $meta ), 1, 0 ) . ' class="checkbox_val" value="1" />
								<label for="' . $id . '">' . $desc . '</label>';
						break;
						case 'yesno':
							echo '<div class="select_button yesno"></div>';
                            echo '<select name="' . $id . '" id="' . $id . '" class="select_val">';
                            
                            if($meta == '' || !isset($meta) || !$meta){$meta=$std;}


							foreach ( $options as $option )
								echo '<option' . selected( esc_attr( $meta ), $option['value'], false ) . ' value="' . $option['value'] . '">' . $option['label'] . '</option>';
							echo '</select><br />' . $desc;
						break;
                        case 'showhide':
							echo '<div class="select_button"></div>';
                            echo '<select name="' . $id . '" id="' . $id . '" class="select_val">';
                            
                            if($meta == '' || !isset($meta) || !$meta){$meta=$std;}


							foreach ( $options as $option )
								echo '<option' . selected( esc_attr( $meta ), $option['value'], false ) . ' value="' . $option['value'] . '">' . $option['label'] . '</option>';
							echo '</select><br />' . $desc;
						break;
						// select
						case 'select':
							echo '<select name="' . $id . '" id="' . $id . '" class="select">';
                                                        if($meta == '' || !isset($meta)){$meta=$std;}
							foreach ( $options as $option )
								echo '<option' . selected( esc_attr( $meta ), $option['value'], false ) . ' value="' . $option['value'] . '">' . $option['label'] . '</option>';
							echo '</select><br />' . $desc;
						break;
						// select
						case 'selectcpt':
							echo '<select name="' . $id . '" id="' . $id . '" class="chzn-select">';
                            if($meta == '' || !isset($meta)){$meta=$std;}

                            $args= array(
                            	'post_type'=>$post_type,
                            	'numberposts'=> -1
                            	);
                            $kposts=get_posts($args);
                            echo '<option value="">None</option>';
							foreach ( $kposts as $kpost ){
								echo '<option' . selected( esc_attr( $meta ), $kpost->ID, false ) . ' value="' . $kpost->ID . '">' . $kpost->post_title . '</option>';
							}

							echo '</select><br />' . $desc;
						break;
						
						case 'selectmulticpt':
							echo '<select name="' . $id . '[]" id="' . $id . '" class="select chzn-select" data-placeholder="Select '.$post_type.'s" multiple>';
                            if($meta == '' || !isset($meta)){$meta=$std;}

                            $args= array(
                            	'post_type'=>$post_type,
                            	'numberposts'=> -1
                            	);
                            $kposts=get_posts($args);
                            echo '<option value="">None</option>';
							foreach ( $kposts as $kpost ){
								echo '<option  '.(in_array($kpost->ID,$meta)?'SELECTED':'').' value="' . $kpost->ID . '">' . $kpost->post_title . '</option>';
							}

							echo '</select><br />' . $desc;
						break;
                                                // Multiselect
						case 'multiselect':
							echo '<select name="' . $id . '[]" id="' . $id . '" multiple class="chzn-select chosen">';
                                                        if($meta == '' || !isset($meta)){$meta=$std;}
							foreach ( $options as $option )
                                                            
								echo '<option value="' . $option['value'] . '" '.(in_array($option['value'],$meta)?'SELECTED':'').'>' . $option['label'] . '</option>';
							echo '</select><br />' . $desc;
						break;
						// radio
						case 'radio':
							foreach ( $options as $option )
								echo '<input type="radio" name="' . $id . '" id="' . $id . '-' . $option['value'] . '" value="' . $option['value'] . '" ' . checked( esc_attr( $meta ), $option['value'], false ) . ' />
										<label for="' . $id . '-' . $option['value'] . '">' . $option['label'] . '</label><br />';
							echo '' . $desc;
						break;
                        case 'radio_img': 
                                                    if($meta == '' || !isset($meta)){$meta=$std;}
							foreach ( $options as $option )
								echo '<div class="radio-image-wrapper">
                                                                        <label for="' . $option['value'] . '">
                                                                            <img src="'.$option['image'].'">
                                                                            <div class="select '.((esc_attr( $meta ) == $option['value'])?"selected":"").'"></div>
                                                                        </label>
                                                                        <input type="radio" class="radio_img" name="' . $id . '" id="' . $id . '-' . $option['value'] . '" value="' . $option['value'] . '" ' . checked( esc_attr( $meta ), $option['value'], false ) . ' />
                                                                     </div>';
							echo '' . $desc;
						break;
						// checkbox_group
						case 'checkbox_group':
							foreach ( $options as $option )
								echo '<input type="checkbox" value="' . $option['value'] . '" name="' . $id . '[]" id="' . $id . '-' . $option['value'] . '"' , is_array( $meta ) && in_array( $option['value'], $meta ) ? ' checked="checked"' : '' , ' /> 
										<label for="' . $id . '-' . $option['value'] . '">' . $option['label'] . '</label><br />';
							echo '' . $desc;
						break;
						// tax_select
						case 'tax_select':
							echo '<select name="' . $id . '" id="' . $id . '">
									<option value="">Select One</option>'; // Select One
							$terms = get_terms( $id, 'get=all' );
							$selected = wp_get_object_terms( $post->ID, $id );
							foreach ( $terms as $term ) 
									echo '<option value="' . $term->slug . '"' . selected( $selected[0]->slug, $term->slug, false ) . '>' . $term->name . '</option>'; 
							$taxonomy = get_taxonomy( $id);
							echo '</select> &nbsp;<span class="description"><a href="' . home_url() . '/wp-admin/edit-tags.php?taxonomy=' . $id . '&post_type=' . $post_type . '">Manage ' . $taxonomy->label . '</a></span>
								<br />' . $desc;
						break;
						// post_list
						case 'post_list':
							echo '<select name="' . $id . '" id="' . $id . '">
									<option value="">Select One</option>'; // Select One
							$posts = get_posts( array( 'post-type' => $post_type, 'numberposts' => 9999 ) );
							foreach ( $posts as $item ) 
									echo '<option value="' . $item->ID . '"' . selected( intval( $meta ), $item->ID, false ) . '>' . $item->post_title . '</option>';
							echo '</select><br />' . $desc;
						break;
						// date
						case 'date':
							echo '<input type="text" class="datepicker" name="' . $id . '" id="' . $id . '" value="' . esc_attr( $meta ) . '" size="30" />
									<br />' . $desc;
						break;

						case 'groups':
						global $bp;
						//$vgroups=groups_get_groups('type=alphabetical&per_page=-1');
						if(class_exists('BP_Groups_Group')){
							$vgroups =  BP_Groups_Group::get(array(
							'type'=>'alphabetical',
							'per_page'=>999
							));

							
							echo '<select name="' . $id . '" id="' . $id . '" class="chzn-select">
									<option value="">None</option>';

							foreach($vgroups['groups'] as $vgroup){
								echo '<option value="'.$vgroup->id.'" '.selected($vgroup->id,intval( $meta)).'>'.$vgroup->name.'</option>';
							}
							echo '</select>';
						}else{
							echo 'Buddypress is not Active';
						}
						
						
						echo '<br />' . $desc;
						break;

						case 'curriculum':
							echo '<a class="meta_box_add_section button-primary button-large" href="#">Add Section</a>
									<a class="meta_box_add_posttype1 button-primary button-large" href="#">Add '.$post_type1.'</a>
									<a class="meta_box_add_posttype2 button button-primary button-large" href="#">Add '.$post_type2.'</a>
									<ul id="' . $field['id'] . '-repeatable" class="meta_box_repeatable">';
							$i = 0;
							if ( $meta ) {
								foreach( $meta as $row ) {
									echo '<li><span class="sort handle dashicons dashicons-sort"></span>
												 <input type="text" name="' . $field['id'] . '[' . $i . ']" id="' . $field['id'] . '" class="'.(is_numeric($row)?'small postid':'').'" value="' . esc_attr( $row ) . '" size="30" READONLY /> <span>'.(is_numeric($row)?get_the_title($row):'').'</span>
												<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';
									$i++;
								}
							} 
								echo '<li class="section hide"><span class="sort handle dashicons dashicons-sort"></span>
											<input type="text" rel-name="' . $field['id'] . '[]" id="' . $field['id'] . '" value="" size="30" />
											<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';

								echo '<li class="posttype1 hide"><span class="sort handle dashicons dashicons-sort"></span>
											<select rel-name="' . $field['id'] . '[]" class="chz-select">
												<option value="">Select..</option>';
											$posts = get_posts( array( 'post_type' => $post_type1, 'posts_per_page' => -1 ) );
                                            foreach ( $posts as $item ) 
                                            	echo '<option value="' . $item->ID . '"' . selected( intval( $meta ), $item->ID, false ) . '>' . $item->post_title . '</option>';
								echo '</select><a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';											

								echo '<li class="posttype2 hide"><span class="sort handle dashicons dashicons-sort"></span>
											<select rel-name="' . $field['id'] . '[]" class="chz-select">
												<option value="">Select..</option>';
											$posts = get_posts( array( 'post_type' => $post_type2, 'posts_per_page' => -1 ) );
                                            foreach ( $posts as $item ) 
                                            	echo '<option value="' . $item->ID . '"' . selected( intval( $meta ), $item->ID, false ) . '>' . $item->post_title . '</option>';
								echo '</select><a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';											

							
							echo '</ul>
								<span class="description">' . $field['desc'] . '</span>';
						break;
                                            
                        case 'tax_select':
							echo '<select name="' . $id . '" id="' . $id . '">
									<option value="">Select One</option>'; // Select One
							$terms = get_terms( $id, 'get=all' );
							$selected = wp_get_object_terms( $post->ID, $id );
							foreach ( $terms as $term ) 
									echo '<option value="' . $term->slug . '"' . selected( $selected[0]->slug, $term->slug, false ) . '>' . $term->name . '</option>'; 
							$taxonomy = get_taxonomy( $id);
							echo '</select> &nbsp;<span class="description"><a href="' . home_url() . '/wp-admin/edit-tags.php?taxonomy=' . $id . '&post_type=' . $post_type . '">Manage ' . $taxonomy->label . '</a></span>
								<br />' . $desc;
						break;
						// slider
						case 'slider':
						$value = $meta != '' ? intval( $meta ) : '0';
							echo '<div id="' . $id . '-slider"></div>
									<input type="text" name="' . $id . '" id="' . $id . '" value="' . $value . '" size="5" />
									<br />' . $desc;
						break;
						// image
						case 'image':
							$image = VIBE_URL . '/includes/metaboxes/images/image.png';	
							echo '<span class="meta_box_default_image" style="display:none">' . $image . '</span>';
                                                        if ( $meta ) {
								$image = wp_get_attachment_image_src( intval( $meta ), 'full' );
								$image = $image[0];
							}else
                                                            $meta='';
							echo	'<input name="' . $id . '" type="hidden" class="meta_box_upload_image" value="' . intval( $meta ) . '" />
										<img src="' . $image . '" class="meta_box_preview_image" alt="" /><br />
											<input class="meta_box_upload_image_button button" type="button" rel="' . $post->ID . '" value="Choose Image" />
											<small>&nbsp;<a href="#" class="meta_box_clear_image_button">Remove Image</a></small>
											<br clear="all" />' . $desc;
						break;
						// repeatable
						case 'user_repeatable':
							echo '<a class="meta_box_repeatable_add button button-primary button-large" href="#">Add More</a>
									<ul id="' . $field['id'] . '-repeatable" class="meta_box_repeatable">';
							$i = 0;
							if ( $meta ) {
								foreach( $meta as $row ) {
									$user_info = get_userdata($row);
									echo '<li><span class="sort handle dashicons dashicons-sort"></span>
												<input type="text" name="' . $field['id'] . '[]" id="' . $field['id'] . $i .'" value="' . esc_attr( $row ) . '" size="30" /><span>'. $user_info->user_login.'</span>
												<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';
									$i++;
								}
							} 
								echo '<li class="hide"><span class="sort handle dashicons dashicons-sort"></span>
											<input type="text" rel-name="' . $field['id'] . '[]" id="' . $field['id'] .$i .'" value="" size="30" />
											<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';
							
							echo '</ul>
								<span class="description">' . $field['desc'] . '</span>';
						break;

						case 'repeatable':
							echo '<a class="meta_box_repeatable_add button button-primary button-large" href="#">Add More</a>
									<ul id="' . $field['id'] . '-repeatable" class="meta_box_repeatable">';
	
							if ( $meta ) {
								foreach( $meta as $row ) {
									echo '<li><span class="sort handle dashicons dashicons-sort"></span>
												<input type="text" name="' . $field['id'] . '[]" id="' . $field['id'] . '" value="' . esc_attr( $row ) . '" size="30" />
												<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';
								}
							} 
								echo '<li class="hide"><span class="sort handle dashicons dashicons-sort"></span>
											<input type="text" rel-name="' . $field['id'] . '[]" id="' . $field['id'] . '" value="" size="30" />
											<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';
							
							echo '</ul>
								<span class="description">' . $field['desc'] . '</span>';
						break;
						case 'repeatable_count':
							echo '<a class="meta_box_repeatable_add button button-primary button-large" href="#">Add More</a>
									<ul id="' . $field['id'] . '-repeatable" class="meta_box_repeatable">';
							$i=1;
							
								echo '<li class="hide"><span class="sort handle dashicons dashicons-sort"></span><span class="count">'.$i.'</span>
											<input type="text" rel-name="' . $field['id'] . '[]" id="' . $field['id'] . '" value="" size="30" />
											<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';
							
							if ( $meta ) {
								foreach( $meta as $row ) {
									echo '<li><span class="sort handle dashicons dashicons-sort"></span><span class="count">'.$i.'</span>
												<input type="text" name="' . $field['id'] . '[]" id="' . $field['id'] . '" value="' . esc_attr( $row ) . '" size="30" />
												<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';
								$i++;}
							} 
							echo '</ul>
								<span class="description">' . $field['desc'] . '</span>';
						break;
						// repeatable
						case 'repeatable_select':
							echo '<a class="meta_box_repeatable_add button button-primary button-large" href="#">Add More</a>
									<ul id="' . $field['id'] . '-repeatable" class="meta_box_repeatable">';
							$i = 0;
							if ( $meta ) {

								foreach( $meta as $row ) {

									echo '<li><span class="sort handle dashicons dashicons-sort"></span>
											  <select name="' . $field['id'] . '[' . $i . ']" id="' . $field['id'] . '">';
											  foreach ( $options as $option )
													echo '<option value="' . $option['value'] . '" '.selected($option['value'],esc_attr( $row )).'>' . $option['label'] . '</option>';

											  echo '</select>
												<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a>
												</li>';
									$i++;
								}
							} 
								echo '<li  class="hide"><span class="sort handle dashicons dashicons-sort"></span>
											<select name="' . $field['id'] . '[' . $i . ']" id="' . $field['id'] . '">';
											  foreach ( $options as $option )
													echo '<option value="' . $option['value'] . '">' . $option['label'] . '</option>';

											  echo '</select>
											<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';
							
							echo '</ul>
								<span class="description">' . $field['desc'] . '</span>';
						break;
						case 'repeatable_selectcpt':
							echo '<a class="meta_box_repeatable_add button button-primary button-large" href="#">Add More</a>
									<ul id="' . $field['id'] . '-repeatable" class="meta_box_repeatable">';
							$total_marks=0;
							if ( $meta ) {
								
								$quess = $meta['ques'];
								$marks = $meta['marks'];
								
								foreach( $quess as $i=>$ques ) {
									if(!isset($marks[$i]) || !$marks[$i]) $marks[$i]=0;
									$total_marks = $total_marks+intval(esc_attr( $marks[$i] ));
									echo '<li><span class="sort handle dashicons dashicons-sort"></span>
											  <select name="' . $field['id'] . '[ques][]" class="chzn-select">';

											   $args= array(
					                            	'post_type'=>$post_type,
					                            	'numberposts'=> 999
					                            	);
					                            $posts=get_posts($args);
					                            echo '<option value="">None</option>';
												foreach ( $posts as $post ){
													echo '<option' . selected( esc_attr( $ques ), $post->ID, false ) . ' value="' . $post->ID . '">' . $post->post_title . '</option>';
												}
												wp_reset_postdata();
											  echo '</select>
											  <input type="number" name="' . $field['id'] . '[marks][]" placeholder="Marks for a Correct answer" value="'.esc_attr( $marks[$i] ).'"/>
												<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a>
												</li>';
								}
							} 
								echo '<li class="hide"><span class="sort handle dashicons dashicons-sort"></span>
											<select rel-name="' . $field['id'] . '[ques][]" class="chzn-select">';

											   $args= array(
					                            	'post_type'=>$post_type,
					                            	'numberposts'=> 999
					                            	);
					                            $posts=get_posts($args);
					                            echo '<option value="">None</option>';
												foreach ( $posts as $post ){
													echo '<option value="' . $post->ID . '">' . $post->post_title . '</option>';
												}
												wp_reset_postdata();
											  echo '</select>
											 <input type="number" rel-name="' . $field['id'] . '[marks][]" placeholder="Marks for a Correct answer" value="0" /> 
											<a class="meta_box_repeatable_remove" href="#"><span class="dashicons dashicons-no"></span></a></li>';
							
							echo '</ul>
							<strong>Total marks for the Quiz : <span id="total_quiz_marks"> '.$total_marks.'</span></strong><br />
								<span class="description">' . $field['desc'] . '</span>';
						break;
						case 'payments':
						echo '<ul id="instructor_payments"><li><strong>Instructor</strong><span>Email</span><span>Commission</span></li>';

						foreach($meta as $key=>$row){
							if(isset($row['set']) && $row['set'])
								echo '<li><strong>'.get_the_author_meta('display_name',$key).'</strong><span>'.$row['email'].'</span><span>'.$row['commission'].'</span></li>';
						}
						echo '</ul>';
						break;
                        case 'gallery':
                                                    global $post;
                                                    ?>
                                                <div id="vibe_gallery_container">
                        <ul class="vibe_gallery">
			<?php
                                if(!$meta || $meta == 'Array') $meta = '';
                                if($meta){
				$attachments = array_filter( explode( ',', $meta ) );
                                
                                
				if ( is_array($attachments ) && $attachments)
					foreach ( $attachments as $attachment_id ) {
						echo '<li class="slider_image" data-attachment_id="' . $attachment_id . '">
							' . wp_get_attachment_image( $attachment_id, 'full' ) . '
							<ul class="actions">
								<li><a href="#" class="delete" title="' . __( 'Delete image', 'vibe' ) . '">' . __( 'Delete', 'vibe' ) . '</a></li>
							</ul>
						</li>';
					}
                                }
			?>
		</ul>
            <?php
                echo '<input type="hidden" name="' . $id . '" id="' . $id . '" value="' . esc_attr( $meta ) . '" />';
              ?>
		

	</div>
	<p class="add_gallery hide-if-no-js">
		<a href="#" class="button-primary"><?php _e( 'Add Gallery images', 'vibe' ); ?></a>
	</p>
	<script type="text/javascript">
		jQuery(document).ready(function($){

			// Uploading files
			var media_frame;
			var $image_gallery_ids = $('#<?php echo $id;?>');
			var $media = $('#vibe_gallery_container ul.vibe_gallery');

			jQuery('.add_gallery').on( 'click', 'a', function( event ) {

				var $el = $(this);
				var attachment_ids = $image_gallery_ids.val();

				event.preventDefault();

				// If the media frame already exists, reopen it.
				if ( media_frame ) {
					media_frame.open();
					return;
				}

				// Create the media frame.
				media_frame = wp.media.frames.downloadable_file = wp.media({
					// Set the title of the modal.
					title: '<?php _e( 'Add Images to Gallery', 'vibe' ); ?>',
					button: {
						text: '<?php _e( 'Add to Gallery', 'vibe' ); ?>',
					},
					multiple: true
				});

				// When an image is selected, run a callback.
				media_frame.on( 'select', function() {

					var selection = media_frame.state().get('selection');

					selection.map( function( attachment ) {

						attachment = attachment.toJSON();

						if ( attachment.id ) {
							attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;

							$media.append('\
								<li class="slider_image" data-attachment_id="' + attachment.id + '">\
									<img src="' + attachment.url + '" />\
									<ul class="actions">\
										<li><a href="#" class="delete" title="<?php _e( 'Delete', 'vibe' ); ?>"><?php _e( 'Delete', 'vibe' ); ?></a></li>\
									</ul>\
								</li>');
						}

					} );

					$image_gallery_ids.val( attachment_ids );
				});

				// Finally, open the modal.
				media_frame.open();
			});

			// Image ordering
			$media.sortable({
				items: 'li.slider_image',
				cursor: 'move',
				scrollSensitivity:40,
				forcePlaceholderSize: true,
				forceHelperSize: false,
				helper: 'clone',
				opacity: 0.65,
				placeholder: 'wc-metabox-sortable-placeholder',
				start:function(event,ui){
					ui.item.css('background-color','#f6f6f6');
				},
				stop:function(event,ui){
					ui.item.removeAttr('style');
				},
				update: function(event, ui) {
					var attachment_ids = '';

					$('#vibe_media_container ul li.image').css('cursor','default').each(function() {
						var attachment_id = jQuery(this).attr( 'data-attachment_id' );
						attachment_ids = attachment_ids + attachment_id + ',';
					});

					$image_gallery_ids.val( attachment_ids );
				}
			});

			// Remove images
			$('#vibe_gallery_container').on( 'click', 'a.delete', function() {

				$(this).closest('li.slider_image').remove();

				var attachment_ids = '';

				$('#vibe_gallery_container ul li.slider_image').css('cursor','default').each(function() {
					var attachment_id = jQuery(this).attr( 'data-attachment_id' );
					attachment_ids = attachment_ids + attachment_id + ',';
				});

				$image_gallery_ids.val( attachment_ids );

				return false;
			} );

		});
	</script>
	<?php
						break;
                                                 case 'audio':
                                                    global $post;
                                                    ?>
                                                <div id="vibe_audio_container">
                        <ul class="vibe_audio">
			<?php
                                if(!$meta || $meta == 'Array') $meta = '';
                                if($meta){
				$attachments = array_filter( explode( ',', $meta ) );
                                
                                
				if ( is_array($attachments ) && $attachments)
					foreach ( $attachments as $attachment_id ) {
						echo '<li class="audio_file" data-attachment_id="' . $attachment_id . '">
							' . wp_get_attachment_image( $attachment_id, 'full' ) . '
							<ul class="actions">
								<li><a href="#" class="delete" title="' . __( 'Delete audio file', 'vibe' ) . '">' . __( 'Delete', 'vibe' ) . '</a></li>
							</ul>
						</li>';
					}
                                }
			?>
		</ul>
            <?php
                echo '<input type="hidden" name="' . $id . '" id="' . $id . '" value="' . esc_attr( $meta ) . '" />';
              ?>
		

	</div>
	<p class="add_audio hide-if-no-js">
		<a href="#" class="button-primary"><?php _e( 'Add Audio Files', 'vibe' ); ?></a>
	</p>
	<script type="text/javascript">
		jQuery(document).ready(function($){

			// Uploading files
			var media_frame;
			var $image_gallery_ids = $('#<?php echo $id;?>');
			var $media = $('#vibe_audio_container ul.vibe_audio');

			jQuery('.add_audio').on( 'click', 'a', function( event ) {

				var $el = $(this);
				var attachment_ids = $image_gallery_ids.val();

				event.preventDefault();

				// If the media frame already exists, reopen it.
				if ( media_frame ) {
					media_frame.open();
					return;
				}

				// Create the media frame.
				media_frame = wp.media.frames.downloadable_file = wp.media({
					// Set the title of the modal.
					title: '<?php _e( 'Add Audio', 'vibe' ); ?>',
					button: {
						text: '<?php _e( 'Add Audio', 'vibe' ); ?>',
					},
					multiple: true
				});

				// When an image is selected, run a callback.
				media_frame.on( 'select', function() {

					var selection = media_frame.state().get('selection');

					selection.map( function( attachment ) {

						attachment = attachment.toJSON();

						if ( attachment.id ) {
							attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;

							$media.append('\
								<li class="audio_file" data-attachment_id="' + attachment.id + '">\
									<img src="' + attachment.url + '" />\
									<ul class="actions">\
										<li><a href="#" class="delete" title="<?php _e( 'Delete', 'vibe' ); ?>"><?php _e( 'Delete', 'vibe' ); ?></a></li>\
									</ul>\
								</li>');
						}

					} );

					$image_gallery_ids.val( attachment_ids );
				});

				// Finally, open the modal.
				media_frame.open();
			});

			// Image ordering
			$media.sortable({
				items: 'li.audio_file',
				cursor: 'move',
				scrollSensitivity:40,
				forcePlaceholderSize: true,
				forceHelperSize: false,
				helper: 'clone',
				opacity: 0.65,
				placeholder: 'wc-metabox-sortable-placeholder',
				start:function(event,ui){
					ui.item.css('background-color','#f6f6f6');
				},
				stop:function(event,ui){
					ui.item.removeAttr('style');
				},
				update: function(event, ui) {
					var attachment_ids = '';

					$('#vibe_audio_container ul li.audio_file').css('cursor','default').each(function() {
						var attachment_id = jQuery(this).attr( 'data-attachment_id' );
						attachment_ids = attachment_ids + attachment_id + ',';
					});

					$image_gallery_ids.val( attachment_ids );
				}
			});

			// Remove images
			$('#vibe_audio_container').on( 'click', 'a.delete', function() {

				$(this).closest('li.audio_file').remove();

				var attachment_ids = '';

				$('#vibe_audio_container ul li.audio_file').css('cursor','default').each(function() {
					var attachment_id = jQuery(this).attr( 'data-attachment_id' );
					attachment_ids = attachment_ids + attachment_id + ',';
				});

				$image_gallery_ids.val( attachment_ids );

				return false;
			} );

		});
	</script>
	<?php
						break;
                                                 case 'video':
                                                    global $post;
                                                    ?>
                                                <div id="vibe_media_container">
                        <ul class="vibe_media">
			<?php
                                if(!$meta || $meta == 'Array') $meta = '';
                                if($meta){
				$attachments = array_filter( explode( ',', $meta ) );
                                
                                
				if ( is_array($attachments ) && $attachments)
					foreach ( $attachments as $attachment_id ) {
						echo '<li class="slider_image" data-attachment_id="' . $attachment_id . '">
							' . wp_get_attachment_image( $attachment_id, 'full' ) . '
							<ul class="actions">
								<li><a href="#" class="delete" title="' . __( 'Delete video file', 'vibe' ) . '">' . __( 'Delete', 'vibe' ) . '</a></li>
							</ul>
						</li>';
					}
                                }
			?>
		</ul>
            <?php
                echo '<input type="hidden" name="' . $id . '" id="' . $id . '" value="' . esc_attr( $meta ) . '" />';
              ?>
		

	</div>
	<p class="add_video hide-if-no-js">
		<a href="#" class="button-primary"><?php _e( 'Add Video Files', 'vibe' ); ?></a>
	</p>
	<script type="text/javascript">
		jQuery(document).ready(function($){

			// Uploading files
			var media_frame;
			var $image_gallery_ids = $('#<?php echo $id;?>');
			var $media = $('#vibe_media_container ul.vibe_media');

			jQuery('.add_video').on( 'click', 'a', function( event ) {

				var $el = $(this);
				var attachment_ids = $image_gallery_ids.val();

				event.preventDefault();

				// If the media frame already exists, reopen it.
				if ( media_frame ) {
					media_frame.open();
					return;
				}

				// Create the media frame.
				media_frame = wp.media.frames.downloadable_file = wp.media({
					// Set the title of the modal.
					title: '<?php _e( 'Add Video Files', 'vibe' ); ?>',
					button: {
						text: '<?php _e( 'Add Video', 'vibe' ); ?>',
					},
					multiple: true
				});

				// When an image is selected, run a callback.
				media_frame.on( 'select', function() {

					var selection = media_frame.state().get('selection');

					selection.map( function( attachment ) {

						attachment = attachment.toJSON();

						if ( attachment.id ) {
							attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;

							$media.append('\
								<li class="slider_image" data-attachment_id="' + attachment.id + '">\
									<img src="' + attachment.url + '" />\
									<ul class="actions">\
										<li><a href="#" class="delete" title="<?php _e( 'Delete', 'vibe' ); ?>"><?php _e( 'Delete', 'vibe' ); ?></a></li>\
									</ul>\
								</li>');
						}

					} );

					$image_gallery_ids.val( attachment_ids );
				});

				// Finally, open the modal.
				media_frame.open();
			});

			// Image ordering
			$media.sortable({
				items: 'li.slider_image',
				cursor: 'move',
				scrollSensitivity:40,
				forcePlaceholderSize: true,
				forceHelperSize: false,
				helper: 'clone',
				opacity: 0.65,
				placeholder: 'wc-metabox-sortable-placeholder',
				start:function(event,ui){
					ui.item.css('background-color','#f6f6f6');
				},
				stop:function(event,ui){
					ui.item.removeAttr('style');
				},
				update: function(event, ui) {
					var attachment_ids = '';

					$('#vibe_media_container ul li.image').css('cursor','default').each(function() {
						var attachment_id = jQuery(this).attr( 'data-attachment_id' );
						attachment_ids = attachment_ids + attachment_id + ',';
					});

					$image_gallery_ids.val( attachment_ids );
				}
			});

			// Remove images
			$('#vibe_media_container').on( 'click', 'a.delete', function() {

				$(this).closest('li.slider_image').remove();

				var attachment_ids = '';

				$('#vibe_media_container ul li.slider_image').css('cursor','default').each(function() {
					var attachment_id = jQuery(this).attr( 'data-attachment_id' );
					attachment_ids = attachment_ids + attachment_id + ',';
				});

				$image_gallery_ids.val( attachment_ids );

				return false;
			} );

		});
	</script>
	<?php
						break;

						case 'live_preview':
						echo get_the_ID().'###';
							
						break;
					} //end switch
			echo '</td></tr>';
		} // end foreach
		echo '</table>'; // end table
	}
	
	// Save the Data
	function save_box( $post_id ) {
		global $post, $post_type;
		
		// verify nonce
		if ( ! ( in_array($post_type, $this->page) && @wp_verify_nonce( $_POST[$post_type . '_meta_box_nonce'],  basename( __FILE__ ) ) ) )
			return $post_id;
		// check autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;
		// check permissions
		if ( !current_user_can( 'edit_page', $post_id ) )
			return $post_id;
		
		// loop through fields and save the data
		foreach ( $this->fields as $field ) {
			if( $field['type'] == 'tax_select' ) {
				// save taxonomies
				if ( isset( $_POST[$field['id']] ) )
					$term = $_POST[$field['id']];
				wp_set_object_terms( $post_id, $term, $field['id'] );
			}
			else {
                            
                              
                                
				 if(isset($field['id'])){
				
				// save the rest
                                //print_r($_POST[$field['id']]);
                            
                           
                               $old = get_post_meta( $post_id, $field['id'], true );
                            
                                $old='';
                                $new=$old;
                            
                                
                                
				if ( isset( $_POST[$field['id']] ) )
					$new = $_POST[$field['id']];
				
                                
				if($field['type'] == 'checkbox' || $field['type'] == 'available' || $field['type'] == 'featured') { 
					if ( !isset( $_POST[$field['id']] ) ){
                                            	$new = 0;
                                        }
					
				}	
				if($field['type'] == 'gmap') { 
					if ( isset($_POST[$field['id']]) && is_array( $_POST[$field['id']])){
                                            if(isset($_POST[$field['id']]['city']))
						update_post_meta($post_id,'vibe_gmap_city',$_POST[$field['id']]['city']);
                                            if(isset($_POST[$field['id']]['state']))
                                                update_post_meta($post_id,'vibe_gmap_state',$_POST[$field['id']]['state']);
                                            if(isset($_POST[$field['id']]['pincode']))
                                                update_post_meta($post_id,'vibe_gmap_pincode',$_POST[$field['id']]['pincode']);
                                            if(isset($_POST[$field['id']]['country'])) 
                                                update_post_meta($post_id,'vibe_gmap_country',$_POST[$field['id']]['country']);
                                        }
				}
				if($field['type'] == 'image') { 
					if ( !isset( $_POST[$field['id']] ) || !$_POST[$field['id']]){
						$new = ' ';
                                        }
				}
				if($field['type'] == 'textarea' || $field['type'] == 'editor') { 
					if ( !isset( $_POST[$field['id']] ) || !$_POST[$field['id']])
						$new = ' ';
				}
				/*if( $field['type'] == 'sliders' ) {
					$disable = get_post_meta( $post_id, 'vibe_disable_featured', true );
					if((isset($disable) && $disable =='disable') || (!isset($new[0]['image']) || $new[0]['image'] == '')){
						$new=$old;
					}
				}*/
				
				if ( $new && $new != $old ) {
					if ( is_array( $new ) ) {
						foreach ( $new as &$item ) {
                                                            if(is_array($item)){
                                                                foreach ( $item as &$item2 ) {
                                                                	if($field['type'] == 'editor')
                                                                		$item2 =  $item2 ;
                                                                	else
                                                                    	$item2 = esc_attr( $item2 );
                                                            }
                                                            unset($item2);
                                                            }
                                                            else{
                                                              if($field['type'] == 'editor')
                                                                		$item =  $item ;
                                                                else		
                                                              		$item = esc_attr( $item );  
                                                            }
						}
						unset( $item );
					} else {
						if($field['type'] != 'editor')
							$new = esc_attr( $new );
					}
                                        
					update_post_meta( $post_id, $field['id'], $new );
				} elseif ( !isset($new) && $old ) {
					delete_post_meta( $post_id, $field['id'], $old );
				}elseif(!$new){
                                    update_post_meta( $post_id, $field['id'], $new );
                                }
                            }
			}
		} // end foreach
	}
}


?>