<?php

/**
 * FILE: css.php 
 * Author: Mr.Vibe 
 * Credits: www.VibeThemes.com
 * Project: WPLMS
 */
//.widget .item-options

function print_customizer_style(){

$theme_customizer=get_option('vibe_customizer');


echo '<style>';

$dom_array = array(
    'primary_bg'  => array(
                            'element' => 'a:hover',
                            'css' => 'primary'
                            ),
    'primary_color'  => array(
                            'element' => '#nav_horizontal li.current-menu-ancestor>a, 
                                          #nav_horizontal li.current-menu-item>a, 
                                          #nav_horizontal li a:hover, 
                                          #nav_horizontal li:hover a,,
                                          .vibe_filterable li.active a,.tabbable .nav.nav-tabs li:hover a,
                                          .btn,a.btn.readmore:hover,
                                          footer .tagcloud a:hover,.tagcloud a,
                                          .pagination a:hover,
                                          .hover-link:hover,
                                          .pagination .current,#question #submit:hover,.ques_link:hover,.reset_answer:hover',
                            'css' => 'color'
                            ),
        'logo_size' => array(
                            'element' => '#logo img',
                            'css' => 'height'
                            ),
    'header_top_bg'  => array(
                            'element' => '#headertop,.pagesidebar,#vibe_bp_login,#pmpro_confirmation_table thead,
                            .pmpro_checkout thead th,#pmpro_levels_table thead,.boxed #headertop .container',
                            'css' => 'background-color'
                            ),
    'header_top_color'  => array(
                            'element' => '#headertop a,.sidemenu li a,#pmpro_confirmation_table thead,
                            .pmpro_checkout thead th,#pmpro_levels_table thead,#vibe_bp_login label ',
                            'css' => 'color'
                            ),
    'header_bg'  => array(
                            'element' => 'header,.sidemenu li.active a, .sidemenu li a:hover,
                            header #searchform input[type="text"],.boxed header .container,.reset_answer:hover',
                            'css' => 'background-color'
                            ),
    'header_color'  => array(
                            'element' => 'nav .menu li a,nav .menu li.current-menu-item a',
                            'css' => 'color'
                            ),
    'nav_bg'  => array(
                            'element' => '.sub-menu,nav .sub-menu,
                            header #searchform,#headertop #vibe_bp_login ul+ul',
                            'css' => 'background-color'
                            ),
    'nav_color'  => array(
                            'element' => 'nav .sub-menu li a, nav .menu li.current-menu-item .sub-menu li a,
                                          nav .sub-menu li.current-menu-item a,
                                          .megadrop .menu-sidebar,
                                          .megadrop .menu-sidebar .widget ul li a,
                                          .megadrop .menu-sidebar .widgettitle,
                                          header #searchform input[type="text"]
                                          ',
                            'css' => 'color'
                            ),
    'nav_font' => array(
                            'element' => 'nav .menu li a,.megadrop .widget',
                            'css' => 'font-family'
                            ),
    'top_nav_font'=> array(
                            'element' => '#headertop a, .sidemenu li a',
                            'css' => 'font-family'
                            ),

    'h1_font' => array(
                            'element' => 'h1',
                            'css' => 'font-family'
                            ),
  'h1_font_weight'=> array(
                            'element' => 'h1',
                            'css' => 'font-weight'
                            ),  
  'h1_color'=> array(
                            'element' => 'h1',
                            'css' => 'color'
                            ),
  'h1_size'=> array(
                            'element' => 'h1',
                            'css' => 'font-size'
                            ),
  'h2_font' => array(
                            'element' => 'h2',
                            'css' => 'font-family'
                            ),
  'h2_font_weight'=> array(
                            'element' => 'h2',
                            'css' => 'font-weight'
                            ),  
  'h2_color'=> array(
                            'element' => 'h2',
                            'css' => 'color'
                            ),
  'h2_size'=> array(
                            'element' => 'h2',
                            'css' => 'font-size'
                            ),
   'h3_font' => array(
                            'element' => 'h3',
                            'css' => 'font-family'
                            ),
  'h3_font_weight'=> array(
                            'element' => 'h3',
                            'css' => 'font-weight'
                            ),  
  'h3_color'=> array(
                            'element' => 'h3',
                            'css' => 'color'
                            ),
  'h3_size'=> array(
                            'element' => 'h3',
                            'css' => 'font-size'
                            ),
   'h4_font' => array(
                            'element' => 'h4',
                            'css' => 'font-family'
                            ),
   'h4_font_weight'=> array(
                            'element' => 'h4',
                            'css' => 'font-weight'
                            ), 
  'h4_color'=> array(
                            'element' => 'h4,h4.block_title a',
                            'css' => 'color'
                            ),
  'h4_size'=> array(
                            'element' => 'h4',
                            'css' => 'font-size'
                            ),
  'h5_font' => array(
                            'element' => 'h5',
                            'css' => 'font-family'
                            ),
  'h5_font_weight'=> array(
                            'element' => 'h5',
                            'css' => 'font-weight'
                            ),  
  'h5_color'=> array(
                            'element' => 'h5',
                            'css' => 'color'
                            ),
  'h5_size'=> array(
                            'element' => 'h5',
                            'css' => 'font-size'
                            ),
  'h6_font' => array(
                            'element' => 'h6',
                            'css' => 'font-family'
                            ),
  'h6_font_weight'=> array(
                            'element' => 'h6',
                            'css' => 'font-weight'
                            ),  
  'h6_color'=> array(
                            'element' => 'h6',
                            'css' => 'color'
                            ),
  'h6_size'=> array(
                            'element' => 'h6',
                            'css' => 'font-size'
                            ),
  'widget_title_font' => array(
                            'element' => '#buddypress .widget_title,.widget .widget_title',
                            'css' => 'font-family'
                            ),
  'widget_title_font_weight'=> array(
                            'element' => '#buddypress .widget_title,.widget .widget_title',
                            'css' => 'font-weight'
                            ),  
  'widget_title_color'=> array(
                            'element' => '#buddypress .widget_title,.widget .widget_title',
                            'css' => 'color'
                            ),
  'widget_title_size'=> array(
                            'element' => '#buddypress .widget_title,.widget .widget_title',
                            'css' => 'font-size'
                            ),

  'woo_prd_title_font_weight'=> array(
                            'element' => '.woocommerce ul.products li.product h3, .woocommerce-page ul.products li.product h3,.woocommerce .woocommerce-tabs h2, .woocommerce .related h2',
                            'css' => 'font-weight'
                            ),  
  'woo_prd_title_color'=> array(
                            'element' => '.woocommerce ul.products li.product h3, .woocommerce-page ul.products li.product h3,.woocommerce .woocommerce-tabs h2, .woocommerce .related h2',
                            'css' => 'color'
                            ),
  'woo_prd_title_size'=> array(
                            'element' => '.woocommerce ul.products li.product h3, .woocommerce-page ul.products li.product h3',
                            'css' => 'font-size'
                            ),
  
  'woo_heading_title_size'=> array(
                            'element' => '.woocommerce .woocommerce-tabs h2, .woocommerce .related h2',
                            'css' => 'font-size'
                            ),

  'body_bg'  => array(
                            'element' => 'body,.pusher',
                            'css' => 'background-color'
                            ),
  'content_bg'  => array(
                            'element' => '.content,#item-body,.widget.pricing,.dir-list,.item-list-tabs,
                            #groups-dir-list, #course-dir-list,
                            #buddypress #groups-directory-form div.item-list-tabs#subnav, #buddypress #course-directory-form div.item-list-tabs#subnav,
                            .boxed #content .container',
                            'css' => 'background-color'
                            ),
  'content_color'  => array(
                            'element' => '.content,#item-body,.widget.pricing,.dir-list,.item-list-tabs,
                            #groups-dir-list, #course-dir-list,#buddypress ul.item-list li div.item-desc',
                            'css' => 'color'
                            ),
  'content_link_color'  => array(
                            'element' => '.content p a,.course_description p a,#buddypress a.activity-time-since',
                            'css' => 'color'
                            ),
  'body_font_size'  => array(
                            'element' => 'body,.content,#item-body,#buddypress ul.item-list li div.item-desc,p',
                            'css' => 'font-size'
                            ),
  'body_font_family' => array(
                            'element' => 'body,.content,#item-body,#buddypress ul.item-list li div.item-desc,p',
                            'css' => 'font-family'
                            ),
  'single_light_color'  => array(
                            'element' => '#buddypress div.item-list-tabs,.widget .item-options,
                            #buddypress div.item-list-tabs,.quiz_bar',
                            'css' => 'background-color'
                            ),
  'single_dark_color'  => array(
                            'element' => '#buddypress div#item-header',
                            'css' => 'background-color'
                            ),
  'checkout_bar'      => array(
                            'element' => '.checkoutsteps ul li.active:nth-child(even)',
                            'css' => 'background-color'
                            ),
  'main_button_color' => array(
                            'element' => '.button.primary,#vibe_bp_login li span,#buddypress li span.unread-count,
                              #buddypress tr.unread span.unread-count',
                            'css' => 'background-color'
                            ),
  'footer_bg'  => array(
                            'element' => 'footer,
                                          .bbp-header,
                                          .bbp-footer,
                                          .boxed footer .container,
                                          footer .form_field, 
                                          footer .input-text, 
                                          footer .ninja-forms-field, 
                                          footer .wpcf7 input.wpcf7-text, 
                                          footer #s,
                                          footer .chosen-container.chosen-with-drop .chosen-drop,
                                          footer .chosen-container-active.chosen-with-drop .chosen-single, 
                                          footer .chosen-container-single .chosen-single',
                            'css' => 'background-color'
                            ),
  'footer_color'  => array(
                            'element' => 'footer,footer a,.footerwidget li a,
                            footer .form_field, 
                            footer .input-text, 
                            footer .ninja-forms-field, 
                            footer .wpcf7 input.wpcf7-text, 
                            footer #s,
                            footer .chosen-container.chosen-with-drop .chosen-drop,
                            footer .chosen-container-active.chosen-with-drop .chosen-single, 
                            footer .chosen-container-single .chosen-single',
                            'css' => 'color'
                            ),
  'footer_heading_color'  => array(
                            'element' => '.footertitle, footer h4,footer a,.footerwidget ul li a',
                            'css' => 'color'
                            ),

  'footer_bottom_bg'  => array(
                            'element' => '#footerbottom,
                            .boxed #footerbottom .container',
                            'css' => 'background-color'
                            ),
  'footer_bottom_color'  => array(
                            'element' => '#footerbottom,#footerbottom a',
                            'css' => 'color'
                            ),
  'custom_css'  => array(
                            'element' => 'body',
                            'css' => 'custom_css'
                            ),
    
);


foreach($dom_array as $style => $value){
    if(isset($theme_customizer[$style]) && $theme_customizer[$style] !=''){
        switch($value['css']){
            case 'font-size':
                echo $value['element'].'{'.$value['css'].':'.$theme_customizer[$style].'px;}';
                break;
            case 'background-image':
                echo $value['element'].'{'.$value['css'].':url('.$theme_customizer[$style].');}';
                break;
             case 'margin-top':
                echo $value['element'].'{'.$value['css'].':'.$theme_customizer[$style].'px;}';
                break;
            case 'height':
            echo $value['element'].'{'.$value['css'].':'.$theme_customizer[$style].'px;max-height:'.$theme_customizer[$style].'px;
                  }';
            break;
            case 'padding-left-right':
                echo $value['element'].'{
                            padding-left:'.$theme_customizer[$style].'px;
                            padding-right:'.$theme_customizer[$style].'px;
                        }';
                break;
            case 'padding-top-bottom':
                echo $value['element'].'{
                            padding-top:'.$theme_customizer[$style].'px;
                            padding-bottom:'.$theme_customizer[$style].'px;
                    }';
                break;
             case 'primary':
                echo '.button,.heading_more:before,.vibe_carousel .flex-direction-nav a,
                      .nav-tabs > li.active > a, 
                      .nav-tabs > li.active > a:hover, 
                      .nav-tabs > li.active > a:focus,
                      .sidebar .widget #searchform input[type="submit"], 
                      #signup_submit, #submit,
                      #buddypress button,
                      #buddypress a.button,
                      #buddypress input[type=button],
                      #buddypress input[type=submit],
                      #buddypress input[type=reset],
                      #buddypress ul.button-nav li a,
                      #buddypress div.generic-button a,
                      #buddypress .comment-reply-link,
                      a.bp-title-button,
                      #buddypress div.item-list-tabs#subnav ul li.current a,
                      #buddypress div.item-list-tabs ul li a span,
                      #buddypress div.item-list-tabs ul li.selected a,
                      #buddypress div.item-list-tabs ul li.current a,
                      .course_button.button,.unit_button.button,
                      .woocommerce-message,.woocommerce-info,
                      .woocommerce-message:before,
                      .woocommerce div.product .woocommerce-tabs ul.tabs li.active,.woocommerce #content div.product .woocommerce-tabs ul.tabs li.active,.woocommerce-page div.product .woocommerce-tabs ul.tabs li.active,
                      .woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active,
                      .woocommerce a.button,.woocommerce button.button,.woocommerce input.button,.woocommerce #respond input#submit,.woocommerce #content input.button,.woocommerce-page a.button,.woocommerce-page button.button,.woocommerce-page input.button,
                      .woocommerce-page #respond input#submit,.woocommerce-page #content input.button,
                      .woocommerce ul.products li a.added_to_cart,
                      .woocommerce ul.products li a.button,
                      .woocommerce a.button.alt,
                      .woocommerce button.button.alt,
                      .woocommerce input.button.alt,
                      .woocommerce #respond input#submit.alt,
                      .woocommerce #content input.button.alt,
                      .woocommerce-page a.button.alt,
                      .woocommerce-page button.button.alt,
                      .woocommerce-page input.button.alt,
                      .woocommerce-page #respond input#submit.alt,
                      .woocommerce-page #content input.button.alt,
                      .woocommerce .widget_layered_nav_filters ul li a,
                      .woocommerce-page .widget_layered_nav_filters ul li a,
                      .woocommerce .widget_price_filter .ui-slider .ui-slider-range,
                      .woocommerce-page .widget_price_filter .ui-slider .ui-slider-range,
                      .price_slider .ui-slider-range,.ui-slider .ui-slider-handle,
                      .tabs-left > .nav-tabs > li > a:hover, .tabs-left > .nav-tabs > li > a:focus,
                      .page-numbers.current, .pagination span.current,
                      .block_media .hover-link,.vibe_filterable li a:hover,.vibe_filterable li.active a,
                      .chosen-container .chosen-results li.highlighted,
                      .checkoutsteps ul li:hover,
                      .checkoutsteps ul li.active, .woocommerce-info a,.woocommerce-info:before,
                      #wplms-calendar td.active,.btn.primary,
                      #wplms-calendar td a span,.tagcloud a,
                      .checkoutsteps ul li.checkout_begin,
                      .widget.pricing .course_sharing .socialicons.round li > a:hover,
                      .widget.pricing .course_sharing .socialicons.square li > a:hover,
                      .widget_carousel .flex-direction-nav a, .vibe_carousel .flex-direction-nav a,
                      #question #submit:hover,.ques_link:hover,.reset_answer,
                      .quiz_timeline li:hover > span, .quiz_timeline li.active > span,
                      .course_timeline li.done > span, .course_timeline li:hover > span, .course_timeline li.active > span,
                      .active .quiz_question span,.vbplogin em,#buddypress div.item-list-tabs#subnav ul li.switch_view a.active
                      {
                            background-color:'.$theme_customizer[$style].'; 
                      }
                      .button,
                      .nav-tabs > li.active > a, 
                      .nav-tabs > li.active > a:hover, 
                      .nav-tabs > li.active > a:focus,
                      .tab-pane li:hover img,
                      #buddypress div.item-list-tabs ul li.current,
                      #buddypress div.item-list-tabs#subnav ul li.current a,
                      .unit_button.button,
                      #item-header-avatar,
                      .woocommerce div.product .woocommerce-tabs ul.tabs li.active,
                      .woocommerce a.button,.woocommerce button.button,.woocommerce input.button,.woocommerce #respond input#submit,.woocommerce #content input.button,.woocommerce-page a.button,.woocommerce-page button.button,.woocommerce-page input.button,
                      .woocommerce-page #respond input#submit,.woocommerce-page #content input.button,
                      .woocommerce a.button.alt,
                      .woocommerce button.button.alt,
                      .woocommerce input.button.alt,
                      .woocommerce #respond input#submit.alt,
                      .woocommerce #content input.button.alt,
                      .woocommerce-page a.button.alt,
                      .woocommerce-page button.button.alt,
                      .woocommerce-page input.button.alt,
                      .woocommerce-page #respond input#submit.alt,
                      .woocommerce-page #content input.button.alt,
                      .woocommerce .widget_layered_nav_filters ul li a,
                      .woocommerce-page .widget_layered_nav_filters ul li a,
                      .tabs-left > .nav-tabs > li > a:hover, 
                      .tabs-left > .nav-tabs > li > a:focus,
                      .tabs-left > .nav-tabs .active > a, 
                      .tabs-left > .nav-tabs .active > a:hover, 
                      .tabs-left > .nav-tabs .active > a:focus,
                      .vibe_filterable li a:hover,.vibe_filterable li.active a,
                      .checkoutsteps ul li:hover,.btn.primary,
                      .checkoutsteps ul li.active,
                      #wplms-calendar td.active,
                      .checkoutsteps ul li.checkout_begin,
                      .widget_course_list a:hover img,
                      .quiz_timeline li.active,.widget_course_list a:hover img{
                        border-color:'.$theme_customizer[$style].';
                      }
                      .reply a, .link,a:hover,
                      .author_desc .social li a:hover,
                      .widget ul > li:hover > a,
                      .course_students li > ul > li > a:hover,
                      .quiz_students li > ul > li > a:hover,
                      #buddypress div.activity-meta a ,
                      #buddypress div.activity-meta a.button,
                      #buddypress .acomment-options a,
                      .total_students span,
                      #buddypress a.primary,
                      #buddypress a.secondary,
                      .activity-inner a,#latest-update h6 a,
                      .bp-primary-action,.bp-secondary-action,
                      #buddypress div.item-list-tabs ul li.selected a span,
                      #buddypress div.item-list-tabs ul li.current a span,
                      #buddypress div.item-list-tabs ul li a:hover span,
                      .activity-read-more a,.unitattachments h4 span,
                      .unitattachments li a:after,
                      .noreviews a,.expand .minmax:hover,
                      .connected_courses li a,
                      #buddypress #item-body span.highlight a,
                      #buddypress div#message-thread div.message-content a,
                      .course_students li > ul > li > a:hover,
                      .quiz_students li > ul > li > a:hover,
                      .assignment_students li > ul > li > a:hover,.widget ul li:hover > a,
                      .widget ul li.current-cat a,.quiz_timeline li:hover a, .quiz_timeline li.active a,
                      .woocommerce .star-rating span, .woocommerce-page .star-rating span, .product_list_widget .star-rating span
                      {
                        color:'.$theme_customizer[$style].';
                      }
                      #ajaxloader{
                      border-color: '.$theme_customizer[$style].' #efefef #efefef;
                      }
                    ';
                break;
                case 'custom_css':
                echo $theme_customizer[$style];
                break; 
                default:
                echo $value['element'].'{'.$value['css'].':'.$theme_customizer[$style].';}';
                break;    
        }
      }
    }
        

        if(isset($theme_customizer['header_top_color'])){
        echo '#headertop li{
               border-color: '.$theme_customizer['header_top_color'].';
            }';
        }

        if(isset($theme_customizer['nav_bg'])){
        echo 'header #searchform:after{
               border-color: transparent transparent '.$theme_customizer['nav_bg'].' transparent;
            }';
        }

        if(isset($theme_customizer['primary_bg'])){
        echo '.checkoutsteps ul li:hover:after,
              .checkoutsteps ul li.active:after,
              .checkoutsteps ul li.checkout_begin:after{
                  border-color: transparent transparent transparent '.$theme_customizer['primary_bg'].';
              }';
        }

        if(isset($theme_customizer['checkout_bar'])){
        echo '.checkoutsteps ul li.active:nth-child(even):after{
                  border-color: transparent transparent transparent '.$theme_customizer['checkout_bar'].';
              }
              .checkoutsteps ul li.active:nth-child(even){
                border-color:'.$theme_customizer['checkout_bar'].'}
                ';
        }

        if(isset($theme_customizer['header_top_bg'])){
        echo '#vibe_bp_login:after{
                border-color: transparent transparent '.$theme_customizer['header_top_bg'].' transparent;
              }';
        }     

        if(isset($theme_customizer['nav_color'])){
        echo '#trigger .lines, 
              .lines:before, .lines:after {
                background:'.$theme_customizer['nav_color'].'}
                
               header #searchicon{color:'.$theme_customizer['nav_color'].';} ';
        }
        
        if(isset($theme_customizer['single_light_color'])){
        echo '.unit_prevnext{
                border-color:'.$theme_customizer['single_light_color'].' !important;}';
        echo '.course_timeline,.quiz_details{
          background:'.$theme_customizer['single_light_color'].' !important;}';   
        }
        if(isset($theme_customizer['single_dark_color'])){
        echo '.unit_prevnext,
        .course_timeline h4{
                background:'.$theme_customizer['single_dark_color'].' !important;}
                .quiz_timeline li > span,.quiz_question span{
                background:'.$theme_customizer['single_dark_color'].';
              }';
        echo '.course_timeline,
        .course_timeline li.unit_line,.course_timeline li > span,
        .quiz_timeline .timeline_wrapper
        {border-color: '.$theme_customizer['single_dark_color'].' !important;}';
        }
         if(isset($theme_customizer['main_button_color'])){
        echo '.button.primary,#vibe_bp_login li span{
                border-color:'.$theme_customizer['main_button_color'].'}';

         echo '#buddypress a.bp-primary-action:hover span,
                #buddypress #reply-title small a:hover span,
         #buddypress div.messages-options-nav a,.unit_module ul.actions li span{
                color:'.$theme_customizer['main_button_color'].'}';
        }

        if(isset($theme_customizer['nav_bg'])){
          echo 'nav .menu-item-has-children:hover > a:before{
            border-color: transparent transparent '.$theme_customizer['nav_bg'].' transparent;
          }';
        }
        
        if(isset($theme_customizer['footer_bottom_bg'])){
          echo 'footer .form_field, 
                            footer .input-text, 
                            footer .ninja-forms-field, 
                            footer .wpcf7 input.wpcf7-text, 
                            footer #s,
                            footer .chosen-container.chosen-with-drop .chosen-drop,
                            footer .chosen-container-active.chosen-with-drop .chosen-single, 
                            footer .chosen-container-single .chosen-single{border-color: '.$theme_customizer['footer_bottom_bg'].';}';
        }
    echo '</style>';
}
?>