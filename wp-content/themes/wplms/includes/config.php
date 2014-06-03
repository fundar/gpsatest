<?php

//Define CONSTANTS
define('THEME_DOMAIN','vibe'); 
define('THEME_SHORT_NAME','wplms');
define('THEME_FULL_NAME','WPLMS');
define('VIBE_PATH',get_theme_root().'/wplms');
define('VIBE_URL',get_template_directory_uri());
//define('VIBE_COURSES',get_theme_root());

if ( !defined( 'BP_AVATAR_THUMB_WIDTH' ) )
define( 'BP_AVATAR_THUMB_WIDTH', 150 ); //change this with your desired thumb width

if ( !defined( 'BP_AVATAR_THUMB_HEIGHT' ) )
define( 'BP_AVATAR_THUMB_HEIGHT', 150 ); //change this with your desired thumb height

if ( !defined( 'BP_AVATAR_FULL_WIDTH' ) )
define( 'BP_AVATAR_FULL_WIDTH', 460 ); //change this with your desired full size,weel I changed it to 260 :) 

if ( !defined( 'BP_AVATAR_FULL_HEIGHT' ) )
define( 'BP_AVATAR_FULL_HEIGHT', 460 ); //change this to default height for full avatar

if ( ! defined( 'BP_DEFAULT_COMPONENT' ) )
define( 'BP_DEFAULT_COMPONENT', 'profile' );

$vibe_options = get_option(THEME_SHORT_NAME);
// Auto Update
if(isset($vibe_options['username']) && isset($vibe_options['apikey'])){ 
require_once(VIBE_PATH."/options/validation/theme-update/class-theme-update.php");
VibeThemeUpdate::init($vibe_options['username'],$vibe_options['apikey']);
}

?>