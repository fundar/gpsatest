(function($) {
	
	$(document).ready(function() {
		
		var isAjaxRunning = false,			
			pluginUrl = touchcarousel_ajax_vars.pluginurl,			
			saveText = touchcarousel_ajax_vars.saveText,
			createText = touchcarousel_ajax_vars.createText,			
			deleteDialogText = touchcarousel_ajax_vars.deleteDialogText,
			savingText = touchcarousel_ajax_vars.savingText,
			savedText = touchcarousel_ajax_vars.savedText,
			unsavedText = touchcarousel_ajax_vars.unsavedText,
			layoutText = touchcarousel_ajax_vars.layoutText,
			customLayoutText =  touchcarousel_ajax_vars.customLayoutText,
			autoText = touchcarousel_ajax_vars.autoText;
			emptyTaxonomiesText = touchcarousel_ajax_vars.emptyTaxonomiesText;
			
			
		/* MANAGE SLIDERS	*/
		// delete the slider
		

		var tableTc = $('table.touchcarousels-table');
		var deleteBtn = $('.delete-tcarousel-btn').click(function(e) {
			e.preventDefault();	
			if( confirm(deleteDialogText) ) {
				window.location = $(this).attr('data-protected-href');			
			} 
		});			
		if(tableTc && tableTc.length > 0) {			
			return;
		}		
		/* MANAGE SLIDERS END */
		
		//jQuery("#post-categories-select").dropdownchecklist({emptyText: autoText, width: 300});
		
		// layoutText is translated word "Layout"
		
		
					
		var itemsHtmlText = '';
		var iconUrl;
		$.each(layoutsArr, function(key, value){ 
			iconUrl = pluginUrl + 'img/'+value.id+'.png';
			itemsHtmlText += ('<li id="'+value.id+'"><span style="background: url(\'' +  iconUrl + '\')"  class="layout-image"></span><span class="layout-title">'+ value.label + '</span></li>');
		});
		itemsHtmlText += ('<li id="'+customLayoutCode.id+'"><span id="layout-custom-img" class="layout-image"></span><span class="layout-title">'+customLayoutCode.label+'</span></li>');
		
		$("#layout-type-group").append(itemsHtmlText);
		
		
		var layoutItems = $("#layout-type-group li");
		var selectedLayout;
		var layoutTextInput = $("#layout-text-input");
		var layoutCSSTextInput = $('#layout-css-text-input');
		var customLayoutItem = $("#"+customLayoutCode.id);
		var cssClassesInput = $("#carousel-css-classes");
		
		var numLayouts = layoutItems.length;
		
		
		var saveButton = $("#save-slider");
		var optionsContainer = $("#touchcarousel-options");
		var saveProgressButton = $('.touchcarousel-admin #save-progress');
		var isUnsaved = false;
		
	
		if(sliderSettings) {
			populate($('#touchcarousel-options'), jQuery.parseJSON(sliderSettings));		
		}
		
			
			
		function setLayoutValues(id) {
			if(id) {
				id = id.toLowerCase();
				$.each(layoutsArr, function(key, value) {
					if(id == value.id) {
						layoutTextInput.val(value.html);
						layoutCSSTextInput.val(value.css);
						cssClassesInput.val(value.id);
						return;
					}
				});
				
				
			} else {
				
				layoutTextInput.val(customLayoutCode.html);
				layoutCSSTextInput.val(customLayoutCode.css);
				cssClassesInput.val(customLayoutCode.cssClasses);
			}
			
		}
		
		
		
		if(!startLayoutName) {
			selectedLayout = layoutItems.eq(0).addClass('selected');
			setLayoutValues(selectedLayout.attr('id'));
			
		} else {
			selectedLayout = $('#layout-type-group li[id="'+startLayoutName+'"]').addClass('selected');
			if(startLayoutName.toLowerCase() == customLayoutCode.id) {
				customLayoutCode.html = layoutTextInput.val();
				customLayoutCode.css = layoutCSSTextInput.val();
				customLayoutCode.cssClasses = cssClassesInput.val();
			} else {
				setLayoutValues(selectedLayout.attr('id'));
			}
			
		}
	
		
		
		layoutItems.click(function(e) {		
			if(selectedLayout) {
				selectedLayout.removeClass('selected');
			}			
			unsaved();
			selectedLayout = $(e.currentTarget).addClass('selected');
			
			if(selectedLayout.attr('id') == customLayoutCode.id) {
				setLayoutValues(false);
				return;
			}
			setLayoutValues(selectedLayout.attr('id'));
		});
		
		
		function textChanged() {
			if(selectedLayout != customLayoutItem) {				
				selectedLayout.removeClass('selected');
				selectedLayout = customLayoutItem.addClass('selected');
			}			
			unsaved();
			customLayoutCode.html = layoutTextInput.val();
			customLayoutCode.css = layoutCSSTextInput.val();
			customLayoutCode.cssClasses = cssClassesInput.val();
		}
		
		layoutTextInput.bind('textchange', function (event, previousText) {
			textChanged();
			
		});
		layoutCSSTextInput.bind('textchange', function (event, previousText) {
			textChanged();
		});

		cssClassesInput.bind('textchange', function (event, previousText) {
			textChanged();
		});
		
		
		
		
		
		
		$("#view-vars-list").click(function(e) {
			e.preventDefault();
			showVariables();
		});
		
		
		
		
		
		
		
		
		if(saveButton) {
			if(!(sliderID >= 0)) {
				unsaved();
			}
			saveButton.click(function(e) {
				e.preventDefault();
				saveSlider();
			});
		}
		
		$('.touchcarousel-admin #title').bind('textchange', function() {
			unsaved();
		});
		
		
		$('#sortable-slides-boxes').bind('sortupdate', function() {
			unsaved();
		});
		
		var tooltipDefault = {
			content: {
				attr: 'data-help'
			},
			position: {
				at: 'center left', 
				my: 'center right'
			},
			style: {
				classes: 'ui-tooltip-rounded ui-tooltip-shadow ui-tooltip-tipsy rs-tooltip'
			}
		};
		
		
		
		
		
		
		optionsContainer.find('label').each( function( ) {			
			var help = $(this).attr( 'data-help' );
			if ( help != undefined && help != '' ) {
				$(this).qtip(tooltipDefault);
            }
		});
		
		var lGroup;
		var lField;
		optionsContainer.find(".group-leader input[type=checkbox]").each(function() {
			$(this).click(function() {	
				lGroup = $(this).closest('.fields-group');
				lField = $(this);
			
				if(lField.is(':checked')) {
					lGroup.find(".field-row:not(.group-leader)").removeClass('rs-hidden-controls');	
				} else {
					lGroup.find(".field-row:not(.group-leader)").addClass('rs-hidden-controls');	
				}				
			}).triggerHandler('click');
		});			
		
		updateTaxonomies();
		$('#post-types-select').bind('change', function() {
			updateTaxonomies();
		});
		
		$('input').bind('click', function(e) {
			unsaved();
		
		});
		$('select').bind('change', function(e) {
			unsaved();
		});
		
		
		
		
		function getOptionFromMainSidebar(optionId) {			
			var obj = optionsContainer.find('#' + optionId);
			if(obj.is(':checkbox')) {
				return obj.is(':checked');
			} else {
				return obj.val();
			}			
		}
		
		
		function generateSliderJSOptions() {
			
			var opts = form2object('touchcarousel-options');	
            return opts;
		}
		
		
		function saveSlider() {
			
			if (!isAjaxRunning) {
				isAjaxRunning = true;
			
				/*var sHTMLStr = generateSliderHTML();
				sHTMLStr = $('<div>').append(sHTMLStr.clone()).remove().html();*/
				var sliderSkin = getOptionFromMainSidebar('skin');
				
				var jsonOpts = JSON.stringify(generateSliderJSOptions());
				
				var preloadSkin = getOptionFromMainSidebar('preload-skin');
				preloadSkin = preloadSkin ? 1 : 0;
				
				saveProgressButton.removeClass('ajax-saved').html('');			
			
				saveButton.html(savingText);	
				
				
				var post_categories = $("#post-categories-select :selected");
				var opt_parent;
				var taxonomies_obj = {};
				var insert_index = 0;
				$.each(post_categories, function(index, value) {
					opt_parent = $(value).parent().attr('id');

					if(!taxonomies_obj[opt_parent]) {
						taxonomies_obj[opt_parent] = [];
					}
					taxonomies_obj[opt_parent].push($(value).attr('value'));
					
				});

				$.ajax({
					url: touchcarousel_ajax_vars.ajaxurl,
					type: 'post',
					data: {
						action : 'touchcarouselSave',
						
						id : sliderID,
						name : $('.touchcarousel-admin #title').val(),
						
						skin : sliderSkin,
						preload_skin : preloadSkin,
						
						width : $('#carousel-width').val(),
						height: $('#carousel-height').val(),
						
						max_posts : $('#max-posts-input').val(),
						post_type : $('#post-types-select').val(),
						post_categories : JSON.stringify(taxonomies_obj),
						post_orderby : $('.radio-buttons input[name=post-order-radio]:checked').val(),
												
						layout_name : $('#layout-type-group li.selected').attr('id'),
						layout_code : $('#layout-text-input').val(),
						layout_css : $('#layout-css-text-input').val(),
							  			
												
						js_settings : jsonOpts,
						
						post_relation : $('#post-taxonomy-relation').val(),
						
						css_classes : cssClassesInput.val(),
						
						
						
						touchcarousel_ajax_nonce : touchcarousel_ajax_vars.touchcarousel_ajax_nonce
					},
					complete: function(data) {	
										
						if(!(sliderID >= 0)) {
							if(parseInt(data.responseText, 10) > -1) {
								sliderID = parseInt(data.responseText, 10);							
							} 
							window.location.href = (touchcarousel_ajax_vars.admin_edit_url + sliderID);
							
						} else {
							if(parseInt(data.responseText, 10) > -1) {
								sliderID = parseInt(data.responseText, 10);							
							}
						}
						
						saveButton.html(saveText);
						isUnsaved = false;
						saveProgressButton.html(savedText).addClass('ajax-saved').removeClass('unsaved');						
						
						isAjaxRunning = false;
					},
				    error: function(jqXHR, textStatus, errorThrown) { isAjaxRunning = false; alert(textStatus); alert(errorThrown); }
				});
			}
		}
		 
		function updateTaxonomies() {
			if (!isAjaxRunning) {
				isAjaxRunning = true;
				
				$('#post-types-select').attr('disabled', 'disabled');
				$("#post-categories-select").dropdownchecklist('disable');

				
				$.ajax({
					url: touchcarousel_ajax_vars.ajaxurl,
					type: 'post',
					data: {
						action : 'touchcarouselUpdateTaxonomies',
						id : sliderID,
						post_type : $('#post-types-select').val(),
						
						touchcarousel_ajax_nonce : touchcarousel_ajax_vars.touchcarousel_ajax_nonce
					},
					complete: function(data) {		
					
						$('#post-types-select').removeAttr('disabled');		

						$("#post-categories-select").dropdownchecklist("destroy");
						$("#post-categories-select").empty();
						var newData = data.responseText;
						if(newData) {
							$("#post-categories-select").html(newData);
							$("#post-categories-select").dropdownchecklist({emptyText: autoText, width: 300, onItemClick:function() { unsaved(); }});
							$("#post-taxonomy-relation").show();
						} else {
							$("#post-categories-select").dropdownchecklist({emptyText: emptyTaxonomiesText, width: 300, onItemClick:function() { unsaved(); }});
							$("#post-categories-select").dropdownchecklist('disable');
							$("#post-taxonomy-relation").hide();
						}
						
						
						isAjaxRunning = false;
					},
				    error: function(jqXHR, textStatus, errorThrown) { isAjaxRunning = false; alert(textStatus); alert(errorThrown); }
				});
				
			}
		
		}
		
		function showVariables() {
			if (!isAjaxRunning) {
				isAjaxRunning = true;
				
				$.ajax({
					url: touchcarousel_ajax_vars.ajaxurl,
					type: 'post',
					data: {
						action : 'touchcarouselShowVariables',
						id : sliderID,
						touchcarousel_ajax_nonce : touchcarousel_ajax_vars.touchcarousel_ajax_nonce
					},
					complete: function(data) {	
						$.colorbox({
							html:data.responseText,
							initialHeight: 400,
							width: "70%"
						});
						isAjaxRunning = false;
					},
				    error: function(jqXHR, textStatus, errorThrown) { isAjaxRunning = false; alert(textStatus); alert(errorThrown); }
				});
			}
		}
		
		
		
		
		function unsaved() {
			if(!isUnsaved) {
				saveProgressButton.addClass('unsaved');
				saveProgressButton.html(unsavedText);
				isUnsaved = true;
			}			
		}
		
		function parseSliderOptionsNode() {
			var sliderSettingsNode = $('#touchcarousel-settings-data');	
			
			if(sliderSettingsNode && sliderSettingsNode.length > 0 ) {					
				populate(optionsContainer, jQuery.parseJSON(sliderSettingsNode.text()));				
				sliderSettingsNode.remove();
			}
		}
		function populate(frm, data) {
			var input;
			$.each(data, function(key, value){
				input =  $('[name='+key+']', frm);
				if(input.is(':checkbox')) {
					input.attr('checked', value);
				} else {
					input.val(value);
				}
			});
		}
		function getURLParameter(name, url) {
		    return decodeURI(
		        (RegExp(name + '=' + '(.+?)(&|$)').exec(url)||[,null])[1]
		    );
		}
	});
})(jQuery);

