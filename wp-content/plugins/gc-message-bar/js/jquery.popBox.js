/**
 * popBox is a jQuery popup plugin
 * 
 * @name: popBox
 * @type: jQuery
 * @author: (c) Tibor Pintér (Dimiona)
 * @version: 0.8.2
 * @developed: jQuery 1.8.3
 */
/**
 * Descriptions:
 *
 * - Iframes: to close popBox from iframe simply use this code:
 *   parent.$('body').trigger('popBox.close');
 *   IMPORTANT! You need to know if iframe's src not under the
 *   same domain as where you use popBox it will not working,
 *   because its against the same-origin policy.
 *   (http://en.wikipedia.org/wiki/Same_origin_policy)
 */
;(function($){
    var popBoxAnimating = false;
    $.fn.popBox = function(options){

        if( popBoxAnimating === true ){
            return false;
        }
        
        popBoxAnimating = true;

        var base = this;
        
        base.$el = $(this);
        base.el = this;
        base.popBox = {};

        var popBoxData = base.$el.data("popBox");
        if( typeof(popBoxData) != 'undefined' && popBoxData != null ){
            //return popBoxData.init();
        }
        
        base.popBox.init = function(){
            options.popBoxID = base.popBox._uniq(6);
            base.popBox.options = $.extend({},$.fn.popBox.defaultOptions, options);
            
            if( $('#popbox-wrapper').length === 0 ){
                /* append popBox */
                $('body').append('<div id="popbox-wrapper"></div>'+
                '<div id="popbox-container">'+
                    '<a href="#" id="popbox-close"></a>'+
                    '<div id="popbox-loader"></div>'+
                    '<div class="popbox-border">'+
                    '<div id="popbox-content">'+
                    '</div></div>'+
                '</div>');
            }

            if( !$.browser.msie && parseFloat($('#popbox-wrapper').css('opacity')) > 0 ){
                return false;
            }
            
            /* SET CLOSE BUTTON */
            if( base.popBox.options.showCloseButton )
            {
                $('#popbox-close').unbind('click').click(function(){ 
                    base.popBox._close()});
            }else{
                $('#popbox-close').hide();
            }

            /* ESCAPE BUTTON */
            if( base.popBox.options.enableEscapeButton )
            {
                $(document).keydown(function(e){
                   var code = e.keyCode ? e.keyCode : e.which;

                   if( code === 27 )
                       base.popBox._close();
                });
            }

            if(base.popBox.options.useBeforeUnload){
                $(window).unbind('beforeunload');
                $(window).bind('beforeunload', function(e){
                    $('body').trigger('popBox.beforeClose');
                    base.popBox.options.onClose(); // onClose Event
                    $(window).unbind('beforeunload');
                    e.returnValue = 'Are you sure you want to leave?';
                    return 'Are you sure you want to leave?';
                });
            }

            /* ESCAPE BUTTON */
            if( base.popBox.options.closeOnOverlay )
            {
                $('#popbox-wrapper').click(function(e){
                    base.popBox._close();
                });
                $('#popbox-content').click(function(e){
                    e.stopPropagation();
                });
            }

            $('body').on('popBox.close',function(){
                base.popBox._close();
            });
            
            // content
            //if( base.popBox.options.contentUrl.length > 0 && base.popBox.options.contentID.length === 0){
                $('#popbox-loader').show();
                $('#popbox-content').empty();

                /**
                 * This is a fix for "load" event. If you use a form
                 * and submit it within this iframe, the "load" event firing too
                 * and the previously sent ajax request's response will OVERWRITE
                 * the iframe submit's response!!!
                 *
                 * DEPRECATED
                 */
                //var ajaxRequestSend = true;

                switch(base.popBox.options.content){
                    case 'iframe':
                        base.popBox._content_iframe();
                        break;
                    case 'image':
                        base.popBox._content_image();
                        break;
                    default:
                        base.popBox._content_static();
                        break;
                }

                base.popBox._setCenter();
                base.popBox._show_content();
            //}
        };

        base.popBox._content_static = function(){
            $('#popbox-loader').hide();
            var content = $(base.popBox.options.contentID);
            $('#popbox-content').html(content.html());
            $('#popbox-content').height(content.height());
            $('body').trigger('popBox.loaded');
        }

        base.popBox._content_image = function(){
            var image = $('<img/>')
                            .load(function(){
                                $('#popbox-loader').hide();
                                base.popBox._setCenter();
                                $(this).fadeIn(350);
                            })
                            .attr('src',base.popBox.options.contentUrl)
                            .hide()
                            .appendTo($('#popbox-content'));
            $('body').trigger('popBox.loaded');
        }

        base.popBox._content_iframe = function(){
            var $iframe = $('<iframe id="popbox-iframe-'+options.popBoxID+'" class="popbox-iframe" scrolling="no" frameborder="0" style="width:100%;height:100%;"></iframe>');
                $iframe.appendTo($('#popbox-content'));

                $("#popbox-iframe-"+options.popBoxID).on('load',function(){
                    $('#popbox-loader').hide();
                    base.popBox._setCenter();
                });
                
                $iframe.attr('src',base.popBox.options.contentUrl);
            
            $('body').trigger('popBox.loaded');
        }
        
        base.popBox._show_content = function(){
            $('#popbox-wrapper')
                .show()
                .animate({
                    "opacity" : base.popBox.options.overlayOpacity
                }, 200, function(){
                    $(this).show();
                    base.popBox._show_box();
                });

            $('#popbox-container').show();
        }
        
        base.popBox._setCenter = function(){
            var contentHeight = screen.height,
                popBorder = parseInt( $('.popbox-border').css('padding-top') )+parseInt( $('.popbox-border').css('padding-bottom') );
            switch(base.popBox.options.content){
                case 'iframe':
                    var iframeHeight = $('#popbox-content .popbox-iframe').contents().find('body').height();
                    if(iframeHeight>0){
                        contentHeight = base.popBox._popbox_height(iframeHeight);
                    }else{
                        contentHeight = $('#popbox-content').height();
                    }
                    break;
                case 'image':
                    var imageHeight = parseInt($('#popbox-content img').get(0).height);
                    contentHeight = base.popBox._popbox_height(imageHeight);
                    if(imageHeight > contentHeight){
                        $('#popbox-content img').height(contentHeight-popBorder);
                        var imageWidth = parseInt($('#popbox-content img').get(0).width);
                        var popboxWidth = parseInt($('#popbox-content').width());
                        if(imageWidth>popboxWidth){
                            $('#popbox-content img')
                                .width(popboxWidth)
                                .height('auto')
                                .css('margin-top',(contentHeight-parseInt($('#popbox-content img').height()))/2+'px');

                        }
                    }
                    break;
                default:
                    contentHeight = base.popBox._popbox_height();
                    break;
            }
            $('#popbox-container').css('height',(contentHeight+popBorder)+'px');
            $('#popbox-content,.popbox-border').css({
                'height' : contentHeight+'px',
                'margin-top' : '-'+(contentHeight/2+popBorder/2)+'px'
            });
        }

        base.popBox._popbox_height = function(contentHeight){
            if(typeof(contentHeight)=='undefined'){
                contentHeight = screen.height;
            }
            return (window.innerHeight > 500 && contentHeight > 500 ? parseInt(window.innerHeight)*0.75 : contentHeight );
        }

        base.popBox._close = function(){
            if(base.popBox.options.useBeforeUnload){
                $(window).unbind('beforeunload');
            }
            $('body').trigger('popBox.beforeClose');
            base.popBox.options.onClose(); // onClose Event
            $('#popbox-container').stop().animate( {"opacity" : "0"}, 200, base.popBox.options.easing, function(){
                    $('#popbox-wrapper')
                        .stop()
                        .animate(
                            {
                               "opacity" : "0"
                            },
                            200,
                            function(){
                                $(this).hide();
                                $('#popbox-container').css('opacity','0');
                                popBoxAnimating = false;
                                $('body').trigger('popBox.afterClose');
                            }
                        );
                    $(this).hide();
                }
            );
        }

        base.popBox._show_box = function(){
            $('#popbox-container')
                .animate( {"opacity": 1}, 200, base.popBox.options.easing, function(){
                    popBoxAnimating = false;
                });
        }

        base.popBox._uniq = function(s){
            var n;
            if (typeof(s) == 'number' && s === parseInt(s, 10)){
                s = Array(s + 1).join('x');
            }
            return s.replace(/x/g, function(){
                var n = Math.round(Math.random() * 61) + 48;
                n = n > 57 ? (n + 7 > 90 ? n + 13 : n + 7) : n;
                return String.fromCharCode(n);
            });
        }

        return this.each(function() {
            //if ($(this).data('popBox')) return; //POPUP already exists?
            base.$el.data("popBox", base.popBox);
            base.popBox.init();
        });
    };
    
    $.fn.popBox.defaultOptions = {

        popBoxID : null,
    
        contentID : '',
        contentUrl : '',
        content : 'static', // It can be (static|image|iframe)
    
        overlayOpacity: (!$.browser.msie || $.browser.version == "10.0") ? '.4' : '.4',
        easing: 'swing',
        speed: 800,

        /*easingIn : 'easingOutQuint',
        easingOut : 'easingInQuint',*/

        showCloseButton : true,
        closeOnOverlay : true,
        enableEscapeButton : true,
        useBeforeUnload : true,
        onClose : function(){}
    };
    
})(jQuery);