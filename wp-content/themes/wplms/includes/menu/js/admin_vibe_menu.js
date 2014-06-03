/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function(){
   jQuery('.on_off').click(function(){
      if(jQuery(this).hasClass('active')){
          jQuery(this).removeClass('active');
          jQuery(this).parent().find('.sidebar').hide(200);
      }else{
          jQuery(this).addClass('active');
          jQuery(this).parent().find('.sidebar').show(200);
      }
   });
});