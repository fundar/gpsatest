<?php

/**
 * FILE: register.php 
 * Author: Mr.Vibe 
 * Credits: www.VibeThemes.com
 * Project: WPLMS
 */

global $vibe_options;

/*============================================*/
/*=====  REGISTER CUSTOM IMAGE SIZES  ========*/
/*============================================*/

if(function_exists('add_image_size')){
  function wplms_add_image_sizes() {
    add_image_size('mini', 120, 999);
    add_image_size('small', 310, 9999);
    add_image_size('medium', 460, 9999);
    add_image_size('big', 768, 9999);
  }
  add_action( 'init', 'wplms_add_image_sizes' );
}

add_filter( 'image_size_names_choose', 'wplms_custom_image_sizes' );
function wplms_custom_image_sizes( $sizes ) {
    $custom_sizes = array(
        'big' => 'Big Size',
        'small' => 'Small Size',
        'mini' => 'Extra small/Mini'
    );
    return array_merge( $sizes, $custom_sizes );
}
/*============================================*/
/*===========  REMGISTER CUSTOM USER ROLES  ============*/
/*============================================*/

if(!function_exists('vibe_user_roles')){
    function vibe_user_roles(){
    $teacher_capability=array(
        'delete_posts'=> true,
        'delete_published_posts'=> true,
        'edit_posts'=> true,
        'manage_categories' => true,
        'edit_published_posts'=> true,
        'publish_posts'=> true,
        'read' => true,
        'upload_files'=> true,
        'unfiltered_html'=> true,
        'level_1' => true
        );
    $student_capability=array(
        'read'
        );
    
        add_role( 'student', __('Student','vibe'), $student_capability );
        add_role( 'instructor', __('Instructor','vibe'),$teacher_capability);      
    }
    add_action('init','vibe_user_roles');
}

/* ===== FIX FOR Existing Instructors ====== */
function add_theme_caps() {
    // gets the author role
    $role = get_role( 'instructor' );
    $role->add_cap( 'unfiltered_html' ); 
}
add_action( 'admin_init', 'add_theme_caps');

/*============================================*/
/*===========  REMOVE DEFAULT BUDDYPRESS ADMIN BAR  ============*/
/*============================================*/
add_action('after_setup_theme', 'remove_admin_bar');

if(!function_exists('remove_admin_bar')){
    function remove_admin_bar() {
        if (!current_user_can('edit_posts')) {
          show_admin_bar(false);
        }
    }
}

add_filter( 'woocommerce_enqueue_styles', '__return_false' );

if(!function_exists('vibe_header_essentials')){
    function vibe_header_essentials(){
        $favicon = vibe_get_option('favicon');
        if(!isset($favicon))
            $favicon = VIBE_URL.'/images/favicon.png';

          $credits = vibe_get_option('credits');
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta name="author" content="'.(isset($credits)?$credits:'vibethemes').'">
                <link rel="shortcut icon" href="'.$favicon.'" />
                <link rel="icon" type="image/png" href="'.$favicon.'">
                <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
                <!--[if lt IE 9]>
                  <script src="'.VIBE_URL.'/js/html5shiv.js"></script>
                  <script src="'.VIBE_URL.'/js/respond.js"></script>
                <![endif]-->';

    }
}

add_action('wp_enqueue_scripts', 'vibe_header_essentials');
/*============================================*/
/*===========  REGISTER BACK SCRIPTS  ============*/
/*============================================*/

function enqueue_admin(){
    wp_enqueue_style( 'admin-css', VIBE_URL .'/css/admin.css' );
    wp_enqueue_style( 'chosen-css', VIBE_URL .'/css/chosen.css' );
    wp_enqueue_script( 'chosen-js', VIBE_URL .'/js/chosen.jquery.js');
    wp_enqueue_script( 'admin-js', VIBE_URL .'/js/vibe_admin.js');
}
add_action("admin_enqueue_scripts", "enqueue_admin");


