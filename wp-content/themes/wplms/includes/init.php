<?php


if ( ! isset( $content_width ) ) $content_width = 1170;

add_theme_support( 'post-thumbnails' );
add_theme_support( 'woocommerce' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'buddypress' );
add_theme_support( 'bp-default-responsive' );
add_theme_support( 'html5', array( 'gallery', 'caption' ) );

add_post_type_support( 'course', 'front-end-editor' );
add_post_type_support( 'unit', 'front-end-editor' );
add_post_type_support( 'quiz', 'front-end-editor' );
add_post_type_support( 'question', 'front-end-editor' );
add_post_type_support( 'wplms-event', 'front-end-editor' );
add_post_type_support( 'wplms-assignment', 'front-end-editor' );
add_post_type_support( 'testimonials', 'front-end-editor' );
add_post_type_support( 'popups', 'front-end-editor' );
remove_post_type_support('page', 'front-end-editor' );
add_post_type_support( 'topic', 'front-end-editor' );
add_post_type_support( 'reply', 'front-end-editor' );

$defaults = array(
    'default-color'          => '',
    'default-image'          => '',
    'default-repeat'         => '',
    'default-position-x'     => '',
    'wp-head-callback'       => 'vibe_custom_background_cb',
    'admin-head-callback'    => '',
    'admin-preview-callback' => 'vibe_custom_background_cb'
);
add_theme_support( 'custom-background', $defaults );


function vibe_admin_url($url='/') {
    if (is_multisite()) {
        if  (is_super_admin())
            return network_admin_url($url);
    } else {
        return admin_url($url);
    }
}

function vibe_site_url($url='/') {
    if (is_multisite()) {
        return network_site_url($url);
    } else {
        return site_url($url);
    }
}

add_filter('wplms_logo_url','vibe_logo_url');
function vibe_logo_url($url='/'){
    $logo=vibe_get_option('logo'); 
    
    if(isset($logo) && $logo)
        $url = $logo;

    if(is_ssl()){
        if (substr($url, 0, 7) == "http://")
            $url = str_replace('http','https',$url);
    }
    return $url;
}

function count_user_posts_by_type( $userid, $post_type = 'post' ) {
    global $wpdb;

    $where = get_posts_by_author_sql( $post_type, true, $userid );

    $count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts $where" );

    return apply_filters( 'get_usernumposts', $count, $userid );
}

if(!function_exists('vibe_get_option')){
function vibe_get_option($field,$compare = NULL){
    
    $option=get_option(THEME_SHORT_NAME);
    
    $return = isset($option[$field])?$option[$field]:NULL;
    if(isset($return)){
        if(isset($compare)){
        if($compare === $return){
            return true;
        }else
            return false;
    }
    
        return $return;
    }else
    return NULL;
   }   
}

if(!function_exists('getPostMeta')){
    function getPostMeta($postID,$count_key){
        $count = get_post_meta($postID, $count_key, true);
        if($count==''){
            delete_post_meta($postID, $count_key);
            add_post_meta($postID, $count_key, '0');
            return "0";
       }
       return $count;
    }
}


//Translation Support
function wpse49326_translate_theme() {
    // Load Theme textdomain
    load_theme_textdomain( 'vibe', get_template_directory() . '/languages');
    // Include Theme text translation file
    $locale = get_locale();
    $locale_file = get_template_directory() . "/languages/$locale.php";
    if ( is_readable( $locale_file ) ) {
        require_once( $locale_file );
    }
}
add_action( 'after_setup_theme', 'wpse49326_translate_theme' );

// Restricting Excerpt Length
// 
if(!function_exists('new_excerpt_length')){
function new_excerpt_length($length) {
    $excerpt_length=vibe_get_option('excerpt_length');
    if(isset($excerpt_length) && $excerpt_length){
        return $excerpt_length;
    }else
        return 20;
}
add_filter('excerpt_length', 'new_excerpt_length');
}

