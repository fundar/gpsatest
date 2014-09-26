<?php require_once(NB_DIR . '/functions.php'); ?>
<style type="text/css">
#poststuff {overflow: visible !important;}
</style>

<div class="wrap lcwp_form">  
	<div class="icon32"><img src="<?php echo NB_URL.'/img/nb_logo.png'; ?>" alt="newsbox" /><br/></div>
    <?php echo '<h2 class="lcwp_page_title" style="border: none;">News Box - ' . __( 'Manage Boxes', 'nb_ml') . "</h2>"; ?>  

	<div id="ajax_mess"></div>
	
    <div id="poststuff" class="metabox-holder has-right-sidebar" style="overflow: hidden;">
    	
        <?php // SIDEBAR ?>
        <div id="side-info-column" class="inner-sidebar">
          <form class="form-wrap">	
           
           <div class="postbox lcwp_sidebox_meta">
            	<h3 class="hndle"><?php _e('Add Box', 'nb_ml') ?></h3> 
				<div class="inside">
                	<input type="text" name="nb_name" value="" id="nb_name" maxlenght="100" style="width: 205px;" placeholder="Box Name" />
                    <input type="button" name="add_box" id="add_box" value="<?php _e('Add', 'nb_ml') ?>" class="button-primary" style="width: 35px; margin-left: 5px;" />
                </div>
            </div>
           
            <div id="nb_boxes_list" class="postbox lcwp_sidebox_meta">
            	<h3 class="hndle"><?php _e('Boxes List', 'nb_ml') ?></h3> 
				<div class="inside"></div>
            </div>
            
            <div id="save_nb_box" class="postbox lcwp_sidebox_meta" style="display: none; background: none; border: none; position: relative; box-shadow: none;">
            	<input type="button" name="save-box" value="<?php _e('Save box', 'nb_ml') ?>" class="button-primary" />
                
                <?php if(get_option('nb_preview_pag')) : ?>
                <input type="button" id="preview_box" value="<?php _e('Preview', 'nb_ml') ?>" class="button-secondary" pv-url="<?php echo get_permalink(get_option('nb_preview_pag')) ?>" style="margin-left: 18px;" />
                <?php endif; ?>
                
                <div style="width: 30px; padding: 0 0 0 7px; float: right;"></div>
            </div>
          </form>	 
        </div>
    	
        <?php // PAGE CONTENT ?>
        
        <div id="post-body">
        <div id="post-body-content" class="nb_builder lcwp_table">
            <p><?php _e('Select a box', 'nb_ml') ?> ..</p>
        </div>
        </div>
        
        <br class="clear">
    </div>
    
</div>  


<?php // SCRIPTS ?>
<script src="<?php echo NB_URL; ?>/js/functions.js" type="text/javascript"></script>
<script src="<?php echo NB_URL; ?>/js/chosen/chosen.jquery.min.js" type="text/javascript"></script>
<script src="<?php echo NB_URL; ?>/js/iphone_checkbox/iphone-style-checkboxes.js" type="text/javascript"></script> 
<script src="<?php echo NB_URL; ?>/js/colpick/js/colpick.min.js" type="text/javascript"></script>

