
jQuery(document).ready(function($){

  $('.assignment_timer').each(function(){
      var qtime = parseInt($(this).attr('data-time'));
      var $timer =$(this).find('.timer');
      $timer.knob({
        'readonly':true,
        'max': qtime,
        'width' : 200 ,
        'height' : 200 ,
        'fgColor' : vibe_course_module_strings.theme_color,
        'bgColor' : "#232b2d",
        'thickness': 0.2 ,
        'readonly':true 
      });
      if($(this).hasClass('start')){
        $(this).trigger('activate');
      }
  });

  $('.assignment_timer.start').each(function(){
    var qtime = parseInt($(this).attr('data-time'));

    var $timer =$(this).find('.timer');
    var $this=$(this);
    
    $timer.timer({
      'timer': qtime,
      'width' : 200 ,
      'height' : 200 ,
      'fgColor' : vibe_course_module_strings.theme_color, 
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

          if(val > 3600){
            var mins = Math.floor((val%3600)/60);  
            $text = Math.floor(val/3600) + ':' + ((mins < 10)?'0'+mins:mins) + '';
          }else{
            var mins = Math.floor((val%3600)/60);  
            $text = '00:'+ ((mins < 10)?'0'+mins:mins);
          }
          countdown.html($text);
        }else{
            countdown.html('Timeout');
            $('.submit_assignment').trigger('click');
            $('.assignment_timer').trigger('end');
        }  
    });
    
  });

  $('.assignment_timer').one('end',function(){
    var qtime = parseInt($(this).attr('data-time'));
    var $timer =$(this).find('.timer');
    var $this=$(this);
    
    $timer.knob({
        'readonly':true,
        'max': qtime,
        'width' : 200 ,
        'height' : 200 ,
        'fgColor' : vibe_course_module_strings.theme_color, 
        'bgColor' : "#232b2d",
        'thickness': 0.2 ,
        'readonly':true 
      });
    event.stopPropagation();
  });



  $('#clear_previous_submissions').click(function(event){
      event.preventDefault();
      var $this = $(this);
      var defaulttxt = $this.html();
      $this.prepend('<i class="icon-sun-stroke animated spin"></i>');

      $.ajax({
              type: "POST",
              url: ajaxurl,
              data: { action: 'clear_previous_submissions', 
                      id: $this.attr('data-id'),
                      security: $this.attr('data-security')
                    },
              cache: false,
              success: function (html) {
                  $this.find('i').remove();
                  $this.html(html);
                   setTimeout(function(){location.reload();}, 3000);
              }
      });

  });

  $('.reset_assignment_user').click(function(event){
      event.preventDefault();
      var assignment_id=$(this).attr('data-assignment');
      var user_id=$(this).attr('data-user');
      $(this).addClass('animated spin');
      var $this = $(this);
      
      $.confirm({
          text: wplms_assignment_messages.assignment_rest,
          confirm: function() {

      $.ajax({
              type: "POST",
              url: ajaxurl,
              data: { action: 'wplms_reset_assignment', 
                      security: $('#asecurity').val(),
                      id: assignment_id,
                      user: user_id
                    },
              cache: false,
              success: function (html) {
                  $(this).removeClass('animated');
                  $(this).removeClass('spin');
                  $('#message').html(html);
                  $('#as'+user_id).fadeOut('fast');
              }
      });
      }, 
       cancel: function() {
            $this.removeClass('animated');
            $this.removeClass('spin');
        },
        confirmButton: wplms_assignment_messages.assignment_rest_button,
        cancelButton: wplms_assignment_messages.cancel
      });
  });

  $('.evaluate_assignment_user').click(function(event){
    event.preventDefault();
    var assignment_id=$(this).attr('data-assignment');
    var user_id=$(this).attr('data-user');
    $(this).addClass('animated spin');
    $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'evaluate_assignment', 
                    security: $('#asecurity').val(),
                    id: assignment_id,
                    user: user_id
                  },
            cache: false,
            success: function (html) {
                $(this).removeClass('animated');
                $(this).removeClass('spin');
                $('.assignment_students').html(html);
            }
      });
  }); 

  $('body').delegate('#give_assignment_marks','click',function(event){
    event.preventDefault();
    var $this=$(this);
      var ansid=$this.attr('data-ans-id');
      var aval = $('#assignment_marks').val();
      var message = $('#remarks_message').val();
      $this.prepend('<i class="icon-sun-stroke animated spin"></i>');
      $.ajax({
              type: "POST",
              url: ajaxurl,
              data: { action: 'give_assignment_marks', 
                      id: ansid,
                      aval: aval,
                      message:message
                    },
              cache: false,
              success: function (html) {
                  $this.find('i').remove();
                  $this.html(wplms_assignment_messages.marks_saved);
              }
      });
  });
});

