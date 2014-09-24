;(function($) {
(function(e){"use strict";function t(t){var n=e("");try{n=e(t).clone()}catch(r){n=e("<span />").html(t)}return n}function n(e){return!!(typeof Node==="object"?e instanceof Node:e&&typeof e==="object"&&typeof e.nodeType==="number"&&typeof e.nodeName==="string")}e.print=e.fn.print=function(){var r,i,s=this;if(s instanceof e){s=s.get(0)}if(n(s)){i=e(s);if(arguments.length>0){r=arguments[0]}}else{if(arguments.length>0){i=e(arguments[0]);if(n(i[0])){if(arguments.length>1){r=arguments[1]}}else{r=arguments[0];i=e("html")}}else{i=e("html")}}var o={globalStyles:true,mediaPrint:false,stylesheet:null,noPrintSelector:".no-print",iframe:true,append:null,prepend:null};r=e.extend({},o,r||{});var u=e("");if(r.globalStyles){u=e("style, link, meta, title")}else if(r.mediaPrint){u=e("link[media=print]")}if(r.stylesheet){u=e.merge(u,e('<link rel="stylesheet" href="'+r.stylesheet+'">'))}var a=i.clone();a=e("<span/>").append(a);a.find(r.noPrintSelector).remove();a.append(u.clone());a.append(t(r.append));a.prepend(t(r.prepend));var f=a.html();a.remove();var l,c;if(r.iframe){try{var h=e(r.iframe+"");var p=h.length;if(p===0){h=e('<iframe height="0" width="0" border="0" wmode="Opaque"/>').prependTo("body").css({position:"absolute",top:-999,left:-999})}l=h.get(0);l=l.contentWindow||l.contentDocument||l;c=l.document||l.contentDocument||l;c.open();c.write(f);c.close();setTimeout(function(){l.focus();l.print();setTimeout(function(){if(p===0){h.remove()}},100)},250)}catch(d){console.error("Failed to print from iframe",d.stack,d.message);l=window.open();l.document.write(f);l.document.close();l.focus();l.print();l.close()}}else{l=window.open();l.document.write(f);l.document.close();l.focus();l.print();l.close()}return this}})(jQuery);
$.fn.timer = function( useroptions ){ 
        var $this = $(this), opt,newVal, count = 0; 

        opt = $.extend( { 
            // Config 
            'timer' : 300, // 300 second default
            'width' : 24 ,
            'height' : 24 ,
            'fgColor' : "#ED7A53" ,
            'bgColor' : "#232323" 
            }, useroptions 
        ); 

        
        $this.knob({ 
            'min':0, 
            'max': opt.timer, 
            'readOnly': true, 
            'width': opt.width, 
            'height': opt.height, 
            'fgColor': opt.fgColor, 
            'bgColor': opt.bgColor,                 
            'displayInput' : false, 
            'dynamicDraw': false, 
            'ticks': 0, 
            'thickness': 0.1 
        }); 

        setInterval(function(){ 
            newVal = ++count; 
            $this.val(newVal).trigger('change'); 
        }, 1000); 
    };

// Necessary functions
function runnecessaryfunctions(){
  
  jQuery('.fitvids').fitVids();
  jQuery('.tip').tooltip();
  jQuery('.nav-tabs li:first a').tab('show');
  jQuery('.nav-tabs li a').click(function(){
    $(this).tab('show');
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
$('.quiz_results_popup').magnificPopup({
    type: 'ajax',
    alignTop: true,
    fixedContentPos: true,
    fixedBgPos: true,
    overflowY: 'auto',
    closeBtnInside: true,
    preloader: false,
    midClick: true,
    removalDelay: 300,
    mainClass: 'my-mfp-zoom-in',
    callbacks: {
             parseAjax: function( mfpResponse ) {
              mfpResponse.data = $(mfpResponse.data).find('#item-body');
            }
          }
});
if ( typeof vc_js == 'function' ) { 
    window.vc_js();
  }

}

//AJAX Comments
function ajaxsubmit_comments(){
  $('#question').each(function(){

   var $this=$(this);
  $('#submit').click(function(event){
    event.preventDefault();
    var value = '';

    $('#ajaxloader').removeClass('disabled');
    $('#question').css('opacity',0.2);

    if($this.find('input[type="radio"]:checked').length)
    $this.find('input[type="radio"]:checked').each(function(){
      value = $(this).val();
    });
    if($this.find('input[type="checkbox"]:checked').length)
    $this.find('input[type="checkbox"]:checked').each(function(){
      value= $(this).val()+','+value;
    });

    if($this.find('.vibe_fillblank').length)
    $this.find('.vibe_fillblank').each(function(){
      value += $(this).text();  
    });
    if($this.find('#vibe_select_dropdown').length)
    value = $this.find('#vibe_select_dropdown').val();

    if($this.find('.matchgrid_options li.match_option').length){
        $('.matchgrid_options li.match_option').each(function(){
        var id = $(this).attr('id');
        if( jQuery.isNumeric(id))
          value +=id+',';
      });  
    }

    if($('#comment').hasClass('option_value'))
      $('#comment.option_value').val(value);

    $('#commentform').submit();
  });
    
  var commentform=$('#commentform'); // find the comment form
  var statusdiv=$('#comment-status'); // define the infopanel
  var qid = statusdiv.attr('data-quesid');
  
  commentform.submit(function(){

    var formdata=commentform.serialize();

    statusdiv.html('<p>'+vibe_course_module_strings.processing+'</p>');

    var formurl=commentform.attr('action');

    $.ajax({
      type: 'post',
      url: formurl,
      data: formdata,
      error: function(XMLHttpRequest, textStatus, errorThrown){
        $('#ajaxloader').addClass('disabled');
        $('#question').css('opacity',1);
        statusdiv.html('<p class="wdpajax-error">'+vibe_course_module_strings.too_fast_answer+'</p>');
        setTimeout(function(){statusdiv.hide(300).html('').show();}, 2000);
      },
      success: function(data, textStatus){
        $('#question').css('opacity',1);
        $('#ajaxloader').addClass('disabled');
        if(data=="success"){
          statusdiv.html('<p class="ajax-success" >'+vibe_course_module_strings.answer_saved+'</p>');
          setTimeout(function(){statusdiv.hide(300).html('').show();}, 2000);
          $('#ques'+qid).addClass('done');
          $('.reset_answer').removeClass('hide');
        }
        else{
          statusdiv.html('<p class="ajax-error" >'+vibe_course_module_strings.saving_answer+'</p>'); 
          setTimeout(function(){statusdiv.hide(300).html('').show();}, 2000);
        }
      }
    });
    return false;
    });
  }); 
} // END Function



jQuery(document).ready( function($) {
  var cookieValue = $.cookie("course_directory");
      if ((cookieValue !== null) && cookieValue == 'grid') {      
        $('#course-list').addClass('grid');
        $('#list_view').removeClass('active');
        $('#grid_view').addClass('active');
      }
  $('.quiz_results_popup').magnificPopup({
      type: 'ajax',
      alignTop: true,
      fixedContentPos: true,
      fixedBgPos: true,
      overflowY: 'auto',
      closeBtnInside: true,
      preloader: false,
      midClick: true,
      removalDelay: 300,
      mainClass: 'my-mfp-zoom-in',
      callbacks: {
          parseAjax: function( mfpResponse ) {
                mfpResponse.data = $(mfpResponse.data).find('#item-body');
              },
          ajaxContentAdded: function() {        {
                $('#prev_results a').on('click',function(event){
                    event.preventDefault();
                    $(this).toggleClass('show');
                    $('.prev_quiz_results').toggleClass('show');
                });
                $('.print_results').click(function(event){
                    event.preventDefault();
                    $('.quiz_result').print();
                });
              }
            }
      }      
  });    
	$('#grid_view').click(function(){
    $('#course-list').addClass('grid');
    $.cookie('course_directory', 'grid', { expires: 2 ,path: '/'});
    $('#list_view').removeClass('active');
    $(this).addClass('active');
  });
  $('#list_view').click(function(){
    $('#course-list').removeClass('grid');
    $.removeCookie('course_directory', { path: '/' });
    $('#grid_view').removeClass('active');
    $(this).addClass('active');
  });
	$("#average .dial").knob({
	  	'readOnly': true, 
	    'width': 120, 
	    'height': 120, 
	    'fgColor': vibe_course_module_strings.theme_color, 
	    'bgColor': '#f6f6f6',   
	    'thickness': 0.1
	});
	$("#pass .dial").knob({
	  	'readOnly': true, 
	    'width': 120, 
	    'height': 120, 
	    'fgColor': vibe_course_module_strings.theme_color, 
	    'bgColor': '#f6f6f6',   
	    'thickness': 0.1
	});
	$("#badge .dial").knob({
	  	'readOnly': true, 
	    'width': 120, 
	    'height': 120, 
	    'fgColor': vibe_course_module_strings.theme_color, 
	    'bgColor': '#f6f6f6',   
	    'thickness': 0.1
	});

	$(".course_quiz .dial").knob({
	  	'readOnly': true, 
	    'width': 120, 
	    'height': 120, 
	    'fgColor': vibe_course_module_strings.theme_color, 
	    'bgColor': '#f6f6f6',   
	    'thickness': 0.1 
	});

  //RESET Ajx
$( 'body' ).delegate( '.remove_user_course','click',function(event){
      event.preventDefault();
      var course_id=$(this).attr('data-course');
      var user_id=$(this).attr('data-user');
      $(this).addClass('animated spin');
      var $this = $(this);
      $.confirm({
          text: vibe_course_module_strings.remove_user_text,
          confirm: function() {
             $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'remove_user_course', 
                            security: $('#security').val(),
                            id: course_id,
                            user: user_id
                          },
                    cache: false,
                    success: function (html) {
                        $(this).removeClass('animated');
                        $(this).removeClass('spin');
                        runnecessaryfunctions();
                        $('#message').html(html);
                        $('#s'+user_id).fadeOut('fast');
                    }
            });
          },
          cancel: function() {
              $this.removeClass('animated');
              $this.removeClass('spin');
          },
          confirmButton: vibe_course_module_strings.remove_user_button,
          cancelButton: vibe_course_module_strings.cancel
      });
	});

$( 'body' ).delegate( '.reset_course_user','click',function(event){
      event.preventDefault();
      var course_id=$(this).attr('data-course');
      var user_id=$(this).attr('data-user');
      $(this).addClass('animated spin');
      var $this = $(this);
      $.confirm({
        text: vibe_course_module_strings.reset_user_text,
          confirm: function() {
          $.ajax({
                  type: "POST",
                  url: ajaxurl,
                  data: { action: 'reset_course_user', 
                          security: $('#security').val(),
                          id: course_id,
                          user: user_id
                        },
                  cache: false,
                  success: function (html) {
                      $this.removeClass('animated');
                      $this.removeClass('spin');
                      $('#message').html(html);
                  }
          });
         }, 
         cancel: function() {
              $this.removeClass('animated');
              $this.removeClass('spin');
          },
          confirmButton: vibe_course_module_strings.reset_user_button,
          cancelButton: vibe_course_module_strings.cancel
        });
	});

  
$( 'body' ).delegate( '.course_stats_user', 'click', function(event){
      event.preventDefault();
      var $this=$(this);
      var course_id=$this.attr('data-course');
      var user_id=$this.attr('data-user');
      
      if($this.hasClass('already')){
      	$('#s'+user_id).find('.course_stats_user').fadeIn('fast');
      }else{
      	  $this.addClass('animated spin');		
	      $.ajax({
	              type: "POST",
	              url: ajaxurl,
	              data: { action: 'course_stats_user', 
	                      security: $('#security').val(),
	                      id: course_id,
	                      user: user_id
	                    },
	              cache: false,
	              success: function (html) {
	                  $this.removeClass('animated');
	                  $this.removeClass('spin');
	                  $this.addClass('already');
	                  $('#s'+user_id).append(html);
	                  $(".dial").knob({
	                  	'readOnly': true, 
			            'width': 160, 
			            'height': 160, 
			            'fgColor': vibe_course_module_strings.theme_color, 
			            'bgColor': '#f6f6f6',   
			            'thickness': 0.3 
	                  });
	              }
	      });
  		}
	});


  $('#calculate_avg_course').click(function(event){
      event.preventDefault();
      var course_id=$(this).attr('data-courseid');
      $(this).addClass('animated spin');

      $.ajax({
              type: "POST",
              url: ajaxurl,
              data: { action: 'calculate_stats_course', 
                      security: $('#security').val(),
                      id: course_id
                    },
              cache: false,
              success: function (html) {
                  $(this).removeClass('animated');
                  $(this).removeClass('spin');
                  $('#message').html(html);
                   setTimeout(function(){location.reload();}, 3000);
              }
      });

  });

  $('.reset_quiz_user').click(function(event){
      event.preventDefault();
      var course_id=$(this).attr('data-quiz');
      var user_id=$(this).attr('data-user');
      $(this).addClass('animated spin');
      var $this = $(this);
      $.confirm({
          text: vibe_course_module_strings.quiz_rest,
          confirm: function() {

      $.ajax({
              type: "POST",
              url: ajaxurl,
              data: { action: 'reset_quiz', 
                      security: $('#qsecurity').val(),
                      id: course_id,
                      user: user_id
                    },
              cache: false,
              success: function (html) {
                  $(this).removeClass('animated');
                  $(this).removeClass('spin');
                  $('#message').html(html);
                  $('#qs'+user_id).fadeOut('fast');
              }
      });
      }, 
       cancel: function() {
            $this.removeClass('animated');
            $this.removeClass('spin');
        },
        confirmButton: vibe_course_module_strings.quiz_rest_button,
        cancelButton: vibe_course_module_strings.cancel
      });
  });

  $('.evaluate_quiz_user').click(function(event){
      event.preventDefault();
      var quiz_id=$(this).attr('data-quiz');
      var user_id=$(this).attr('data-user');
      $(this).addClass('animated spin');

      $.ajax({
              type: "POST",
              url: ajaxurl,
              data: { action: 'evaluate_quiz', 
                      security: $('#qsecurity').val(),
                      id: quiz_id,
                      user: user_id
                    },
              cache: false,
              success: function (html) {
                  $(this).removeClass('animated');
                  $(this).removeClass('spin');
                  $('.quiz_students').html(html);
                  calculate_total_marks();
              }
      });
  });


 $('.evaluate_course_user').click(function(event){
      event.preventDefault();
      var course_id=$(this).attr('data-course');
      var user_id=$(this).attr('data-user');
      $(this).addClass('animated spin');

      $.ajax({
              type: "POST",
              url: ajaxurl,
              data: { action: 'evaluate_course', 
                      security: $('#security').val(),
                      id: course_id,
                      user: user_id
                    },
              cache: false,
              success: function (html) {
                  $(this).removeClass('animated');
                  $(this).removeClass('spin');
                  $('.course_students').html(html);
                  calculate_total_marks();
              }
      });
  });

$( 'body' ).delegate( '.reset_answer', 'click', function(event){
       event.preventDefault();
      var ques_id=$('#comment-status').attr('data-quesid');
      var $this = $(this);
      var qid = $('#comment-status').attr('data-quesid');
      $this.prepend('<i class="icon-sun-stroke animated spin"></i>');
      $.ajax({
              type: "POST",
              url: ajaxurl,
              data: { action: 'reset_question_answer', 
                      security: $this.attr('data-security'),
                      ques_id: ques_id,
                    },
              cache: false,
              success: function (html) {
                  $this.find('i').remove();
                   $('#comment-status').html(html);
                   $('#ques'+qid).removeClass('done');
                   setTimeout(function(){ $this.addClass('hide');}, 500);
              }
      });
});

$( 'body' ).delegate( '#course_complete', 'click', function(event){
      event.preventDefault();
      var $this=$(this);
      var user_id=$this.attr('data-user');
      var course = $this.attr('data-course');
      var marks = parseInt($('#course_marks_field').val());
      if(marks <= 0){
        alert('Enter Marks for User');
        return;
      }

      $this.prepend('<i class="icon-sun-stroke animated spin"></i>');
      $.ajax({
              type: "POST",
              url: ajaxurl,
              data: { action: 'complete_course_marks', 
                      course: course,
                      user: user_id,
                      marks:marks
                    },
              cache: false,
              success: function (html) {
                  $this.find('i').remove();
                  $this.html(html);
              }
      });
});

  // Registeration BuddyPress
  $('.register-section h4').click(function(){
      $(this).toggleClass('show');
      $(this).parent().find('.editfield').toggle('fast');
  });

});

$( 'body' ).delegate( '.hide_parent', 'click', function(event){
	$(this).parent().fadeOut('fast');
});


$( 'body' ).delegate( '.give_marks', 'click', function(event){
	    event.preventDefault();
	    var $this=$(this);
	    var ansid=$this.attr('data-ans-id');
	    var aval = $('#'+ansid).val();
	    $this.prepend('<i class="icon-sun-stroke animated spin"></i>');
	    $.ajax({
	            type: "POST",
	            url: ajaxurl,
	            data: { action: 'give_marks', 
	                    aid: ansid,
	                    aval: aval
	                  },
	            cache: false,
	            success: function (html) {
	                $this.find('i').remove();
	                $this.html(vibe_course_module_strings.marks_saved);
	            }
	    });
});

$( 'body' ).delegate( '#mark_complete', 'click', function(event){
    event.preventDefault();
    var $this=$(this);
    var quiz_id=$this.attr('data-quiz');
    var user_id = $this.attr('data-user');
    var marks = parseInt($('#total_marks strong > span').text());
    $this.prepend('<i class="icon-sun-stroke animated spin"></i>');
    $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'save_quiz_marks', 
                    quiz_id: quiz_id,
                    user_id: user_id,
                    marks: marks,
                  },
            cache: false,
            success: function (html) {
                $this.find('i').remove();
                $this.html(vibe_course_module_strings.quiz_marks_saved);
            }
    });
});

