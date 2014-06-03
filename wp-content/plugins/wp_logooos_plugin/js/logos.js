(function($){
$(document).ready(function(){
	
	/*======================== Logos ========================*/
	
		logooos = $('.logooos');
		logooos_items = $('.logooos .logooos_item');
		logooos_withTooltip = $('.logooos.logooos_withtooltip');
		logooos_sliders = $('.logooos.logooos_slider');
		logooos_links = $('.logooos .logooos_item a');
		logooos_images = $('.logooos .logooos_item img');
		logooos_item_height_percentage= 0.66;
		
		if (logooos.length )
		{
			// IE 8
			
			if ( $.browser.msie && $.browser.version <= 8 ) {
				logooos_links.css('background-image','none');	
				logooos_images.css('display','inline-block');
			}
			
			logooos.each(function(){
				
				logooos_calculateItemsWidthAndHight($(this));
				
			});
			
			if (logooos_sliders.length )
			{
				logooos_sliders.each(function(){	
					logooos_runSlider($(this));		
				});
			}

			
			$(window).resize(function() {

					logooos.each(function(){
						
						logooos_calculateItemsWidthAndHight($(this));
						
					});
					
					if (logooos_sliders.length )
					{
						setTimeout(function(){
							logooos_sliders.each(function(){
								logooos_runSlider($(this));
							});
						},500);
					}

			});
			
			
			// Hover Effects
			
			logooos_items.mouseenter(function(){
				
				if($(this).parent().data('hovereffect')=='effect1') {
					
					$(this).css('box-shadow', '0px 0px 10px 2px '+$(this).parent().data('hovereffectcolor'));
					
				}
				else if($(this).parent().data('hovereffect')=='effect2') {
					
					$(this).children('a').children('.logooos_effectspan').css('box-shadow', 'inset 0px 0px '+$(this).width()/10+'px 3px '+$(this).parent().data('hovereffectcolor'));
					
				}
				else if($(this).parent().data('hovereffect')=='effect3') {
					$(this).css('border-color', $(this).parent().data('hovereffectcolor'));
				}
				else if($(this).parent().data('hovereffect')=='effect4') {
					
					$(this).parent().children('.logooos_item').stop().animate({opacity: 0.3},300);
					
					if($(this).parent().hasClass('logooos_list')) {
						$(this).parent().children('.logooos_textcontainer').stop().animate({opacity: 0.3},300);
						$(this).next().stop().animate({opacity: 1},300);
					}
					
					$(this).stop().animate({opacity: 1},300);
				}
				
			});
			
			logooos_items.mouseleave(function(){
				if($(this).parent().data('hovereffect')=='effect1') {
					$(this).css('box-shadow', '');
				}
				else if($(this).parent().data('hovereffect')=='effect2') {
					$(this).children('a').children('.logooos_effectspan').css('box-shadow', '');
				}
				else if($(this).parent().data('hovereffect')=='effect3') {
					$(this).css('border-color', $(this).parent().data('bordercolor'));
				}
				else if($(this).parent().data('hovereffect')=='effect4') {
					$(this).parent().children('.logooos_item').stop().animate({opacity: 1},300);
					
					if($(this).parent().hasClass('logooos_list')) {
						$(this).parent().children('.logooos_textcontainer').stop().animate({opacity: 1},300);
					}
				}

			});
			
			// Tooltip
			
			logooos_withTooltip.children('.logooos_item').mouseenter(function(){
				
				tooltips=$('.logooos_tooltip');
				if(tooltips.length) {
					$('.logooos_tooltip').remove();
				}
				
				if($(this).data('title')!='') 
				{
					tooltip=$('<div class="logooos_tooltip"><span class="logooos_tooltipText">'+$(this).data('title')+'<span class="logooos_tooltipArrow"></span></span></div>');
					tooltip.appendTo('body');
						
					tooltip.css('opacity',0);
						
					arrowBgPosition='';
						
					// Left
					if($(this).offset().left + $(this).width()/2 - tooltip.width()/2 < 0) {
						tooltip.css('left', 1 );
						arrowBgPosition = $(this).offset().left + $(this).width()/2 - 11 +'px';
					}
					else if($(this).offset().left + $(this).width()/2 - tooltip.width()/2 +tooltip.width() > $(window).width()) {
						tooltip.css('right', 1 );
						arrowBgPosition = $(this).offset().left - tooltip.offset().left + $(this).width()/2 - 11 +'px';
					}
					else {
						tooltip.css('left', $(this).offset().left + $(this).width()/2 - tooltip.width()/2 );
						arrowBgPosition='center';
					}
						
					// Top
					if($(window).scrollTop() > $(this).offset().top - tooltip.height()) {
						tooltip.css('top', $(this).offset().top + $(this).height()+13);
						arrowBgPosition+=' top';
						tooltip.find('.logooos_tooltipArrow').css({'background-position': arrowBgPosition, 'bottom': '100%'});
					}
					else {
						tooltip.css('top', $(this).offset().top - tooltip.height()+9);
						arrowBgPosition+=' bottom';
						tooltip.find('.logooos_tooltipArrow').css({'background-position': arrowBgPosition, 'top': '100%'});
					}
						
					// Show
					if( $(this).offset().left < $(this).parent().parent().offset().left + $(this).parent().parent().width()) {
						tooltip.animate({opacity:1,top:'-=10px'},'slow');
					}
				}
					
			});
				
			// Remove Tooltip
			logooos_withTooltip.children('.logooos_item').mouseleave(function(){
				tooltips=$('.logooos_tooltip');
				if(tooltips.length) {
					$('.logooos_tooltip').remove();
				}
			});
			
			
		}

});

function logooos_calculateItemsWidthAndHight(list) {

	logooos_itemBorderLeftRight = parseInt(list.children('.logooos_item').css('borderLeftWidth').replace('px', ''))+parseInt(list.children('.logooos_item').css('borderRightWidth').replace('px', ''));
		
	if(list.hasClass('logooos_grid') || list.hasClass('logooos_slider')) {
					
		if(list.data('marginBetweenItems')!='') {
			list.children('.logooos_item').css('margin',parseFloat(list.data('marginbetweenitems'))/2);
		}
							
		logooos_itemMarginLeftRight = parseFloat(list.children('.logooos_item').css('marginLeft').replace('px', ''))+parseFloat(list.children('.logooos_item').css('marginRight').replace('px', ''));
					
					
		if( $(window).width() >= 1024 || !list.hasClass('logooos_responsive') ) {
			list.children('.logooos_item').width(Math.floor(list.width()/list.data('columns'))-(logooos_itemMarginLeftRight+logooos_itemBorderLeftRight) );
		}
		else if( $(window).width() < 1024 && $(window).width() >= 481) {
			windowHeight = $(window).height();
			windowWidth = $(window).width();
						
			if(windowHeight < windowWidth && list.data('columns') > 4) {
				list.children('.logooos_item').width(Math.floor(list.width()/4)-(logooos_itemMarginLeftRight+logooos_itemBorderLeftRight) );
			}
			else if(windowHeight > windowWidth && list.data('columns') > 3) {
				list.children('.logooos_item').width(Math.floor(list.width()/3)-(logooos_itemMarginLeftRight+logooos_itemBorderLeftRight) );
			}
			else {
				list.children('.logooos_item').width(Math.floor(list.width()/list.data('columns'))-(logooos_itemMarginLeftRight+logooos_itemBorderLeftRight) );
			}
		}
		else if( $(window).width() < 481 && list.data('columns') > 2 ) {
			list.children('.logooos_item').width(Math.floor(list.width()/2)-(logooos_itemMarginLeftRight+logooos_itemBorderLeftRight) );
		}
		else {
			list.children('.logooos_item').width(Math.floor(list.width()/list.data('columns'))-(logooos_itemMarginLeftRight+logooos_itemBorderLeftRight) );
		}
					
					
					
	}
	else if(list.hasClass('logooos_list')) {
						
		if( list.parent().width() < 481 && list.hasClass('logooos_responsive') ) {
			list.children('.logooos_item').width(Math.floor(list.width())-logooos_itemBorderLeftRight ).css({'marginBottom':20, 'float':'none'});
			list.children('.logooos_textcontainer').css('min-height',0);
			list.children('.logooos_textcontainer').children('.logooos_text, .logooos_title').css({'marginLeft':0});
		}
		else {
			list.children('.logooos_item').width(180).css({'marginBottom':0, 'float':'left'})
			list.children('.logooos_textcontainer').css('min-height',120);
			list.children('.logooos_textcontainer').children('.logooos_text, .logooos_title').css({'marginLeft':210});
		}
							
	}
				
	list.children('.logooos_item').height(parseInt(list.children('.logooos_item').width()*logooos_item_height_percentage));
	
	list.children('.logooos_item').css('display','inline-block');
}

function logooos_runSlider(slider) {
	
	
			min=slider.data('columns');
			max=slider.data('columns');

			if( $(window).width() <= 480 ) {
				min=1;
				max=1;
			}
			else if($(window).width() > 480 &&  $(window).width() < 600 && slider.data('columns') > 3 ) {		
				min=3;
				max=3;
			}
			else if($(window).width() > 600 &&  $(window).width() < 1024 && slider.data('columns') > 4 ) {
				min=4;
				max=4;
			}
				
			
			slider.carouFredSel({
				responsive: true,
				width:'100%',
				prev: {
					button: function() {
						$(this).parent().append('<a class="logooos_prev '+$(this).data('buttonsarrowscolor')+'" style="background-color:'+$(this).data('buttonsbgcolor')+';border-color:'+$(this).data('buttonsbordercolor')+';" href="#"></a>');
						return $(this).parents().children(".logooos_prev");
					}
				},
				next: {
					button: function() {
						$(this).parent().append('<a class="logooos_next '+$(this).data('buttonsarrowscolor')+'" style="background-color:'+$(this).data('buttonsbgcolor')+';border-color:'+$(this).data('buttonsbordercolor')+';" href="#"></a>');
						return $(this).parents().children(".logooos_next");
					}
				},
				scroll: {
					items:function(num) {
						if(num==1) {
							return 1;
						}
						else if(num>=2 && num<=5) {
							return 2;
						}
						else if(num>=6 && num<=7) {
							return 3;
						}
						else if(num>=8 && num<=9) {
							return 4;
						}
						else if(num>=10) {
							return 5;
						}
					},
					easing:'quadratic',
					duration: slider.data('scrollduration')
				},
				items: {
					width: 200,
					visible: {
						min: min,
						max: max
					}
				},
				auto: {
					play: slider.data('autoplay'),
					timeoutDuration: slider.data('pauseduration'),
					pauseOnHover: true
				}
			});
			
			if( $(window).width() > 1024) {
				slider.parents('.caroufredsel_wrapper').mouseenter(function(){
					$(this).children(".logooos_prev").fadeIn('slow');
					$(this).children(".logooos_next").fadeIn('slow');
				});
				
				slider.parents('.caroufredsel_wrapper').mouseleave(function(){
					$(this).children(".logooos_prev").fadeOut('slow');
					$(this).children(".logooos_next").fadeOut('slow');
				});
			}
			
			logooos_itemMarginTopBottom = parseFloat(slider.children('.logooos_item').css('marginLeft').replace('px', ''))+parseFloat(slider.children('.logooos_item').css('marginRight').replace('px', ''));
			logooos_itemBorderTopBottom = parseInt(slider.children('.logooos_item').css('borderLeftWidth').replace('px', ''))+parseInt(slider.children('.logooos_item').css('borderRightWidth').replace('px', ''));
			
			slider.children('.logooos_item').height(parseInt(slider.children('.logooos_item').width()*logooos_item_height_percentage));
			
			if(logooos_itemBorderTopBottom >= 1) {
				slider.parent().height(parseInt(slider.children('.logooos_item').width()*logooos_item_height_percentage + logooos_itemMarginTopBottom + logooos_itemBorderTopBottom +1));
			}
			else {
				slider.parent().height(parseInt(slider.children('.logooos_item').width()*logooos_item_height_percentage + logooos_itemMarginTopBottom + logooos_itemBorderTopBottom ));
			}
			
			slider.height(parseInt(slider.children('.logooos_item').height()+ logooos_itemMarginTopBottom + logooos_itemBorderTopBottom));
			
			if(logooos_itemBorderTopBottom >= 1) {
				slider.parent().height(parseInt(slider.children('.logooos_item').height()+ logooos_itemMarginTopBottom + logooos_itemBorderTopBottom +1));
				slider.parent().width(slider.parent().width()+1);
			}
			else {
				slider.parent().height(parseInt(slider.children('.logooos_item').height()+ logooos_itemMarginTopBottom + logooos_itemBorderTopBottom ));
				slider.parent().width(slider.parent().width());
			}
					
			logooos_prev=slider.parents().children(".logooos_prev");
			logooos_prev.css('top',slider.parents().height()/2 - logooos_prev.height()/2 );
			logooos_prev.css('display','none');
						
			logooos_next=slider.parents().children(".logooos_next");
			logooos_next.css('top',slider.parents().height()/2 - logooos_next.height()/2 );
			logooos_next.css('display','none');
			
}

})(jQuery);