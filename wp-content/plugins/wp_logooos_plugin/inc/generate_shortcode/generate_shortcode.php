<?php
	
?>

	<h2 id="logooos_page_title">Generate Shortcode</h2>
	
	<div id="logooos_gene_short_leftSidebar">
		
		<div class="row">
			<label for="logooos_categoriesList">Category Name</label>
			<?php

			wp_dropdown_categories(array('taxonomy' =>'logooocategory',
										 'show_count' => 1, 
									     'pad_counts' => 1, 
										 'id' => 'logooos_categoriesList',
										 'name' => 'logooos_categoriesList',
										 'hide_empty' => 0,
										 'show_option_none' => 'All Categories',
										 'hierarchical'=>1));
				
			?>

		</div>
		
		<div class="row">
			<label for="logooos_layout">Layout</label>
			<select id="logooos_layout" name="logooos_layout">
				<option value="slider" selected>Slider</option>
				<option value="grid">Grid</option>
				<option value="list">List</option>
			</select>
		</div>
		
		<div class="row slider_option grid_option">
			<label for="logooos_columnsNumberList">Columns Number</label>
			<select id="logooos_columnsNumberList" name="logooos_columnsNumberList">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5" selected>5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
			</select>
		</div>
		
		<div class="row slider_option grid_option">
			<label for="logooos_marginBetweenItems">Margin between items</label>
			<select id="logooos_marginBetweenItems" name="logooos_marginBetweenItems">
				<option value="">0px</option>
				<option value="5px">5px</option>
				<option value="10px">10px</option>
				<option value="15px">15px</option>
				<option value="20px">20px</option>
				<option value="25px" selected >25px</option>
				<option value="30px">30px</option>
			</select>
		</div>
		
		<div class="row">
			<label for="logooos_hovereffect">Hover effect</label>
			<select id="logooos_hovereffect" name="logooos_hovereffect">
				<option value="">None</option>
				<option value="effect1" selected >Effect 1 ( outer shadow )</option>
				<option value="effect2">Effect 2 ( inner shadow )</option>
				<option value="effect3">Effect 3 ( border color )</option>
				<option value="effect4">Effect 4 ( fading )</option>
			</select>
		</div>
		
		<div class="row hovereffect_option">
			<label for="logooos_hoverEffectColor">Hover effect color</label>
			<input type="text" id="logooos_hoverEffectColor" name="logooos_hoverEffectColor" value="#DCDCDC" />
			<div id="logooos_hoverEffectColor_colorpicker" class="logooos_farbtastic"></div>
			<input type="button" id="logooos_hoverEffectColor_btn" name="logooos_hoverEffectColor_btn" value="View Color" class="button-primary" />
		</div>
		
		<div class="row">
			<label for="logooos_border">Border</label>
			<select id="logooos_border" name="logooos_border">
				<option value="enabled" selected>enabled</option>
				<option value="disabled">disabled</option>
			</select>
		</div>
		
		<div class="row border_option">
			<label for="logooos_borderColor">Border Color</label>
			<input type="text" id="logooos_borderColor" name="logooos_borderColor" value="#DCDCDC" />
			<div id="logooos_borderColor_colorpicker" class="logooos_farbtastic"></div>
			<input type="button" id="logooos_borderColor_btn" name="logooos_borderColor_btn" value="View Color" class="button-primary" />
		</div>
		
		<div class="row">
			<label for="logooos_borderRadius">Border Radius</label>
			<select id="logooos_borderRadius" name="logooos_borderRadius">
				<option value="logooos_no_radius" selected >no radius</option>
				<option value="logooos_small_radius" >small radius</option>
				<option value="logooos_medium_radius" >medium radius</option>
				<option value="logooos_large_radius" >large radius</option>
			</select>
		</div>
		
		<div class="row">
			<label for="logooos_bgColorInput">Items Background Color</label>
			<input type="text" id="logooos_bgColorInput" name="logooos_bgColorInput" value="transparent" />
			<div id="logooos_bgColorInput_colorpicker" class="logooos_farbtastic"></div>
			<input type="button" id="logooos_bgColorInput_btn" name="logooos_bgColorInput_btn" value="View Color" class="button-primary" />
		</div>
		
		<div class="row slider_option grid_option">
			<label for="logooos_tooltipList">Tooltip</label>
			<select id="logooos_tooltipList" name="logooos_tooltipList">
				<option value="enabled" selected >enabled</option>
				<option value="disabled" >disabled</option>
			</select>
		</div>
		
		<div class="row">
			<label for="logooos_grayscaleList">Grayscale</label>
			<select id="logooos_grayscaleList" name="logooos_grayscaleList">
				<option value="enabled">enabled</option>
				<option value="disabled" selected >disabled</option>
			</select>
		</div>
		
		<div class="row">
			<label for="logooos_responsiveList">Responsive</label>
			<select id="logooos_responsiveList" name="logooos_responsiveList">
				<option value="enabled" selected >enabled</option>
				<option value="disabled" >disabled</option>
			</select>
		</div>
		
		
		<div class="row">
			<label for="logooos_orderByList">Order By</label>
			<select id="logooos_orderByList" name="logooos_orderByList">
				<option value="menu_order">Order</option>
				<option value="date" selected >Publish Date</option>
				<option value="title">Title</option>
				<option value="rand">Random </option>
			</select>
		</div>
		
		<div class="row">
			<label for="logooos_orderList">Order</label>
			<select id="logooos_orderList" name="logooos_orderList">
				<option value="DESC" selected >Descending</option>
				<option value="ASC">Ascending</option>
			</select>
		</div>
		
		<div class="row">
			<label for="logooos_NumberInput">Number of items</label>
			<input type="text" id="logooos_NumberInput" name="logooos_NumberInput" value="" placeholder="All" />
		</div>
		
		<div class="row slider_option">
			<label for="logooos_autoplay">Auto Play</label>
			<select id="logooos_autoplay" name="logooos_autoplay">
				<option value="true" selected >true</option>
				<option value="false">false</option>
			</select>
		</div>
		
		<div class="row slider_option">
			<label for="logooos_scrollduration">Scroll Duration</label>
			<input type="text" id="logooos_scrollduration" name="logooos_scrollduration" value="1000" />
		</div>
		
		<div class="row slider_option">
			<label for="logooos_pauseduration">Pause Duration</label>
			<input type="text" id="logooos_pauseduration" name="logooos_pauseduration" value="9000" />
		</div>
		
		<div class="row slider_option">
			<label for="logooos_buttonsbordercolor">Buttons border color</label>
			<input type="text" id="logooos_buttonsbordercolor" name="logooos_buttonsbordercolor" value="#DCDCDC" />
			<div id="logooos_buttonsbordercolor_colorpicker" class="logooos_farbtastic"></div>
			<input type="button" id="logooos_buttonsbordercolor_btn" name="logooos_buttonsbordercolor_btn" value="View Color" class="button-primary" />
		</div>
		
		<div class="row slider_option">
			<label for="logooos_buttonsbgcolor">Buttons background color</label>
			<input type="text" id="logooos_buttonsbgcolor" name="logooos_buttonsbgcolor" value="#FFFFFF" />
			<div id="logooos_buttonsbgcolor_colorpicker" class="logooos_farbtastic"></div>
			<input type="button" id="logooos_buttonsbgcolor_btn" name="logooos_buttonsbgcolor_btn" value="View Color" class="button-primary" />
		</div>
		
		<div class="row slider_option">
			<label for="logooos_buttonsarrowscolor">Buttons arrows color</label>
			<select id="logooos_buttonsarrowscolor" name="logooos_buttonsarrowscolor">
				<option value="darkgray">dark gray</option>
				<option value="lightgray" selected >light gray</option>
				<option value="white" >white</option>
			</select>
		</div>
		
		
		
		
		
		
		
		
		
		
		
		
		<div class="row list_option">
			<label for="logooos_font_style">Font Style</label>
			<select id="logooos_font_style" name="logooos_font_style">
				<option value="custom" >custom style</option>
				<option value="default" >current theme style</option>
			</select>
		</div>
		
		<div class="row list_option font_option">
			<label for="logooos_title_font_family">Title Font Family</label>
			<select id="logooos_title_font_family" name="logooos_title_font_family">
				<option value="" >current theme font</option>
				<option value="Georgia, serif" >Georgia</option>
				<option value="'Palatino Linotype', 'Book Antiqua', Palatino, serif" >Palatino Linotype</option>
				<option value="'Times New Roman', Times, serif" >Times New Roman</option>
				<option value="Arial, Helvetica, sans-serif" >Arial</option>
				<option value="'Arial Black', Gadget, sans-serif" >Arial Black</option>
				<option value="'Comic Sans MS', cursive, sans-serif" >Comic Sans MS</option>
				<option value="Impact, Charcoal, sans-serif" >Impact</option>
				<option value="'Lucida Sans Unicode', 'Lucida Grande', sans-serif" >Lucida Sans Unicode</option>
				<option value="Tahoma, Geneva, sans-serif" >Tahoma</option>
				<option value="'Trebuchet MS', Helvetica, sans-serif" >Trebuchet MS</option>
				<option value="Verdana, Geneva, sans-serif" >Verdana</option>
				<option value="'Courier New', Courier, monospace" >Courier New</option>
				<option value="'Lucida Console', Monaco, monospace" >Lucida Console</option>
			</select>
		</div>
		
		<div class="row list_option font_option">
			<label for="logooos_title_font_color">Title Font Color</label>
			<input type="text" id="logooos_title_font_color" name="logooos_title_font_color" value="#777777" placeholder="#777777" />
			<div id="logooos_title_font_color_colorpicker" class="logooos_farbtastic"></div>
			<input type="button" id="logooos_title_font_color_btn" name="logooos_title_font_color_btn" value="View Color" class="button-primary" />
		</div>
		
		<div class="row list_option font_option">
			<label for="logooos_title_font_size">Title Font Size</label>
			<select id="logooos_title_font_size" name="logooos_title_font_size">
				<option value="9px" >9px</option>
				<option value="10px" >10px</option>
				<option value="11px" >11px</option>
				<option value="12px" >12px</option>
				<option value="13px" >13px</option>
				<option value="14px" >14px</option>
				<option value="15px" selected >15px</option>
				<option value="16px" >16px</option>
				<option value="17px" >17px</option>
				<option value="18px" >18px</option>
				<option value="19px" >19px</option>
				<option value="20px" >20px</option>
				<option value="21px" >21px</option>
				<option value="22px" >22px</option>
				<option value="23px" >23px</option>
				<option value="24px" >24px</option>
				<option value="25px" >25px</option>
				<option value="26px" >26px</option>
				<option value="27px" >27px</option>
				<option value="28px" >28px</option>
				<option value="29px" >29px</option>
				<option value="30px" >30px</option>
				<option value="31px" >31px</option>
				<option value="32px" >32px</option>
				<option value="33px" >33px</option>
				<option value="34px" >34px</option>
				<option value="35px" >35px</option>
				<option value="36px" >36px</option>
			</select>
			
		</div>
		
		<div class="row list_option font_option">
			<label for="logooos_title_font_weight">Title Font Weight</label>
			<select id="logooos_title_font_weight" name="logooos_title_font_weight">
				<option value="bold" >bold</option>
				<option value="normal" >normal</option>
			</select>
		</div>
		
		<div class="row list_option font_option">
			<label for="logooos_text_font_family">Text Font Family</label>
			<select id="logooos_text_font_family" name="logooos_text_font_family">
				<option value="" >current theme font</option>
				<option value="Georgia, serif" >Georgia</option>
				<option value="'Palatino Linotype', 'Book Antiqua', Palatino, serif" >Palatino Linotype</option>
				<option value="'Times New Roman', Times, serif" >Times New Roman</option>
				<option value="Arial, Helvetica, sans-serif" >Arial</option>
				<option value="'Arial Black', Gadget, sans-serif" >Arial Black</option>
				<option value="'Comic Sans MS', cursive, sans-serif" >Comic Sans MS</option>
				<option value="Impact, Charcoal, sans-serif" >Impact</option>
				<option value="'Lucida Sans Unicode', 'Lucida Grande', sans-serif" >Lucida Sans Unicode</option>
				<option value="Tahoma, Geneva, sans-serif" >Tahoma</option>
				<option value="'Trebuchet MS', Helvetica, sans-serif" >Trebuchet MS</option>
				<option value="Verdana, Geneva, sans-serif" >Verdana</option>
				<option value="'Courier New', Courier, monospace" >Courier New</option>
				<option value="'Lucida Console', Monaco, monospace" >Lucida Console</option>
			</select>
		</div>
		
		<div class="row list_option font_option">
			<label for="logooos_text_font_color">Text Font Color</label>
			<input type="text" id="logooos_text_font_color" name="logooos_text_font_color" value="#777777" placeholder="#777777" />
			<div id="logooos_text_font_color_colorpicker" class="logooos_farbtastic"></div>
			<input type="button" id="logooos_text_font_color_btn" name="logooos_text_font_color_btn" value="View Color" class="button-primary" />
		</div>
		
		<div class="row list_option font_option">
			<label for="logooos_text_font_size">Text Font Size</label>
			<select id="logooos_text_font_size" name="logooos_text_font_size">
				<option value="9px" >9px</option>
				<option value="10px" >10px</option>
				<option value="11px" >11px</option>
				<option value="12px" selected >12px</option>
				<option value="13px" >13px</option>
				<option value="14px" >14px</option>
				<option value="15px" >15px</option>
				<option value="16px" >16px</option>
				<option value="17px" >17px</option>
				<option value="18px" >18px</option>
				<option value="19px" >19px</option>
				<option value="20px" >20px</option>
				<option value="21px" >21px</option>
				<option value="22px" >22px</option>
				<option value="23px" >23px</option>
				<option value="24px" >24px</option>
				<option value="25px" >25px</option>
				<option value="26px" >26px</option>
				<option value="27px" >27px</option>
				<option value="28px" >28px</option>
				<option value="29px" >29px</option>
				<option value="30px" >30px</option>
				<option value="31px" >31px</option>
				<option value="32px" >32px</option>
				<option value="33px" >33px</option>
				<option value="34px" >34px</option>
				<option value="35px" >35px</option>
				<option value="36px" >36px</option>
			</select>
			
		</div>
		
		<div class="row list_option">
			<label for="logooos_moreLinkText">More Link Text</label>
			<input type="text" id="logooos_moreLinkText" name="logooos_moreLinkText" value="Read More" />
		</div>
		
		<div class="row list_option font_option">
			<label for="logooos_more_link_text_color">More Link Text Color</label>
			<input type="text" id="logooos_more_link_text_color" name="logooos_more_link_text_color" value="#999999" placeholder="#999999" />
			<div id="logooos_more_link_text_color_colorpicker" class="logooos_farbtastic"></div>
			<input type="button" id="logooos_more_link_text_color_btn" name="logooos_more_link_text_color_btn" value="View Color" class="button-primary" />
		</div>
		
		
		<div class="row list_option">
			<label for="logooos_list_border">List Border</label>
			<select id="logooos_list_border" name="logooos_list_border">
				<option value="enabled" selected>enabled</option>
				<option value="disabled">disabled</option>
			</select>
		</div>
		
		<div class="row list_option list_border_option">
			<label for="logooos_listBorderColor">List Border Color</label>
			<input type="text" id="logooos_listBorderColor" name="logooos_listBorderColor" value="#DCDCDC" />
			<div id="logooos_listBorderColor_colorpicker" class="logooos_farbtastic"></div>
			<input type="button" id="logooos_listBorderColor_btn" name="logooos_listBorderColor_btn" value="View Color" class="button-primary" />
		</div>
		
		<div class="row list_option list_border_option">
			<label for="logooos_list_border_style">List Border Style</label>
			<select id="logooos_list_border_style" name="logooos_list_border_style">
				<option value="dashed" selected>dashed</option>
				<option value="solid">solid</option>
			</select>
		</div>
		
		

		
	</div>
	
	<p id="logooos_noteParagraph">
		<strong>Note: </strong>copy the following shortcode in the yellow box to the page editor or post editor or logos widget to display the logos in the website.
	</p>
	
	<div id="logooos_div_shortcode">[logooos]</div>
	
	<div id="logooos_gene_short_preview">Loading ...</div>
