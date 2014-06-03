;(function($) {
  $.expr[":"].onScreen = function(elem) {
    var $window = $(window);
    var viewport_top = $window.scrollTop();
    var viewport_height = $window.height();
    var viewport_bottom = viewport_top + viewport_height;
    var $elem = $(elem);
    var top = $elem.offset().top;
    var height = $elem.height();
    var bottom = top + height;

    return (top >= viewport_top && top < viewport_bottom) ||
           (bottom > viewport_top && bottom <= viewport_bottom) ||
           (height > viewport_height && top <= viewport_top && bottom >= viewport_bottom);
  };
})(jQuery);

;(function($) {

jQuery(document).ready(function($){
     NProgress.inc();
    SidebarMenuEffects();

    $('nav .menu-item').has('.sub-menu').each(function(){
        if($(this).find('.megadrop').length > 0 ){
      }else{
          $(this).addClass('hasmenu');
      }
    });
    
    $('.vbplogin').click(function(event){
      event.preventDefault();
      $('#vibe_bp_login').fadeIn(300);
      $('#vibe_bp_login').toggleClass('active');
      event.stopPropagation();
    });
    $('#searchicon').click(function(event){
        $('#searchdiv').toggleClass('active');
    });

    $(document).mouseup(function (e)
    {
        var container = $("#searchdiv");

        if (!container.is(e.target) && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            container.hide();
            container.removeClass('active');
        }

        container = $("#vibe_bp_login");

        if (!container.is(e.target) && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            container.hide();
        }

    });


    $('#headernotification').each(function(){
      var cookieValue = $.cookie("closed");
      if ((cookieValue !== null) && cookieValue == 'headernotification') {      
        $(this).hide();
      }
      });

      $('#widget-tabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
      });

    $('#footernotification').each(function(){
      var cookieValue = $.cookie("closed");
      if ((cookieValue !== null) && cookieValue == 'footernotification') {      
        $(this).hide();
      }
      });
    $('.close').click(function(){
      var parent=$(this).parent().parent();
      var id=parent.attr('id');
      parent.hide(200);
       $.cookie('closed', id, { expires: 2 ,path: '/'});
    });

     jQuery('#scrolltop a').click(function(event){
      event.preventDefault();
      $('body,html').animate({
              scrollTop: 0
            }, 1200);
            return false;
    });

    $('.tip').tooltip(); 
    $('.nav-tabs li:first a').tab('show');


    $('#more_desc').click(function(event){
      event.preventDefault();
        $('.full_desc').fadeIn('fast');  
        $(this).fadeOut('fast');
      
    });

    $('#less_desc').click(function(event){
      event.preventDefault();
      
      $('.full_desc').fadeOut('fast');
      $('#more_desc').fadeIn('fast');
    });
}); // END ready

jQuery(document).ready(function($){  
  $('.form select').chosen({
      allow_single_deselect: true,
      disable_search_threshold: 8});

  $('.chosen').chosen({
      allow_single_deselect: true,
      disable_search_threshold: 8});

  $(window).scroll(function(event){
    var st = $(this).scrollTop();
    if($('#headertop').hasClass('fix')){
      var headerheight=$('header').height();
      if(st > headerheight){
        $('#headertop').addClass('fixed');
      }else{
        $('#headertop').removeClass('fixed');
      }
    }
    if(st > 700){
      $('#scrolltop').addClass('fix');
    }else{
      $('#scrolltop').removeClass('fix');
    }
  });

    $('.twitter_carousel').each(function(){
      
  var $this = $(this);
   $this.flexslider({
    animation: "slide",
    controlNav: false,
    directionNav: false,
    animationLoop: true,
    slideshow: true,
    prevText: "<i class='icon-arrow-1-left'></i>",
    nextText: "<i class='icon-arrow-1-right'></i>",
    start: function() {
               $this.removeClass('loading');
           }
    });    
  });

});// END ready

//vibe_carousel flexslider direction horizontal  columns1
jQuery(document).ready(function($){    
 

$('section.stripe').each(function(){
        var style = $(this).find('.v_column.stripe_container .v_module').attr('data-class');
        if(style){style='stripe '+style;
            $(this).find('.v_column.stripe .v_module').removeAttr('data-class');
            $(this).attr('class',style);
        }
        var style = $(this).find('.v_column.stripe .v_module').attr('data-class');
        if(style){style='stripe '+style;
            $(this).find('.v_column.stripe .v_module').removeAttr('data-class');
            $(this).attr('class',style);
        }
    });

function v_carousel_fx($this){
    var direction,control,itemwidth,maxitem,minitem,scroll,vmargin;
    direction=control=false;vmargin=margin=30;
    scroll='horizontal';
    $this.find('li.product').removeClass().addClass('product');
    if($this.parent().hasClass('direction'))
        direction = true;
    else
        direction = false;
    if($this.parent().hasClass('control'))
        control = true;
    else
        control = false;

   if($this.parent().hasClass('columns1')){
       itemwidth=true;
       maxitem=1;
       minitem=1;
       margin=0;
   }
       
       
   if($this.parent().hasClass('columns2')){
       itemwidth = 420;
       maxitem=2;
       minitem=2;
   }
       
   
   if($this.parent().hasClass('columns3')){
       itemwidth=320;
       maxitem=3;
       minitem=2;
   }
       
   
   if($this.parent().hasClass('columns4')){
       itemwidth=200;
       maxitem=4;
       minitem=2;
   }
       
   
   if($this.parent().hasClass('columns5')){
       itemwidth=180;
       maxitem=5;
       minitem=2;
   }
   if($this.parent().hasClass('columns6')){
       itemwidth=140;
       maxitem=6;
       minitem=2;
   }
       
   
        $this.flexslider({
      animation: "slide",
      selector: ".products > li", 
      controlNav: control,
      directionNav: direction,
      animationLoop: false,
      slideshow: false,
      itemWidth: itemwidth,
      itemMargin:margin,
      maxItems:maxitem,
      minItems:minitem,
      prevText: "<i class='icon-arrow-1-left'></i>",
      nextText: "<i class='icon-arrow-1-right'></i>",
    });
  }

  $('.vibe_carousel.flexslider .woocommerce').each(function(){
     var $this= $(this);
     if($this.is(":visible")){
        v_carousel_fx($this);
     }
  });

});


jQuery(document).ready(function($){    
           
    
    var $container = $('#filtercontainer'),
        filters = {};

    $container.isotope({
      itemSelector : '.filteritem',
    });

    // filter buttons
    $('.filters a').click(function(){
      var $this = $(this);
      // don't proceed if already selected
      if ( $this.hasClass('active') ) {
        return;
      }
      
      var $optionSet = $this.parents('.option-set');
      // change selected class
      $optionSet.find('.active').removeClass('active');
      $this.addClass('active');
      
      // store filter value in object
      // i.e. filters.color = 'red'
      var group = $optionSet.attr('data-filter-group');
      filters[ group ] = $this.attr('data-filter-value');
      // convert object into array
      var isoFilters = [];
      for ( var prop in filters ) {
        isoFilters.push( filters[ prop ] );
      }
      var selector = isoFilters.join('');
      $container.isotope({ filter: selector });

      return false;
    });   
    
});// END ready


jQuery(document).ready(function($){

  $('.inmenu').each(function(){
      var inmenu_top = $('.inmenu').offset().top - 40;
      var footer_top = $('footer').offset().top - Math.round($(window).height()/2) - 90;
      $(window).scroll(function(){
          var top=$(window).scrollTop();
          if(top > inmenu_top && top < footer_top){
            $('.inmenu').addClass('affix');
          }else{
            $('.inmenu').removeClass('affix');
          }
      });
  });
});// END ready


jQuery(document).ready(function($) { 
 // Cache selectors
 var lastId;
 var topMenu = $(".scrollmenu"); 
 var topMenuHeight = 0;//topMenu.outerHeight()+15
     // All list items
 var menuItems = topMenu.find("a"),
     // Anchors corresponding to menu items
     scrollItems = menuItems.map(function(){
       var item = $($(this).attr("href"));
       
       if (item.length) { return item; }
     });
  

 // Bind click handler to menu items
 // so we can get a fancy scroll animation
menuItems.click(function(e){
  e.preventDefault();
   var href = $(this).attr("href"),
       offsetTop = href === "#" ? 0 : $(href).offset().top-topMenuHeight+1;

   $('html, body').stop().animate({ 
       scrollTop: offsetTop
   }, 800);
  //return false;
 });


        $(window).scroll( function ()
        {
            var fromTop = $(this).scrollTop()+25;
            var cur = scrollItems.map(function(){
              if ($(this).offset().top < fromTop)
                return this;
            });
            cur = cur[cur.length-1];
            var id = cur && cur.length ? cur[0].id : "";
            if (lastId !== id) {
                lastId = id;
                menuItems
                  .parent().removeClass("active");
                  menuItems.filter("[href=#"+id+"]").parent().addClass("active");              
                   }
                   
                 // Animation function  
                   $('.animate').filter(":onScreen").not('.load').each(function(i){ 
                      var $this=$(this);
                           var ind = i * 100;
                           var docViewTop = $(window).scrollTop();
                           var docViewBottom = docViewTop + $(window).height();
                           var elemTop = $this.offset().top;      
                               if (docViewBottom >= elemTop) { 
                                   setTimeout(function(){ 
                                        $this.addClass('load');
                                    }, ind);
                                   }      
                       });
                      //End function 
                   
        });
});// END ready



  
//AJAX CONTACT FORM
jQuery(document).ready(function ($) {
       
    $('.checkout_steps li').click(function(){
    var index= $(this).index();
       if(index < ($('.checkout_steps li').length-1) && index > 0){
            $('.step').fadeOut(200);
            $('.step.step'+index).fadeIn(200);
            $('.checkout_steps').find('.active').removeClass('active');
            $('.checkout_steps li:lt('+index+')').addClass('active');
            $(this).addClass('active');
       }
    });
    
    $('.proceed .btn').live("click", function () {
        var index=$(this).attr('rel');
            $('.step').fadeOut(200);
            $('.step.step'+index).fadeIn(200);
            $('.checkout_steps').find('.active').removeClass('active');
            $('.checkout_steps li:lt('+index+')').addClass('active');
            $('.checkout_steps li:eq('+index+')').addClass('active');
    });
    $('.minmax').click(function(event){
        event.preventDefault();
        $(this).parent().toggleClass('show');
        $(this).find('i').toggleClass('icon-minus');
    });
});// END ready

// ADD Question Form
jQuery(document).ready(function($){ 
  $( ".repeatablelist" ).each(function(){
    $(this).sortable({ handle: '.sort_handle' }); 
  });
  $('.add_repeatable').click(function(){
    var repeatablelist=$(this).parent().find('.repeatablelist');
    var lastitem=$(this).parent().find('.repeatablelist li:last-child');
    var cloneditem=lastitem.clone();
    var name= cloneditem.find('.option_text').attr('rel-name');

    cloneditem.find('.option_text').attr('name',name);
    repeatablelist.append(cloneditem);
  });

});// END ready

jQuery(window).load(function($){
     NProgress.done();
});


})(jQuery);