function calculate_total_marks(){
  $('.question_marks').blur(function(){
      var marks=parseInt(0);
      var $this = $('#total_marks strong > span');
      $('.question_marks').each(function(){
          if($(this).val())
            marks = marks + parseInt($(this).val());
        });
      $this.html(marks);
  });
}


$( 'body' ).delegate( '.submit_quiz', 'click', function(event){
    event.preventDefault();
    if($(this).hasClass('disabled')){
      return false;
    }
     
    var $this = $(this);
    var quiz_id=$(this).attr('data-quiz');
    $this.prepend('<i class="icon-sun-stroke animated spin"></i>');
    $('#question').addClass('quiz_submitted_fade');
    $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'submit_quiz', 
                    start_quiz: $('#start_quiz').val(),
                    id: quiz_id
                  },
            cache: false,
            success: function (html) {
                $('#ajaxloader').removeClass('disabled');
                $('#question').css('opacity',0.2);
                $this.find('i').remove();
                location.reload();
            }
    });
});

// QUIZ RELATED FUCNTIONS
// START QUIZ AJAX
jQuery(document).ready( function($) {
	$('.begin_quiz').click(function(event){
	    event.preventDefault();
	    var $this = $(this);
	    var quiz_id=$(this).attr('data-quiz');
	    $this.prepend('<i class="icon-sun-stroke animated spin"></i>');
	    $.ajax({
	            type: "POST",
	            url: ajaxurl,
	            data: { action: 'begin_quiz', 
	                    start_quiz: $('#start_quiz').val(),
	                    id: quiz_id
	                  },
	            cache: false,
	            success: function (html) {
	                $this.find('i').remove();
	                $('.content').fadeOut("fast");
	                $('.content').html(html);
	                $('.content').fadeIn("fast");
	                ajaxsubmit_comments();
	                var ques=$($.parseHTML(html)).filter("#question");
	                var q='#ques'+ques.attr('data-ques');

	                $('.quiz_timeline').find('.active').removeClass('active');
	                $(q).addClass('active');
                  $('#question').trigger('question_loaded');
	                if(ques != 'undefined'){
	                  $('.quiz_timer').trigger('activate');
	                }

                $('.tip').tooltip();
                $('.begin_quiz').each(function(){
                    $(this).removeClass('begin_quiz');
                    $(this).addClass('submit_quiz');
                    $(this).text(vibe_course_module_strings.submit_quiz);
                });
            }
        });
	});
});



