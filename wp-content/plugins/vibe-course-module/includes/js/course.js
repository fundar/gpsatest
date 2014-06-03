;(function($) {

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
}
//AJAX Comments
function ajaxsubmit_comments(){
  $('#question').each(function(){

   var $this=$(this);
  $('#submit').click(function(event){
    event.preventDefault();
    var value = '';

    $this.find('input[type="radio"]:checked').each(function(){
      value= $(this).val();

    });

    $this.find('input[type="checkbox"]:checked').each(function(){
      value= $(this).val()+','+value;
    });
    
    $('#comment.option_value').val(value);
    $('#commentform').submit();
  });
    
  var commentform=$('#commentform'); // find the comment form
  var statusdiv=$('#comment-status'); // define the infopanel
  var qid = statusdiv.attr('data-quesid');
  
  commentform.submit(function(){

    var formdata=commentform.serialize();

    statusdiv.html('<p>Processing...</p>');

    var formurl=commentform.attr('action');

    $.ajax({
      type: 'post',
      url: formurl,
      data: formdata,
      error: function(XMLHttpRequest, textStatus, errorThrown){
        statusdiv.html('<p class="wdpajax-error">Too Fast or You might have not marked the answer.</p>');
      },
      success: function(data, textStatus){
        if(data=="success"){
          statusdiv.html('<p class="ajax-success" >Answer Saved.</p>');
          $('#ques'+qid).addClass('done');
        }
        else
          statusdiv.html('<p class="ajax-error" >Saving Answer...please wait</p>');
          //commentform.find('textarea[name=comment]').val('');
        }
    });
    return false;
    });
  }); 
} // END Function



jQuery(document).ready( function($) {
	
	$("#average .dial").knob({
	  	'readOnly': true, 
	    'width': 120, 
	    'height': 120, 
	    'fgColor': '#78c8ce', 
	    'bgColor': '#f6f6f6',   
	    'thickness': 0.1
	});
	$("#pass .dial").knob({
	  	'readOnly': true, 
	    'width': 120, 
	    'height': 120, 
	    'fgColor': '#78c8ce', 
	    'bgColor': '#f6f6f6',   
	    'thickness': 0.1
	});
	$("#badge .dial").knob({
	  	'readOnly': true, 
	    'width': 120, 
	    'height': 120, 
	    'fgColor': '#78c8ce', 
	    'bgColor': '#f6f6f6',   
	    'thickness': 0.1
	});

	$(".course_quiz .dial").knob({
	  	'readOnly': true, 
	    'width': 120, 
	    'height': 120, 
	    'fgColor': '#78c8ce', 
	    'bgColor': '#f6f6f6',   
	    'thickness': 0.1 
	});
  //RESET Ajx
  $('.remove_user_course').click(function(event){
      event.preventDefault();
      var course_id=$(this).attr('data-course');
      var user_id=$(this).attr('data-user');
      $(this).addClass('animated spin');
      var $this = $(this);
      $.confirm({
          text: "This step is irreversible. Are you sure you want to remove the User from the course ? ",
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
          confirmButton: "Confirm, Remove User from Course",
          cancelButton: "Cancel"
      });
	});

  $('.reset_course_user').click(function(event){
      event.preventDefault();
      var course_id=$(this).attr('data-course');
      var user_id=$(this).attr('data-user');
      $(this).addClass('animated spin');
      var $this = $(this);
      $.confirm({
        text: "This step is irreversible. All Units, Quiz results would be reset for this user. Are you sure you want to Reset the Course for this User? ",
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
          confirmButton: "Confirm, Reset Course for this User",
          cancelButton: "Cancel"
        });
	});

  $('.course_stats_user').click(function(event){
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
			            'fgColor': '#78c8ce', 
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
          text: "This step is irreversible. All Questions answers would be reset for this user. Are you sure you want to Reset the Quiz for this User? ",
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
                  $('#s'+user_id).fadeOut('fast');
              }
      });
      }, 
       cancel: function() {
            $this.removeClass('animated');
            $this.removeClass('spin');
        },
        confirmButton: "Confirm, Reset Quiz for this User",
          cancelButton: "Cancel"
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
	    var aval = $('#'+ansid).val()
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
	                $this.html('Marks Saved');
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
                $this.html('Quiz Marks Saved');
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

    $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'submit_quiz', 
                    start_quiz: $('#start_quiz').val(),
                    id: quiz_id
                  },
            cache: false,
            success: function (html) {
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

	                if(ques != 'undefined'){
	                  $('.quiz_timer').trigger('activate');
	                }

                $('.begin_quiz').each(function(){
                    $(this).removeClass('begin_quiz');
                    $(this).addClass('submit_quiz');
                    $(this).text('Submit Quiz');
                });
            }
        });
	});
});






