jQuery(document).ready(function($){

	$('.event_list').click(function(event){
		event.preventDefault();
		var $this = $(this);
	    $this.prepend('<i class="icon-spinner animated spin"></i>');
      $('#wplms-calendar').find('.active').removeClass('active');
	    $this.parent().addClass('active');
        $('.events_this_day').remove();
		 $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'display_event_list', 
                            course: $this.attr('data-course'),
                            day: $this.attr('data-day'),
                            month: $this.attr('data-month'),
                            year: $this.attr('data-year')
                          },
                    cache: false,
                    success: function (html) {
                        $this.find('i').remove();
                        $('#wplms-calendar').after(html);
                        $('#item-body').trigger('event_list_loaded');
                        var $topoffset=$('.events_this_day').offset().top - 100;
                        $('html, body').stop().animate({ 
						       scrollTop: $topoffset
						   }, 800);
                    }
            });
	});

	$('#item-body').on('event_list_loaded',function(){

		var event_link=$(this).find('.events_list a');

		event_link.magnificPopup({
		  type: 'ajax',
		  alignTop: true,
          fixedContentPos: false,
          fixedBgPos: true,
          overflowY: 'auto',
          closeBtnInside: true,
          preloader: false,
          midClick: true,
          removalDelay: 300,
          mainClass: 'my-mfp-zoom-in',
          disableOn: 480,
		  callbacks: {
			  parseAjax: function(mfpResponse) {
			     mfpResponse.data = $(mfpResponse.data).find('.event_card');
			  }
			}
		});
	});

  $('#send_event_invitation').each(function(){
    $(this).magnificPopup({
    items: {
        src: '#invitation_form',
        type: 'inline',
        closeBtnInside:false,
        removalDelay: 300,
        mainClass: 'mfp-fade'
    }
    });
  });
  $('#send_event_reminder').each(function(){
      $(this).magnificPopup({
      items: {
          src: '#reminder_form',
          type: 'inline',
          closeBtnInside:false,
          removalDelay: 300,
          mainClass: 'mfp-fade'
      }
    });
  });  

});