$( 'body' ).delegate( '.show_hint', 'click', function(event){
  event.preventDefault();
  $(this).toggleClass('active');
  $('.hint').toggle(400);
});

$('.show_explaination').click(function(event){
    event.preventDefault();
    var $this = $(this);
    $this.toggleClass('active');
    $this.closest('li').find('.explaination').toggle();
});

$( 'body' ).delegate( '.quiz_question', 'click', function(event){
    event.preventDefault();
    var $this = $(this);
    var quiz_id=$(this).attr('data-quiz');
    var ques_id=$(this).attr('data-qid');
    $this.prepend('<i class="icon-sun-stroke animated spin"></i>');
    $('#ajaxloader').removeClass('disabled');
    $('#question').css('opacity',0.2);
    $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'quiz_question', 
                    start_quiz: $('#start_quiz').val(),
                    quiz_id: quiz_id,
                    ques_id: ques_id
                  },
            cache: false,
            success: function (html) {
                $this.find('i').remove();
                $('.content').html(html);
                $('#ajaxloader').addClass('disabled');
                $('#question').css('opacity',1);
                ajaxsubmit_comments();
                var ques=$($.parseHTML(html)).filter("#question");
                var q='#ques'+ques.attr('data-ques');
                $('.quiz_timeline').find('.active').removeClass('active');
                $(q).addClass('active');
                $('#question').trigger('question_loaded');
                $('.tip').tooltip();
                if(ques != 'undefined')
                  $('.quiz_timer').trigger('activate');

                //Fill in the Blank Live EDIT
                $(".live-edit").liveEdit({
                    afterSaveAll: function(params) {
                      return false;
                    }
                });


                //Match question type
                $('.question_options.match').droppable({
                  drop: function( event, ui ){
                    $(ui.draggable).removeAttr('style');
                    $( this )
                          .addClass( "ui-state-highlight" )
                          .append($(ui.draggable))
                  }
                });
                $('.question_options.match li').draggable({
                  revert: "invalid",
                  containment:'#question'
                });
                $( ".matchgrid_options li" ).droppable({
                    activeClass: "ui-state-default",
                    hoverClass: "ui-state-hover",
                    drop: function( event, ui ){
                      childCount = $(this).children().length;
                      $(ui.draggable).removeAttr('style');
                      if (childCount !=0){
                          return;
                      }   
                      
                       $( this )
                          .addClass( "ui-state-highlight" )
                          .append($(ui.draggable))
                    }
                  });
                if($('.matchgrid_options').hasClass('saved_answer')){
                    var id;
                    $('.matchgrid_options li').each(function(index,value){
                        id = $('.matchgrid_options').attr('data-match'+index);
                        $(this).append($('#'+id));
                    });
                } 

                //END Match question type
                //
                if($('.timeline_wrapper').height() > $('.quiz_timeline').height()){
                     $('.quiz_timeline').animate({scrollTop: $(q).position().top}, 'slow');
                }
            }
      });
});

