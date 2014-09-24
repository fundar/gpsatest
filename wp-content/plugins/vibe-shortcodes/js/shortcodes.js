/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
(function($) {
  $.expr[":"].onScreen = function(elem) {
    var $window = $(window);
    var viewport_top = $window.scrollTop()
    var viewport_height = $window.height()
    var viewport_bottom = viewport_top + viewport_height
    var $elem = $(elem)
    var top = $elem.offset().top
    var height = $elem.height()
    var bottom = top + height

    return (top >= viewport_top && top < viewport_bottom) ||
           (bottom > viewport_top && bottom <= viewport_bottom) ||
           (height > viewport_height && top <= viewport_top && bottom >= viewport_bottom)
  }
})(jQuery);

jQuery(document).ready(function($){
    $(window).scroll( function (){
         $('.animate').filter(":onScreen").not('.load').each(function(i){ 
            var $this=$(this);
                 var ind = i * 100;
                 var docViewTop = $(window).scrollTop();
                 var docViewBottom = docViewTop + $(window).height();
                 var elemTop = $this.offset().top;      
                     if (docViewBottom >= elemTop) { 
                         setTimeout(function(){ 
                              $this.trigger('load');
                          }, ind);
                         }      
             });
            //End function 
    });
    $('.animate').on('load',function(){
        $(this).addClass('load');
    });
    $('.form_field').click(function(){ 
        $(this).removeAttr('style');
    });
});

jQuery(document).ready(function($){
    
$('.nav-tabs').each(function(){
  if(!$(this).find('li').hasClass('active')){
      $(this).find('a:first').tab('show');
      $(this).find('li:first').addClass('active');
  }
}); 

$('.nav-tabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
});

$('.image_slider').flexslider({
  prevText: "<i class='icon-arrow-1-left'></i>",
  nextText: "<i class='icon-arrow-1-right'></i>",
});
    
$('.ajax-popup-link').magnificPopup({
    type: 'ajax',
    alignTop: true,
    fixedContentPos: true,
    fixedBgPos: true,
    overflowY: 'auto',
    closeBtnInside: true,
    preloader: false,
    midClick: true,
    removalDelay: 300,
    mainClass: 'my-mfp-zoom-in'
});

jQuery('.pop').magnificPopup({
  type: 'image',
  gallery:{
    enabled:true
  }
});

jQuery('.gallery').magnificPopup({
  delegate: 'a',
  type: 'image',
  tLoading: 'Loading image #%curr%...',
  mainClass: 'mfp-img-mobile',
  gallery: {
  	enabled: true,
  	navigateByImgClick: true,
  	preload: [0,1] // Will preload 0 - before current, and 1 after the current image
  },
  image: {
  	tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
  	titleSrc: function(item) {
  		return item.el.attr('title');
  	}
  }
});

$('.knob').each(function(){
         var $this = $(this).find('.dial');
        var myVal = $this.val();
        $this.knob({
            draw : function () {

                    // "tron" case
                    if(this.$.data('skin') == 'tron') {

                        var a = this.angle(this.cv)  // Angle
                            , sa = this.startAngle          // Previous start angle
                            , sat = this.startAngle         // Start angle
                            , ea                            // Previous end angle
                            , eat = sat + a                 // End angle
                            , r = true;

                        this.g.lineWidth = this.lineWidth;

                        this.o.cursor
                            && (sat = eat - 0.3)
                            && (eat = eat + 0.3);

                        if (this.o.displayPrevious) {
                            ea = this.startAngle + this.angle(this.value);
                            this.o.cursor
                                && (sa = ea - 0.3)
                                && (ea = ea + 0.3);
                            this.g.beginPath();
                            this.g.strokeStyle = this.previousColor;
                            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
                            this.g.stroke();
                        }

                        this.g.beginPath();
                        this.g.strokeStyle = r ? this.o.fgColor : this.fgColor ;
                        this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
                        this.g.stroke();

                        this.g.lineWidth = 2;
                        this.g.beginPath();
                        this.g.strokeStyle = this.o.fgColor;
                        this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
                        this.g.stroke();

                        return false;
                    }
        }
       });
         $({
           value: 0
       }).animate({

           value: myVal
       }, {
           duration: 2400,
           easing: 'swing',
           step: function () {
               $this.val(Math.ceil(this.value)).trigger('change');

           }
       })
     });

});

