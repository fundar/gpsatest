<?php

class WonderPlugin_Carousel_Creator {

	private $parent_view, $list_table;
	
	function __construct($parent) {
		
		$this->parent_view = $parent;
	}
	
	function render( $id, $config ) {
		
		?>
		
		<h3><?php _e( 'General Options', 'wonderplugin_carousel' ); ?></h3>
		
		<div id="wonderplugin-carousel-id" style="display:none;"><?php echo $id; ?></div>
		
		<?php 
		$config = str_replace('\\\"', '"', $config);
		$config = str_replace("\\\'", "'", $config);
		?>
		
		<div id="wonderplugin-carousel-id-config" style="display:none;"><?php echo $config; ?></div>
		<div id="wonderplugin-carousel-jsfolder" style="display:none;"><?php echo WONDERPLUGIN_CAROUSEL_URL . 'engine/'; ?></div>
		<div id="wonderplugin-carousel-wp-history-media-uploader" style="display:none;"><?php echo ( function_exists("wp_enqueue_media") ? "0" : "1"); ?></div>
				
		<div style="margin:0 12px;">
		<table class="wonderplugin-form-table">
			<tr>
				<th><?php _e( 'Name', 'wonderplugin_carousel' ); ?></th>
				<td><input name="wonderplugin-carousel-name" type="text" id="wonderplugin-carousel-name" value="My Carousel" class="regular-text" /></td>
			</tr>
		</table>
		</div>
		
		<h3><?php _e( 'Designing', 'wonderplugin_carousel' ); ?></h3>
		
		<div style="margin:0 12px;">
		<ul class="wonderplugin-tab-buttons" id="wonderplugin-carousel-toolbar">
			<li class="wonderplugin-tab-button step1 wonderplugin-tab-buttons-selected"><?php _e( 'Images & Videos', 'wonderplugin_carousel' ); ?></li>
			<li class="wonderplugin-tab-button step2"><?php _e( 'Skins', 'wonderplugin_carousel' ); ?></li>
			<li class="wonderplugin-tab-button step3"><?php _e( 'Options', 'wonderplugin_carousel' ); ?></li>
			<li class="wonderplugin-tab-button step4"><?php _e( 'Preview', 'wonderplugin_carousel' ); ?></li>
			<li class="laststep"><input class="button button-primary" type="button" value="<?php _e( 'Save & Publish', 'wonderplugin_carousel' ); ?>"></input></li>
		</ul>
				
		<ul class="wonderplugin-tabs" id="wonderplugin-carousel-tabs">
			<li class="wonderplugin-tab wonderplugin-tab-selected">	
			
				<div class="wonderplugin-toolbar">	
					<input type="button" class="button" id="wonderplugin-add-image" value="<?php _e( 'Add Image', 'wonderplugin_carousel' ); ?>" />
					<input type="button" class="button" id="wonderplugin-add-video" value="<?php _e( 'Add Video', 'wonderplugin_carousel' ); ?>" />
					<input type="button" class="button" id="wonderplugin-add-youtube" value="<?php _e( 'Add YouTube', 'wonderplugin_carousel' ); ?>" />
					<input type="button" class="button" id="wonderplugin-add-vimeo" value="<?php _e( 'Add Vimeo', 'wonderplugin_carousel' ); ?>" />
				</div>
        		
        		<table class="wonderplugin-table" id="wonderplugin-carousel-media-table">
			        <thead>
			          	<tr>
			            	<th>#</th>
			            	<th><?php _e( 'Media', 'wonderplugin_carousel' ); ?></th>
			            	<th><?php _e( 'Type', 'wonderplugin_carousel' ); ?></th>
			            	<th><?php _e( 'Actions', 'wonderplugin_carousel' ); ?></th>
			          	</tr>
			        </thead>
			        <tbody>
			        </tbody>
			      </table>
      
			</li>
			<li class="wonderplugin-tab">
				<form>
					<fieldset>
						
						<?php 
						$skins = array(
								"classic" => "Classic",
								"gallery" => "Gallery",
								"highlight" => "Highlight",
								"list" => "List",
								"navigator" => "Navigator",
								"showcase" => "Showcase",
								"simplicity" => "Simplicity",
								"stylish" => "Stylish",
								"thumbnail" => "Thumbnail",
								"vertical" => "Vertical"
								);
						
						foreach ($skins as $key => $value) {
						?>
							<div class="wonderplugin-tab-skin">
							<label><input type="radio" name="wonderplugin-carousel-skin" value="<?php echo $key; ?>" selected> <?php echo $value; ?> <br /><img class="selected" style="width:300px;" src="<?php echo WONDERPLUGIN_CAROUSEL_URL; ?>images/<?php echo $key; ?>.png" /></label>
							</div>
						<?php
						}
						?>
						
					</fieldset>
				</form>
			</li>
			<li class="wonderplugin-tab">
			
				<div class="wonderplugin-carousel-options">
					<div class="wonderplugin-carousel-options-menu" id="wonderplugin-carousel-options-menu">
						<div class="wonderplugin-carousel-options-menu-item wonderplugin-carousel-options-menu-item-selected"><?php _e( 'Skin options', 'wonderplugin_carousel' ); ?></div>
						<div class="wonderplugin-carousel-options-menu-item"><?php _e( 'Content template', 'wonderplugin_carousel' ); ?></div>
						<div class="wonderplugin-carousel-options-menu-item"><?php _e( 'Skin CSS', 'wonderplugin_carousel' ); ?></div>
						<div class="wonderplugin-carousel-options-menu-item"><?php _e( 'Advanced options', 'wonderplugin_carousel' ); ?></div>
					</div>
					
					<div class="wonderplugin-carousel-options-tabs" id="wonderplugin-carousel-options-tabs">
					
						<div class="wonderplugin-carousel-options-tab wonderplugin-carousel-options-tab-selected">
							<p class="wonderplugin-carousel-options-tab-title"><?php _e( 'Options will be restored to the default value if you switch to a new skin in the Skins tab.', 'wonderplugin_gallery' ); ?></p>
							<table class="wonderplugin-form-table-noborder">
							
								<tr>
									<th>Width / Height</th>
									<td><label><input name="wonderplugin-carousel-width" type="text" id="wonderplugin-carousel-width" value="300" class="small-text" /> / <input name="wonderplugin-carousel-height" type="text" id="wonderplugin-carousel-height" value="300" class="small-text" /></label></td>
								</tr>
								
								<tr>
									<th>Play mode</th>
									<td><label><input name='wonderplugin-carousel-autoplay' type='checkbox' id='wonderplugin-carousel-autoplay'  /> Auto play</label>
									<br /><label><input name='wonderplugin-carousel-random' type='checkbox' id='wonderplugin-carousel-random'  /> Random</label>
									</td>
								</tr>
								<tr>
									<th>Circular</th>
									<td><label><input name='wonderplugin-carousel-circular' type='checkbox' id='wonderplugin-carousel-circular'  /> Circular</label>
									</td>
								</tr>
								<tr>
									<th>Responsive</th>
									<td><label><input name='wonderplugin-carousel-responsive' type='checkbox' id='wonderplugin-carousel-responsive'  /> Responsive</label>
									</td>
								</tr>
								<tr>
									<th>Visible items</th>
									<td><label><input name='wonderplugin-carousel-visibleitems' type='text' size="10" id='wonderplugin-carousel-visibleitems' value='3' /></label></td>
								</tr>
							</table>
						</div>
						
						<div class="wonderplugin-carousel-options-tab">
							<table class="wonderplugin-form-table-noborder">
								<tr>
									<th>Skin template</th>
									<td><textarea name='wonderplugin-carousel-skintemplate' id='wonderplugin-carousel-skintemplate' value='' class='large-text' rows="20"></textarea></td>
								</tr>
							</table>
						</div>
						
						<div class="wonderplugin-carousel-options-tab">
							<table class="wonderplugin-form-table-noborder">
								<tr>
									<th>Skin CSS</th>
									<td><textarea name='wonderplugin-carousel-skincss' id='wonderplugin-carousel-skincss' value='' class='large-text' rows="20"></textarea></td>
								</tr>
							</table>
						</div>
						
						<div class="wonderplugin-carousel-options-tab">
							<table class="wonderplugin-form-table-noborder">
								<tr>
									<th>Custom CSS</th>
									<td><textarea name='wonderplugin-carousel-custom-css' id='wonderplugin-carousel-custom-css' value='' class='large-text' rows="10"></textarea></td>
								</tr>
								<tr>
									<th>Advanced Options</th>
									<td><textarea name='wonderplugin-carousel-data-options' id='wonderplugin-carousel-data-options' value='' class='large-text' rows="10"></textarea></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<div style="clear:both;"></div>
				
			</li>
			<li class="wonderplugin-tab">
				<div id="wonderplugin-carousel-preview-tab">
					<div id="wonderplugin-carousel-preview-container">
					</div>
				</div>
			</li>
			<li class="wonderplugin-tab">
				<div id="wonderplugin-carousel-publish-loading"></div>
				<div id="wonderplugin-carousel-publish-information"></div>
			</li>
		</ul>
		</div>
		
		<?php
	}
	
	function get_list_data() {
		return array();
	}
}