if(!function_exists('trim_excerpt')){
  function trim_excerpt($text) {
    $text = str_replace('[', '', $text);
     $text = str_replace(']', '', $text);
     return $text;
    //return rtrim($text,'[...]');
  }
  add_filter('get_the_excerpt', 'trim_excerpt');

}

if(!function_exists('ajaxify_comments')){
    add_action('comment_post', 'ajaxify_comments',20, 2);
    function ajaxify_comments($comment_ID, $comment_status){

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
        //If AJAX Request Then
            switch($comment_status){
                case '0':
                //notify moderator of unapproved comment
                wp_notify_moderator($comment_ID);
                case '1': //Approved comment
                echo "success";
                $commentdata=&get_comment($comment_ID, ARRAY_A);
                $post=&get_post($commentdata['comment_post_ID']);
                //wp_notify_postauthor($comment_ID, $commentdata['comment_type']);
                break;
                default:
                echo "error";
            }
            exit;
        }
    }

}

if(!function_exists('vibe_set_menu')){
    function vibe_set_menu(){
         echo '<p style="padding:20px 0 10px;color:#FFF;text-align:center;">'.__('Setup Menus in Admin Panel','vibe').'</p>';
    }
}


function vibe_wp_title( $title, $sep ) {
    global $paged, $page;
 
    if ( is_feed() ) {
        return $title;
    } // end if
 
    // Add the site name.
    $title .= get_bloginfo( 'name' );
 
    // Add the site description for the home/front page.
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) ) {
        $title = "$title $sep $site_description";
    } // end if
 
    // Add a page number if necessary.
    if ( $paged >= 2 || $page >= 2 ) {
        $title = sprintf( __( 'Page %s', 'vibe' ), max( $paged, $page ) ) . " $sep $title";
    } // end if
 
    return $title;
 
} // end mayer_wp_title
add_filter( 'wp_title', 'vibe_wp_title', 10, 2 );



function vibe_custom_background_cb(){
    // $background is the saved custom image, or the default image.
    $background = set_url_scheme( get_background_image() );

    // $color is the saved custom color.
    // A default has to be specified in style.css. It will not be printed here.
    $color = get_theme_mod( 'background_color' );

    if ( ! $background && ! $color )
        return;

    $style = $color ? "background-color: #$color;" : '';

    if ( $background ) {
        $image = " background-image: url('$background');";

        $repeat = get_theme_mod( 'background_repeat', get_theme_support( 'custom-background', 'default-repeat' ) );
        if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
            $repeat = 'repeat';
        $repeat = " background-repeat: $repeat;";

        $position = get_theme_mod( 'background_position_x', get_theme_support( 'custom-background', 'default-position-x' ) );
        if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) )
            $position = 'left';
        $position = " background-position: top $position;";

        $attachment = get_theme_mod( 'background_attachment', get_theme_support( 'custom-background', 'default-attachment' ) );
        if ( ! in_array( $attachment, array( 'fixed', 'scroll' ) ) )
            $attachment = 'scroll';
        $attachment = " background-attachment: $attachment;";

        $style .= $image . $repeat . $position . $attachment;
    }
?>
<style type="text/css" id="custom-background-css">
body.custom-background .pusher { <?php echo trim( $style ); ?> }
</style>
<?php

}