<script type="text/javascript" charset="utf8" >
jQuery(document).ready(function($) {
	var nb_is_acting = false;
	var lcwp_nonce = '<?php echo wp_create_nonce('lcwp_nonce') ?>';
	
	// var for the selected grid
	var sel_box = 0;
	var nb_pag = 1;
	
	// initial load
	nb_load_boxes();
	
	
	// add box
	jQuery('#add_box').click(function() {
		var box_name = jQuery('#nb_name').val();
		
		if( jQuery.trim(box_name) != '' ) {
			var data = {
				action: 'nb_add_box',
				nb_name: box_name,
				lcwp_nonce: lcwp_nonce
			};
			
			jQuery.post(ajaxurl, data, function(response) {
				var resp = jQuery.trim(response); 
				
				if(resp == 'success') {
					jQuery('#ajax_mess').empty().append('<div class="updated"><p><strong><?php echo nb_sanitize_input( __('Box added', 'nb_ml')) ?></strong></p></div>');	
					jQuery('#nb_name').val('');
					
					nb_pag = 1;
					nb_load_boxes();
					nb_hide_wp_alert();
				}
				else {
					jQuery('#ajax_mess').empty().append('<div class="error"><p>'+resp+'</p></div>');
				}
			});	
		}
	});
	
	
	// load boxes list
	function nb_load_boxes() {
		jQuery('#nb_boxes_list .inside').html('<div style="height: 30px;" class="lcwp_loading"></div>');
		
		
		var data = {
			action: 'nb_boxes_list',
			nb_page: nb_pag,
			lcwp_nonce: lcwp_nonce
			//,box_src: src_str
		};
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: data,
			dataType: "json",
			success: function(response){	
				jQuery('#nb_boxes_list .inside').empty();
				
				// get elements
				nb_pag = response.pag;
				var tot_pag = response.tot_pag;
				var boxes = response.elems;	

				var a = 0;
				jQuery.each(boxes, function(k, v) {	
					if(sel_box == v.id) {var sel = 'checked="checked"';}
					else {var sel = '';}
				
					jQuery('#nb_boxes_list .inside').append('<div class="misc-pub-section-last">\
						<span><input type="radio" name="gl" value="'+ v.id +'" '+ sel +' /></span>\
						<span class="nb_box_tit" style="padding-left: 7px;" title="ID '+ v.id +'">'+ v.name +'</span>\
						<span class="nb_del_box" id="bdel_'+ v.id +'"></span>\
					</div>');
					
					a = a + 1;
				});
				
				if(a == 0) {
					jQuery('#nb_boxes_list .inside').html('<p><?php echo nb_sanitize_input( __('No existing boxes', 'nb_ml')) ?></p>');
					jQuery('#nb_boxes_list h3.hndle').html('<?php echo nb_sanitize_input( __('Boxes List', 'nb_ml')) ?>');
				}
				else {
					// manage pagination elements
					if(tot_pag > 1) {
						jQuery('#nb_boxes_list h3.hndle').html('<?php echo nb_sanitize_input( __('Boxes List', 'nb_ml')) ?> (<?php echo nb_sanitize_input( __('pag', 'nb_ml')) ?> '+ nb_pag +' <?php echo nb_sanitize_input( __('of', 'nb_ml')) ?> '+ _tot_pag +')\
						<span id="nb_next_boxes">&raquo;</span><span id="nb_prev_boxes">&laquo;</span>');
					} else {
						jQuery('#nb_boxes_list h3.hndle').html('<?php echo nb_sanitize_input( __('Boxes List', 'nb_ml')) ?>');	
					}
					
					// different cases
					if(nb_pag <= 1) { jQuery('#nb_next_boxes').hide(); }
					if(nb_pag >= tot_pag) {jQuery('#nb_next_boxes').hide();}	
				}
			}
		});	
	}
	
	
	// delete box
	jQuery('body').delegate('.nb_del_box', 'click', function() {
		$subj = jQuery(this).parent(); 
		var box_id  = jQuery(this).attr('id').substr(5);
		
		if(confirm('<?php echo nb_sanitize_input( __('Delete definitively the box?', 'nb_ml')) ?>')) {
			var data = {
				action: 'nb_del_box',
				box_id: box_id
			};
			
			jQuery.post(ajaxurl, data, function(response) {
				var resp = jQuery.trim(response); 
				
				if(resp == 'success') {
					// if is this one opened
					if(sel_box == box_id) {
						jQuery('.nb_builder').html('<p><?php echo nb_sanitize_input( __('Select a box', 'nb_ml')) ?> ..</p>');
						sel_box = 0;
						
						// savegrid box
						jQuery('#save_nb_box').fadeOut();
					}
					
					$subj.slideUp(function() {
						jQuery(this).remove();
						
						if( jQuery('#nb_boxes_list .inside .misc-pub-section-last').size() == 0) {
							jQuery('#nb_boxes_list .inside').html('<p><?php echo nb_sanitize_input( __('No existing boxes', 'nb_ml')) ?></p>');
						}
					});	
				}
				else {alert(resp);}
			});
		}
	});
	
	
	// select a box
	jQuery('body').delegate('#nb_boxes_list input[type=radio]', 'click', function() {
		sel_box = parseInt(jQuery(this).val());
		var box_title = jQuery(this).parent().siblings('.nb_box_tit').text();

		jQuery('.nb_builder').html('<div style="height: 30px;" class="lcwp_loading"></div>');

		var data = {
			action: 'nl_box_builder',
			box_id: sel_box 
		};
		
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('.nb_builder').html(response);
			nb_async_form();
			
			// add the title
			jQuery('.nb_builder > h2').html(box_title);
			
			// save and preview boxes
			jQuery('#save_nb_box').fadeIn();
		});	
	});
	

	// save overlay
	jQuery('body').delegate('#save_nb_box input', 'click', function() {
		
		// sources - data array for each source
		var src_data = jQuery.makeArray();
		jQuery('.nb_sources .nb_type_block').each(function(i,v) {
            vals = jQuery(this).find('input, select, textarea').serializeArray();
			src_data[i] = vals;
        });
		
		// check against no sources
		if( jQuery('.nb_sources .nb_type_block').size() == 0 ) {
			alert('<?php echo nb_sanitize_input( __('A news source is needed', 'nb_ml')) ?>');
			return false;
		}
		
		// check against empty fields
		jQuery('#post-body-content .nb_field > input').not('.nb_optional_f').each(function() {
			if( jQuery.trim(jQuery(this).val()) == '' ) {
				alert('<?php echo nb_sanitize_input( __('One or more mandatory fields are empty', 'nb_ml')) ?>');
				nb_abort_saving = true;
				return false;	
			}
        });
		if(typeof(nb_abort_saving) != 'undefined') {return false;}
		
		
		// save
		jQuery('#save_nb_box div').html('<div style="height: 30px;" class="lcwp_loading"></div>');
		var data = 'action=nb_save_box&box_id='+ sel_box +'&src_data='+ JSON.stringify(src_data) +'&'+ jQuery('#nb_box_settings').find('input, select, textarea').serialize();
		
		jQuery.post(ajaxurl, data, function(response) {
			var resp = jQuery.trim(response); 
			jQuery('#save_nb_box div').empty();	
			
			if(resp == 'success') {
				jQuery('#ajax_mess').empty().append('<div class="updated"><p><strong><?php echo nb_sanitize_input( __('Overlay saved', 'nb_ml')) ?></strong></p></div>');	
				nb_hide_wp_alert();
			}
			else {
				jQuery('#ajax_mess').empty().append('<div class="error"><p>'+resp+'</p></div>');
			}
		});
	});


	//////////////////////////////////////////////
	
	// add news source
	jQuery('body').delegate('.nb_add_src input', 'click', function() {
		var type = jQuery(this).parent().find('select').val();
		var $subj = jQuery(this).parents('form.form-wrap').find('.mgom_elements');
		var $loader = jQuery(this).next('span');
	
		if(!nb_is_acting) {
			nb_is_acting = true;
	
			// add
			$loader.html('<div style="height: 25px;" class="lcwp_loading"></div>');
			var data = {
				action: 'nb_add_src',
				nb_type: type 
			};
			
			jQuery.post(ajaxurl, data, function(response) {
				jQuery('.nb_sources').prepend(response);
				nb_is_acting = false;
				
				nb_async_form();
				$loader.empty();
			});
		}
	});
	
	
	// remove news source
	jQuery('body').delegate('.nb_sources h4 .lcwp_del_row', 'click', function() {
		if(confirm('<?php echo nb_sanitize_input( __('Remove news source?', 'nb_ml')) ?>')) {
			var $subj = jQuery(this).parents('.nb_type_block');
			$subj.slideUp(function() {
				$subj.remove();
			});
		}
	});

	
	// preview box
	jQuery('body').delegate('#preview_box', "click", function() {
		var url = jQuery(this).attr('pv-url');
		var char = (url.indexOf('?') != -1) ? '&' : '?';
		
		window.open( url + char + '?nb_preview=' + sel_box ,'_blank');
	});


	<!-- other -->
	
	// async form elements init
	function nb_async_form() {
		nb_live_chosen();
		nb_live_ip_checks();
		nb_slider_opt();
		nb_colpick();
	}
	
	// init chosen for live elements
	function nb_live_chosen() {
		jQuery('.lcweb-chosen').each(function() {
			var w = jQuery(this).css('width');
			jQuery(this).chosen({width: w}); 
		});
		jQuery(".lcweb-chosen-deselect").chosen({allow_single_deselect:true});
	}
	
	// init iphone checkbox
	function nb_live_ip_checks() {
		jQuery('.ip-checkbox').each(function() {
			jQuery(this).iphoneStyle({
			  checkedLabel: 'ON',
			  uncheckedLabel: 'OFF'
			});
		});	
	}
	
	// date format helper
	jQuery('body').delegate('#nb_show_date_helper', "click", function (e) {
		e.preventDefault();
		tb_show('News Box date helper', '#TB_inline?height=600&width=640&inlineId=nb_date_helper');
		setTimeout(function() {
			jQuery('#TB_window').css('background-color', '#fff');
		}, 50);
	});

	// hide message after 3 sec
	function nb_hide_wp_alert() {
		setTimeout(function() {
			jQuery('#ajax_mess').slideUp(function() {
				jQuery(this).empty().show();
			});
		}, 3500);	
	}
	
	// visibility fix
	//jQuery('#poststuff').css('overflow', 'visible');
	
});
</script>
