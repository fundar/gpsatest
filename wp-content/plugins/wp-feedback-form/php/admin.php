<?php
  if (!class_exists("FeedbackAdmin")) {
	class FeedbackAdmin {
		var $adminOptionsName = "FeedbackAdminAdminOptions";
		function FeedbackAdmin() {
		}
		function init() {
			$this->getAdminOptions();
		}
		function getAdminOptions() {         
			$devloungeAdminOptions = array('api_key' => 'sasa ', '_id' => '1', 'email' => get_option('admin_email'), 'name' => get_bloginfo('name'), 'domain' => get_option('siteurl'), 'title' => 'feedback', 'tab_name' =>'Feedback', 'tab_text_color' => 'ebebeb', 'tab_background_color' => '3f6eba', 'tab_border_color' => '000', 'tab_background_color_title_box' => '3f6eba', 'tab_text_color_title_box' => 'ebebeb', 'tab_border_color_form' => '000', 'tab_background_color_form' => 'dadafa', 'tab_text_color_form' => '000', 'tab_background_color_action_button' => '3f6eba', 'tab_text_color_action_button' => 'ebebeb', 'tab_text_drop_shadow' => 0, 'tab_alignment' => 0, 'form_title' => 'Please give your valuable feedback ', 'feed_back_form_content' => 1, 'feed_back_form_content1' => 1, 'feed_back_form_content2' => 1, 'feed_back_form_content3' => 1, 'feed_back_form_content4' => 1, 'app_logo' => 1, 'configure_post_thanku_msg' => 'Your feedback has been successfully sent', 'email_address' => '', 'action_text_color' => 'fff', 'action_border_color' => '000', 'call_action_text' => 'Click!', 'action_button_url' => 'Call to Action Url', 'new_window' => 0 );
			$devOptions = get_option($this->adminOptionsName);
			if (!empty($devOptions)) {
				foreach ($devOptions as $key => $option)
					$devloungeAdminOptions[$key] = $option;
			}
			update_option($this->adminOptionsName, $devloungeAdminOptions);
                        remove_action( 'admin_notices', 'feedback_admin_notices' );
			return $devloungeAdminOptions;
		}

		function addContent($keyword = '') {
			$devOptions = $this->getAdminOptions();
			if ($devOptions['add_content'] == "true") {
				$keyword .= $devOptions['keyword'];
			}
			return $keyword;
		}
		function printAdminPage() {
                    $devOptions = $this->getAdminOptions();

                    if (isset($_POST['update_devloungePluginSeriesSettings'])) {
					
                        if (isset($_POST['devloungeApikey'])) {
                                $devOptions['api_key'] = apply_filters('keyword_save_pre', $_POST['devloungeApikey']);
								
                        }
                        if (isset($_POST['devloungeId'])) {
                                $devOptions['_id'] = apply_filters('keyword_save_pre', $_POST['devloungeId']);
                        }
                        if (isset($_POST['devloungeEmail'])) {
                                $devOptions['email'] = apply_filters('keyword_save_pre', $_POST['devloungeEmail']);
                        }

                        if (isset($_POST['devloungeName'])) {
                                $devOptions['name'] = apply_filters('keyword_save_pre', $_POST['devloungeName']);
                        }

                        if (isset($_POST['devloungeDomain'])) {
                                $devOptions['domain'] = apply_filters('keyword_save_pre', $_POST['devloungeDomain']);
                        }
                        if (isset($_POST['devloungeTabName'])) {
                                $devOptions['tab_name'] = apply_filters('keyword_save_pre', $_POST['devloungeTabName']);
                        }
						if (isset($_POST['devloungeTabTextColor'])) {
                                $devOptions['tab_text_color'] = apply_filters('keyword_save_pre', $_POST['devloungeTabTextColor']);
                        }
						if (isset($_POST['devloungeTabBackgroundColor'])) {
                                $devOptions['tab_background_color'] = apply_filters('keyword_save_pre', $_POST['devloungeTabBackgroundColor']);
                        }
						if (isset($_POST['devloungeTabBorderColor'])) {
                                $devOptions['tab_border_color'] = apply_filters('keyword_save_pre', $_POST['devloungeTabBorderColor']);
                        }
						if (isset($_POST['devloungeBackgroundColorTitleBox'])) {
                                $devOptions['tab_background_color_title_box'] = apply_filters('keyword_save_pre', $_POST['devloungeBackgroundColorTitleBox']);
                        }
						if (isset($_POST['devloungeTextColorTitleBox'])) {
                                $devOptions['tab_text_color_title_box'] = apply_filters('keyword_save_pre', $_POST['devloungeTextColorTitleBox']);
                        }
						if (isset($_POST['devloungeBorderColorForm'])) {
                                $devOptions['tab_border_color_form'] = apply_filters('keyword_save_pre', $_POST['devloungeBorderColorForm']);
                        }
						if (isset($_POST['devloungeBackgroundColorForm'])) {
                                $devOptions['tab_background_color_form'] = apply_filters('keyword_save_pre', $_POST['devloungeBackgroundColorForm']);
                        }
						if (isset($_POST['devloungeTextColorForm'])) {
                                $devOptions['tab_text_color_form'] = apply_filters('keyword_save_pre', $_POST['devloungeTextColorForm']);
                        }
						if (isset($_POST['devloungeBackgroundColorActionButton'])) {
                                $devOptions['tab_background_color_action_button'] = apply_filters('keyword_save_pre', $_POST['devloungeBackgroundColorActionButton']);
                        }
						if (isset($_POST['devloungeTextColorActionButton'])) {
                                $devOptions['tab_text_color_action_button'] = apply_filters('keyword_save_pre', $_POST['devloungeTextColorActionButton']);
                        }
						if (isset($_POST['devloungeTabTextDropShadow'])) {
                                $devOptions['tab_text_drop_shadow'] = apply_filters('keyword_save_pre', $_POST['devloungeTabTextDropShadow']);
                        }
						if (isset($_POST['devloungeTabAlignment'])) {
                                $devOptions['tab_alignment'] = apply_filters('keyword_save_pre', $_POST['devloungeTabAlignment']);
                        }
 # End OF Tab Configure....................................................................................................................................-->     
                        
						if (isset($_POST['devloungeFormTitle'])) {
                                $devOptions['form_title'] = apply_filters('keyword_save_pre', $_POST['devloungeFormTitle']);
                        }
						if (isset($_POST['devloungeFeedbackFormContent'])) {
                                $devOptions['feed_back_form_content'] = apply_filters('keyword_save_pre', $_POST['devloungeFeedbackFormContent']);
                        }
						if (isset($_POST['devloungeFeedbackFormContent1'])) {
                                $devOptions['feed_back_form_content1'] = apply_filters('keyword_save_pre', $_POST['devloungeFeedbackFormContent1']);
                        }
						if (isset($_POST['devloungeFeedbackFormContent2'])) {
                                $devOptions['feed_back_form_content2'] = apply_filters('keyword_save_pre', $_POST['devloungeFeedbackFormContent2']);
                        }
						if (isset($_POST['devloungeFeedbackFormContent3'])) {
                                $devOptions['feed_back_form_content3'] = apply_filters('keyword_save_pre', $_POST['devloungeFeedbackFormContent3']);
                        }
						if (isset($_POST['devloungeFeedbackFormContent4'])) {
                                $devOptions['feed_back_form_content4'] = apply_filters('keyword_save_pre', $_POST['devloungeFeedbackFormContent4']);
                        }
						if (isset($_POST['devloungeAppLogo'])) {
                                $devOptions['app_logo'] = apply_filters('keyword_save_pre', $_POST['devloungeAppLogo']);
                        }
# End OF Configure Form Content...................................................................................................................................-->  						
						if (isset($_POST['devloungeConfigurePostThankuMsg'])) {
                                $devOptions['configure_post_thanku_msg'] = apply_filters('keyword_save_pre', $_POST['devloungeConfigurePostThankuMsg']);
                        }
						if (isset($_POST['devloungeEmailAddress'])) {
                                $devOptions['email_address'] = apply_filters('keyword_save_pre', $_POST['devloungeEmailAddress']);
                        }
                        update_option($this->adminOptionsName, $devOptions);

                        ?>
                        <div class="updated"><p><strong><?php _e("Settings Updated.", "FeedbackAdmin");?></strong></p></div>
                        <?php
                        } ?>

                        <style>

                            .wrap h3{float: left;width:400px;margin:10px 10px 0 0;font-weight:normal}
                            .wrap p{float: left; max-width:300px;margin:0}
                            .wrap small{float: left; width:100%;margin:0}
                            .wrap .input_color{width:200px}
                            .wrap .input_kwd{width:200px; margin-left:10px;}
                            .wrap .row{float:left;width:100%; margin:5px 0;}
                            .wrap .title{font-weight:bold;}
                            .wrap .input_font{margin-left:10px; width:200px}
                            .wrap .che{margin:0px; -webkit-border-radius:0px  !important;-moz-border-radius:0px  !important;border-radius:0px !important; }
                            .wrap p input{background-color: #ffffff;
                                          border: 1px solid #cccccc;
                                          -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
                                          -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
                                          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
                                          -webkit-transition: border linear .2s, box-shadow linear .2s;
                                          -moz-transition: border linear .2s, box-shadow linear .2s;
                                          -o-transition: border linear .2s, box-shadow linear .2s;
                                          transition: border linear .2s, box-shadow linear .2s; margin-top:5px;
                                        }
                            .wrap p input:hover{border-color: rgba(82, 168, 236, 0.8);
                              outline: 0;
                              outline: thin dotted \9;


                              -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(82,168,236,.6);
                              -moz-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(82,168,236,.6);
                              box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(82,168,236,.6);
                            }

                            .btn-primary {
                              cursor: pointer;
                              color: #ffffff !important;
                              text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25) !important;
                              background-color: #006dcc !important;
                              background-image: -moz-linear-gradient(top, #0088cc, #0044cc)!important;
                              background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#0088cc), to(#0044cc))!important;
                              background-image: -webkit-linear-gradient(top, #0088cc, #0044cc)!important;
                              background-image: -o-linear-gradient(top, #0088cc, #0044cc)!important;
                              background-image: linear-gradient(to bottom, #0088cc, #0044cc)!important;
                              background-repeat: repeat-x;
                              filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff0088cc', endColorstr='#ff0044cc', GradientType=0);
                              border-color: #0044cc #0044cc #002a80 !important;
                              border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25)!important;
                              *background-color: #0044cc !important;
                              /* Darken IE7 buttons by default so they stand out more given they won't have borders */

                              filter: progid:DXImageTransform.Microsoft.gradient(enabled = false);-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px; padding:10px; text-align:center; text-decoration:none; }

                            .btn-primary:hover{
                             color: #ffffff !important;
                              background-color: #0044cc !important; }

.btn-success {
color: #ffffff;border-radius:3px;padding: 10px;
text-decoration: none;
border: 1px solid #098C06;-moz-border-radius:3px;-webkit-border-radius:3px;
  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
  background-color: #5bb75b;
  *background-color: #51a351;
  background-image: -moz-linear-gradient(top, #62c462, #51a351);
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#62c462), to(#51a351));
  background-image: -webkit-linear-gradient(top, #62c462, #51a351);
  background-image: -o-linear-gradient(top, #62c462, #51a351);
  background-image: linear-gradient(to bottom, #62c462, #51a351);
  background-repeat: repeat-x;
  border-color: #51a351 #51a351 #387038;
  border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=&#039;#ff62c462&#039;, endColorstr=&#039;#ff51a351&#039;, GradientType=0);
  filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
}

.btn-success:hover,
.btn-success:active,
.btn-success.active,
.btn-success.disabled,
.btn-success[disabled] {color: #ffffff;  background-color: #51a351;  *background-color: #499249;}
.btn-success:active,
.btn-success.active { background-color: #408140 \9;}
.wrap h2{float: left;width: 100%;}
.wrap {float: left;width: 100%;}
</style>
<script type="text/javascript" >
var response_received = 0;

function submit_form() {
   var name = jQuery('#name').val();
   var email = jQuery('#email').val();
   var domain = jQuery('#domain').val();
   var tb_name = jQuery('#tab_name').val();
   var tb_txt_clr = jQuery('#tab_text_color').val();
   var tb_bg_clr = jQuery('#tab_background_color').val();
   var tb_bdr_clr = jQuery('#tab_border_color').val();
   var title_bg_clr = jQuery('#tab_background_color_title_box').val();
   var title_txt_clr = jQuery('#tab_text_color_title_box').val();
   var tb_bdr_clr_form = jQuery('#tab_border_color_form').val();
   var tb_bg_clr_form = jQuery('#tab_background_color_form').val();
   var tb_txt_clr_form = jQuery('#tab_text_color_form').val();
   var tb_bg_clr_actn_btn = jQuery('#tab_background_color_action_button').val();
   var tb_txt_clr_actn_btn = jQuery('#tab_text_color_action_button').val();
   var tb_txt_drop_shadow = jQuery("#tab_text_drop_shadow").is(':checked')?1:0;
   var tab_alignment = jQuery('input[name=devloungeTabAlignment]:radio:checked').val();
   var form_titl = jQuery('#form_title').val();
   var fd_bk_form_cntnt = jQuery('#feed_back_form_content').is(':checked')?1:0;
   var fd_bk_form_cntnt1 = jQuery('#feed_back_form_content1').is(':checked')?1:0;
   var fd_bk_form_cntnt2 = jQuery('#feed_back_form_content2').is(':checked')?1:0;
   var fd_bk_form_cntnt3 = jQuery('#feed_back_form_content3').is(':checked')?1:0;
   var fd_bk_form_cntnt4 = jQuery('#feed_back_form_content4').is(':checked')?1:0;
   var fd_bk_form_cntnt5 = jQuery('#feed_back_form_content5').is(':checked')?1:0;
   var app_logo = jQuery('#app_logo').is(':checked')?1:0;
   var cnfigr_thnk_msg = jQuery('#configure_post_thanku_msg').val();
   var email_adres = jQuery('#email_address').val();
   // IN dataString add title field extra 
   dataString = "title=WebFeedback&name="+name+"&email="+email+"&domain="+domain+"&tb_name="+tb_name+"&tb_txt_clr="+tb_txt_clr+"&tb_bg_clr="+tb_bg_clr+"&tb_bdr_clr="+tb_bdr_clr+"&title_bg_clr="+title_bg_clr+"&title_txt_clr="+title_txt_clr+"&form_bdr_clr="+tb_bdr_clr_form+"&frm_bg_clr="+tb_bg_clr_form+"&form_txt_clr="+tb_txt_clr_form+"&action_bg_clr="+tb_bg_clr_actn_btn+"&action_txt_clr="+tb_txt_clr_actn_btn+"&drop_shadow="+tb_txt_drop_shadow+"&tab_align="+tab_alignment+"&form_title="+form_titl+"&ask_name="+fd_bk_form_cntnt+"&ask_mobile="+fd_bk_form_cntnt1+"&ask_ctgry="+fd_bk_form_cntnt2+"&ask_mssg="+fd_bk_form_cntnt3+"&ask_rating="+fd_bk_form_cntnt4+"&ask_screenshot="+fd_bk_form_cntnt5+"&allow_logo="+app_logo+"&thnk_msg="+cnfigr_thnk_msg+"&send_to_email="+email_adres;
   jQuery("#status").html('<img src="<?php echo site_url()?>/wp-content/plugins/wp-feedback-form/images/ajax-loader.gif">');
   jQuery("#status").show();
      jQuery.ajax({
        url: "http://www.usersdelight.com/api/wp/notifier",
        type: "POST",
        data :dataString,
        dataType:'jsonp',
        success:function(response_data){
		
            jQuery("#set_api_key").val(response_data['api_key']);
            jQuery('#set_api_id').val(response_data['_id']);
            var a = location.hostname;
            jQuery("#domain").val(a);
            response_received = 1;
            jQuery("#status").html('Successfully updated');
        },
          error:function(response_data){
         }
        });
  };

  function waitForElement(){
  
    if (response_received == 1) {
       jQuery.post('<?php echo site_url() ?>/wp-admin/admin.php?page=feedback-menu-id', jQuery('#feedback_form').serialize());
      }
    else{
        setTimeout(function(){
            waitForElement();
        },250);
    }
}
jQuery(document).ready(function(){
  jQuery('#feedback_form').submit(function (e) {
    e.preventDefault();
    submit_form();
    waitForElement();
  })
}) 
</script>
                                <div class="wrap">
                                <div style="float:left;width:60%">

                                <form method="post" action="#" id="feedback_form">
                                  <h2>Configure of Tab:</h2>
                                <div class="row" style="width: 90%;"><hr></div>

                                <input type="hidden" id="set_api_key" name="devloungeApikey" value="<?php echo $devOptions['api_key'] ?>"/>
                                <input type="hidden" id="set_api_id" name="devloungeId" value="<?php echo $devOptions['_id'] ?>"/>
                                <input type="hidden" name="update_devloungePluginSeriesSettings" value="1"/>

                                <div class="row">
                                  <h3>Email</h3>
                                  <p><input required id="email" name="devloungeEmail" required class="input_color" value="<?php echo $devOptions['email'] ?>">
                                  <small>e.g  yourname@example.com</small></p>
                                </div>
                                
                                <input type="hidden" id="name" name="devloungeName"  class="input_color" value="<?php echo $devOptions['name'] ?>">                                
                                <div class="row">
                                  <h3>Domain name:</h3>
                                  <p><input type="text" id="domain" name="devloungeDomain" required class="input_color" value="<?php echo $devOptions['domain'] ?>">
                                  <small>e.g  www.yourdomainname.com</small></p>
                                </div>
                                <div class="row">
                                <div class="row">
                                  <h3>Name of Tab :</h3>
                                  <p><input id="tab_name" name="devloungeTabName" class="input_color" required value="<?php echo $devOptions['tab_name'] ?>">
                                  <small>e.g. Feedback</small></p>
                                </div>

                                <div class="row">
                                  <h3>Color of Tab Text :</h3>
                                  <p>#<input id="tab_text_color" name="devloungeTabTextColor" class="input_color" required value="<?php echo $devOptions['tab_text_color'] ?>">
                                  <small>e.g. FFFFFF</small></p>
                                </div>
                               <div class="row">
                                  <h3>Background Color of Tab :</h3>
                                  <p>#<input id="tab_background_color" name="devloungeTabBackgroundColor" class="input_color" required value="<?php echo $devOptions['tab_background_color'] ?>">
                                  <small>e.g. CCCCCC</small></p>
                               </div>
                                <div class="row">
                                  <h3>Border Color of Tab :</h3>
                                  <p>#<input id="tab_border_color" name="devloungeTabBorderColor" class="input_color" required value="<?php echo $devOptions['tab_border_color'] ?>">
                                  <small>e.g. CCCCCC</small></p>
                                </div>
								<div class="row">
                                  <h3>Background Color of Title Box :</h3>
                                  <p>#<input id="tab_background_color_title_box" name="devloungeBackgroundColorTitleBox" required class="input_color" value="<?php echo $devOptions['tab_background_color_title_box'] ?>">
                                  <small>e.g. CCCCCC</small></p>
                                </div>
								<div class="row">
                                  <h3>Text Color of Title Box :</h3>
                                  <p>#<input id="tab_text_color_title_box" name="devloungeTextColorTitleBox" class="input_color" required value="<?php echo $devOptions['tab_text_color_title_box'] ?>">
                                  <small>e.g. CCCCCC</small></p>
                                </div>
								<div class="row">
                                  <h3>Border Color of Form :</h3>
                                  <p>#<input id="tab_border_color_form" name="devloungeBorderColorForm" required class="input_color" value="<?php echo $devOptions['tab_border_color_form'] ?>">
                                  <small>e.g. CCCCCC</small></p>
                                </div>
								<div class="row">
                                  <h3>Background Color of Form :</h3>
                                  <p>#<input id="tab_background_color_form" name="devloungeBackgroundColorForm" required class="input_color" value="<?php echo $devOptions['tab_background_color_form'] ?>">
                                  <small>e.g. CCCCCC</small></p>
                                </div>
								<div class="row">
                                  <h3>Text Color of Form :</h3>
                                  <p>#<input id="tab_text_color_form" name="devloungeTextColorForm" class="input_color"  required value="<?php echo $devOptions['tab_text_color_form'] ?>">
                                  <small>e.g. CCCCCC</small></p>
                                </div>
								<div class="row">
                                  <h3>Background Color of Action buttons :</h3>
                                  <p>#<input id="tab_background_color_action_button" name="devloungeBackgroundColorActionButton" required class="input_color" value="<?php echo $devOptions['tab_background_color_action_button'] ?>">
                                  <small>e.g. CCCCCC</small></p>
                                </div>
								<div class="row">
                                  <h3>Text Color of Action buttons :</h3>
                                  <p>#<input id="tab_text_color_action_button" name="devloungeTextColorActionButton" class="input_color" required value="<?php echo $devOptions['tab_text_color_action_button'] ?>">
                                  <small>e.g. CCCCCC</small></p>
                                </div>
                               <div class="row">
                                  <h3>Show Drop Shadow on Tab Text :</h3>
                                   <?php
                                       if($devOptions['tab_text_drop_shadow'] == 1) {
                                                 $select = 'checked';
                                   }
                                        else {
                                                     $select = '';
                                 } ?>

                                 <input type="hidden" name="devloungeTabTextDropShadow" value="0"/>
                                 <p><input id="tab_text_drop_shadow" type="checkbox" name="devloungeTabTextDropShadow" value="1"<?php echo $select ?>>Yes</p>

                             </div>
							 <div class="row">
                                  <h3>Tab Alignment :</h3>
                                   <?php
										if($devOptions['tab_alignment'] == 0) {
                                            $select = 'checked';
										}
										elseif ($devOptions['tab_alignment'] == 1) {
                                            $select1 = 'checked';
                                        }
                                  ?>
                                  <input type="hidden" name="devloungeTabAlignment" value="0"/>
                                  <p><input id="tab_alignment" type="radio" name="devloungeTabAlignment" value="0"<?php echo $select ?>>Left&nbsp;&nbsp;&nbsp;</p>
                                  <p><input id="tab_alignment" type="radio" name="devloungeTabAlignment" value="1"<?php echo $select1 ?>>Right </p>
                                </div>
                             <br/>
<!-- End OF Tab Configure....................................................................................................................................-->  
                                <div class="row" style="width: 90%;"><hr></div>
                                  <h2>Configure Form Content</h2>
                                <div class="row" style="width: 90%;"><hr></div>
                                <div class="row">
                                  <h3>Form Title :</h3>
                                  <p><input id="form_title" name="devloungeFormTitle" class="input_color" required value="<?php echo $devOptions['form_title'] ?>">
                                  <small>e.g. Your feedback is valuable for us.</small></p>
                               </div>
                               <div class="row">
                                  <h3>Feedback Form Content :</h3>
                                   <?php
                                       if($devOptions['feed_back_form_content'] == 1) {
                                                 $select = 'checked';
                                   }
                                        else {
                                                     $select = '';
                                 } ?>
								 <input type="hidden" name="devloungeFeedbackFormContent" value="0"/>
                                  <p><input id="feed_back_form_content" type="checkbox" name="devloungeFeedbackFormContent" value="1"<?php echo $select ?>>Ask for Name<br/>
								  <?php
                                       if($devOptions['feed_back_form_content1'] == 1) {
                                                 $select = 'checked';
                                   }
                                        else {
                                                     $select = '';
                                 } ?>
								 <input type="hidden" name="devloungeFeedbackFormContent1" value="0"/>
                                  <input id="feed_back_form_content1" type="checkbox" name="devloungeFeedbackFormContent1" value="1"<?php echo $select ?>>Ask for mobile number<br/>
								 <?php
                                       if($devOptions['feed_back_form_content2'] == 1) {
                                                 $select = 'checked';
                                   }
                                        else {
                                                     $select = '';
                                 } ?>
								 <input type="hidden" name="devloungeFeedbackFormContent2" value="0"/>
                                 <input id="feed_back_form_content2" type="checkbox" name="devloungeFeedbackFormContent2" value="1"<?php echo $select ?>>Ask to select the feedback category<br/>
								 <?php
                                       if($devOptions['feed_back_form_content3'] == 1) {
                                                 $select = 'checked';
                                   }
                                        else {
                                                     $select = '';
                                 } ?>
								 <input type="hidden" name="devloungeFeedbackFormContent3" value="0"/>
                                 <input id="feed_back_form_content3" type="checkbox" name="devloungeFeedbackFormContent3" value="1"<?php echo $select ?>>Ask to leave a message<br/> 
								  <?php
                                       if($devOptions['feed_back_form_content4'] == 1) {
                                                 $select = 'checked';
                                   }
                                        else {
                                                     $select = '';
                                 } ?>
								 <input type="hidden" name="devloungeFeedbackFormContent4" value="0"/>
                                  <input id="feed_back_form_content4" type="checkbox" name="devloungeFeedbackFormContent4" value="1"<?php echo $select ?>>Ask to give rating<br/>
					<?php
                                       if($devOptions['feed_back_form_content5'] == 1) {
                                                 $select = 'checked';
                                   }
                                        else {
                                                     $select = '';
                                 } ?>
                                                                 <input type="hidden" name="devloungeFeedbackFormContent5" value="0"/>
                                 <input id="feed_back_form_content5" type="checkbox" name="devloungeFeedbackFormContent5" value="1"<?php echo $select ?>>Ask for screenshot<br/></p>

								  </div>
										
                                <div class="row">
                                  <h3>Allow "UsersDelight" logo</h3>
                                 <?php
                                       if($devOptions['app_logo'] == 1) {
                                                 $select = 'checked';
                                   }
                                        else {
                                                     $select = '';
                                 } ?>

                                 <input type="hidden" name="devloungeAppLogo" value="1"/>
                                  <p><input id="app_logo" type="checkbox" onclick="return false;" name="devloungeAppLogo" value="1"<?php echo $select ?>>Yes</p>
								  <small>Only paid users can hide UsersDelight logo.</small>
                                </div>
                                </br>
<!-- End OF Configure Form Content...................................................................................................................................-->  

                                <div class="row" style="width: 90%;"><hr>
                                  <h2>Configure Post submission Content</h2></div>
                                <div class="row" style="width: 90%;"><hr></div>
                                <div class="row">
                                  <h3>Thank You Message</h3>
                                  <p><textarea id="configure_post_thanku_msg" name="devloungeConfigurePostThankuMsg" class="input_color" value="<?php echo $devOptions['configure_post_thanku_msg'] ?>"><?php echo $devOptions['configure_post_thanku_msg'] ?></textarea></p>
                                </div>

                                <div class="row">
                                  <h3>Send to Email Address :</h3>
                                  <p><input id="email_address" name="devloungeEmailAddress" class="input_color" placeholder="abc@companyname.com" value="<?php echo $devOptions['email_address'] ?>"></p>
                                  <small>Email Address to get feedback details of individuals.</small>
                                </div>
                                <div class="row" style="width: 90%;"><hr></div>
                                <div class="row">
                                <div class="submit" style="clear: both;">
                                <input type="submit" name="update_devloungePluginSeriesSettings" value="Update Settings" class="btn-primary" >
                                <div id='status' style='display:none'>
                                    <img src="<?php echo site_url()?>/wp-content/plugins/wp-feedback-form/images/ajax-loader.gif">
                                </div>                           
                                </div>						
                                </div>
                                </form>
                            </div>
                            <div style="float: left; width: 28%;margin-left:-13%">
                                <p style="font-size: 18px; width: 100%; float: left; margin-top:-1500px; margin-left:930px;">Mockup of Widget<br><br>
                                <img src="<?php echo site_url()?>/wp-content/plugins/wp-feedback-form/images/mockup.png"></p>
                            </div>
                        </div>

                    <?php
            }
	}
}
if (class_exists("FeedbackAdmin")) {
	$dl_pluginSeries_feedback = new FeedbackAdmin();
}

function feedback_admin_notices() {
    echo "<div id='notice' class='updated fade'><p><h2>Feedback Bar is not configured yet. Please do it now.</h2>
          <br/><a href='"+site_url()+"/wp-admin/admin.php?page=feedback-menu-configuration'><img src='"+site_url()+"/wp-content/plugins/wp-feedback-form/images/configure.png'></img></a>
          </p></div>\n";
}


function my_feedback_menu() {
   	add_menu_page( 'Configure Feedback Bar', 'Feedback', 'manage_options', 'feedback-menu-id', 'my_feedback_options', '../wp-content/plugins/wp-feedback-form/images/favicon.ico');
	add_options_page( 'Configure Feedback Bar', 'Feedback', 'manage_options', 'feedback-menu-configuration', 'my_feedback_options' );
}

function my_feedback_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

    global $dl_pluginSeries_feedback;
    if (!isset($dl_pluginSeries_feedback)) {
         return;
    }

    $dl_pluginSeries_feedback->printAdminPage();
	remove_action( 'admin_notices', 'feedback_admin_notices' );
}
?>
