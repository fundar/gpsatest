<?php
//Header File
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="icon" type="image/png" href="/favicon.ico">
<title>
<?php 
echo get_the_title();
//wp_title('|',true,'right'); 
?>
</title>
<?php
wp_head();
?>

</head>
<body <?php body_class(); ?>>

<div id="global" class="global">
    <div class="pagesidebar">
        <div class="sidebarcontent">    
            <h2 id="sidelogo">
            <a href="<?php vibe_site_url(); ?>"><img src="<?php $logo=vibe_get_option('logo'); echo (isset($logo)?$logo:VIBE_URL.'/images/logo.png'); ?>" /></a>
            </h2>
            <?php
                $args = array(
                    'theme_location'  => 'mobile-menu',
                    'container'       => '',
                    'menu_class'      => 'sidemenu',
                    'fallback_cb'     => 'vibe_set_menu',
                );
                wp_nav_menu( $args );
            ?>
        </div>
        <a class="sidebarclose"><span></span></a>
    </div>  
    <div class="pusher">
        <?php
            $fix=vibe_get_option('header_fix');
        ?>
        <div id="headertop" class="<?php if(isset($fix) && $fix){echo 'fix';} ?>">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-sm-9">
                       <a href="<?php echo vibe_site_url(); ?>" class="homeicon"><img src="<?php echo (isset($logo)?$logo:VIBE_URL.'/images/logo.png'); ?>" alt="<?php echo get_bloginfo('name'); ?>"></a>
                    </div>
                    <div class="col-md-4 col-sm-3">
                    <?php
                    if ( function_exists('bp_loggedin_user_link') && is_user_logged_in() ) :
                        ?>
                        <ul class="topmenu">
                            <li><a href="<?php bp_loggedin_user_link(); ?>" class="smallimg vbplogin"><?php bp_loggedin_user_avatar( 'type=full' ); ?><?php bp_loggedin_user_fullname(); ?></a></li>
                        </ul>
                    <?php
                    else :
                        ?>
                        <ul class="topmenu">
                            <li><a href="#login" class="smallimg vbplogin"><?php _e('Login','vibe'); ?></a></li>
                            <li><?php if ( function_exists('bp_get_signup_allowed') && bp_get_signup_allowed() ) :
                                printf( __( '<a href="%s" class="vbpregister" title="Create an account">Sign Up</a> ', 'vibe' ), site_url( BP_REGISTER_SLUG . '/' ) );
                            endif; ?>
                            </li>
                        </ul>
                    
                    <?php
                    endif;

                        ?>
                    </div>
                    <div id="vibe_bp_login">
                    <?php
                        if ( function_exists('bp_get_signup_allowed')){
                            the_widget('vibe_bp_login',array(),array());   
                        }
                    ?>
                   </div>
                   <div class="container-logo">
                                <div class="row">
                                        <?php
                                            if(is_home()){
                                                echo '<h1 id="logo">';
                                            }else{
                                                echo '<h2 id="logo">';
                                            }
                                        ?>
                
                                            <a href="<?php echo home_url();?>/"><img src="<?php echo (isset($logo)?$logo:VIBE_URL.'/images/logo.png'); ?>" alt="<?php echo get_bloginfo('name'); ?>" /></a>
                                        <?php
                                            if(is_home()){
                                                echo '</h1>';
                                            }else{
                                                echo '</h2>';
                                            }
                                        ?>                     
                                </div>
                </div>
                </div>
            </div>
        </div>
        <header>
            <div class="container">               
                <div class="row">
                    <div class="col-xs-12">
                        <div id="searchicon"><i class="icon-search-2"></i></div>
                        <div id="searchdiv">
                            <form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
                                <div><label class="screen-reader-text" for="s">Search for:</label>
                                    <input type="text" value="<?php the_search_query(); ?>" name="s" id="s" placeholder="Hit enter to search..." />
                                    <input type="submit" id="searchsubmit" value="Search" />
                                </div>
                            </form>
                        </div>
                        <?php
                             wp_nav_menu( array(
                                 'theme_location'  => 'main-menu',
                                 'container'       => 'nav',
                                 'menu_class'      => 'menu',
                                 'walker'          => new vibe_walker,
                                 'fallback_cb'     => 'vibe_set_menu'
                             ) );
                        ?> 
                    </div>
                    <a id="trigger">
                        <span class="lines"></span>
                    </a>
                </div>
            </div>
        </header>
		<?php if(!is_home()) { getSurvey(); } ?>
		