$( 'body' ).delegate( '.quiz_question', 'click', function(event){
    event.preventDefault();
    var $this = $(this);
    var quiz_id=$(this).attr('data-quiz');
    var ques_id=$(this).attr('data-qid');
    $this.prepend('<i class="icon-sun-stroke animated spin"></i>');
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
                $('.content').fadeOut("fast");
                $('.content').html(html);
                $('.content').fadeIn("fast");
                ajaxsubmit_comments();
                var ques=$($.parseHTML(html)).filter("#question");
                var q='#ques'+ques.attr('data-ques');
                $('.quiz_timeline').find('.active').removeClass('active');
                $(q).addClass('active');

                if(ques != 'undefined')
                  $('.quiz_timer').trigger('activate');
            }
      });
});

jQuery(document).ready( function($) {
  $('.quiz_timer').each(function(){
      var qtime = parseInt($(this).attr('data-time'));
      var $timer =$(this).find('.timer');
      $timer.knob({
        'readonly':true,
        'max': qtime,
        'width' : 200 ,
        'height' : 200 ,
        'fgColor' : "#78c8ce" ,
        'bgColor' : "#232b2d",
        'thickness': 0.2 ,
        'readonly':true 
      });
  });

  $('.quiz_timer').one('activate',function(){
    var qtime = parseInt($(this).attr('data-time'));

    var $timer =$(this).find('.timer');
    var $this=$(this);
    
    $timer.timer({
      'timer': qtime,
      'width' : 200 ,
      'height' : 200 ,
      'fgColor' : "#78c8ce" ,
      'bgColor' : "#232b2d" 
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
            $('.submit_quiz').trigger('click');
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
        'fgColor' : "#78c8ce" ,
        'bgColor' : "#232b2d",
        'thickness': 0.2 ,
        'readonly':true 
      });
    event.stopPropagation();
  });

  $('.quiz_timer').one('end',function(){
    var qtime = parseInt($(this).attr('data-time'));
    var $timer =$(this).find('.timer');
    var $this=$(this);
    
    $timer.knob({
        'readonly':true,
        'max': qtime,
        'width' : 200 ,
        'height' : 200 ,
        'fgColor' : "#78c8ce" ,
        'bgColor' : "#232b2d",
        'thickness': 0.2 ,
        'readonly':true 
      });
    event.stopPropagation();
  });
}); 

$( 'body' ).delegate( '.send_course_message', 'click', function(event){
  event.preventDefault();
  $('.course_message').toggle('slow');
});

$( 'body' ).delegate( '#send_course_message', 'click', function(event){
  event.preventDefault();
  var members=[];

  var $this = $(this);
  var defaultxt=$this.html();
  $this.html('<i class="icon-sun-stroke animated spin"></i> Sending messages...');
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
                security: $('#buk_message').val(),
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
                var unit=$($.parseHTML(html)).filter("#unit");
                var u='#unit'+unit.attr('data-unit');

                $('.course_timeline').find('.active').removeClass('active');
                $(u).addClass('active');

                
                $('audio,video').mediaelementplayer();

                $('.mejs-container').each(function(){
                  $(this).addClass('mejs-mejskin');
                }); 


                if(unit != 'undefined')
                  $('.unit_timer').trigger('activate');
            }
    });
});

$( 'body' ).delegate( '#mark-complete', 'click', function(event){
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
            data: { action: 'complete_unit', 
                    security: $('#hash').val(),
                    course_id: $('#course_id').val(),
                    id: unit_id
                  },
            cache: false,
            success: function (html) {
                $this.find('i').remove();
                $this.html('<i class="icon-check"></i>');
               $('.course_timeline').find('.active').addClass('done');

                if(unit != 'undefined')
                  $('.unit_timer').trigger('finish');
            }
    });
});

jQuery(document).ready(function($){
	$('.showhide_indetails').click(function(event){
		event.preventDefault();
		$(this).find('i').toggleClass('icon-minus');
		$(this).parent().find('.in_details').toggle();
	});

    $('.ajax-certificate').magnificPopup({
          type: 'ajax',
          fixedContentPos: true,
          closeBtnInside: true,
          preloader: false,
          midClick: true,
          removalDelay: 300,
          mainClass: 'mfp-with-zoom',
          callbacks: {
             parseAjax: function( mfpResponse ) {
              mfpResponse.data = $(mfpResponse.data).find('#certificate');
            }
          }
      });

$( 'body' ).delegate( '.print_unit', 'click', function(event){
    $('.unit_content').print();
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
        $this.find('i').remove();
        $( ".main_unit_content" ).trigger('unit_reload');
    });

  });	
})(jQuery);