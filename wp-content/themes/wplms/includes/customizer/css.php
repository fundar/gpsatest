<?php

/**
 * FILE: css.php 
 * Author: Mr.Vibe 
 * Credits: www.VibeThemes.com
 * Project: WPLMS
 */


function print_customizer_style(){

$theme_customizer=get_option('vibe_customizer');


echo '<style>';

$dom_array = array(
    'primary_bg'  => array(
                            'element' => 'a:hover,',
                            'css' => 'primary'
                            ),
    'primary_color'  => array(
                            'element' => '#nav_horizontal li.current-menu-ancestor>a, 
                                          #nav_horizontal li.current-menu-item>a, 
                                          #nav_horizontal li a:hover, 
                                          #nav_horizontal li:hover a,
                                          nav li.menu-item a:hover,
                                          nav li.menu-item.current-menu-ancestor > a,
                                          .vibe_filterable li.active a,.tabbable .nav.nav-tabs li:hover a,
                                          .btn,a.btn.readmore:hover,
                                          footer .tagcloud a:hover,
                                          .pagination a:hover,
                                          .hover-link:hover,
                                          .pagination .current',
                            'css' => 'color'
                            ),
        'logo_size' => array(
                            'element' => '#logo img',
                            'css' => 'height'
          ),
    'header_top_bg'  => array(
                            'element' => '#headertop,.pagesidebar',
                            'css' => 'background-color'
                            ),
    'header_top_color'  => array(
                            'element' => '#headertop a,.sidemenu li a',
                            'css' => 'color'
                            ),
    'header_bg'  => array(
                            'element' => 'header,.sidemenu li.active a, .sidemenu li a:hover',
                            'css' => 'background-color'
                            ),
    'header_color'  => array(
                            'element' => 'nav .menu li a',
                            'css' => 'color'
                            ),
    'nav_bg'  => array(
                            'element' => '.sub-menu,nav .sub-menu',
                            'css' => 'background-color'
                            ),
    'nav_color'  => array(
                            'element' => 'nav .sub-menu li a,
                                          .megadrop .menu-sidebar,
                                          .megadrop .menu-sidebar .widget ul li a,
                                          .megadrop .menu-sidebar .widgettitle',
                            'css' => 'color'
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

  'body_bg'  => array(
                            'element' => 'body,.pusher',
                            'css' => 'background-color'
                            ),
  'content_bg'  => array(
                            'element' => '.content,#item-body',
                            'css' => 'background-color'
                            ),
  'content_color'  => array(
                            'element' => '.content,#item-body',
                            'css' => 'color'
                            ),

  'footer_bg'  => array(
                            'element' => 'footer,
                                          .bbp-header,
                                          .bbp-footer',
                            'css' => 'background-color'
                            ),
  'footer_color'  => array(
                            'element' => 'footer,footer a,.footerwidget li a',
                            'css' => 'color'
                            ),
  'footer_heading_color'  => array(
                            'element' => '.footertitle, footer h4,footer a,.footerwidget ul li a',
                            'css' => 'color'
                            ),

  'footer_bottom_bg'  => array(
                            'element' => '#footerbottom',
                            'css' => 'background-color'
                            ),
  'footer_bottom_color'  => array(
                            'element' => '#footerbottom,#footerbottom a',
                            'css' => 'color'
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
            echo $value['element'].'{'.$value['css'].':'.$theme_customizer[$style].'px;}';
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
                echo '.button,.vibe_carousel .flex-direction-nav a,
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
                      .price_slider .ui-slider-range,.ui-slider .ui-slider-handle    
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
                      .woocommerce-page .widget_layered_nav_filters ul li a
                       {
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
                      #buddypress #item-body span.highlight a
                      {
                        color:'.$theme_customizer[$style].';
                      }
                    ';
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
          echo 'nav .menu-item-has-children:hover > a:before{
            border-color: transparent transparent '.$theme_customizer['nav_bg'].' transparent;
          }';
        }
        
echo '</style>';
}
?>