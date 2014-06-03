<div class="postbox">	
	<div class="handlediv" title="<?php _e('Toggle view', 'touchcarousel'); ?>"></div>			
	<h3 class="hndle">
		<span><?php _e('General Options', 'touchcarousel'); ?></span>
	</h3>
	<div class="inside">
		
		
		<!-- Width and height -->
		<div class="fields-group">		
			<div class="field-row">
				<label for="carousel-width" data-help="<?php _e('Carousel width. Any CSS acceptable value.', 'touchcarousel'); ?>"><?php _e('Width', 'touchcarousel'); ?></label>
				<input id="carousel-width" data-visible="false" name="width" type="text" value="<?php if($slider_row) echo $slider_row['width']; else echo "600px"; ?>" size="5" />							
			</div>			
			<div class="field-row">
				<label for="carousel-height" data-help="<?php _e('Carousel height. Any CSS acceptable value.', 'touchcarousel'); ?>"><?php _e('Height', 'touchcarousel'); ?></label>
				<input id="carousel-height" data-visible="false" name="height" type="text" value="<?php if($slider_row) echo $slider_row['height']; else echo "400px"; ?>" size="5" />							
			</div>
		</div>
		
		
		<!-- Skin -->
		<div class="fields-group">
			<div class="field-row">				
				<label for="skin" data-help="<?php _e('Set of CSS styles for carousel.', 'touchcarousel'); ?>"><?php _e('Skin', 'touchcarousel'); ?></label>	
				<select id="skin"  data-visible="false" name="skin">
				<?php 
					$curr_skin = $slider_row ? $slider_row['skin'] : array_shift(array_keys($this->skins)); 
					$selected;
					foreach($this->skins as $skin => $skinName) {
						$selected = "";
						if($skin == $curr_skin) {
							$selected = " selected=\"selected\"";
						}
						echo "<option value=\"$skin\"$selected>$skinName</option>";
					}
				?>			
				</select>
			</div>
			<div class="field-row">			
				<label for="preload-skin" data-help="<?php _e('Check this if you embed carousel outside post or page, or not with shortcode. Files will be embedded to every page with this option.', 'touchcarousel'); ?>"><?php _e('Preload files', 'touchcarousel'); ?></label>
				<input id="preload-skin"  data-visible="false" name="preloadSkin" type="checkbox" <?php if(!$slider_row || !$slider_row['preload_skin']) echo "value=\"false\""; else echo "value=\"true\" checked=\"checked\"" ?> />		
			</div>
		</div>
		
		
		
		
		<!-- paging (control navigation) -->
		<div class="fields-group">	
			<div class="field-row group-leader">			
				<label for="snap-to-items" data-help="<?php _e('Enables snapping to items, based on items per move.', 'touchcarousel'); ?>"><?php _e('Snap to items', 'touchcarousel'); ?></label>
				<input id="snap-to-items" name="snapToItems" type="checkbox" value="true" checked="checked" />			
			</div>
			<div class="field-row">			
				<label for="paging-nav-controls" data-help="<?php _e('Enable page indicator controls(bullets).', 'touchcarousel'); ?>"><?php _e('Paging controls', 'touchcarousel'); ?></label>
				<input id="paging-nav-controls" name="pagingNavControls" type="checkbox" value="true" checked />			
			</div>
		</div>
		<div class="fields-group">		
			<div class="field-row">
				<label for="items-per-move" data-help="<?php _e('The number of items to move per arrow click.', 'touchcarousel'); ?>"><?php _e('Items per move', 'touchcarousel'); ?></label>
				<input id="items-per-move" name="itemsPerMove" type="number" max="50" min="0" step="1" value="1" size="4" />							
			</div>
			<div class="field-row">			
				<label for="loop-items" data-help="<?php _e('Loop items (don\'t disable arrows on last slide).', 'touchcarousel'); ?>"><?php _e('Loop items', 'touchcarousel'); ?></label>
				<input id="loop-items" name="loopItems" type="checkbox" value="false" />			
			</div>
		</div>
		
		
		
		
		<!-- Arrows navigation -->
		<div class="fields-group">
			<div class="field-row group-leader">
				<label for="arrows" data-help="<?php _e('Navigation left and right arrows.', 'touchcarousel'); ?>"><?php _e('Arrows (next, prev)', 'touchcarousel'); ?></label>
				<input id="arrows" name="directionNav" type="checkbox" value="true" checked="checked"/>
			</div>
			<div class="field-row">
				<label for="arrows-auto-hide" data-help="<?php _e('Auto hide navigation arrows when mouse leaves slider area', 'touchcarousel'); ?>"><?php _e('Arrows auto-hide', 'touchcarousel'); ?></label>
				<input id="arrows-auto-hide" name="directionNavAutoHide" type="checkbox" value="false"/>
			</div>
		</div>
		
		
		
		
		<!-- Slideshow(autoplay) -->
		<div class="fields-group">
			<div class="field-row group-leader">			
				<label for="autoplay" data-help="<?php _e('Enable autoplay (slideshow).', 'touchcarousel'); ?>"><?php _e('Autoplay', 'touchcarousel'); ?></label>
				<input id="autoplay" name="autoplay" type="checkbox" value="false"/>			
			</div>
			<div class="field-row">
				<label for="delay" data-help="<?php _e('Time is milliseconds before next item is shown.', 'touchcarousel'); ?>"><?php _e('Delay', 'touchcarousel'); ?></label>
				<input id="delay" name="autoplayDelay" type="number" value="3000" size="5" min="0" step="100" max="20000" />	
				<span class="unit">ms</span>		
			</div>
			<div class="field-row">			
				<label for="autoplay-stop-at-action" data-help="<?php _e('Stops autoplay when user takes control over carousel(drag, click arrow e.t.c.).', 'touchcarousel'); ?>"><?php _e('Stop at action', 'touchcarousel'); ?></label>
				<input id="autoplay-stop-at-action" name="autoplayStopAtAction" type="checkbox" value="true" checked/>			
			</div>
		</div>
					
			
		
		
		
		
		
		
		<!-- Keyboard and mouse navigation -->
		<div class="fields-group">
			<div class="field-row">			
				<label for="keyboardnav" data-help="<?php _e('Enable keyboard arrows(left and right) navigation.', 'touchcarousel'); ?>"><?php _e('Keyboard nav', 'touchcarousel'); ?></label>
				<input id="keyboardnav" name="keyboardNav" type="checkbox" value="false"/>			
			</div>	
			<div class="field-row">			
				<label for="mouse-drag" data-help="<?php _e('Enable carousel items dragging using mouse on non-touch devices', 'touchcarousel'); ?>"><?php _e('Mouse drag', 'touchcarousel'); ?></label>
				<input id="mouse-drag" name="dragUsingMouse" type="checkbox" value="true" checked/>			
			</div>									
		</div>
		
		<!-- Transition Speed -->
		<div class="fields-group">	
			<div class="field-row">
				<label for="slide-transition-speed" data-help="<?php _e('Slide transition speed', 'touchcarousel'); ?>"><?php _e('Transition speed', 'touchcarousel'); ?></label>
				<input id="slide-transition-speed" name="transitionSpeed" type="number" step="50" value="400" min="0" max="10000" />	
				<span class="unit">ms</span>		
			</div>
		</div>
		
		<!-- other… -->
		<div class="fields-group">
			<div class="field-row">			
				<label for="item-fallback-width" data-help="<?php _e('Default width of single carousel item (if can\'t determine automatically).', 'touchcarousel'); ?>"><?php _e('Item fallback width', 'touchcarousel'); ?></label>
				<input id="item-fallback-width" name="itemFallbackWidth" type="number" step="20" value="300" step="1" min="0" />	
				<span class="unit">px</span>		
			</div>
		</div>
		
		<!-- scrollbar -->
		<div class="fields-group">	
			<div class="field-row group-leader">			
				<label for="scrollbar-nav" data-help="<?php _e('Display scrollbar.', 'touchcarousel'); ?>"><?php _e('Scrollbar', 'touchcarousel'); ?></label>
				<input id="scrollbar-nav" name="scrollbar" type="checkbox" value="false" />			
			</div>
			<div class="field-row">			
				<label for="scrollbar-autohide" data-help="<?php _e('Auto hide scrollbar after transition.', 'touchcarousel'); ?>"><?php _e('Scrollbar auto hide', 'touchcarousel'); ?></label>
				<input id="scrollbar-autohide" name="scrollbarAutoHide" type="checkbox" value="true" checked />			
			</div>
			<div class="field-row">			
				<label for="scrollbar-theme" data-help="<?php _e('Scrollbar color.', 'touchcarousel'); ?>"><?php _e('Scrollbar theme', 'touchcarousel'); ?></label>
				<select id="scrollbar-theme" name="scrollbarTheme">
					<option value="dark" selected>Dark</option>	
					<option value="light">Light</option>		
				</select>
			</div>
		</div>
		
	</div>
</div>		