$( 'body' ).delegate( '#question', 'question_loaded',function(){
  
  jQuery('.question_options.sort').each(function(){

    var defaultanswer='1';
    var lastindex = $('ul.question_options li').size();
    if(lastindex>1)
    for(var i=2;i<=lastindex;i++){
      defaultanswer = defaultanswer+','+i;
    }
    $('#comment').val(defaultanswer);
    $('#comment').trigger('change');
    jQuery('.question_options.sort').sortable({
      revert: true,
      cursor: 'move',
      refreshPositions: true, 
      opacity: 0.6,
      scroll:true,
      containment: 'parent',
      placeholder: 'placeholder',
      tolerance: 'pointer',
      update: function( event, ui ) {
          var order = $('.question_options.sort').sortable('toArray').toString();
          $('#comment').val(order);
          $('#comment').trigger('change');
      }
    }).disableSelection();
  });
});



jQuery(document).ready( function($) {
 

  $('.quiz_timer').one('activate',function(){

    var qtime = parseInt($(this).attr('data-time'));

    var $timer =$(this).find('.timer');
    var $this=$(this);
    
    $timer.timer({
      'timer': qtime,
      'width' : 200 ,
      'height' : 200 ,
      'fgColor' : vibe_course_module_strings.theme_color ,
      'bgColor' : vibe_course_module_strings.single_dark_color 
    });

    var $timer =$(this).find('.timer');

    $timer.on('change',function(){
        var countdown= $this.find('.countdown');
        var val = parseInt($timer.attr('data-timer'));
        if(val > 0){
          val--;
          $timer.attr('data-timer',val);
          var $text='';
          if(val > 60){
            $text = Math.floor(val/60) + ':' + ((parseInt(val%60) < 10)?'0'+parseInt(val%60):parseInt(val%60)) + '';
          }else{
            $text = '00:'+ ((val < 10)?'0'+val:val);
          }

          countdown.html($text);
        }else{
            countdown.html('Timeout');
            if(!$('.submit_quiz').hasClass('triggerred')){
                $('.submit_quiz').trigger('click');
                $('.submit_quiz').addClass('triggerred');
            } 

            $('.quiz_timer').trigger('end');
        }  
    });
    
  });

  $('.quiz_timer').one('deactivate',function(){
    var qtime = parseInt($(this).attr('data-time'));
    var $timer =$(this).find('.timer');
    var $this=$(this);
    
    $timer.knob({
        'readonly':true,
        'max': qtime,
        'width' : 200 ,
        'height' : 200 ,
        'fgColor' : vibe_course_module_strings.theme_color ,
        'bgColor' : vibe_course_module_strings.single_dark_color,
        'thickness': 0.2 ,
        'readonly':true 
      });
    event.stopPropagation();
  });

  $('.quiz_timer').one('end',function(event){
    var qtime = parseInt($(this).attr('data-time'));
    var $timer =$(this).find('.timer');
    var $this=$(this);
    
    $timer.knob({
        'readonly':true,
        'max': qtime,
        'width' : 200 ,
        'height' : 200 ,
        'fgColor' : vibe_course_module_strings.theme_color ,
        'bgColor' : vibe_course_module_strings.single_dark_color,
        'thickness': 0.2 ,
        'readonly':true 
      });
    event.stopPropagation();
  });
// Timer function runs after Trigger event definition
$('.quiz_timer').each(function(){
    var qtime = parseInt($(this).attr('data-time'));
    var $timer =$(this).find('.timer');
    $timer.knob({
      'readonly':true,
      'max': qtime,
      'width' : 200 ,
      'height' : 200 ,
      'fgColor' : vibe_course_module_strings.theme_color ,
      'bgColor' : vibe_course_module_strings.single_dark_color,
      'thickness': 0.2 ,
      'readonly':true 
    });
    if($(this).hasClass('start')){
      $('.quiz_timer').trigger('activate');
    }
});

jQuery('.question_options.sort').each(function(){
    var defaultanswer='1';
    var lastindex = $('ul.question_options li').size();
    if(lastindex>1)
    for(var i=2;i<=lastindex;i++){
      defaultanswer = defaultanswer+','+i;
    }
    $('#comment').val(defaultanswer);
    $('#comment').trigger('change');
    jQuery('.question_options.sort').sortable({
      revert: true,
      cursor: 'move',
      refreshPositions: true, 
      opacity: 0.6,
      scroll:true,
      containment: 'parent',
      placeholder: 'placeholder',
      tolerance: 'pointer',
      update: function( event, ui ) {
          var order = $('.question_options.sort').sortable('toArray').toString();
          $('#comment').val(order);
          $('#comment').trigger('change');
      }
    }).disableSelection();
  });
}); 

