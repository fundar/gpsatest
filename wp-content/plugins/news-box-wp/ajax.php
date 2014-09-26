<?php

////////////////////////////////////////////////
////// ADD NEW BOX /////////////////////////////
////////////////////////////////////////////////

function nb_add_box() {
	if(!isset($_POST['lcwp_nonce']) || !wp_verify_nonce($_POST['lcwp_nonce'], 'lcwp_nonce')) {die('Cheating?');};
	if(!isset($_POST['nb_name'])) {die('data is missing');}
	$name = $_POST['nb_name'];
	
	// default checkbox values
	$default = serialize(array(
		'src'=>array(), 
		'settings'=>array(
			'expandable_news' => 1,
			'scroll_exp_elem' => 1,
			'manage_exp_images' => 1,
		)
	));
	$resp = wp_insert_term($name, 'nb_boxes', array('description'=>$default));
	
	if(is_array($resp)) {die('success');}
	else {
		$err_mes = $resp->errors['term_exists'][0];
		die($err_mes);
	}
}
add_action('wp_ajax_nb_add_box', 'nb_add_box');



////////////////////////////////////////////////
////// LOAD OVERLAYS LIST //////////////////////
////////////////////////////////////////////////

function nb_boxes_list() {
	if(!isset($_POST['lcwp_nonce']) || !wp_verify_nonce($_POST['lcwp_nonce'], 'lcwp_nonce')) {die('Cheating?');};
	//if(!isset($_POST['nb_page']) || !filter_var($_POST['nb_page'], FILTER_VALIDATE_INT)) {$pag = 1;}
	
	$pag = 1;
	$per_page = 100;
	
	// search
	$search = (isset($_POST['box_src'])) ? $_POST['box_src']: '';
	$src_string = (!empty($search)) ? '&search='.$search : '';	
	
	// get all terms 
	$grids = get_terms('nb_boxes', 'hide_empty=0'.$src_string);
	$total = count($grids);
	
	$tot_pag = ceil( $total / $per_page );
	
	
	if($pag > $tot_pag) {$pag = $tot_pag;}
	$offset = ($pag - 1) * $per_page;
	
	// get page terms
	$args =  array(
		'number' => $per_page,
		'offset' => $offset,
		'hide_empty' => 0
	);
	if($src_string != '') {
		$args['search'] = $search;	
	}
	$items = get_terms('nb_boxes', $args);

	// clean term array
	$clean_elems = array();
	
	foreach ( $items as $item ) {
		$clean_elems[] = array('id' => $item->term_id, 'name' => $item->name);
	}
	
	$to_return = array(
		'elems' => $clean_elems,
		'pag' => $pag, 
		'tot_pag' => $tot_pag
	);
    
	echo json_encode($to_return);
	die();
}
add_action('wp_ajax_nb_boxes_list', 'nb_boxes_list');



////////////////////////////////////////////////
////// DELETE BOX //////////////////////////////
////////////////////////////////////////////////

function nb_del_box() {
	if(!isset($_POST['box_id'])) {die('data is missing');}
	$id = addslashes($_POST['box_id']);
	
	$resp = wp_delete_term($id, 'nb_boxes');

	if($resp == '1') {die('success');}
	else {die('error during box deletion');}
}
add_action('wp_ajax_nb_del_box', 'nb_del_box');



////////////////////////////////////////////////
////// DISPLAY BOX BUILDER /////////////////////
////////////////////////////////////////////////

