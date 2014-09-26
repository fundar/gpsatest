<?php 
include_once(NB_DIR . '/functions.php');
?>

<div class="wrap lcwp_form">  
	<div class="icon32"><img src="<?php echo NB_URL.'/img/nb_logo.png'; ?>" alt="newsbox" /><br/></div>
    <?php echo '<h2 class="lcwp_page_title" style="border: none;">News Box ' . __('Settings', 'nb_ml') . "</h2>"; ?>  

    <?php
	// HANDLE DATA
	if(isset($_POST['lcwp_admin_submit'])) { 
		if (!isset($_POST['lcwp_nonce']) || !wp_verify_nonce($_POST['lcwp_nonce'], 'lcwp')) {die('<p>Cheating?</p>');};
		include_once(NB_DIR . '/classes/simple_form_validator.php');		
		
		$validator = new simple_fv;
		$indexes = array();
		
		$indexes[] = array('index'=>'nb_min_news_w', 'label'=>__("Minimum news width (for horizontal layout)", 'nb_ml'), 'type'=>'int');
		$indexes[] = array('index'=>'nb_min_news_h', 'label'=>__("Minimum news height (for vertical layout)", 'nb_ml'), 'type'=>'int');
		$indexes[] = array('index'=>'nb_min_horiz_w', 'label'=>__("Minimum horizontal News Box width", 'nb_ml'), 'type'=>'int');
		$indexes[] = array('index'=>'nb_read_more_txt', 'label'=>'Shortened text suffix');
		$indexes[] = array('index'=>'nb_date_format', 'label'=>'Default date format');
		$indexes[] = array('index'=>'nb_read_more_txt', 'label'=>'Shortened text suffix');
		$indexes[] = array('index'=>'nb_lightbox', 'label'=>'Lightbox integration');
		$indexes[] = array('index'=>'nb_touchswipe', 'label'=>'TouchSwipe integration');
		
		$indexes[] = array('index'=>'nb_img_check', 'label'=>'Check images?');
		$indexes[] = array('index'=>'nb_max_img_w', 'label'=>__( 'Images max width', 'nb_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'nb_max_img_h', 'label'=>__( 'Images max height', 'nb_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'nb_preview_pag', 'label'=>'Preview page');
		$indexes[] = array('index'=>'nb_js_head', 'label'=>'Javascript in Header');	
		
		$indexes[] = array('index'=>'nb_main_style', 'label'=>'Main style');
		$indexes[] = array('index'=>'nb_custom_style', 'label'=>'Use custom style');
		$indexes[] = array('index'=>'nb_force_inline_css', 'label'=>'Force inline css usage');
		
		$indexes[] = array('index'=>'nb_img_margin', 'label'=>__("Image's margin", 'nb_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'nb_box_margin', 'label'=>__("News margin", 'nb_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'nb_border_w', 'label'=>__("Border width", 'nb_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'nb_border_radius', 'label'=>__("Border radius", 'nb_ml' ), 'type'=>'int');
		$indexes[] = array('index'=>'nb_use_shadows', 'label'=>'Use shadows?');
		$indexes[] = array('index'=>'nb_exp_img_padding', 'label'=>__("Image's padding in expanded mode", 'nb_ml' ), 'type'=>'int');

		$indexes[] = array('index'=>'nb_bg_color', 'label'=>__("Background", 'nb_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'nb_title_color', 'label'=>__("Titles color", 'nb_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'nb_txt_color', 'label'=>__("Text color", 'nb_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'nb_link_color', 'label'=>__("Links color", 'nb_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'nb_border_color', 'label'=>__("Border color - default state", 'nb_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'nb_border_color_h', 'label'=>__("Border color - hover state", 'nb_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'nb_btn_color', 'label'=>__("Buttons color", 'nb_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'nb_sep_color', 'label'=>__("Separator color", 'nb_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'nb_loader_style', 'label'=>'Loader style');
		$indexes[] = array('index'=>'nb_ol_icon_color', 'label'=>__("Overlay icon", 'nb_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'nb_ol_bg_color', 'label'=>__("Overlay background", 'nb_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'nb_date_bg', 'label'=>__("Date/'Read More' box - background", 'nb_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'nb_date_bg_h', 'label'=>__("'Read More' box - background on hover", 'nb_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'nb_date_txt_col_h', 'label'=>__("'Read More' box - text color on hover", 'nb_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'nb_exp_img_bg', 'label'=>__("Image's background - expanded mode", 'nb_ml' ), 'type'=>'hex');
		$indexes[] = array('index'=>'nb_exp_img_border_col', 'label'=>__("Image's border color - expanded mode", 'nb_ml' ), 'type'=>'hex');

		$indexes[] = array('index'=>'nb_custom_css', 'label'=>__( 'Custom CSS', 'nb_ml' ));

		$validator->formHandle($indexes);
		$fdata = $validator->form_val;
		$error = $validator->getErrors();
		
		if($error) {echo '<div class="error"><p>'.$error.'</p></div>';}
		else {
			// clean data and save options
			foreach($fdata as $key=>$val) {
				if(!is_array($val)) {
					$fdata[$key] = stripslashes($val);
				}
				else {
					$fdata[$key] = array();
					foreach($val as $arr_val) {$fdata[$key][] = stripslashes($arr_val);}
				}
				
				if($fdata[$key] === false) {delete_option($key);}
				else {
					if(!get_option($key)) { add_option($key, '255', '', 'yes'); }
					update_option($key, $fdata[$key]);	
				}
			}
			
			// try creating custom theme
			if(!get_option('nb_inline_css') && !empty($fdata['nb_custom_style'])) {
				if(!nb_create_custom_theme()) {
					if(!get_option('nb_inline_css')) {update_option('nb_inline_css', 1);}	
					echo '<div class="updated"><p>'. __('An error occurred during dynamic CSS creation. The code will be used inline anyway', 'nb_ml') .'</p></div>';
				}
				else {delete_option('nb_inline_css');}
			}
			
			echo '<div class="updated"><p><strong>'. __('Options saved.', 'nb_ml') .'</strong></p></div>';
		}
	}
	
	else {  
		// Normal page display
		$fdata['nb_min_news_w'] = get_option('nb_min_news_w', 200); 
		$fdata['nb_min_news_h'] = get_option('nb_min_news_h', 150); 
		$fdata['nb_min_horiz_w'] = get_option('nb_min_horiz_w', 400); 
		$fdata['nb_read_more_txt'] = get_option('nb_read_more_txt', '..'); 
		$fdata['nb_date_format'] = get_option('nb_date_format', 'd mmmm yyyy'); 
		$fdata['nb_lightbox'] = get_option('nb_lightbox', 1);
		$fdata['nb_touchswipe'] = get_option('nb_touchswipe', 1);
		
		$fdata['nb_img_check'] = get_option('nb_img_check');  
		$fdata['nb_max_img_w'] = get_option('nb_max_img_w', 800);  
		$fdata['nb_max_img_h'] = get_option('nb_max_img_h', 600);  
		$fdata['nb_preview_pag'] = get_option('nb_preview_pag');
		$fdata['nb_js_head'] = get_option('nb_js_head'); 
		
		$fdata['nb_main_style'] = get_option('nb_main_style', 'minimal');
		$fdata['nb_custom_style'] = get_option('nb_custom_style');
		$fdata['nb_force_inline_css'] = get_option('nb_force_inline_css');
		
		$fdata['nb_img_margin'] = get_option('nb_img_margin', 0);
		$fdata['nb_box_margin'] = get_option('nb_box_margin', 6);
		$fdata['nb_border_w'] = get_option('nb_border_w', 1);
		$fdata['nb_border_radius'] = get_option('nb_border_radius', 2);
		$fdata['nb_use_shadows'] = get_option('nb_use_shadows');
		$fdata['nb_exp_img_padding'] = get_option('nb_exp_img_padding', 3);
		
		$fdata['nb_bg_color'] = get_option('nb_bg_color', '#FFFFFF');
		$fdata['nb_title_color'] = get_option('nb_title_color', '#444444');
		$fdata['nb_txt_color'] = get_option('nb_txt_color', '#555555');
		$fdata['nb_link_color'] = get_option('nb_link_color', '#111111');
		$fdata['nb_border_color'] = get_option('nb_border_color', '#D5D5D5');
		$fdata['nb_border_color_h'] = get_option('nb_border_color_h', '#C4C4C4');
		$fdata['nb_btn_color'] = get_option('nb_btn_color', '#5F5F5F');
		$fdata['nb_sep_color'] = get_option('nb_sep_color', '#CFCFCF');
		$fdata['nb_loader_style'] = get_option('nb_loader_style');
		$fdata['nb_ol_icon_color'] = get_option('nb_ol_icon_color', '#333333');
		$fdata['nb_ol_bg_color'] = get_option('nb_ol_bg_color', '#FFFFFF');
		$fdata['nb_date_bg'] = get_option('nb_date_bg', '#F3F3F3');
		$fdata['nb_date_bg_h'] = get_option('nb_date_bg_h', '#E8E8E8');
		$fdata['nb_date_txt_col_h'] = get_option('nb_date_txt_col_h', '#303030');
		$fdata['nb_exp_img_bg'] = get_option('nb_exp_img_bg', '#FFFFFF');
		$fdata['nb_exp_img_border_col'] = get_option('nb_exp_img_border_col', '#AAAAAA');
		
		$fdata['nb_custom_css'] = get_option('nb_custom_css'); 
	}  
	?>

	<br/>
    <div id="tabs">
    <form name="lcwp_admin" method="post" class="form-wrap" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    	
    <ul class="tabNavigation">
    	<li><a href="#main_opt"><?php _e('Main Options', 'nb_ml') ?></a></li>
        <li><a href="#styling"><?php _e('Styling', 'nb_ml') ?></a></li>
        <li><a href="#custom_css"><?php _e('Custom CSS', 'nb_ml') ?></a></li>
    </ul>    
        
    
    <div id="main_opt"> 
    	<h3><?php _e("Global settings", 'nb_ml'); ?></h3>
        
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Minimum news width (for horizontal layout)", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="10" max="400" min="180"></div>
                <input type="text" value="<?php echo(int)$fdata['nb_min_news_w']; ?>" name="nb_min_news_w" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e('Set the minimum width for a single news in horizontal layout', 'nb_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Minimum news height (for vertical layout)", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="10" max="250" min="90"></div>
                <input type="text" value="<?php echo(int)$fdata['nb_min_news_h']; ?>" name="nb_min_news_h" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e('Set the minimum width for a single news in horizontal layout', 'nb_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Minimum horizontal News Box width", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="10" max="900" min="250"></div>
                <input type="text" value="<?php echo(int)$fdata['nb_min_horiz_w']; ?>" name="nb_min_horiz_w" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e('Set the minimum width for horizontal layout. Below, vertical layout will be applied', 'nb_ml') ?></span></td>
          </tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Shortened text suffix", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
            	<input type="text" name="nb_read_more_txt" value="<?php echo $fdata['nb_read_more_txt'] ?>" />
            </td>
            <td>
                <span class="info"><?php _e('Text added at the end of shotrened text', 'nb_ml') ?></span>
            </td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Default date format", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
            	<input type="text" name="nb_date_format" value="<?php echo $fdata['nb_date_format'] ?>" />
            </td>
            <td>
                <span class="info"><?php _e('News date composition - editable for each box', 'nb_ml') ?> (<a id="nb_show_date_helper" href="#"><?php _e('show guide', 'nb_ml') ?></a>)</span>
            </td>
          </tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Lightbox integration?", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['nb_lightbox'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="nb_lightbox" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
                <span class="info"><?php _e('If checked, enable lightbox integration for news images, youtube videos and soundcloud player', 'nb_ml') ?></span>
            </td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("TouchSwipe integration?", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['nb_touchswipe'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="nb_touchswipe" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
                <span class="info"><?php _e('If checked, enable swipe events', 'nb_ml') ?></span>
            </td>
          </tr>
        </table>
        
        <h3><?php _e("Images size control", 'nb_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Check image sizes?", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['nb_img_check'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="nb_img_check" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
                <span class="info"><?php _e('If checked, resize images bigger than sizes below (improve loading speed)', 'nb_ml') ?></span>
            </td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Max sizes <small>(width x height)</small>", 'gg_ml'); ?></td>
            <td class="lcwp_field_td">
                <input type="text" name="nb_max_img_w" value="<?php echo $fdata['nb_max_img_w'] ?>" maxlength="4" class="lcwp_slider_input" /> x 
            	<input type="text" name="nb_max_img_h" value="<?php echo $fdata['nb_max_img_h'] ?>" maxlength="4" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e("Maximum size for displayed images. Bigger will be resized", 'gg_ml'); ?></span></td>
          </tr>
        </table>
        

        <h3><?php _e("Various", 'nb_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Preview container", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
            	<select name="nb_preview_pag" class="lcweb-chosen" data-placeholder="<?php _e("Select a page", 'nb_ml'); ?> ..">
                  <?php
                  foreach(get_pages() as $pag) {
                      ($fdata['nb_preview_pag'] == $pag->ID) ? $selected = 'selected="selected"' : $selected = '';
                      echo '<option value="'.$pag->ID.'" '.$selected.'>'.$pag->post_title.'</option>';
                  }
                  ?>
                </select>  
            </td>
            <td><span class="info"><?php _e("Choose page to use as preview container", 'nb_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Use javascript in the head?", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['nb_js_head'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="nb_js_head" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
            	<span class="info"><?php _e('Put javascript in the website head, check it ONLY IF you notice some incompatibilities', 'nb_ml') ?></span>
            </td>
          </tr> 
        </table>
    </div>
    
    
    <div id="styling"> 
    	<h3><?php _e("Styling", 'nb_ml'); ?></h3>
        
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Choose the main style", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <select data-placeholder="<?php _e('Select a style', 'nb_ml') ?> .." name="nb_main_style" id="nb_main_style" class="lcweb-chosen" autocomplete="off">
                  <?php 
                  $styles = nb_main_styles();
                  foreach($styles as $style => $val) {
					$sel = ($style == $fdata['nb_main_style']) ? 'selected="selected"' : ''; 
				  	echo '<option value="'.$style.'" '.$sel.'>'.$style.'</option>'; 
				  }
                  ?>
                </select>
            </td>
            <td>
            	<span class="info"><?php _e('Choose the main style that will be applied to boxes', 'nb_ml') ?></span>
            </td>
          </tr> 
          <tr>
            <td class="lcwp_label_td"><?php _e("(main style preview)", 'nb_ml'); ?></td>
            <td class="lcwp_field_td" colspan="2">
            	<?php
				$styles = nb_main_styles();
                foreach($styles as $style => $val) { 
					$sel = ($style == $fdata['nb_main_style']) ? '' : 'style="display: none;"'; 
					echo '<img src="'.NB_URL.'/img/pred_styles_demo/'.$val['preview'].'" class="nb_styles_preview" alt="'.$style.'" '.$sel.' />';	
				}
				?>
            </td>
          </tr>
          
          <?php if(ini_get('allow_url_fopen')) : ?>
          <tr>
            <td class="lcwp_label_td"><?php _e("Use custom style?", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['nb_custom_style'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="nb_custom_style" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
                <span class="info"><?php _e('If checked, apply custom styling settings', 'nb_ml') ?></span>
            </td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Use custom CSS inline?", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['nb_force_inline_css'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="nb_force_inline_css" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
            	<span class="info"><?php _e('If checked, uses custom CSS inline (useful for multisite installations)', 'nb_ml') ?></span>
            </td>
          </tr>
          <?php endif; ?>
        </table>
        
        
        <?php
		/*** CUSTOM THEME OPTIONS ***/
		if(!ini_get('allow_url_fopen')) :
			echo '<h3>'. __("Custom style - layout", 'nb_ml').'</h3>';
			echo '<p>&nbsp;</p><p>' . __("Your server doesn't give the permissions to manage files. Please enable <em>allow_url_fopen</em> directive.", 'pg_ml') .'</p><p style="padding-bottom: 40px;"><br/></p>';
		else :
		?>
        
        <h3><?php _e("Custom style - layout", 'nb_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Image's margin", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
            	<div class="lcwp_slider" step="1" max="15" min="0"></div>
                <input type="text" value="<?php echo $fdata['nb_img_margin']; ?>" name="nb_img_margin" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td>
                <span class="info"><?php _e("Define space between box's edge and image in initial mode", 'nb_ml') ?></span>
            </td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("News margin <small>(boxed style)</small>", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
            	<div class="lcwp_slider" step="1" max="20" min="2"></div>
                <input type="text" value="<?php echo $fdata['nb_box_margin']; ?>" name="nb_box_margin" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td>
                <span class="info"><?php _e("News side space in boxed layout", 'nb_ml') ?></span>
            </td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Border width", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="10" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['nb_border_w']; ?>" name="nb_border_w" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e('Set boxes border width', 'nb_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Border Radius", 'mg_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_slider" step="1" max="20" min="0"></div>
                <input type="text" value="<?php echo (int)$fdata['nb_border_radius']; ?>" name="nb_border_radius" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td><span class="info"><?php _e('Set boxes border radius', 'nb_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Use shadows?", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <?php ($fdata['nb_use_shadows'] == 1) ? $sel = 'checked="checked"' : $sel = ''; ?>
                <input type="checkbox" value="1" name="nb_use_shadows" class="ip-checkbox" <?php echo $sel; ?> />
            </td>
            <td>
                <span class="info"><?php _e('If checked, apply shadows to boxes', 'nb_ml') ?></span>
            </td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Image's padding in expanded mode", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
            	<div class="lcwp_slider" step="1" max="20" min="0"></div>
                <input type="text" value="<?php echo $fdata['nb_exp_img_padding']; ?>" name="nb_exp_img_padding" class="lcwp_slider_input" />
                <span>px</span>
            </td>
            <td>
                <span class="info"><?php _e("Image's padding for managed images in expanded mode", 'nb_ml') ?></span>
            </td>
          </tr>
        </table>
        
        <h3><?php _e("Custom style - colors", 'nb_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_label_td"><?php _e("Background", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['nb_bg_color']; ?>;"></span>
                	<input type="text" name="nb_bg_color" value="<?php echo $fdata['nb_bg_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('Boxes background color', 'nb_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Titles", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['nb_title_color']; ?>;"></span>
                	<input type="text" name="nb_title_color" value="<?php echo $fdata['nb_title_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('News title color', 'nb_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Text", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['nb_txt_color']; ?>;"></span>
                	<input type="text" name="nb_txt_color" value="<?php echo $fdata['nb_txt_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('News text color', 'nb_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Links", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['nb_link_color']; ?>;"></span>
                	<input type="text" name="nb_link_color" value="<?php echo $fdata['nb_link_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('News links color', 'nb_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Border - default state", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['nb_border_color']; ?>;"></span>
                	<input type="text" name="nb_border_color" value="<?php echo $fdata['nb_border_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('News border color on default state', 'nb_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Border - hover state", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['nb_border_color_h']; ?>;"></span>
                	<input type="text" name="nb_border_color_h" value="<?php echo $fdata['nb_border_color_h']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('News border color on hover state', 'nb_ml') ?></span></td>
          </tr>          
          <tr>
            <td class="lcwp_label_td"><?php _e("Buttons", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['nb_btn_color']; ?>;"></span>
                	<input type="text" name="nb_btn_color" value="<?php echo $fdata['nb_btn_color']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e('News buttons color (social share, link, expand)', 'nb_ml') ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e('Buttons separator and title border', 'nb_ml') ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['nb_sep_color']; ?>;"></span>
                	<input type="text" name="nb_sep_color" value="<?php echo $fdata['nb_sep_color']; ?>" />
                </div>
            </td>
            <td><span class="info"></span></td>
          </tr>      
          <tr>
            <td class="lcwp_label_td"><?php _e("Loader style", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <select name="nb_loader_style" class="lcweb-chosen" data-placeholder="<?php _e("Select a style", 'mg_ml'); ?> ..">
                  <option value="l"><?php _e('light', 'nb_ml') ?></option>
                  <option value="d" <?php if($fdata['nb_loader_style'] == 'd') {echo 'selected="selected"';} ?>><?php _e('dark', 'nb_ml') ?></option>
                </select>  
            </td>
            <td><span class="info"><?php _e("Choose the images preloader style", 'mg_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Overlay icon", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['nb_ol_icon_color']; ?>;"></span>
                	<input type="text" name="nb_ol_icon_color" value="<?php echo $fdata['nb_ol_icon_color']; ?>" />
                </div>
            </td>
            <td><span class="info"></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Overlay background", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['nb_ol_bg_color']; ?>;"></span>
                	<input type="text" name="nb_ol_bg_color" value="<?php echo $fdata['nb_ol_bg_color']; ?>" />
                </div>
            </td>
            <td><span class="info"></span></td>
          </tr>
          
          <tr><td colspan="3"></td></tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Date/'Read More' box - background", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['nb_date_bg']; ?>;"></span>
                	<input type="text" name="nb_date_bg" value="<?php echo $fdata['nb_date_bg']; ?>" />
                </div>
            </td>
            <td><span class="info"></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("'Read More' box - background on hover", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['nb_date_bg_h']; ?>;"></span>
                	<input type="text" name="nb_date_bg_h" value="<?php echo $fdata['nb_date_bg_h']; ?>" />
                </div>
            </td>
            <td><span class="info"></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("'Read More' box - text color on hover", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['nb_date_txt_col_h']; ?>;"></span>
                	<input type="text" name="nb_date_txt_col_h" value="<?php echo $fdata['nb_date_txt_col_h']; ?>" />
                </div>
            </td>
            <td><span class="info"></span></td>
          </tr>
          
          <tr><td colspan="3"></td></tr>
          
          <tr>
            <td class="lcwp_label_td"><?php _e("Image's background <small>(expanded mode)</small>", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['nb_exp_img_bg']; ?>;"></span>
                	<input type="text" name="nb_exp_img_bg" value="<?php echo $fdata['nb_exp_img_bg']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e("Set background color for managed images in expanded mode", 'nb_ml'); ?></span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td"><?php _e("Image's border color <small>(expanded mode)</small>", 'nb_ml'); ?></td>
            <td class="lcwp_field_td">
                <div class="lcwp_colpick">
                	<span class="lcwp_colblock" style="background-color: <?php echo $fdata['nb_exp_img_border_col']; ?>;"></span>
                	<input type="text" name="nb_exp_img_border_col" value="<?php echo $fdata['nb_exp_img_border_col']; ?>" />
                </div>
            </td>
            <td><span class="info"><?php _e("Set border color for managed images in expanded mode", 'nb_ml'); ?></span></td>
          </tr>
        </table>
        <?php endif; ?>
    </div>

    
    <div id="custom_css">    
        <h3><?php _e("Custom CSS", 'nb_ml'); ?></h3>
        <table class="widefat lcwp_table">
          <tr>
            <td class="lcwp_field_td">
            	<textarea name="nb_custom_css" style="width: 100%" rows="18"><?php echo $fdata['nb_custom_css']; ?></textarea>
            </td>
          </tr>
        </table>
        
        <h3><?php _e("Initial mode - Elements Legend", 'nb_ml'); ?></h3> 
        <table class="widefat lcwp_table">  
          <tr>
            <td class="lcwp_label_td">.lcnb_news</td>
            <td><span class="info">News wrapper</td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.lcnb_title</td>
            <td><span class="info">News title</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.lcnb_txt</td>
            <td><span class="info">News text</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.lcnb_btn_time</td>
            <td><span class="info">Date / "read more" box</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.lcnb_lb_overlay</td>
            <td><span class="info">Image's overlay (layer under icon)</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.lcnb_prev</td>
            <td><span class="info">Navigator - previous news button</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.lcnb_next</td>
            <td><span class="info">Navigator - next news button</span></td>
          </tr>
		</table>
        
        <h3><?php _e("Expanded mode - Elements Legend", 'nb_ml'); ?></h3> 
        <table class="widefat lcwp_table">  
          <tr>
            <td class="lcwp_label_td">.lcnb_exp_block</td>
            <td><span class="info">Expanded news wrapper</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.lcnb_exp_block .lcnb_title</td>
            <td><span class="info">News title</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.lcnb_exp_body_img > div:first-child</td>
            <td><span class="info">Managed image wrapper</span></td>
          </tr>
          <tr>
            <td class="lcwp_label_td">.lcnb_exp_img_wrap</td>
            <td><span class="info">Side image wrapper</span></td>
          </tr>
        </table> 
    </div> 
   
   	<input type="hidden" name="lcwp_nonce" value="<?php echo wp_create_nonce('lcwp') ?>" /> 
    <input type="submit" name="lcwp_admin_submit" value="<?php _e('Update Options', 'nb_ml' ) ?>" class="button-primary" />  
    
	</form>
    </div>
</div>  

<?php // SCRIPTS ?>
<script src="<?php echo NB_URL; ?>/js/functions.js" type="text/javascript"></script>
<script src="<?php echo NB_URL; ?>/js/chosen/chosen.jquery.min.js" type="text/javascript"></script> 
<script src="<?php echo NB_URL; ?>/js/iphone_checkbox/iphone-style-checkboxes.js" type="text/javascript"></script> 
<script src="<?php echo NB_URL; ?>/js/colpick/js/colpick.min.js" type="text/javascript"></script>

<script type="text/javascript" charset="utf8" >
jQuery(document).ready(function($) {
	
	// predefined style preview toggle
	jQuery('body').delegate('#nb_main_style', "change", function() {
		var sel = jQuery(this).val();
		
		jQuery('.nb_styles_preview').hide();
		jQuery('.nb_styles_preview').each(function() {
			if( jQuery(this).attr('alt') == sel) {jQuery(this).fadeIn();}
		});
	});
	
	
	// date format helper
	jQuery('body').delegate('#nb_show_date_helper', "click", function (e) {
		e.preventDefault();
		tb_show('News Box date helper', '#TB_inline?height=600&width=640&inlineId=nb_date_helper');
		setTimeout(function() {
			jQuery('#TB_window').css('background-color', '#fff');
		}, 50);
	});

	
	// tabs
	jQuery("#tabs").tabs();
});
</script>