$( 'body' ).delegate( '.expand_message', 'click', function(event){
  event.preventDefault();
  $('.bulk_message').toggle('slow');
});

$( 'body' ).delegate( '.expand_add_students', 'click', function(event){
  event.preventDefault();
  $('.bulk_add_students').toggle('slow');
});

$( 'body' ).delegate( '.expand_assign_students', 'click', function(event){
  event.preventDefault();
  $('.bulk_assign_students').toggle('slow');
});

$( 'body' ).delegate( '#send_course_message', 'click', function(event){
  event.preventDefault();
  var members=[];

  var $this = $(this);
  var defaultxt=$this.html();
  $this.html('<i class="icon-sun-stroke animated spin"></i> '+vibe_course_module_strings.sending_messages);
  var i=0;
  $('.member').each(function(){
    if($(this).is(':checked')){
      members[i]=$(this).val();
      i++;
    }
  });
  $.ajax({
        type: "POST",
        url: ajaxurl,
        data: { action: 'send_bulk_message', 
                security: $('#buk_action').val(),
                course:$this.attr('data-course'),
                sender: $('#sender').val(),
                members: JSON.stringify(members),
                subject: $('#bulk_subject').val(),
                message: $('#bulk_message').val(),
              },
        cache: false,
        success: function (html) {
            $('#send_course_message').html(html);
            setTimeout(function(){$this.html(defaultxt);}, 5000);
        }
    });    
});

