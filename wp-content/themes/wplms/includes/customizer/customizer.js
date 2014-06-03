/* 
 * Customizer.js
 */
jQuery(document).ready(function(){
    
jQuery( ".slider_value" ).each( function() { 
        var slide_val = jQuery(this).parent().find('.customizer_slider');
        var $this = jQuery(this);
        var std=parseInt($this.attr('value'));
        slide_val.slider({
                    range: "min",
                    value: 0,
                    min: 0,
                    max: 100,
                    slide: function( event, ui ) { 
                        var val=ui.value;
                        $this.attr('value', val);
                        $this.trigger('change');
                    }
                });
                
            $this.val( slide_val.slider( "value" ));
    });
    
});