/*============================================*/
/*===========  REGISTER FRONT SCRIPTS  ============*/
/*============================================*/
//ENQUEUE SCRIPTS TO HEAD
function enqueue_head() {
    global $vibe_options;
    
 if( ! is_admin() )
  {
     $protocol = is_ssl() ? 'https' : 'http';
     /*=== Enqueing Google Web Fonts =====*/
     $font_string='';
     $google_fonts=vibe_get_option('google_fonts');
     if(isset($google_fonts) && is_array($google_fonts)){
     foreach($google_fonts as $font){
         $font= preg_replace('/(?<! )(?<!^)[A-Z]/',' $0', $font);
         $font=str_replace(' ','+',$font);
         $font_string.=$font.':300,400,600,700,800|';
     }
        $font_string .='Oswald:600'; // Used for price display, hard coded in the Theme
        $google_fonts_subsets = vibe_get_option('google_fonts_subsets');
        if(isset($google_fonts_subsets) && is_array($google_fonts_subsets)){
          $google_fonts_subsets = implode(',',$google_fonts_subsets);
        }else{
          $google_fonts_subsets = 'latin,latin-ext';
        }
        $query_args = apply_filters('vibe_font_query_args',array(
        'family' => $font_string,
        'subset' => $google_fonts_subsets,
        ));

        

        wp_enqueue_style('google-webfonts',
        add_query_arg($query_args, "$protocol://fonts.googleapis.com/css" ),
        array(), null);
     }
      
    $minified=vibe_get_option('minified');
    if(isset($minified) && $minified){
       wp_enqueue_style('twitter_bootstrap', VIBE_URL.'/css/wplms-minified.css');
    } else{
       wp_enqueue_style('twitter_bootstrap', VIBE_URL.'/css/bootstrap.css');
       wp_enqueue_style( 'fonticons-css', VIBE_URL .'/css/fonticons.css' );
       wp_enqueue_style( 'animation-css', VIBE_URL .'/css/animate.css' );
       wp_enqueue_style( 'progress-css', VIBE_URL .'/css/nprogress.css' );
       wp_enqueue_style( 'search-css', VIBE_URL .'/css/chosen.css' );
       wp_enqueue_style( 'buddypress-css', VIBE_URL .'/css/buddypress.css' );
       wp_enqueue_style( 'bbpress-css', VIBE_URL .'/css/bbpress.css' );
    } 
     wp_enqueue_style( 'style-css', VIBE_URL .'/css/style.css' );
     
     $layout=vibe_get_option('layout');
     if(isset($layout) && $layout){
        wp_enqueue_style( 'boxed-style-css', VIBE_URL .'/css/boxed.css' ); 
     }
     if ( is_rtl() ){
        wp_enqueue_style( 'bootstrap-rtl-css', $protocol.'://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.1.1/css/bootstrap-rtl.min.css' );
        wp_enqueue_style( 'rtl-css', VIBE_URL .'/css/rtl.css' );
      }

     if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {     
       wp_deregister_style( 'woocommerce_chosen_styles' );
       wp_enqueue_style( 'woocommerce-css', VIBE_URL .'/css/woocommerce.css' );
      }
     
      if ( in_array( 'sfwd-lms/sfwd_lms.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )){
        wp_enqueue_style( 'learndash-css', VIBE_URL .'/css/learndash.css' );
      }

     wp_enqueue_style('theme-css', get_stylesheet_uri(), 'twitter_bootstrap');

     wp_enqueue_script( 'jquery' );
     $minified=vibe_get_option('minified');
      if(isset($minified) && $minified){
        wp_enqueue_script( 'minified-js', VIBE_URL.'/js/wplms-minified.js');
      }else{
        wp_enqueue_script( 'bootstrap', VIBE_URL.'/js/bootstrap.min.js');
        wp_enqueue_script( 'nprogress-js', VIBE_URL.'/js/nprogress.js');
      }  
     echo '<script type="javascript/text">
              NProgress.start();
           </script>';       
    }
}    
add_action('wp_enqueue_scripts', 'enqueue_head');
add_action("wp_head", "print_customizer_style");

//ENQUEUE SCRIPTS TO FOOTER
function enqueue_footer() {
    if(!is_admin()){ 
      $minified=vibe_get_option('minified');
      if(!isset($minified) || !$minified){
        wp_enqueue_script( 'modernizr', VIBE_URL.'/js/modernizr.custom.js');
        wp_enqueue_script( 'flexslider', VIBE_URL.'/js/jquery.flexslider-min.js');
        wp_enqueue_script( 'isotope', VIBE_URL.'/js/jquery.isotope.min.js');
        wp_enqueue_script( 'chosen', VIBE_URL.'/js/chosen.jquery.js');
        wp_enqueue_script( 'classie', VIBE_URL.'/js/classie.js');
        wp_enqueue_script( 'sidebareffects', VIBE_URL.'/js/sidebarEffects.js');
        wp_enqueue_script( 'cookie', VIBE_URL.'/js/jquery.cookie.js');
        wp_enqueue_script( 'knob-js', VIBE_URL .'/js/jquery.knob.js' ); 
        wp_enqueue_script( 'buddypress-js', VIBE_URL .'/js/buddypress.js' );
      }
      wp_enqueue_script( 'custom', VIBE_URL.'/js/custom.js');
      $translation_array = array( 
      'wplms_woocommerce_validate' => __( 'Please fill in all the required fields (indicated by *)','vibe' )
      );
      wp_localize_script( 'custom', 'wplms_strings', $translation_array );
    }
}    
 
add_action('wp_footer', 'enqueue_footer');


/*============================================*/
/*===========  REGISTER MENUS  ============*/
/*============================================*/
//ENABLE MENUS
if(!function_exists('register_vibe_menus')){
    function register_vibe_menus() {
        register_nav_menus(
            array(
                'top-menu' => __( 'Top Menu','vibe' ),
                'main-menu' => __( 'Main Menu','vibe' ),
                'mobile-menu' => __( 'Mobile Menu','vibe' ),
                'footer-menu' => __( 'Footer Menu','vibe' )
               )
              );
        }
  add_action( 'init', 'register_vibe_menus' );
}



/*=== Add Scripts in Admin Footer ===*/

/*============================================*/
/*===========  REGISTER SIDEBARS  ============*/
/*============================================*/
if(function_exists('register_sidebar')){     
    register_sidebar( array(
		'name' => 'MainSidebar',
		'id' => 'mainsidebar',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widget_title">',
		'after_title' => '</h4>',
        'description'   => __('This is the global default widget area/sidebar for pages, posts, categories, tags and archive pages','vibe')
	) );
    register_sidebar( array(
        'name' => 'Course Sidebar',
        'id' => 'coursesidebar',
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget_title">',
        'after_title' => '</h4>',
        'description'   => __('This is the default widget area/sidebar shown in course pages and free units','vibe')
    ) );
     register_sidebar( array(
		'name' => 'SearchSidebar',
		'id' => 'searchsidebar',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widget_title">',
		'after_title' => '</h4>',
        'description'   => __('This is the widget area/sidebar shown in search results page.','vibe')
	) );

    register_sidebar( array(
        'name' => 'Shop',
        'id' => 'shopsidebar',
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget_title">',
        'after_title' => '</h4>',
        'description'   => __('This is the widget area/sidebar shown in the shop page ','vibe')
    ) );
    
    register_sidebar( array(
        'name' => 'Product',
        'id' => 'productsidebar',
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget_title">',
        'after_title' => '</h4>',
        'description'   => __('This is the default widget area/sidebar shown in single product pages and product categories','vibe')
    ) );

     register_sidebar( array(
        'name' => 'Buddypress',
        'id' => 'buddypress',
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget_title">',
        'after_title' => '</h4>',
        'description'   => __('This is the default widget area/sidebar shown in buddypress pages like : All Activity, All Groups, All members, All courses, All blogs','vibe')
    ) );

     register_sidebar( array(
        'name' => 'Checkout',
        'id' => 'checkout',
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget_title">',
        'after_title' => '</h4>',
        'description'   => __('This is the default widget area/sidebar shown in Checkout pages below coupons.','vibe')
    ) );

$topspan=vibe_get_option('top_footer_columns');
if(!isset($topspan)) {$topspan = 'col-md-3 col-sm-6';}
     register_sidebar( array(
        'name' => 'Top Footer Sidebar',
        'id' => 'topfootersidebar',
        'before_widget' => '<div class="'.$topspan.'"><div class="footerwidget">',
        'after_widget' => '</div></div>',
        'before_title' => '<h4 class="footertitle">',
        'after_title' => '</h4>',
        'description'   => __('Top Footer widget area / sidebar','vibe')
    ) );

$bottomspan=vibe_get_option('bottom_footer_columns');
if(!isset($bottomspan)) {$bottomspan = 'col-md-4 col-md-4';}
     register_sidebar( array(
        'name' => 'Bottom Footer Sidebar',
        'id' => 'bottomfootersidebar',
        'before_widget' => '<div class="'.$bottomspan.'"><div class="footerwidget">',
        'after_widget' => '</div></div>',
        'before_title' => '<h4 class="footertitle">',
        'after_title' => '</h4>',
        'description'   => __('Bottom Footer widget area / sidebar','vibe')
    ) );

     $sidebars=vibe_get_option('sidebars');
    if(isset($sidebars) && is_array($sidebars)){ 
        foreach($sidebars as $sidebar){ 
            register_sidebar( array(
    		'name' => $sidebar,
    		'id' => $sidebar,
    		'before_widget' => '<div class="widget"><div class="inside">',
    		'after_widget' => '</div></div>',
    		'before_title' => '<h4 class="widgettitle">',
    		'after_title' => '</h4>',
            'description'   => __('Custom sidebar, created from Sidebar Manager','vibe')
    	) );
      }
    }
}

if ( ! function_exists( 'storegoogle_webfonts' ) ){
    function storegoogle_webfonts(){
        $google_webfonts=get_option('google_webfonts');
            if(!isset($google_webfonts) || $google_webfonts ==''){
                $url='http://api.vibethemes.com/fonts.php';       
                $fonts = wp_remote_retrieve_body( wp_remote_get($url));
                $fonts=(string)$fonts;
                add_option( 'google_webfonts', "$fonts",'', 'no');
            }
    }
}

add_action( 'admin_init', 'storegoogle_webfonts' );



?>