function nl_box_builder() {
	require_once(NB_DIR . '/functions.php');
	
	if(!isset($_POST['box_id'])) {die('data is missing');}
	$box_id = addslashes($_POST['box_id']);

	// get term and unserialize contents
	$box = get_term($box_id, 'nb_boxes');
	$data = (empty($box->description)) ? '' : unserialize($box->description);
	if(!is_array($data)) {$data = array('src'=>array(), 'settings'=>array());}
	?>
    <form class="form-wrap" id="nb_box_opts">  
		<div class="postbox">
          <h3 class="hndle"><?php _e('News Sources', 'nb_ml') ?></h3>
          <div class="inside">
        
            <div class="lcwp_mainbox_meta">
				<div class="nb_add_src">
                	<?php _e('Add new source', 'nb_ml') ?> 
                    <select class="lcweb-chosen" data-placeholder="Select a source" tabindex="2">
                    	<?php 
						foreach(nb_news_sources() as $k => $v) {
							echo '<option value="'.$k.'">'.$v.'</option>';	
						}
						?>
                    </select>
                    <input type="button" class="button-secondary" value="<?php _e('Add', 'nb_ml') ?>" /><span></span>
                </div>
                <div class="nb_sources">
                	<?php 
					// saved sources
					foreach($data['src'] as $src) {
						$type = $src['src_type'];
						echo '
						<div class="nb_type_block nb_'.$type.'_src">
						<h4>
							'. nb_news_sources($type) .' 
							<div class="nb_cmd"><span class="lcwp_del_row"></span></div>
						</h4>';
					
						echo nb_src_form_block($src) . '<div class="mgom_btm_border_fix"></div></div>';
					}
					?>
                </div>
            </div>  
          </div>
        </div>
        
        <br/>
        
        <div class="postbox" id="nb_box_settings">
          <h3 class="hndle"><?php _e('Settings', 'nb_ml') ?></h3>
          <div class="inside">
            <div class="lcwp_mainbox_meta">
            	
                <div class="nb_type_block">
                    <h4><?php _e('Initial mode', 'nb_ml') ?></h4>
                    
                    <?php
					$fields = array('max_news', 'news_per_time', 'height', 'layout', 'boxed_news', 'buttons_position', 'btn_over_img', 'hide_elements', 'show_src_logo', 'horiz_img_h', 'vert_img_w', 'title_behavior', 'img_behavior', 'date_format', 'elapsed_time', 'read_more_btn', 'read_more_btn_txt');
                    foreach($fields as $field) {
                        $val = (isset($data['settings'][$field])) ? $data['settings'][$field] : ''; 
                        echo nb_fields_builder($field, $val);
                    }
                    ?>
                
                	<div class="mgom_btm_border_fix"></div>
            	</div>
                
                <div class="nb_type_block">
                    <h4><?php _e('Expanded mode', 'nb_ml') ?></h4>
                    
                    <?php
					//$fields = array('expandable_news', 'scroll_exp_elem', 'exp_main_img_pos', 'manage_exp_images', 'exp_img_w', 'exp_img_h');
					$fields = array('expandable_news', 'scroll_exp_elem', 'manage_exp_images');
                    foreach($fields as $field) {
                        $val = (isset($data['settings'][$field])) ? $data['settings'][$field] : ''; 
                        echo nb_fields_builder($field, $val);
                    }
                    ?>
                
                	<div class="mgom_btm_border_fix"></div>
            	</div>
                
                <div class="nb_type_block">
                    <h4><?php _e('Navigation / slideshow', 'nb_ml') ?></h4>
                    
                    <?php
					$fields = array('nav_arrows', 'carousel', 'animation_time', 'autoplay', 'slideshow_time');
                    foreach($fields as $field) {
                        $val = (isset($data['settings'][$field])) ? $data['settings'][$field] : ''; 
                        echo nb_fields_builder($field, $val);
                    }
                    ?>
                
                	<div class="mgom_btm_border_fix"></div>
            	</div>
                
            </div>  
          </div>
        </div>
    </form>    
	<?php
	die();
}
add_action('wp_ajax_nl_box_builder', 'nl_box_builder');



////////////////////////////////////////////////
////// ADD NEWS SOURCE /////////////////////////
////////////////////////////////////////////////

function nb_add_src() {
	require_once(NB_DIR . '/functions.php');
	
	if(!isset($_POST['nb_type'])) {die('data is missing');}
	$type = addslashes($_POST['nb_type']);
	
	echo '
	<div class="nb_type_block nb_'.$type.'_src">
	<h4>
		'. nb_news_sources($type) .' 
		<div class="nb_cmd"><span class="lcwp_del_row"></span></div>
	</h4>';

	echo nb_src_form_block(array('src_type' => $type)) . '<div class="mgom_btm_border_fix"></div></div>';
	die();
}
add_action('wp_ajax_nb_add_src', 'nb_add_src');



////////////////////////////////////////////////
////// SAVE BOX ////////////////////////////////
////////////////////////////////////////////////