$( 'body' ).delegate( '#add_student_to_course', 'click', function(event){
  event.preventDefault();
  var $this = $(this);
  var defaultxt=$this.html();
  var students = $('#student_usernames').val();

  if(students.length <= 0){ 
    $('#add_student_to_course').html(vibe_course_module_strings.unable_add_students);
    setTimeout(function(){$this.html(defaultxt);}, 2000);
    return;
  }

  $this.html('<i class="icon-sun-stroke animated spin"></i>'+vibe_course_module_strings.adding_students);
  var i=0;
  $.ajax({
        type: "POST",
        url: ajaxurl,
        data: { action: 'add_bulk_students', 
                security: $('#buk_action').val(),
                course:$this.attr('data-course'),
                members: students,
              },
        cache: false,
        success: function (html) {
          if(html.length && html !== '0'){
            $('#add_student_to_course').html(vibe_course_module_strings.successfuly_added_students);
            $('ul.course_students').append(html);
          }else{
            $('#add_student_to_course').html(vibe_course_module_strings.unable_add_students);
          }
            
            setTimeout(function(){$this.html(defaultxt);}, 3000);
        }
    });    
});


$( 'body' ).delegate( '#assign_course_badge_certificate', 'click', function(event){
  event.preventDefault();
  var members=[];

  var $this = $(this);
  var defaultxt=$this.html();
  $this.html('<i class="icon-sun-stroke animated spin"></i> '+vibe_course_module_strings.processing);
  var i=0;
  $('.member').each(function(){
    if($(this).is(':checked')){
      members[i]=$(this).val();
      i++;
    }
  });

  $.ajax({
        type: "POST",
        url: ajaxurl,
        data: { action: 'assign_badge_certificates', 
                security: $('#buk_action').val(),
                course: $this.attr('data-course'),
                members: JSON.stringify(members),
                assign_action: $('#assign_action').val(),
              },
        cache: false,
        success: function (html) {
            $this.html(html);
            setTimeout(function(){$this.html(defaultxt);}, 5000);
        }
    });    
});