//AJAX CONTACT FORM
jQuery(document).ready(function ($) {
	// SUBSCRIPTION FORM AJAX HANDLE
 $( 'body' ).delegate( '.form .form_submit', 'click', function(event){
    event.preventDefault();
    var parent = $(this).parent();
    var $response= parent.find(".response");
    var error= '';
    var to = []
    var data = [];
    var label = [];
    var regex = [];
    var to = parent.attr('data-to');
    var subject = parent.attr('data-subject');
    regex['email'] = /^([a-z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,4}$)/i;
    regex['phone'] = /[A-Z0-9]{7}|[A-Z0-9][A-Z0-9-]{7}/i;
    regex['numeric'] = /^[0-9]+$/i;
    regex['captcha'] = /^[0-9]+$/i;
    regex['required'] = /([^\s])/;
    var i=0;
    
    parent.find('.form_field').each(function(){
        i++;
        var validate=$(this).attr('data-validate');
        var value = $(this).val();
        if(!value.match(regex[validate])){
            error += vibe_shortcode_strings.invalid_string+validate;
            $(this).css('border-color','#e16038');
        }else{
            data[i]=value;
            if(parent.hasClass('isocharset')){
              data[i]=encodeURI(value);
            }
            label[i]=$(this).attr('placeholder');
        }
        if(validate === 'captcha' && error === ""){
            var $num = $(this).attr('id');
            var $sum=$(this).closest('.math-sum');
            var num1 = parseInt($('#'+$num+'-1').text());
            var num2 = parseInt($('#'+$num+'-2').text());
            var sumval = parseInt($(this).val());
            if( sumval != (num1+num2))
              error += vibe_shortcode_strings.captcha_mismatch;
        }
    });
    
  if (error !== "") {
    $response.fadeIn("slow");
    $response.html("<span style='color:#D03922;'>"+vibe_shortcode_strings.error_string+" " + error + "</span>");
  } else {
    $response.css("display", "block");
    $response.html("<span style='color:#0E7A00;'>"+vibe_shortcode_strings.sending_mail+"... </span>");
    $response.fadeIn("slow");
    setTimeout(function(){sendmail(to,subject,data,label,parent);}, 2000);
  }
    
  return false;
  });

  function sendmail(to,subject,formdata,labels,parent) { 
  	var $response= parent.find(".response");
    var isocharset = false;
    
    if(parent.hasClass('isocharset'))
      isocharset=true;

  	$.ajax({
          type: "POST",
          url: ajaxurl,
                data: {   action: 'vibe_form_submission', 
                          to: to,
                          subject : subject,
                          data:JSON.stringify(formdata),
                          label:JSON.stringify(labels),
                          isocharset:isocharset
                      },
          cache: false,
          success: function (html) {
              console.log(html);
              $response.fadeIn("slow");
              $response.html(html);
              if(html.indexOf('<span') === 0) {
                parent.find('textarea').val('');
                parent.find('input[type="text"]').val('');
              }
              setTimeout(function(){$response.fadeOut("slow");}, 10000);
          }
      });
  }
	     
});

jQuery(document).load(function($){
  $('.mejs-container').each(function(){
    $(this).addClass('mejs-mejskin');
  });  
});


jQuery(window).load(function ($) {
    var $ = jQuery;

/*=== CAROUSELs ===*/

$('.vibe_carousel.flexslider').each(function(){

    var $this = $(this);
    if($this.find('.woocommerce').length > 0){
      return;
    }
    var itemwidth = parseInt($this.attr('data-block-width'));
    var min= parseInt($this.attr('data-block-min'));
    var max = parseInt($this.attr('data-block-max'));

    var vibe_carousel={
    animation: "slide",
    controlNav: false,
    directionNav: true,
    animationLoop: false,
    slideshow: false,
    itemWidth: itemwidth,
    maxItems: max,
    minItems: min,
    itemMargin: 30,
    prevText: "<i class='icon-arrow-1-left'></i>",
    nextText: "<i class='icon-arrow-1-right'></i>",
     start: function() {
               $this.removeClass('loading');
           }    
    };
    if($this.length > 0){
        var custom=eval('op'+$this.attr('id'));
          $.extend(vibe_carousel,custom); 
        $this.flexslider(vibe_carousel);
    }  
});



});