function nb_save_box() {
	include_once(NB_DIR . '/functions.php');
	include_once(NB_DIR . '/classes/simple_form_validator.php');		
		
		$validator = new simple_fv;
		$indexes = array();
		
		// sources
		$indexes[] = array('index'=>'box_id', 'label'=>__('Box ID', 'nb_ml'), 'required'=>true, 'type'=>'int');
		$indexes[] = array('index'=>'max_news', 'label'=>__('News to keep', 'nb_ml'), 'required'=>true, 'type'=>'int', 'min_val'=>1, 'max_val'=>20);
		$indexes[] = array('index'=>'news_per_time', 'label'=>__('News per time', 'nb_ml'), 'required'=>true, 'type'=>'int', 'min_val'=>1, 'max_val'=>15);
		$indexes[] = array('index'=>'height', 'label'=>__('Box height', 'nb_ml'), 'required'=>true, 'type'=>'int');
		$indexes[] = array('index'=>'layout', 'label'=>'Box main layout');
		$indexes[] = array('index'=>'boxed_news', 'label'=>"Boxed style?");
		$indexes[] = array('index'=>'buttons_position', 'label'=>"Buttons position");
		$indexes[] = array('index'=>'hide_elements', 'label'=>"Global elements to hide");
		$indexes[] = array('index'=>'btn_over_img', 'label'=>"Side buttons over image?");
		$indexes[] = array('index'=>'show_src_logo', 'label'=>"Show news source logo?");
		$indexes[] = array('index'=>'horiz_img_h', 'label'=>__("Image's height <small>(for horizontal layout)</small>", 'nb_ml'), 'type'=>'int');
		$indexes[] = array('index'=>'vert_img_w', 'label'=>__("Image's width <small>(for vertical layout)</small>", 'nb_ml'), 'type'=>'int');
		$indexes[] = array('index'=>'title_behavior', 'label'=>"News title behavior");
		$indexes[] = array('index'=>'img_behavior', 'label'=>"Main image behavior");
		$indexes[] = array('index'=>'date_format', 'label'=>__('Date format', 'nb_ml'), 'required'=>true);
		$indexes[] = array('index'=>'elapsed_time', 'label'=>"Use elapsed time?");
		$indexes[] = array('index'=>'read_more_btn', 'label'=>'Replace date with "read more" button?');
		$indexes[] = array('index'=>'read_more_btn_txt', 'label'=>__('"Read more"', 'nb_ml').' '. __("button's text", 'nb_ml'));
		
		$indexes[] = array('index'=>'expandable_news', 'label'=>"Expandable news?");
		$indexes[] = array('index'=>'scroll_exp_elem', 'label'=>"Keep close button and side image visible on scroll?");
		//$indexes[] = array('index'=>'exp_main_img_pos', 'label'=>"Main image position");
		$indexes[] = array('index'=>'manage_exp_images', 'label'=>"Manage news images?");
		//$indexes[] = array('index'=>'exp_img_w', 'label'=>"Image's container width");
		//$indexes[] = array('index'=>'height', 'label'=>__("Image's container width", 'nb_ml'), 'required'=>true);
		
		$indexes[] = array('index'=>'nav_arrows', 'label'=>"Navigation arrows position");
		$indexes[] = array('index'=>'carousel', 'label'=>'Carousel mode?');
		$indexes[] = array('index'=>'animation_time', 'label'=>__("Animation time", 'nb_ml'), 'required'=>true, 'type'=>'int');
		$indexes[] = array('index'=>'autoplay', 'label'=>'Autoplay slideshow?');
		$indexes[] = array('index'=>'slideshow_time', 'label'=>__("Slideshow interval", 'nb_ml'), 'required'=>true, 'type'=>'int');
		
		$validator->formHandle($indexes);
		$fdata = $validator->form_val;
		$error = $validator->getErrors();
		
		if($error) {die('<div class="error"><p>'.$error.'</p></div>');}
		else {
			// sources
			$sources = array();
			$sources_data = json_decode(stripslashes($_POST['src_data']));
			if(!is_array($sources_data) || count($sources_data) == 0) {_e('A news source is needed', 'nb_ml'); die();}
			
			foreach($sources_data as $src) {
				$sources[] = nb_serArr_to_php($src);	
			}
			
			// clean settings
			foreach($fdata as $key=>$val) {
				if(!is_array($val)) {
					$fdata[$key] = stripslashes($val);
				} else {
					$fdata[$key] = array();
					foreach($val as $arr_val) {$fdata[$key][] = stripslashes($arr_val);}
				}
			}

			// wrap up elements 
			$box_data = array(
				'src' 		=> $sources,
				'settings' 	=> $fdata 
			); 
			
			// save
			$result = wp_update_term($fdata['box_id'], 'nb_boxes', array(
			  'description' => serialize($box_data)
			));
			echo (is_wp_error($result)) ? $result->get_error_message() : 'success';	
		}
	die();
}
add_action('wp_ajax_nb_save_box', 'nb_save_box');