(function($){
$(document).ready(function(){
	
	// inputs
	
	logooos_categoriesList = $('#logooos_categoriesList');
	logooos_columnsNumberList = $('#logooos_columnsNumberList');
	logooos_layout = $('#logooos_layout');
	logooos_tooltipList = $('#logooos_tooltipList');
	logooos_responsiveList = $('#logooos_responsiveList');
	logooos_grayscaleList = $('#logooos_grayscaleList');
	logooos_orderByList = $('#logooos_orderByList');
	logooos_orderList = $('#logooos_orderList');
	logooos_bgColorInput = $('#logooos_bgColorInput');
	logooos_NumberInput = $('#logooos_NumberInput');
	
	logooos_marginBetweenItems = $('#logooos_marginBetweenItems');
	logooos_border = $('#logooos_border');
	logooos_borderColor = $('#logooos_borderColor');
	logooos_borderRadius = $('#logooos_borderRadius');
	
	
	logooos_autoplay = $('#logooos_autoplay');
	logooos_scrollduration = $('#logooos_scrollduration');
	logooos_pauseduration = $('#logooos_pauseduration');
	logooos_buttonsbordercolor = $('#logooos_buttonsbordercolor');
	logooos_buttonsbgcolor = $('#logooos_buttonsbgcolor');
	logooos_buttonsarrowscolor = $('#logooos_buttonsarrowscolor');
	
	logooos_hovereffect = $('#logooos_hovereffect');
	logooos_hoverEffectColor = $('#logooos_hoverEffectColor');
	
	
	
	
	
	logooos_font_style = $('#logooos_font_style');
	logooos_title_font_family = $('#logooos_title_font_family');
	logooos_title_font_color = $('#logooos_title_font_color');
	logooos_title_font_size = $('#logooos_title_font_size');
	logooos_title_font_weight = $('#logooos_title_font_weight');
	logooos_text_font_family = $('#logooos_text_font_family');
	logooos_text_font_color = $('#logooos_text_font_color');
	logooos_text_font_size = $('#logooos_text_font_size');
	logooos_list_border = $('#logooos_list_border');
	logooos_listBorderColor = $('#logooos_listBorderColor');
	logooos_list_border_style = $('#logooos_list_border_style');
	logooos_moreLinkText = $('#logooos_moreLinkText');
	logooos_more_link_text_color = $('#logooos_more_link_text_color');
	
	
	
	
	logooos_controls = $('input,select');
	logooos_buttons = $('.button-primary');
	
	// options 
	
	slider_options = $('.slider_option');
	grid_options = $('.grid_option');
	list_options = $('.list_option');
	border_options = $('.border_option');
	hovereffect_options = $('.hovereffect_option');
	font_options = $('.font_option');
	list_border_options = $('.list_border_option');
	
	if(logooos_layout.val() == 'slider') {
		grid_options.slideUp();
		list_options.slideUp();
		slider_options.slideDown();
	}
	else if(logooos_layout.val() == 'grid') {
		slider_options.slideUp();
		list_options.slideUp();
		grid_options.slideDown();
	}
	else if(logooos_layout.val() == 'list') {
		slider_options.slideUp();
		grid_options.slideUp();
		list_options.slideDown();
	}
		
	if(logooos_border.val() == 'disabled') {
		border_options.slideUp();
	}
	
	if(logooos_font_style.val() == 'default') {
		font_options.slideUp();
	}
	
	if(logooos_list_border.val() == 'disabled') {
		list_border_options.slideUp();
	}
	
	if(logooos_hovereffect.val() == '' || logooos_hovereffect.val() == 'effect4') {
		hovereffect_options.slideUp();
	}
	
	logooos_layout.change(function(){
	
		if(logooos_layout.val() == 'slider') {
			grid_options.slideUp();
			list_options.slideUp();
			slider_options.slideDown();
		}
		else if(logooos_layout.val() == 'grid') {
			slider_options.slideUp();
			list_options.slideUp();
			grid_options.slideDown();
		}
		else if(logooos_layout.val() == 'list') {
			slider_options.slideUp();
			grid_options.slideUp();
			list_options.slideDown();
			
			if(logooos_font_style.val() == 'custom') {
				font_options.slideDown();
			}
			else if(logooos_font_style.val() == 'default') {
				font_options.slideUp();
			}
			
			if(logooos_list_border.val() == 'enabled') {
				list_border_options.slideDown();
			}
			else if(logooos_list_border.val() == 'disabled') {
				list_border_options.slideUp();
			}
		}
		
	});
	
	logooos_border.change(function(){
	
		if(logooos_border.val() == 'enabled') {
			border_options.slideDown();
		}
		else if(logooos_border.val() == 'disabled') {
			border_options.slideUp();
		}
		
	});
	
	logooos_font_style.change(function(){
	
		if(logooos_font_style.val() == 'custom') {
			font_options.slideDown();
		}
		else if(logooos_font_style.val() == 'default') {
			font_options.slideUp();
		}
		
	});
	
	logooos_list_border.change(function(){
	
		if(logooos_list_border.val() == 'enabled') {
			list_border_options.slideDown();
		}
		else if(logooos_list_border.val() == 'disabled') {
			list_border_options.slideUp();
		}
		
	});
	
	logooos_hovereffect.change(function(){
	
		if(logooos_hovereffect.val() == '' || logooos_hovereffect.val() == 'effect4') {
			hovereffect_options.slideUp();
		}
		else {
			hovereffect_options.slideDown();
		}
		
	});
	
	// containers
	
	logooos_div_shortcode = $('#logooos_div_shortcode');
	
	logooos_gene_short_preview = $('#logooos_gene_short_preview');
	
	logooos_generate_shortcode();
	
	
	logooos_controls.change(function(){
		logooos_generate_shortcode();
	});
	
	logooos_buttons.click(function(){
		logooos_generate_shortcode();
	});

});

function logooos_generate_shortcode() {
	
	var postarray = {};
	var shortcode='[logooos ';
	
	if( logooos_layout.val()!='list' ) {
		postarray['columns'] = logooos_columnsNumberList.val();
		shortcode+='columns="'+logooos_columnsNumberList.val()+'" ';
	}
	
	if( logooos_bgColorInput.val()!='' ) {
		postarray['backgroundcolor'] = logooos_bgColorInput.val();
		shortcode+='backgroundcolor="'+logooos_bgColorInput.val()+'" ';
	}
	
	postarray['layout'] = logooos_layout.val();
	shortcode+='layout="'+ logooos_layout.val() +'" ';
	
	if( logooos_NumberInput.val()!='' ) {
		postarray['num'] = logooos_NumberInput.val();
		shortcode+='num="'+logooos_NumberInput.val()+'" ';
	}
	
	postarray['category'] = logooos_categoriesList.val();
	shortcode+='category="'+logooos_categoriesList.val()+'" ';
	
	postarray['orderby'] = logooos_orderByList.val();
	shortcode+='orderby="'+logooos_orderByList.val()+'" ';
	
	postarray['order'] = logooos_orderList.val();
	shortcode+='order="'+logooos_orderList.val()+'" ';
	
	if( logooos_layout.val()!='list' ) {
		if( logooos_marginBetweenItems.val()!='' ) {
			postarray['marginbetweenitems'] = logooos_marginBetweenItems.val();
			shortcode+='marginbetweenitems="'+logooos_marginBetweenItems.val()+'" ';
		}
	}
	
	if( logooos_layout.val()!='list' ) {
		postarray['tooltip'] = logooos_tooltipList.val();
		shortcode+='tooltip="'+ logooos_tooltipList.val() +'" ';
	}
	
	postarray['responsive'] = logooos_responsiveList.val();
	shortcode+='responsive="'+ logooos_responsiveList.val() +'" ';
	
	
	postarray['grayscale'] = logooos_grayscaleList.val();
	shortcode+='grayscale="'+ logooos_grayscaleList.val() +'" ';
	
	postarray['border'] = logooos_border.val();
	shortcode+='border="'+ logooos_border.val() +'" ';
	
	if( logooos_border.val()=='enabled' ) {
		
		if( logooos_borderColor.val()!='list' ) {
			postarray['bordercolor'] = logooos_borderColor.val();
			shortcode+='bordercolor="'+ logooos_borderColor.val() +'" ';
		}
	}
	
	postarray['borderradius'] = logooos_borderRadius.val();
	shortcode+='borderradius="'+ logooos_borderRadius.val() +'" ';
	
		
	if( logooos_layout.val()=='slider' ) {
	
		postarray['autoplay'] = logooos_autoplay.val();
		shortcode+='autoplay="'+logooos_autoplay.val()+'" ';
		
		postarray['scrollduration'] = logooos_scrollduration.val();
		shortcode+='scrollduration="'+logooos_scrollduration.val()+'" ';
		
		postarray['pauseduration'] = logooos_pauseduration.val();
		shortcode+='pauseduration="'+logooos_pauseduration.val()+'" ';
		
		if( logooos_buttonsbordercolor.val()!='' ) {
			postarray['buttonsbordercolor'] = logooos_buttonsbordercolor.val();
			shortcode+='buttonsbordercolor="'+ logooos_buttonsbordercolor.val() +'" ';
		}
		
		if( logooos_buttonsbgcolor.val()!='' ) {
			postarray['buttonsbgcolor'] = logooos_buttonsbgcolor.val();
			shortcode+='buttonsbgcolor="'+ logooos_buttonsbgcolor.val() +'" ';
		}
		
		postarray['buttonsarrowscolor'] = logooos_buttonsarrowscolor.val();
		shortcode+='buttonsarrowscolor="'+logooos_buttonsarrowscolor.val()+'" ';
		
	}
	else if( logooos_layout.val()=='list' ) {
	
		if(logooos_font_style.val() == 'custom') {
			
			if( logooos_title_font_family.val()!='' ) {
				postarray['titlefontfamily'] = logooos_title_font_family.val();
				shortcode+='titlefontfamily="'+logooos_title_font_family.val()+'" ';
			}
			
			if( logooos_title_font_color.val()!='' ) {
				postarray['titlefontcolor'] = logooos_title_font_color.val();
				shortcode+='titlefontcolor="'+logooos_title_font_color.val()+'" ';
			}
			
			postarray['titlefontsize'] = logooos_title_font_size.val();
			shortcode+='titlefontsize="'+logooos_title_font_size.val()+'" ';
			
			postarray['titlefontweight'] = logooos_title_font_weight.val();
			shortcode+='titlefontweight="'+logooos_title_font_weight.val()+'" ';
			
			if( logooos_text_font_family.val()!='' ) {
				postarray['textfontfamily'] = logooos_text_font_family.val();
				shortcode+='textfontfamily="'+logooos_text_font_family.val()+'" ';
			}
			
			if( logooos_text_font_color.val()!='' ) {
				postarray['textfontcolor'] = logooos_text_font_color.val();
				shortcode+='textfontcolor="'+logooos_text_font_color.val()+'" ';
			}
			
			postarray['textfontsize'] = logooos_text_font_size.val();
			shortcode+='textfontsize="'+logooos_text_font_size.val()+'" ';
			
			
			if( logooos_more_link_text_color.val()!='' ) {
				postarray['morelinktextcolor'] = logooos_more_link_text_color.val();
				shortcode+='morelinktextcolor="'+logooos_more_link_text_color.val()+'" ';
			}
			
		}
		
		
		postarray['listborder'] = logooos_list_border.val();
		shortcode+='listborder="'+logooos_list_border.val()+'" ';
			
		if(logooos_list_border.val() == 'enabled') {
			
			if( logooos_listBorderColor.val()!='' ) {
				postarray['listbordercolor'] = logooos_listBorderColor.val();
				shortcode+='listbordercolor="'+logooos_listBorderColor.val()+'" ';
			}
			
			postarray['listborderstyle'] = logooos_list_border_style.val();
			shortcode+='listborderstyle="'+logooos_list_border_style.val()+'" ';
			
		}
		
		
		if( logooos_moreLinkText.val()!='' ) {
			postarray['morelinktext'] = logooos_moreLinkText.val();
			shortcode+='morelinktext="'+logooos_moreLinkText.val()+'" ';
		}
		
		
		
	}
	
	
	
	
	if( logooos_hovereffect.val()!='' ) {
		
		postarray['hovereffect'] = logooos_hovereffect.val();
		shortcode+='hovereffect="'+ logooos_hovereffect.val() +'" ';
	
		if( logooos_hoverEffectColor.val()!='' && logooos_hovereffect.val() != '' && logooos_hovereffect.val() != 'effect4' ) {
			postarray['hovereffectcolor'] = logooos_hoverEffectColor.val();
			shortcode+='hovereffectcolor="'+logooos_hoverEffectColor.val()+'" ';
		}
		
	}
	
	shortcode+=']';
	
	logooos_div_shortcode.html(shortcode);
	
	logooos_gene_short_preview.html('<p>Loading ...</p>');
	
	logooos_gene_short_preview.load('../wp-content/plugins/wp_logooos_plugin/inc/generate_shortcode/do_shortcode.php', postarray , function(){
		
		$.getScript('../wp-content/plugins/wp_logooos_plugin/js/logos.js');
		
		if( typeof jQuery.wp === 'object' && typeof jQuery.wp.wpColorPicker === 'function' ){

			jQuery( '#logooos_hoverEffectColor' ).wpColorPicker();
			jQuery( '#logooos_borderColor' ).wpColorPicker();
			jQuery( '#logooos_bgColorInput' ).wpColorPicker();
			jQuery( '#logooos_buttonsbordercolor' ).wpColorPicker();
			jQuery( '#logooos_buttonsbgcolor' ).wpColorPicker();
			jQuery( '#logooos_title_font_color' ).wpColorPicker();
			jQuery( '#logooos_text_font_color' ).wpColorPicker();
			jQuery( '#logooos_listBorderColor' ).wpColorPicker();
			jQuery( '#logooos_more_link_text_color' ).wpColorPicker();

		}
		else {
			//We use farbtastic if the WordPress color picker widget doesn't exist
			jQuery('#logooos_hoverEffectColor_colorpicker').farbtastic('#logooos_hoverEffectColor');
			jQuery('#logooos_borderColor_colorpicker').farbtastic('#logooos_borderColor');
			jQuery('#logooos_bgColorInput_colorpicker').farbtastic('#logooos_bgColorInput');
			jQuery('#logooos_buttonsbordercolor_colorpicker').farbtastic('#logooos_buttonsbordercolor');
			jQuery('#logooos_buttonsbgcolor_colorpicker').farbtastic('#logooos_buttonsbgcolor');
			jQuery('#logooos_title_font_color_colorpicker').farbtastic('#logooos_title_font_color');
			jQuery('#logooos_text_font_color_colorpicker').farbtastic('#logooos_text_font_color');
			jQuery('#logooos_listBorderColor_colorpicker').farbtastic('#logooos_listBorderColor');
			jQuery('#logooos_more_link_text_color_colorpicker').farbtastic('#logooos_more_link_text_color');
			
			logooos_farbtastic_inputs = $('#logooos_hoverEffectColor,#logooos_borderColor,#logooos_bgColorInput,#logooos_buttonsbordercolor,#logooos_buttonsbgcolor,#logooos_title_font_color,#logooos_text_font_color,#logooos_listBorderColor,#logooos_more_link_text_color');
			
			logooos_farbtastic_inputs.focus(function(){
				$(this).parent().children('.logooos_farbtastic').slideDown();
			});
			
			logooos_farbtastic_inputs.focusout(function(){
				$(this).parent().children('.logooos_farbtastic').slideUp();
			});
		}
		
	
	});
	
	
}

})(jQuery);