// Course Unit Traverse
$( 'body' ).delegate( '.unit', 'click', function(event){
    event.preventDefault();
    if($(this).hasClass('disabled')){
      return false;
    }
    
    var $this = $(this);
    var unit_id=$(this).attr('data-unit');
    $this.prepend('<i class="icon-sun-stroke animated spin"></i>');
    
    $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'unit_traverse', 
                    security: $('#hash').val(),
                    course_id: $('#course_id').val(),
                    id: unit_id
                  },
            cache: false,
            success: function (html) {
                 $('body,html').animate({
                    scrollTop: 0
                  }, 1200);
                $this.find('i').remove();

                $('.unit_content').fadeOut("fast");
                $('.unit_content').html(html);
                $('.unit_content').fadeIn("fast");
                $('.unit_content').trigger('unit_traverse');

                var unit=$($.parseHTML(html)).filter("#unit");
                var u='#unit'+unit.attr('data-unit');
                $('.course_timeline').find('.active').removeClass('active');
                $(u).addClass('active');

                $('audio,video').mediaelementplayer({
                    success: function(media,node,player) { 
                      $('#mark-complete').trigger('media_loaded');
                      $('.mejs-container').each(function(){
                        $(this).addClass('mejs-mejskin');
                      });
                      media.addEventListener('ended', function (e) {
                        $('#mark-complete').trigger('media_complete');
                      });
                    }
                });
                runnecessaryfunctions();

                if(typeof unit != 'undefined')
                  $('.unit_timer').trigger('activate');
            }
    });
});