function learndash_admin_notice(){

    if ( in_array( 'sfwd-lms/sfwd_lms.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) &&
        ( in_array('vibe-course-module/loader.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) )) 
        || in_array('vibe-customtypes/vibe-customtypes.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) )) )) {     
        echo '<div class="error">

                <h3><strong>'.__('LearnDash Active. You may disable following plugins to avoid duplicate functionality in the setup','vibe').'</strong></h3>
                <p>'.__('Go to WP Admin -> Plugins -> Installed Plugins','vibe').'</p>
                <ol>
                    <li>'.__('Deactivate Vibe Custom Types','vibe').'</li>
                    <li>'.__('Deactivate Vibe Course Module','vibe').'</li>
                </ol>
            </div>';
    }
}

add_action('admin_notices', 'learndash_admin_notice');

// Auto plugin activation
require_once('plugin-activation.php');

add_action('tgmpa_register', 'register_required_plugins');

function register_required_plugins() {

    if ( in_array( 'sfwd-lms/sfwd_lms.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
        $force_activate = false;
    else
        $force_activate = true;
    
    $plugins = array(
        array(
            'name'                  => 'Buddypress', // The plugin name
            'slug'                  => 'buddypress', // The plugin slug (typically the folder name)
            'source'                => 'http://downloads.wordpress.org/plugin/buddypress.2.0.1.zip', // The plugin source
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
            'version'               => '1.9', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => $force_activate, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
        array(
            'name'                  => 'WooCommerce', // The plugin name
            'slug'                  => 'woocommerce', // The plugin slug (typically the folder name)
            'source'                => 'http://downloads.wordpress.org/plugin/woocommerce.2.1.12.zip', // The plugin source
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
            'version'               => '1.6', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
        array(
            'name'                  => 'BBPress', // The plugin name
            'slug'                  => 'bbpress', // The plugin slug (typically the folder name)
            'source'                => 'http://downloads.wordpress.org/plugin/bbpress.2.5.4.zip', // The plugin source
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
            'version'               => '1.6', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
        
        array(
            'name'                  => 'Layer Slider', // The plugin name
            'slug'                  => 'LayerSlider', // The plugin slug (typically the folder name)
            'source'                => VIBE_URL . '/plugins/layersliderwp-5.2.0.installable.zip', // The plugin source
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
            'version'               => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
        array(
            'name'                  => 'WP Visual Composer', // The plugin name
            'slug'                  => 'js_composer', // The plugin slug (typically the folder name)
            'source'                => VIBE_URL . '/plugins/js_composer.zip', // The plugin source
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
            'version'               => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
          array(
            'name'                  => 'Vibe Shortcodes', // The plugin name
            'slug'                  => 'vibe-shortcodes', // The plugin slug (typically the folder name)
            'source'                => VIBE_URL . '/plugins/vibe-shortcodes.zip', // The plugin source
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
            'version'               => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
        
          
        array(
            'name'                  => 'WPLMS Customizer', // The plugin name
            'slug'                  => 'wplms-customizer', // The plugin slug (typically the folder name)
            'source'                => VIBE_URL . '/plugins/wplms-customizer.zip', // The plugin source
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
            'version'               => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),

    );
    
    if($force_activate){
        $plugins[]= array(
            'name'                  => 'Vibe Custom Types', // The plugin name
            'slug'                  => 'vibe-customtypes', // The plugin slug (typically the folder name)
            'source'                => VIBE_URL . '/plugins/vibe-customtypes.zip', // The plugin source
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
            'version'               => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => $force_activate, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        );  
         $plugins[]=array(
            'name'                  => 'WPLMS Events', // The plugin name
            'slug'                  => 'wplms-events', // The plugin slug (typically the folder name)
            'source'                => VIBE_URL . '/plugins/wplms-events.zip', // The plugin source
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
            'version'               => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        );
          $plugins[]=array(
            'name'                  => 'Vibe Course Module', // The plugin name
            'slug'                  => 'vibe-course-module', // The plugin slug (typically the folder name)
            'source'                => VIBE_URL . '/plugins/vibe-course-module.zip', // The plugin source
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
            'version'               => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => $force_activate, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        );
        $plugins[]=array(
            'name'                  => 'WPLMS Front End', // The plugin name
            'slug'                  => 'wplms-front-end', // The plugin slug (typically the folder name)
            'source'                => VIBE_URL . '/plugins/wplms-front-end.zip', // The plugin source
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
            'version'               => '1.6', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => $force_activate, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        );
        $plugins[]=array(
            'name'                  => 'WPLMS Assignments', // The plugin name
            'slug'                  => 'wplms-assignments', // The plugin slug (typically the folder name)
            'source'                => VIBE_URL . '/plugins/wplms-assignments.zip', // The plugin source
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
            'version'               => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        );
    }
    // Change this to your theme text domain, used for internationalising strings
    $theme_text_domain = 'vibe';

    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        'domain'            =>'vibe',           // Text domain - likely want to be the same as your theme.
        'default_path'      => '',                          // Default absolute path to pre-packaged plugins
        'parent_menu_slug'  => 'themes.php',                // Default parent menu slug
        'parent_url_slug'   => 'themes.php',                // Default parent URL slug
        'menu'              => 'install-required-plugins',  // Menu slug
        'has_notices'       => true,                        // Show admin notices or not
        'is_automatic'      => true,                        // Automatically activate plugins after installation or not
        'message'           => '',                          // Message to output right before the plugins table
        'strings'           => array(
            'page_title'                                => __( 'Install Required Plugins','vibe' ),
            'menu_title'                                => __( 'Install Plugins','vibe' ),
            'installing'                                => __( 'Installing Plugin: %s','vibe' ), // %1$s = plugin name
            'oops'                                      => __( 'Something went wrong with the plugin API.','vibe' ),
            'notice_can_install_required'               => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
            'notice_can_install_recommended'            => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
            'notice_cannot_install'                     => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
            'notice_can_activate_required'              => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
            'notice_can_activate_recommended'           => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
            'notice_cannot_activate'                    => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
            'notice_ask_to_update'                      => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
            'notice_cannot_update'                      => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
            'install_link'                              => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
            'activate_link'                             => _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
            'return'                                    => __( 'Return to Required Plugins Installer','vibe' ),
            'plugin_activated'                          => __( 'Plugin activated successfully.','vibe' ),
            'complete'                                  => __( 'All plugins installed and activated successfully. %s','vibe' ), // %1$s = dashboard link
            'nag_type'                                  => 'updated' // Determines admin notice type - can only be 'updated' or 'error'
        )
    );

    tgmpa($plugins, $config);
}


if(!function_exists('vibe_login_logo')){
function vibe_login_logo() {    //Copy this function to customize WP Admin login screen
    $url=vibe_get_option('logo');
    $customizer = array();
    $customizer=get_option('vibe_customizer');
    if(!isset($customizer) || !is_array($customizer) || !count($customizer)){
    
    if(!isset($customizer['header_top_bg']) || $customizer['header_top_bg']=='')
        $customizer['header_top_bg']='#232b2d';
    if(!isset($customizer['header_top_color']) || $customizer['header_top_color']=='')
         $customizer['header_top_color']= '#FFFFFF';
    if(!isset($customizer['header_bg']) || $customizer['header_bg']=='')
        $customizer['header_bg']='#313b3d';
    if(!isset($customizer['header_color']) || $customizer['header_color']=='')
         $customizer['header_color']= '#FFFFFF';
    }
    if(!isset($url) || $url == ''){
        $url = get_stylesheet_directory_uri().'/images/logo.png';
    }
    ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?php echo $url; ?>);
        }
        .login h1 a{
            width:160px;
            background-size:100%;
        }
        html,body.login {
            background: <?php echo $customizer['header_bg']; ?>;
            }
        body:before{
            content:'';
            background:rgba(0,0,0,0.1);
            width:100%;
            height:10px;
            position:absolute;
            top:0;
            left:0;
        }    
        .login label{
            color: <?php echo $customizer['header_color']; ?>;
            font-size:11px;
            text-transform: uppercase;
            font-weight:600;
            opacity: 0.8;
        }
        .login form{
            background:none;
            box-shadow:none;
            border-radius:2px;
            margin:0;
        }    
        .login form .input, .login input[type=text], .login form input[type=checkbox]{
            background: <?php echo $customizer['header_top_bg']; ?>;
            border-color: rgba(255,255,255,0.1);
            border-radius: 2px;
            color:<?php echo $customizer['header_top_color']; ?>;
        }
        .login #nav a, .login #backtoblog a{
            color: <?php echo $customizer['header_color']; ?>;
            text-transform: uppercase;
            font-size: 11px;
            opacity: 0.8;
        }
        div.error, .login #login_error{border-radius:2px;}
    </style>
<?php 
    }
}
add_action( 'login_enqueue_scripts', 'vibe_login_logo' );

?>
