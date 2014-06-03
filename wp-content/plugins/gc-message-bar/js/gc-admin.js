var Gc = Gc || {};
Gc.Baseurl = Gc.Baseurl || WP.base_url;
Gc.Group_Ajax_Url = Gc.Group_Ajax_Url || WP.group_ajax_url;
Gc.Disconnect_Action = Gc.Disconnect_Action || WP.disconnect_action;

Gc.Option_Group_On_Click = Gc.Option_Group_On_Click || function(el,id){
	var aHref = jQuery("#"+id+"_a");
	var groupBody = jQuery("#"+id+"_body");
	var groupFooter = jQuery("#"+id+"_footer");
	var group = "open";
	if(aHref.hasClass("open")){
		aHref.removeClass("open");
		aHref.addClass("close");
		group = "close";
		groupBody.hide();
		groupFooter.hide();
	}else{
		aHref.removeClass("close");
		aHref.addClass("open");
		groupBody.show();
		groupFooter.show();
	}
	jQuery.get(Gc.Baseurl,{ group:group,id:id,action:Gc.Group_Ajax_Url});
	return false;

}

Gc.Save_Button_On_Click = Gc.Save_Button_On_Click || function(id){
	var form = jQuery("#updateSettings");
	form.prop("action","#"+id);
	return true;
}

Gc.Onoff_Button_On_Click = Gc.Onoff_Button_On_Click || function(el,id){
   var aHref = jQuery("#"+id+"_a");
   var input = jQuery("#"+id+"_input");
	if(aHref.hasClass("on")){
		aHref.removeClass("on");
		input.val("2").trigger("change");

		aHref.addClass("off");
		aHref.html('<b></b><span>OFF</span><div class="clear"></div>');
	}else{
		aHref.removeClass("off");
		input.val("1").trigger("change");

		aHref.addClass("on");
		aHref.html('<span>ON</span><b></b><div class="clear"></div>');
	}
   return false;
}

Gc.Darklight_Button_On_Click = Gc.Darklight_Button_On_Click || function(el,id){
   var aHref = jQuery("#"+id+"_a");
   var input = jQuery("#"+id+"_input");
	if(aHref.hasClass("dark")){
		aHref.removeClass("dark");
		input.val("1").trigger("change");
		aHref.addClass("light");
		aHref.html('<span>Light</span><b></b><div class="clear"></div>');
	}else{
		input.val("2").trigger("change");
		aHref.removeClass("light");
		aHref.addClass("dark");
		aHref.html('<b></b><span>Dark</span><div class="clear"></div>');
	}
   return false;
}

Gc.Input_Type_Text_Character_Counter = Gc.Input_Type_Text_Character_Counter || function(id_input,id_label){

	jQuery("#"+id_input).keyup(function() {
        jQuery("#"+id_label).text(this.value.length);
    });
}

Gc.Disconnect_Button_Click = Gc.Disconnect_Button_Click || function(mtx){
	if(!confirm('Are you sure you want to disconnect? All your data will be lost!')){
		return false;
	}

	jQuery.ajax({
		url:WP.base_url+'?action='+Gc.Disconnect_Action+'-remote&cmd=add_metrix_code&value=',
		method:'GET',
		dataType:'json'
	}).success(function(resp){
		jQuery.ajax({
			url:WP.base_url+'?action='+Gc.Disconnect_Action+'-deactivate&metrixCode='+mtx,
			method:'POST',
			dataType:'json'
		}).always(function(){
			alert('Disconnected successfully.');
			var redirect = window.location.href.substr(0, window.location.href.indexOf('#'));
			if(typeof(redirect) === 'string' && redirect.length > 0){
				window.location = redirect;
			}else{
				window.location = window.location.href;
			}
		});
	}).fail(function(){
		alert('Failed to disconnect. Please try again later.');
	});
	
}