/*======= FILTERABLE=======*/

jQuery(window).load(function ($) {
 var $ = jQuery;
 $('.custom_post_filterable').each(function(){
        var $container = $(this).find('.filterableitems_container'),
      $filtersdiv = $(this).find('.vibe_filterable'),
        $checkboxes = $(this).find('.vibe_filterable a');
    

  $container.imagesLoaded( function(){  
    $container.isotope({
      itemSelector: '.filteritem'
    }); 
  });
  
    $checkboxes.click(function(event){
      event.preventDefault();
      var me = $(this);
      $filtersdiv.find('.active').removeClass();
      var filters = me.attr('data-filter');
      me.parent().addClass('active');
      $container.isotope({filter: filters});
    });
   
   $('.vibe_filterable a:first').trigger('click');
   $('.vibe_filterable a:first').parent().addClass('active');
 });
});

jQuery(window).load(function ($) { 


jQuery('.grid.masonry').each(function($){

var $container = jQuery(this);
$container.imagesLoaded( function(){ 
    var width= parseInt($container.attr('data-width'));
     var gutter= parseInt($container.attr('data-gutter'));

    $container.masonry({
                    itemSelector: '.grid-item',
                    columnWidth: width,
                    gutterWidth: gutter,
                    isAnimated: true
            });
        });
    });
});

jQuery(document).ready(function ($) {
    
    $('.fitvids').fitVids();

    if($('.vibe_grid').length && $('.vibe_grid').hasClass('inifnite_scroll')){
        
        var $this= $('.vibe_grid.inifnite_scroll:not(.loaded)');
        var end = $this.parent().find('.end_grid');
        var load = $this.parent().find('.load_grid');
        var args = $this.find('.wp_query_args').html();
        var max = parseInt($this.find('.wp_query_args').attr('data-max-pages'));
        
        var top = $('.vibe_grid.inifnite_scroll:not(.loaded) li:last').offset().top -500;
        var rel = parseInt($('.vibe_grid.inifnite_scroll:not(.loaded)').attr('data-page')); 
        
     $(window).data('ajaxready', true).scroll(function(e) {
         
           if ($(window).data('ajaxready') == false) return;
          
          if(!$('.vibe_grid.inifnite_scroll').hasClass('loaded'))
            top = $('.vibe_grid.inifnite_scroll:not(.loaded) li:last').offset().top -500;
          else
              rel = max;

         if ($(window).scrollTop() >= top && rel < max ) {
            
        $(window).data('ajaxready', false);
        
       
        $.ajax({
                type: "POST",
                url: ajaxurl,
                      data: {action: 'grid_scroll', 
                                args: args,
                                page: rel
                            },
                cache: false,
                success: function (html) {
                         
                          if(html){
                              rel++;
                              $this.attr('data-page',rel);
                             if($this.hasClass('masonry')){
                                    $('.vibe_grid.inifnite_scroll:not(.loaded) .grid.masonry').append(html).masonry('reload');
                                    $(window).trigger('resize');
                                    $('.vibe_grid.inifnite_scroll .grid.masonry').imagesLoaded( function(){
                                       $('.vibe_grid.inifnite_scroll .grid.masonry').masonry('reload');});
                                }else{
                                $('.vibe_grid.inifnite_scroll:not(.loaded) li:last').after(html); 
                                } 
                          } 
                           $(window).data('ajaxready', true);

                }
            }); 
            }else{
             if(rel == max){
                             end.fadeIn(200);
                                load.fadeOut(200);
                              $this.addClass('loaded');
             }     
            }
        });
    }
});

jQuery(document).ready(function($){
    // Uploading files
  var zip_uploader;
  jQuery('#upload_zip_button').on('click', function( event ){
      event.preventDefault();
      var url = $(this).attr('data-admin-url')+'media-upload.php?type=upload&tab=upload&TB_iframe=1';
      tb_show('Upload ZIP package', url );
    });
});
