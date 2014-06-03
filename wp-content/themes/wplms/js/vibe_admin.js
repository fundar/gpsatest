jQuery(document).ready(function(){
   jQuery('.on_off').click(function(e){
       e.preventDefault();
      if(jQuery(this).hasClass('active')){
          jQuery(this).removeClass('active');
          jQuery(this).parent().parent().find('.sidebar').hide(200);
          jQuery(this).parent().parent().find('.select-sidebar select').val('');
      }else{
          jQuery(this).addClass('active');
          jQuery(this).parent().parent().find('.sidebar').show(200);
      }
   });
});   

jQuery(document).ready(function($){
    
                jQuery('.author-social-remove').live('click', function(){ 
    jQuery(this).parent().parent().fadeOut('slow', function(){jQuery(this).remove();});
                });
    $('#add_social_info').click(function(event){
                    event.preventDefault();

                var new_input = jQuery('#socialtable tr:last-child').clone();
                jQuery('#socialtable').append(new_input);
                
                jQuery('#socialtable tr:last-child').removeAttr('style');
                
                jQuery(new_input).find('select').each(function(){
                   jQuery(this).attr('name' , jQuery(this).attr('rel-name'));
                });
                
                jQuery(new_input).find('input').each(function(){
                   jQuery(this).attr('name' , jQuery(this).attr('rel-name'));
                });
                return false;
                });
        $('select.chosen').chosen();
        $('.chzn-select').chosen();
});