$( 'body' ).delegate( '#mark-complete', 'media_loaded', function(event){
  event.preventDefault();
  if($(this).hasClass('tip')){
      $(this).addClass('disabled');
  }
});

$( 'body' ).delegate( '#mark-complete', 'media_complete', function(event){ 
  event.preventDefault();
  if($(this).hasClass('tip')){
    $(this).removeClass('disabled');
    $(this).removeClass('tip');
    $(this).tooltip('destroy');
    jQuery('.tip').tooltip();
  }  
});


$( 'body' ).delegate( '#mark-complete', 'click', function(event){
    event.preventDefault();
    if($(this).hasClass('disabled')){
      return false;
    }

    var $this = $(this);
    var unit_id=$(this).attr('data-unit');
    $this.prepend('<i class="icon-sun-stroke animated spin"></i>');
    $('body').find('.course_progressbar').removeClass('increment_complete');
    $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'complete_unit', 
                    security: $('#hash').val(),
                    course_id: $('#course_id').val(),
                    id: unit_id
                  },
            cache: false,
            success: function (html) {
              console.log(html);
                $this.find('i').remove();
                $this.html('<i class="icon-check"></i>');
                $('.course_timeline').find('.active').addClass('done');
                $('body').find('.course_progressbar').trigger('increment');
                $('#mark-complete').addClass('disabled');
                
                if(html.length > 0){
                    $('#next_unit').removeClass('hide');
                    $('#next_unit').attr('data-unit',html);  
                    $('#unit'+html).find('a').addClass('unit');
                    $('#unit'+html).find('a').attr('data-unit',html);
                }
                if(typeof unit != 'undefined')
                  $('.unit_timer').trigger('finish');
            }
    });
});


$('.course_progressbar').on('increment',function(event){

  if($(this).hasClass('increment_complete')){
    event.stopPropagation();
    return false;
  }else{
    var iunit = parseInt($(this).attr('data-increase-unit'));
    var per = parseInt($(this).attr('data-value'));
    newper = iunit + per;
    $(this).find('.bar').css('width',newper+'%');
    $(this).find('.bar span').html(newper + '%');
    $(this).addClass('increment_complete');
    $(this).attr('data-value',newper);
  }
  event.stopPropagation();
  return false;
  
});



jQuery(document).ready(function($){
	$('.showhide_indetails').click(function(event){
		event.preventDefault();
		$(this).find('i').toggleClass('icon-minus');
		$(this).parent().find('.in_details').toggle();
	});


$('.ajax-certificate').each(function(){
    $(this).magnificPopup({
          type: 'ajax',
          fixedContentPos: true,
          alignTop:true,
          preloader: false,
          midClick: true,
          removalDelay: 300,
          showCloseBtn:false,
          mainClass: 'mfp-with-zoom',
          callbacks: {
             parseAjax: function( mfpResponse ) {
              mfpResponse.data = $(mfpResponse.data).find('#certificate');
            },
            ajaxContentAdded: function() {
              html2canvas($('#certificate'), {
                  onrendered: function(canvas) {
                      var data = canvas.toDataURL();
                      $('#certificate .certificate_content').html('<img src="'+data+'" />');
                      $('#certificate').trigger('generate_certificate');
                  }
              });
            }
          }
      });
});

$( 'body' ).delegate( '.print_unit', 'click', function(event){
    $('.unit_content').print();
});

$( 'body' ).delegate( '.printthis', 'click', function(event){
    $(this).parent().print();
});

$( 'body' ).delegate( '#certificate', 'generate_certificate', function(event){
    $(this).addClass('certificate_generated');
});

$( 'body' ).delegate( '.certificate_print', 'click', function(event){
    event.preventDefault();
    $(this).parent().parent().print();
});

$('.widget_carousel').flexslider({
  animation: "slide",
  controlNav: false,
  directionNav: true,
  animationLoop: true,
  slideshow: false,
  prevText: "<i class='icon-arrow-1-left'></i>",
  nextText: "<i class='icon-arrow-1-right'></i>",
});

  /*=== Quick tags ===*/
  $( 'body' ).delegate( '.unit-page-links a', 'click', function(event){
        if($('body').hasClass('single-unit'))
          return;

        event.preventDefault();
        
        var $this=$(this);
        $this.prepend('<i class="icon-sun-stroke animated spin"></i>');
        $( ".main_unit_content" ).load( $this.attr('href') +" .single_unit_content" );
        runnecessaryfunctions();
        $('body').trigger('unit_loaded');
        $this.find('i').remove();
        $( ".main_unit_content" ).trigger('unit_reload');
    });

  });	
})(jQuery);