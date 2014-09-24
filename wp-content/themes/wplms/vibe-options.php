<?php

if(!class_exists('VIBE_Options')){
	require_once( dirname( __FILE__ ) . '/options/options.php' );
}

/*
 * 
 * Custom function for filtering the sections array given by theme, good for child themes to override or add to the sections.
 * Simply include this function in the child themes functions.php file.
 *
 * NOTE: the defined constansts for urls, and dir will NOT be available at this point in a child theme, so you must use
 * get_template_directory_uri() if you want to use any of the built in icons
 *
 */
function add_another_section($sections){
	
	//$sections = array();
	$sections[] = array(
				'title' => __('A Section added by hook', 'vibe'),
				'desc' => '<p class="description">'.__('This is a section created by adding a filter to the sections array, great to allow child themes, to add/remove sections from the options.', 'vibe').'</p>',
				//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
				//You dont have to though, leave it blank for default.
				'icon' => trailingslashit(get_template_directory_uri()).'options/img/glyphicons/glyphicons_062_attach.png',
				//Lets leave this as a blank section, no options just some intro text set above.
				'fields' => array()
				);
	
	return $sections;
	
}//function


/*
 * 
 * Custom function for filtering the args array given by theme, good for child themes to override or add to the args array.
 *
 */
function change_framework_args($args){
	
	//$args['dev_mode'] = false;
	
	return $args;
	
}

/*
 * This is the meat of creating the optons page
 *
 * Override some of the default values, uncomment the args and change the values
 * - no $args are required, but there there to be over ridden if needed.
 *
 *
 */

function setup_framework_options(){
$args = array();
global $vibe_options;

      $vibe_options = get_option(THEME_SHORT_NAME);  //Initialize Vibeoptions
//Set it to dev mode to view the class settings/info in the form - default is false
$args['dev_mode'] = false;

//google api key MUST BE DEFINED IF YOU WANT TO USE GOOGLE WEBFONTS
//$args['google_api_key'] = '***';

//Remove the default stylesheet? make sure you enqueue another one all the page will look whack!
//$args['stylesheet_override'] = true;

//Add HTML before the form
$args['intro_text'] = '';

//Setup custom links in the footer for share icons
$args['share_icons']['twitter'] = array(
										'link' => 'http://twitter.com/vibethemes',
										'title' => __('Folow me on Twitter','vibe'), 
										'img' => VIBE_OPTIONS_URL.'img/ico-twitter.png'
										);
$args['share_icons']['facebook'] = array(
										'link' => 'http://facebook.com/vibethemes',
										'title' => __('Be our Fan on Facebook','vibe'), 
										'img' => VIBE_OPTIONS_URL.'img/ico-facebook.png'
										);
$args['share_icons']['gplus'] = array(
										'link' => 'https://plus.google.com/107421230631579548079',
										'title' => __('Follow us on Google Plus','vibe'), 
										'img' => VIBE_OPTIONS_URL.'img/ico-g+.png'
										);
$args['share_icons']['rss'] = array(
										'link' => 'feed://themeforest.net/feeds/users/VibeThemes',
										'title' => __('Latest News from VibeThemes','vibe'), 
										'img' => VIBE_OPTIONS_URL.'img/ico-rss.png'
										);

//Choose to disable the import/export feature
//$args['show_import_export'] = false;

//Choose a custom option name for your theme options, the default is the theme name in lowercase with spaces replaced by underscores
$args['opt_name'] = THEME_SHORT_NAME;

//Custom menu icon
//$args['menu_icon'] = '';

//Custom menu title for options page - default is "Options"
$args['menu_title'] = __(THEME_FULL_NAME, 'vibe');

//Custom Page Title for options page - default is "Options"
$args['page_title'] = __('Vibe Options Panel v 2.0', 'vibe');

//Custom page slug for options page (wp-admin/themes.php?page=***) - default is "vibe_theme_options"
$args['page_slug'] = THEME_SHORT_NAME.'_options';

//Custom page capability - default is set to "manage_options"
$args['page_cap'] = 'manage_options';

//page type - "menu" (adds a top menu section) or "submenu" (adds a submenu) - default is set to "menu"
//$args['page_type'] = 'submenu';
//$args['page_parent'] = 'themes.php';
if(function_exists('social_sharing_links')){
$social_links= social_sharing_links();
foreach($social_links as $link => $value){
    $social_links[$link]=$link;
}
}

$sidebars=$GLOBALS['wp_registered_sidebars'];
$sidebararray=array();
foreach($sidebars as $sidebar){
    $sidebararray[$sidebar['id']]= $sidebar['name'];
}

//custom page location - default 100 - must be unique or will override other items
$args['page_position'] = 62;

$args['help_tabs'][] = array(
							'id' => 'vibe-opts-1',
							'title' => __('Support', 'vibe'),
							'content' => '<p>'.__('We provide support via three mediums (in priority)','vibe').':
                                                            <ul><li><a href="vibethemes.com/forums/forum/wordpress-html-css/wordpress-themes/wplms" target="_blank">'.THEME_FULL_NAME.' VibeThemes Forums</a></li><li>'.__('Support Email: VibeThemes@gmail.com', 'vibe').'</li><li>'.__('ThemeForest Item Comments','vibe').'</li></ul>
                                                            </p>',
							);
$args['help_tabs'][] = array(
							'id' => 'vibe-opts-2',
							'title' => __('Documentation & Links', 'vibe'),
							'content' => '<ul><li><a href="http://vibethemes.com/forums/forum/wordpress-html-css/wordpress-themes/wplms" target="_blank">'.THEME_FULL_NAME.' Support Forums</a></li>
                                                                          <li><a href="http://vibethemes.com/forums/forum/wordpress-html-css/wordpress-themes/wplms/tips-tricks-docs/7312-wplms-theme-setup" target="_blank">'.THEME_FULL_NAME.' Theme Setup</a></li>
                                                                          <li><a href="http://vibethemes.com/forums/forum/wordpress-html-css/wordpress-themes/wplms/tips-tricks-docs/7311-theme-guide" target="_blank">'.THEME_FULL_NAME.' Theme Guide</a></li>  
                                                                          <li><a href="http://vibethemes.com/forums/forum/wordpress-html-css/wordpress-themes/wplms/7309-issue-log" target="_blank">'.THEME_FULL_NAME.' Issue Log</a></li>
                                                                          <li><a href="http://vibethemes.com/forums/forum/wordpress-html-css/wordpress-themes/wplms/7310-feature-requests" target="_blank">'.THEME_FULL_NAME.' Feature Requests</a></li>    
                                                                          <li><a href="http://vibethemes.com/forums/forum/wordpress-html-css/wordpress-themes/wplms/tips-tricks-docs/7313-wplms-faqs" target="_blank">'.THEME_FULL_NAME.' Common FAQs</a></li>    
                                                                      </ul>
                                                            ',
							);
$args['help_tabs'][] = array(
							'id' => 'vibe-opts-3',
							'title' => __('Upgrades', 'vibe'),
							'content' => ' Latest Theme Version  is  1.3.
										<ol>	
										<li> ADDED : INSTRUCTOR COMMISSIONS</li>
										<li> ADDED : Payment History</li>
										<li> ADDED : FULLY Automated Course Evaluation </li>
										<li> ADDED : Course Order controls in Page Builder</li>
										</ol>',
                             );
							
$args['help_tabs'][] = array(
							'id' => 'vibe-opts-4',
							'title' => __('Notices', 'vibe'),
							'content' => ' Latest Theme Version  is  1.3.
										<ol>	
										<li>FIXED : All Units Appearing Simultaneously in the beginning after continue course.</li>
										<li> FIXED : iPhone Mobile menu issue.</li>
										</ol>',
							);


//Set the Help Sidebar for the options page - no sidebar by default										
$args['help_sidebar'] = '<p>For Support/Help and Docuementation open <strong><a href="http://vibethemes.com/forums/forum/wordpress-html-css/wordpress-themes/wplms">'.THEME_FULL_NAME.' forums</a></strong>'.__('Or email us at','vibe').' <a href="mailto:vibethemes@gmail.com">vibethemes@gmail.com</a>. </p>';



$sections = array();

$sections[] = array(
				'title' => __('Getting Started', 'vibe'),
				'desc' => '<p class="description">'.__('Welcome to '.THEME_FULL_NAME.' Theme Options panel. ','vibe').'</p>
                                    <ol>
                                        <li>'.__('See Theme documentation : ','vibe').'<a href="http://vibethemes.com/envato/wplms/documentation/" class="button">Official WPLMS Documentation</a></li> 
                                        <li>'.__('Install the necessary plugins to run this theme : BuddyPress, Vibe Course Module, Vibe Shortcodes, Vibe CustomTypes.','vibe').'</li> 
                                        <li>'.__('Get Sample Data from the demo ','vibe').' <a href="http://vibethemes.com/forums/forum/wordpress-html-css/wordpress-themes/wplms/tips-tricks-docs/7312-wplms-theme-setup" class="button button-primary"> '.__('Get WPLMS Sample Data','vibe').'</a><small>'.__('(* Requires Vibe Importer Plugin and it may take 3-5 minutes, please do not migrate from page while importing data.)','vibe').'</small></li> 
                                        <li>'.__('Other important setups. ','vibe').' <a href="http://vibethemes.com/forums/forum/wordpress-html-css/wordpress-themes/wplms/tips-tricks-docs/7311-theme-guide" class="button" target="_blank">'.__('Full Theme Guide','vibe').'</a></li> 
                                        <li>'.__('How to Update? Facing Issues while updating?','vibe').' <a href="http://vibethemes.com/forums/forum/wordpress-html-css/wordpress-themes/wplms">support forum thread.</a></li>     
                                    </ol>
                                    
                                    </p>',
				//all the glyphicons are included in the options folder, so you can hook into them, or link to your own custom ones.
				//You dont have to though, leave it blank for default.
				'icon' => 'menu',
                                'fields' => array(
                                    array(
						'id' => 'notice',
						'type' => 'divide',
                        'desc' => __('Details required for Auto-Update','vibe')
						),
                                    array(
						'id' => 'username',
						'type' => 'text',
						'title' => __('Enter Your Themeforest Username', 'vibe'), 
						'sub_desc' => __('Required for Automatic Upgrades.', 'vibe'),
                                                'std' => ''
						),
                                    array(
						'id' => 'apikey',
						'type' => 'text',
						'title' => __('Enter Your Themeforest API KEY', 'vibe'), 
						'sub_desc' => __('Please Enter your API Key.Required for Automatic Upgrades.', 'vibe'),
                                                'desc' => __('Whats an API KEY? Where can I find one?','vibe').' : <a href="http://themeforest.net/help/api" target="_blank">Get all your Anwers here</a> or use our Support Forums',
                                                'std' => ''
						),
                                    )
                                );


$sections[] = array(
				'icon' => 'admin-generic',
				'title' => __('Header', 'vibe'),
				'desc' => '<p class="description">'.__('Header settings','vibe').'..</p>',
				'fields' => array(
                    
                       array(
						'id' => 'logo',
						'type' => 'upload',
						'title' => __('Upload Logo', 'vibe'), 
						'sub_desc' => __('Upload your logo', 'vibe'),
						'desc' => __('This Logo is shown in header.', 'vibe'),
                        'std' => VIBE_URL.'/images/logo.png'
						),
                                        array(
						'id' => 'favicon',
						'type' => 'upload',
						'title' => __('Upload Favicon', 'vibe'), 
						'sub_desc' => __('Upload 16x16px Favicon', 'vibe'),
						'desc' => __('Upload 16x16px Favicon.', 'vibe'),
                        'std' => VIBE_URL.'/images/favicon.png'
						),
                         array(
						'id' => 'header_fix',
						'type' => 'button_set',
						'title' => __('Fix Top Header on Scroll', 'vibe'), 
						'sub_desc' => __('Fix header on top of screen' , 'vibe'),
						'desc' => __('header is fixed to top as user scrolls down.', 'vibe'),
						'options' => array('0' => __('Static','vibe'),'1' => __('Fixed on Scroll','vibe')),//Must provide key => value pairs for radio options
						'std' => '0'
						),  
						array(
						'id' => 'course_search',
                        'title' => __('Navigation Search as Course Search', 'vibe'),
                        'sub_desc' => __('Force the header search to search only in courses', 'vibe'),
                        'type' => 'button_set',
						'options' => array('0' => __('No','vibe'),'1'=>__('Yes','vibe')),//Must provide key => value pairs for radio options
						'std' => '0'
						),   
					)
				);

$sections[] = array(
				'icon' => 'feedback',
				'title' => __('Sidebar Manager', 'vibe'),
				'desc' => '<p class="description">'.__('Generate more sidebars dynamically and use them in various layouts','vibe').'..</p>',
				'fields' => array(
					 array(
						'id' => 'sidebars',
						'type' => 'multi_text',
                        'title' => __('Create New sidebars ', 'vibe'),
                        'sub_desc' => __('Dynamically generate sidebars', 'vibe'),
                        'desc' => __('Use these sidebars in various layouts.', 'vibe')
						),	
					array(
							'id' => 'events_sidebar',
							'type' => 'select',
							'title' => __('All Events page Sidebar', 'vibe'), 
							'sub_desc' => __('Select All events page sidebar', 'vibe'),
							'desc' => __('Select a sidebar for all events page', 'vibe'),
							'options' => $sidebararray,
							'std' => 'mainsidebar'
						),			
					)
				);

$sections[] = array(
				'icon' => 'groups',
				'title' => __('Buddypress', 'vibe'),
				'desc' => '<p class="description">'.__('BuddyPress settings and Variables','vibe').'..</p>',
				'fields' => array(
					array(
						'id' => 'default_avatar',
						'type' => 'upload',
						'title' => __('Upload BuddyPress Default avatar', 'vibe'), 
						'sub_desc' => __('BuddyPress default members avatar', 'vibe'),
						'desc' => __('This avatar is shown for members who have not uploaded any custom avatar.', 'vibe'),
                        'std' => VIBE_URL.'/images/avatar.jpg'
						),
					array(
						'id' => 'loop_number',
                        'title' => __('Buddypress Items Per Page', 'vibe'),
                        'sub_desc' => __('number of items shown per page', 'vibe'),
                        'desc' => __('Number of Buddypress items (Courses,Members,Groups,Forums,Blogs etc..)', 'vibe'),
                        'type' => 'text',
						'std' => '5'
						),
					array(
						'id' => 'members_activity',
                        'title' => __('Show Members activity', 'vibe'),
                        'sub_desc' => __('Members latest activity is shown below the name', 'vibe'),
                        'desc' => __('Members activity is shown in All members page.', 'vibe'),
                        'type' => 'button_set',
						'options' => array('0' => __('No','vibe'),'1'=>__('Yes','vibe')),//Must provide key => value pairs for radio options
						'std' => '0'
						),
					array(
						'id' => 'members_view',
                        'title' => __('All Members View', 'vibe'),
                        'sub_desc' => __('All members pages can be viewed by:', 'vibe'),
                        'desc' => __('Profile viewability : All {Non-Loggedin}, Members{Loggedin Members},Teachers {Teachers, Admins,Editors}', 'vibe'),
                        'type' => 'button_set',
						'options' => array('0' => __('All','vibe'),'1'=>__('Members only','vibe'),'2' => __('Teachers only','vibe'),'3' => __('Admins only','vibe')),//Must provide key => value pairs for radio options
						'std' => '0'
						),
					array(
						'id' => 'single_member_view',
                        'title' => __('Single Member Profile View', 'vibe'),
                        'sub_desc' => __('Single members profile can be viewed by:', 'vibe'),
                        'desc' => __('Profile viewability : All {Non-Loggedin}, Members{Loggedin Members},Teachers {Teachers, Admins,Editors}', 'vibe'),
                        'type' => 'button_set',
						'options' => array('0' => __('All','vibe'),'1'=>__('Members only','vibe'),'2' => __('Teachers only','vibe'),'3' => __('Admins only','vibe')),//Must provide key => value pairs for radio options
						'std' => '0'
						),
					array(
						'id' => 'members_redirect',
						'type' => 'pages_select',
                        'title' => __('All Members no-access redirect Page', 'vibe'),
                        'sub_desc' => __('User is redirected to this page on error.', 'vibe'),
                        'desc' => __('In case Members view access is denied to the user, user is redirected to this page.','vibe')
						),
					array(
						'id' => 'activity_view',
                        'title' => __('Activity View', 'vibe'),
                        'sub_desc' => __('Activity can be viewed by :', 'vibe'),
                        'desc' => __('Activity viewability : All {Non-Loggedin}, Members{Loggedin Members},Teachers {Teachers, Admins,Editors}', 'vibe'),
                        'type' => 'button_set',
						'options' => array('0' => __('All','vibe'),'1'=>__('Members only','vibe'),'2' => __('Teachers only','vibe'),'3' => __('Admins only','vibe')),//Must provide key => value pairs for radio options
						'std' => '0'
						),	
					array(
						'id' => 'activity_redirect',
						'type' => 'pages_select',
                        'title' => __('Activity no-access redirect Page', 'vibe'),
                        'sub_desc' => __('User is redirected to this page on error.', 'vibe'),
                        'desc' => __('In case Activity view access is denied to the user, user is redirected to this page.','vibe')
						),
					array(
						'id' => 'group_create',
                        'title' => __('Create Groups', 'vibe'),
                        'sub_desc' => __('Groups can be created by :', 'vibe'),
                        'desc' => __('Group creation : Members{Loggedin Members},Teachers {Teachers, Admins,Editors}', 'vibe'),
                        'type' => 'button_set',
						'options' => array('1'=>__('Members only','vibe'),'2' => __('Teachers only','vibe'),'3' => __('Admins only','vibe')),//Must provide key => value pairs for radio options
						'std' => '1'
						),	
					array(
						'id' => 'activity_tab',
                        'title' => __('Profile Activity Tab', 'vibe'),
                        'sub_desc' => __('Single Profile activity can be viewed by :', 'vibe'),
                        'desc' => __('Activity viewability : All {Non-Loggedin}, Members{Loggedin Members},Teachers {Teachers, Admins,Editors}', 'vibe'),
                        'type' => 'button_set',
						'options' => array('0' => __('All','vibe'),'1'=>__('Members only','vibe'),'2' => __('Teachers only','vibe'),'3' => __('Admins only','vibe')),//Must provide key => value pairs for radio options
						'std' => '0'
						),	
					array(
						'id' => 'groups_tab',
                        'title' => __('Profile Group View', 'vibe'),
                        'sub_desc' => __('Single Profile Groups can be viewed by :', 'vibe'),
                        'desc' => __('Group viewability : All {Non-Loggedin}, Members{Loggedin Members},Teachers {Teachers, Admins,Editors}', 'vibe'),
                        'type' => 'button_set',
						'options' => array('0' => __('All','vibe'),'1'=>__('Members only','vibe'),'2' => __('Teachers only','vibe'),'3' => __('Admins only','vibe')),//Must provide key => value pairs for radio options
						'std' => '0'
						),	
					array(
						'id' => 'forums_tab',
                        'title' => __('Profile Forums View', 'vibe'),
                        'sub_desc' => __('Single Profile Forums can be viewed by :', 'vibe'),
                        'desc' => __('Group viewability : All {Non-Loggedin}, Members{Loggedin Members},Teachers {Teachers, Admins,Editors}', 'vibe'),
                        'type' => 'button_set',
						'options' => array('0' => __('All','vibe'),'1'=>__('Members only','vibe'),'2' => __('Teachers only','vibe'),'3' => __('Admins only','vibe')),//Must provide key => value pairs for radio options
						'std' => '0'
						),
					array(
						'id' => 'activation_redirect',
						'type' => 'pages_select',
                        'title' => __('Redirect Page on User ACtivation', 'vibe'),
                        'sub_desc' => __('User is redirected to this page on activating her account.', 'vibe'),
                        'desc' => __('After registering and activating the account the user is redirected to this page.','vibe')
						),
					array(
						'id' => 'enable_groups_join_button',
						'type' => 'button_set',
                        'title' => __('Enable Join Group/Request Membership button', 'vibe'),
                        'sub_desc' => __('Button is shown in Groups directory', 'vibe'),
                        'desc' => __('Join Group for Public groups and Request Membership button for private groups','vibe'),
                        'options' => array(0 => __('Disable','vibe'),1=>__('Enable','vibe'))
						),
					array(
						'id' => 'student_activity',
                        'title' => __('Restrict Student activity view', 'vibe'),
                        'sub_desc' => __('Restrict student view of activity', 'vibe'),
                        'desc' => __('Student can view only her activity', 'vibe'),
                        'type' => 'button_set',
						'options' => array('' => __('All Activity','vibe'),'1'=>__('Student Activity','vibe')),//Must provide key => value pairs for radio options
						'std' => ''
						),									
					)
				);


$sections[] = array(
				'icon' => 'welcome-learn-more',
				'title' => __('Course Manager', 'vibe'),
				'desc' => '<p class="description">'.__('Manage Course Options from here.','vibe').'..</p>',
				'fields' => array(
					 	array(
						'id' => 'take_course_page',
						'type' => 'pages_select',
                        'title' => __('Take This Course Page', 'vibe'),
                        'sub_desc' => __('A Page with Start Course Page Template', 'vibe'),
						),
						array(
						'id' => 'create_course',
						'type' => 'pages_select',
                        'title' => __('Create a Course Page', 'vibe'),
                        'sub_desc' => __('A Page with "Create Content" Page Template', 'vibe'),
						),
						array(
						'id' => 'new_course_status',
                        'title' => __('Admin Approval for Course', 'vibe'),
                        'sub_desc' => __('Force instructors for Admin approval for new course', 'vibe'),
                        'desc' => __('Force Courses created by Instructors are first sent to Administrator for Approval.', 'vibe'),
                        'type' => 'button_set',
						'options' => array('pending' => __('Yes, require approval','vibe'),'publish'=>__('No, allow publish','vibe')),
						'std' => '0'
						),
						array(
						'id' => 'nextunit_access',
                        'title' => __('Unit Locking', 'vibe'),
                        'sub_desc' => __('Set access to next units based on previous unit status', 'vibe'),
                        'desc' => __('Force users to complete previous units and quiz evalution before viewing the next units', 'vibe'),
                        'type' => 'button_set',
						'options' => array('0' => __('Free Access','vibe'),'1'=>__('Force prev Unit/Quiz Complete','vibe')),
						'std' => '0'
						),
						array(
						'id' => 'unit_media_lock',
                        'title' => __('Unit Media Lock', 'vibe'),
                        'sub_desc' => __('Hide unit completion button unless the Media(Audio/Video) is complete', 'vibe'),
                        'desc' => __('Force users to view/listen the Media, Audio/Video in units before marking the unit as complete', 'vibe'),
                        'type' => 'button_set',
						'options' => array('0' => __('No','vibe'),'1'=>__('Yes','vibe')),
						'std' => '0'
						),
						array(
						'id' => 'assignment_locking',
                        'title' => __('Assignment Locking', 'vibe'),
                        'sub_desc' => __('Force users to finish assignments to complete units', 'vibe'),
                        'desc' => __('Mark complete button for a unit will appear only after student has finished the unit assignment', 'vibe'),
                        'type' => 'button_set',
						'options' => array('0' => __('Do not lock','vibe'),'1'=>__('Lock Unit Completion','vibe')),
						'std' => '0'
						),
						array(
						'id' => 'private_course',
                        'title' => __('Enable Private Courses', 'vibe'),
                        'sub_desc' => __('Enable private courses', 'vibe'),
                        'desc' => __('Courses which are not connected to any WooCommerce Plugin/PMPro Membership will appear as Private', 'vibe'),
                        'type' => 'button_set',
						'options' => array('0' => __('No','vibe'),'1'=>__('Yes','vibe')),
						'std' => '0'
						),
						array(
						'id' => 'course_progressbar',
                        'title' => __('Show Course Progressbar', 'vibe'),
                        'sub_desc' => __('Show course progress bar above course timeline', 'vibe'),
                        'desc' => __('Course Progress bar is shown in Course curriculum page', 'vibe'),
                        'type' => 'button_set',
						'options' => array('0' => __('No','vibe'),'1'=>__('Yes','vibe')),
						'std' => '0'
						),
						array(
						'id' => 'instructor_add_students',
                        'title' => __('Instructors can Add Students', 'vibe'),
                        'sub_desc' => __('Enable Instructors to be able to add students', 'vibe'),
                        'desc' => __('A Bulk Action is added in Course -> Admin -> Members which enables Instructos to add students to the course', 'vibe'),
                        'type' => 'button_set',
						'options' => array('0' => __('No','vibe'),'1'=>__('Yes','vibe')),
						'std' => '0'
						),
						array(
						'id' => 'instructor_assign_badges',
                        'title' => __('Instructors can Assign/Remove Certificates & Badges', 'vibe'),
                        'sub_desc' => __('Enable Instructors to be able to assign Certificates & Badges to Students', 'vibe'),
                        'desc' => __('A Bulk Action is added in Course -> Admin -> Members which enables Instructos to assign Certificates & Badges to Students for the course', 'vibe'),
                        'type' => 'button_set',
						'options' => array('0' => __('No','vibe'),'1'=>__('Yes','vibe')),
						'std' => '0'
						),
						array(
						'id' => 'instructor_content_privacy',
                        'title' => __('Force Instructor Content Privacy', 'vibe'),
                        'sub_desc' => __('Select boxes show only instructor units/quizzes/questions', 'vibe'),
                        'desc' => __('Instructors can view titles but can not open content', 'vibe'),
                        'type' => 'button_set',
						'options' => array('0' => __('No','vibe'),'1'=>__('Yes','vibe')),
						'std' => '0'
						),
                        array(
						'id' => 'teacher_form',
						'type' => 'pages_select',
                        'title' => __('Become n Instructor Page', 'vibe'),
                        'sub_desc' => __('A Page with become a teacher form.', 'vibe'),
						),
                        array(
						'id' => 'certificate_page',
						'type' => 'pages_select',
                        'title' => __('Fallback Certificate Page', 'vibe'),
                        'sub_desc' => __('A Page with certificate page template, Fallback to courses with no Certificate Templates.', 'vibe'),
						),
						
                        array(
						'id' => 'student_field',
						'type' => 'text',
                        'title' => __('Student Field', 'vibe'),
                        'sub_desc' => __('Enter the name of the Student Field to show below the name.', 'vibe'),
                        'std'=>'Location'
						),
                        array(
						'id' => 'instructor_field',
						'type' => 'text',
                        'title' => __('Instructor Field', 'vibe'),
                        'sub_desc' => __('Enter the name of the Instructor Field to show below the name.', 'vibe'),
                        'std'=>'Speciality'
						),
						array(
						'id' => 'instructor_field_group',
						'type' => 'text',
                        'title' => __('Instructor Field Group', 'vibe'),
                        'sub_desc' => __('Enter the name of the Instructor Field Group you want to hide from Students from viewing in Profile -> edit (* No Required fields & Case senstitive).', 'vibe'),
                        'std'=>'Instructor'
						),
                        array(
						'id' => 'instructor_about',
						'type' => 'text',
                        'title' => __('Instructor Description Field', 'vibe'),
                        'sub_desc' => __('Instructor Description is picked from this field in the Instructor Widget', 'vibe'),
                        'std'=>'About'
						),
						array(
						'id' => 'auto_eval_question_type',
						'type' => 'multi_select',
                        'title' => __('Auto-Evaluation Question Types', 'vibe'),
                        'sub_desc' => __('Question types which will be evaluated by Auto-Evaluation', 'vibe'),
                        'desc' => __('These question types will be evaluated by auto quiz evaluation process for exact match.','vibe'),
                        'options' => array(
                        	'truefalse' => __('True False','vibe'),
                        	'single' => __('Multiple Choice','vibe'),
                        	'multiple' => __('Multiple Correct','vibe'),
                        	'sort' => __('Sort Answer Question','vibe'),
                        	'match' => __('Match Answers','vibe'),
                        	'select' => __('Dropdown select','vibe'),
                        	'fillblank' => __('Fill in the Blank','vibe'),
                        	'smalltext' => __('Small Text Answer','vibe'),
                        	),
                        'std' => array('single')
						),
						array(
						'id' => 'hide_courses',
						'type' => 'posts_multi_select',
                        'title' => __('Hide Courses from Directory', 'vibe'),
                        'sub_desc' => __('Hide courses from directory & pages, only accessible via direct link', 'vibe'),
                        'args' => 'post_type=course',
                        'class' => 'chosen',
                        'std'=>''
						),
						array(
						'id' => 'course_duration_display_parameter',
						'type' => 'select',
                        'title' => __('Course Duration parameter', 'vibe'),
                        'sub_desc' => __('Set course duration parameter', 'vibe'),
                        'desc' => __('Course duration parameter for display purpose','vibe'),
                        'options' => array(
                        	0 => __('Automatic','vibe'),
                        	1 => __('Seconds','vibe'),
                        	60 => __('Minutes','vibe'),
                        	3600 => __('Hours','vibe'),
                        	86400 => __('Days','vibe'),
                        	604800 => __('Weeks','vibe'),
                        	2592000 => __('Months','vibe'),
                        	31536000 => __('Years','vibe'),
                        	),
                        'std' => 0
						),

						array(
						'id' => 'ninja_instructor_form_id',
						'type' => 'text',
                        'title' => __('Instructor Form ID', 'vibe'),
                        'sub_desc' => __('Enter the Form ID of the Instructor Form created using NINJA FORMS Plugin.( ** Optional refer ', 'vibe').'<a href="http://vibethemes.com/forums/forum/wordpress-html-css/wordpress-themes/wplms/tips-tricks-docs/13992-tip-using-instructor-sign-up-with-ninja-forms">link</a> )',
                        'std'=>''
						),
						array(
						'id' => 'finished_course_access',
                        'title' => __('Finished Course Access', 'vibe'),
                        'sub_desc' => __('Allow students to view Finished courses', 'vibe'),
                        'desc' => __('Make finished courses secure area viewable to students', 'vibe'),
                        'type' => 'button_set',
						'options' => array('' => __('No','vibe'),'1'=>__('Yes','vibe')),
						'std' => ''
						),
						array(
						'id' => 'level',
                        'title' => __('Enable Levels', 'vibe'),
                        'sub_desc' => __('Enables Level taxonomy', 'vibe'),
                        'desc' => __('Enables Level taxonomy in Course, Units, Quizzes, Questions, Assignment and search page ', 'vibe'),
                        'type' => 'button_set',
						'options' => array('' => __('No','vibe'),'1'=>__('Yes','vibe')),
						'std' => ''
						),
						array(
						'id' => 'linkage',
                        'title' => __('Enable Linkage', 'vibe'),
                        'sub_desc' => __('Connect Course, Units, Quiz, Questions with linkage taxonomy', 'vibe'),
                        'desc' => __('Shorten the list of units, quizzes, courses shown in dropdowns', 'vibe'),
                        'type' => 'button_set',
						'options' => array('0' => __('No','vibe'),'1'=>__('Yes','vibe')),
						'std' => '0'
						),
					)
				);

$sections[] = array(
				'icon' => 'editor-spellcheck',
				'title' => __('Fonts Manager', 'vibe'),
				'desc' => '<p class="description">'.__('Manage Fonts to be used in the Site. Fonts selected here will be available in Theme customizer font family select options.','vibe').'..</p>',
				'fields' => array(
					 array(
						'id' => 'google_fonts',
						'type' => 'google_webfonts_multi_select',
                        'title' => __('Select Fonts for Live Theme Editor ', 'vibe'),
                        'sub_desc' => __('Select Fonts and setup fonts in Live Editor', 'vibe'),
                        'desc' => __('Use these sample layouts in PageBuilder.', 'vibe')
						),
					array(
						'id' => 'google_fonts_subsets',
						'type' => 'multi_select',
                        'title' => __('Select Google Fonts subsets', 'vibe'),
                        'sub_desc' => __('Select a font subset', 'vibe').'<a href="http://academe.co.uk/2013/09/google-web-fonts-api-categories-and-subsets/"><i class="dashicons-plus-alt"></i></a>',
                        'options' => array(
                        	'latin'=>__('Latin','vibe'),
							'latin-ext' =>__('Latin Ext','vibe'),
							'menu'=>__('Menu','vibe'),
							'greek'=>__('Greek','vibe'),
							'greek-ext'=>__('Greek Ext','vibe'),
							'cyrillic'=>__('Cyrillic','vibe'),
							'cyrillic-ext'=>__('Cyrillic Ext','vibe'),
							'vietnamese'=>__('Vietnamese','vibe'),
							'arabic'=>__('Arabic','vibe'),
							'khmer'=>__('Khmer','vibe'),
							'lao'=>__('Lao','vibe'),
							'tamil'=>__('Tamil','vibe'),
							'bengali'=>__('Bengali','vibe'),
							'hindi'=>__('Hindi','vibe'),
							'korean'=>__('Korean','vibe'),
                        	),
                        'std' => array('latin','latin-ext'),
                        'desc' => __('Use these font subsets for better rendering of fonts.', 'vibe')
						),
                        array(
						'id' => 'custom_fonts',
						'type' => 'multi_text',
                        'title' => __('Custom Fonts (Enter CSS Font Family name)', 'vibe'),
                        'sub_desc' => __(' Custom Fonts are added to Theme Customizer Font List.. ', 'vibe').'<a href="http://forums.vibethemes.com">Learn how to add custom fonts</a>'
						)
					 				
					)
				);


$sections[] = array(
				'icon' => 'visibility',
				'title' => __('Customizer', 'vibe'),
				'desc' => '<p class="description">'.__('Import/Export customizer settings. Customize your theme using ','vibe').' <a href="'.get_admin_url().'customize.php" class="button">'.__('WP Theme Customizer','vibe').'</a></p>',
				'fields' => array(
                     array(
						'id' => 'viz_customizer',
						'type' => 'import_export',
                        'title' => __('Import/Export Customizer settings ', 'vibe'),
                        'sub_desc' => __('Import/Export customizer settings', 'vibe'),
                        'desc' => __('Use import/export functionality to import/export your customizer settings.', 'vibe')
						)			
					)
				);

$sections[] = array(
				'icon' => 'editor-kitchensink',
				'title' => __('PageBuilder Manager', 'vibe'),
				'desc' => '<p class="description">'.__('Manage PageBuilder saved layouts and Import/Export pagebuilder Saved layouts','vibe').'</p>',
				'fields' => array(
					array(
						'id' => 'sample_layouts',
						'type' => 'pagebuilder_layouts',
                        'title' => __('Manage Sample Layouts ', 'vibe'),
                        'sub_desc' => __('Delete Sample Layouts', 'vibe'),
                        'desc' => __('Use these sample layouts in PageBuilder.', 'vibe')
						),
                    array(
						'id' => 'vibe_builder_sample_layouts',
						'type' => 'import_export',
                        'title' => __('Import/Export Sample Layouts ', 'vibe'),
                        'sub_desc' => __('Import/Export existing Layouts', 'vibe'),
                        'desc' => __('Use import/export functionality to save your layouts.', 'vibe')
						)
					 				
					)
				);

$sections[] = array(
				'icon' => 'editor-insertmore',
				'title' => __('Footer ', 'vibe'),
				'desc' => '<p class="description">'.__('Setup footer settings','vibe').'..</p>',
				'fields' => array( 
						
					 	array(
							'id' => 'top_footer_columns',
							'type' => 'radio_img',
							'title' => __('Top Footer Sidebar Columns', 'vibe'), 
							'sub_desc' => __('Footer Columns', 'vibe'),
							'options' => array(             
	                                        'col-md-3 col-sm-6' => array('title' => __('Four Columns','vibe'), 'img' => VIBE_OPTIONS_URL.'img/footer-1.png'),
											'col-md-4 col-sm-4' => array('title' => __('Three Columns','vibe'), 'img' => VIBE_OPTIONS_URL.'img/footer-2.png'),    
											'col-md-6 col-sm-6' => array('title' => __('Two Columns','vibe'), 'img' => VIBE_OPTIONS_URL.'img/footer-3.png'),
	                                        'col-md-12' => array('title' => __('One Columns','vibe'), 'img' => VIBE_OPTIONS_URL.'img/footer-4.png'),
	                            ),//Must provide key => value(array:title|img) pairs for radio options
							'std' => '4'
						),
                        array(
							'id' => 'bottom_footer_columns',
							'type' => 'radio_img',
							'title' => __('Bottom Footer Sidebar Columns', 'vibe'), 
							'sub_desc' => __('Footer Columns', 'vibe'),
							'options' => array(             
	                                        'col-md-3 col-sm-6' => array('title' => __('Four Columns','vibe'), 'img' => VIBE_OPTIONS_URL.'img/footer-1.png'),
											'col-md-4 col-sm-4' => array('title' => __('Three Columns','vibe'), 'img' => VIBE_OPTIONS_URL.'img/footer-2.png'),    
											'col-md-6 col-sm-6' => array('title' => __('Two Columns','vibe'), 'img' => VIBE_OPTIONS_URL.'img/footer-3.png'),
	                                        'col-md-12' => array('title' => __('One Columns','vibe'), 'img' => VIBE_OPTIONS_URL.'img/footer-4.png'),
	                            ),//Must provide key => value(array:title|img) pairs for radio options
							'std' => '4'
						),  
                                    
                        array(
							'id' => 'copyright',
							'type' => 'editor',
							'title' => __('Copyright Text', 'vibe'), 
							'sub_desc' => __('Enter copyrighted text', 'vibe'),
							'desc' => __('Also supports shotcodes.', 'vibe'),
	                        'std' => 'Template Design © <a href="http://www.vibethemes.com" title="VibeCom">VibeThemes</a>. All rights reserved.'
						),
                        array(
						'id' => 'footerbottom_right',
                        'title' => __('Footer Bottom', 'vibe'),
                        'sub_desc' => __('Select an option for FooterBottom', 'vibe'),
                        'desc' => __('Select Footer Bottom style, set Social icon links in Social tab', 'vibe'),
                        'type' => 'button_set',
						'options' => array('' => __('Show Footer Menu','vibe'),'1'=>__('Show Social Icons','vibe')),
						'std' => ''
						),             
                        array(
							'id' => 'google_analytics',
							'type' => 'textarea',
							'title' => __('Google Analytics Code', 'vibe'), 
							'sub_desc' => __('Google Analytics account', 'vibe'),
							'desc' => __('Please enter full code with javascript tags.', 'vibe'),
						)
					 				
					)
				);
$sections[] = array(
				'icon' => 'twitter',
				'title' => __('Social Information', 'vibe'),
				'desc' => '<p class="description">'.__('All Social media settings','vibe').'..</p>',
				'fields' => array(
					   
                        array(
						'id' => 'social_icons',
						'type' => 'multi_social',
                        'title' => __('Add Social Media Icons ', 'vibe'),
                        'sub_desc' => __('Dynamically add social media icons', 'vibe'),
                        'desc' => __('Add your Full URL in social media.', 'vibe')
						),
                        array(
						'id' => 'social_icons_type',
						'type' => 'button_set',
						'title' => __('Social Icons Type', 'vibe'), 
						'sub_desc' => __('Social Icons Theme', 'vibe'),
						'options' => array('' => __('Minimal','vibe'),'round' => __('Round','vibe'),'square' => __('Square','vibe'),'round color' => __('Round Colored','vibe'),'square color' => __('Square Colored','vibe')),
						'std' => ''
						),
                        array(
						'id' => 'show_social_tooltip',
						'type' => 'button_set',
						'title' => __('Show Tooltip on Social Icons', 'vibe'), 
						'options' => array(1 => __('Yes','vibe'),0 => __('No','vibe')),
						'std' => 1
						),     
                        array(
						'id' => 'social_share',
						'type' => 'multi_select',
                        'title' => __('Social Sharing buttons', 'vibe'),
                        'sub_desc' => __('Show in-built sharing buttons in the theme', 'vibe'),
                        'desc' => __('Adds Social media sharing buttons in single Courses etc.', 'vibe'),
                        'options' => $social_links
						),
					)
				);

$sections[] = array(
				'icon' => 'location',
				'title' => __('Miscellaneous', 'vibe'),
				'desc' =>'<p class="description">'. __('Miscellaneous settings used in the theme.', 'vibe').'</p>',
				'fields' => array(
                                        
						array(
						'id' => 'layout',
						'type' => 'button_set',
						'title' => __('Theme Layout', 'vibe'), 
						'sub_desc' => __('Configure Layout of the theme', 'vibe'),
						'desc' => __('Select out of predefined layouts for the theme', 'vibe'),
						'options' => array('' => __('Wide','vibe'),'boxed' => __('Boxed','vibe')),
						'std' => ''
						), 
						array(
						'id' => 'excerpt_length',
						'type' => 'text',
						'title' => __('Default Excerpt Length', 'vibe'), 
						'sub_desc' => __('Excerpt length in number of Words.', 'vibe'),
						'std' => '20'
						),
						array(
						'id' => 'direct_checkout',
						'type' => 'button_set',
						'title' => __('Direct Checkout', 'vibe'), 
						'sub_desc' => __('Requires WooCommerce installed','vibe'),
						'desc' => __('User is redirected to the checkout page.', 'vibe'),
						'options' => array(2 => __('Skip Product & Cart page','vibe'),3 => __('Skip Product page','vibe'),1 => __('Skip Cart','vibe'),0 => __('Disable','vibe')),
						'std' => 0
						),
                        array(
						'id' => 'thankyou_redirect',
						'type' => 'button_set',
						'title' => __('Redirect to Course Page on Order completion', 'vibe'), 
						'sub_desc' => __('Only if the order contains one product with one course', 'vibe'),
						'desc' => __('If you\'re forcing the direct checkout, and your products have one course per product then switching this on would send users directly to the course page.', 'vibe'),
						'options' => array(0 => __('Disable','vibe'),1 => __('Enable','vibe')),
						'std' => 0
						),  
						array(
						'id' => 'force_complete',
						'type' => 'button_set',
						'title' => __('Force complete orders', 'vibe'), 
						'sub_desc' => __('Force all WooCommerce virtual products orders complete on thank you page.','vibe'),
						'desc' => __('All Paid Orders : All Orders with only Virtual products and payment complete orders, All Non-Fail Orders : All Orders in Processing, on Hold, Pending orders will be marked complete on Thank you page.', 'vibe'),
						'options' => array(0 => __('Disable','vibe'),1 => __('All Paid Orders','vibe'),2 => __('All Non-Fail Orders','vibe')),
						'std' => 0
						), 
						array(
						'id' => 'remove_woo_fields',
						'type' => 'button_set',
						'title' => __('Remove Extra Checkout Fields', 'vibe'), 
						'sub_desc' => __('Recommended if you\'re only selling courses/virtual products','vibe'),
						'desc' => __('Removes following fields in WooCommerce Checkout : Billing Company/Address,State/Town, Pincode, Phone etc.', 'vibe'),
						'options' => array(0 => __('No','vibe'),1 => __('Yes','vibe')),
						'std' => 0
						),   
						array(
						'id' => 'tincan',
						'type' => 'button_set',
						'title' => __('Enable TinCan recording', 'vibe'), 
						'sub_desc' => __('Record TinCan/XAPI statements in External LRS', 'vibe'),
						'desc' => __('Store all activity in an External LRS, which other XAPI compatible LMSes can read.', 'vibe'),
						'options' => array(0 => __('Disable','vibe'),1 => __('Enable','vibe')),
						'std' => 0
						),	
						array(
						'id' => 'tincan_endpoint',
                        'title' => __('TinCan API EndPoint', 'vibe'),
                        'sub_desc' => __('Add a TinCan API endpoint', 'vibe'),
                        'desc' => __('Add Endpoint to track details in external LRS.', 'vibe'),
                        'type' => 'text',
						'std' => '0'
						),
						array(
						'id' => 'tincan_user',
                        'title' => __('LRS User name', 'vibe'),
                        'sub_desc' => __('TinCan compatible LRS authentication', 'vibe'),
                        'desc' => __('Enter Username for external LRS authentication.', 'vibe'),
                        'type' => 'text',
						'std' => '0'
						),
						array(
						'id' => 'tincan_pass',
                        'title' => __('LRS Password', 'vibe'),
                        'sub_desc' => __('TinCan compatible LRS authentication', 'vibe'),
                        'desc' => __('Enter Password for external LRS authentication.', 'vibe'),
                        'type' => 'text',
						'std' => '0'
						),
                       array(
						'id' => 'contact_ll',
						'type' => 'text',
						'title' => __('Contact Page Latitude and Longitude values', 'vibe'), 
						'sub_desc' => __('Grab the latitude and Longitude values from .', 'vibe').'<a href="http://itouchmap.com/latlong.html">'.__('Link','vibe').'</a>',
						'std' => '43.730325,7.422155'
						),
                       array(
						'id' => 'contact_style',
						'type' => 'button_set',
						'title' => __('Contact Page Map Style', 'vibe'), 
						'sub_desc' => __('Select the map style on contact page.', 'vibe'),
						'desc' => __('Content area is the container in which all content is located.', 'vibe'),
						'options' => array('SATELLITE' => __('Saterllite View','vibe'),'ROADMAP' => __('Road map','vibe')),
						'std' => 'SATELLITE'
						),
						array(
							'id' => 'map_zoom',
							'type' => 'text',
							'title' => __('Google Map Zoom Level', 'vibe'), 
							'sub_desc' => __('Enter the zoom level in Google maps', 'vibe'),
							'desc' => __('Zoom Levels 0 - 19', 'vibe'),
							'std' => 17
						), 
                        array(
							'id' => 'error404',
							'type' => 'pages_select',
							'title' => __('Select 404 Page', 'vibe'), 
							'sub_desc' => __('This page is shown when page not found on your site.', 'vibe'),
							'desc' => __('User redirected to this page when page not found.', 'vibe'),
						), 
						array(
							'id' => 'minified',
							'type' => 'button_set',
							'title' => __('Use Minified Sctipts & Style', 'vibe'), 
							'sub_desc' => __('Force theme to use inbuilt minified scripts', 'vibe'),
							'desc' => __('Enable this option to force theme load inbuilt minified scripts and styles.', 'vibe'),
							'options' => array('' => __('No','vibe'),1 => __('Yes','vibe')),
							'std' => ''
						),
						array(
							'id' => 'xmlrpc',
							'type' => 'button_set',
							'title' => __('Disable XMLRPC/RSD/WLWManifest', 'vibe'), 
							'sub_desc' => __('Remove security vulnerabilities', 'vibe'),
							'desc' => __('Removes vulnerabilities at expense of ability to login via remote apps.', 'vibe'),
							'options' => array('' => __('No','vibe'),1 => __('Yes','vibe')),
							'std' => ''
						),
						array(
							'id' => 'credits',
							'type' => 'text',
							'title' => __('Author & Credits', 'vibe'), 
							'sub_desc' => __('Credits and Author of the Website', 'vibe'),
							'desc' => __('Changes the reference to Author {VibeThemes}', 'vibe'),
	                        'std' => 'VibeThemes'
						),
                      )
                    );      
	$tabs = array();
	
			
	if (function_exists('wp_get_theme')){
		$theme_data = wp_get_theme();
		$theme_uri = $theme_data->get('ThemeURI');
		$description = $theme_data->get('Description');
		$author = $theme_data->get('Author');
		$version = $theme_data->get('Version');
		$tags = $theme_data->get('Tags');
	}else{
		$theme_data = get_theme_data(trailingslashit(get_stylesheet_directory()).'style.css');
		$theme_uri = $theme_data['URI'];
		$description = $theme_data['Description'];
		$author = $theme_data['Author'];
		$version = $theme_data['Version'];
		$tags = $theme_data['Tags'];
	}	

	$theme_info = '<div class="vibe-opts-section-desc">';
	$theme_info .= '<p class="vibe-opts-theme-data description theme-uri"><strong>Theme URL:</strong> <a href="'.$theme_uri.'" target="_blank">'.$theme_uri.'</a></p>';
	$theme_info .= '<p class="vibe-opts-theme-data description theme-author"><strong>Author:</strong>'.$author.'</p>';
	$theme_info .= '<p class="vibe-opts-theme-data description theme-version"><strong>Version:</strong> '.$version.'</p>';
	$theme_info .= '<p class="vibe-opts-theme-data description theme-description">'.$description.'</p>';
	$theme_info .= '<p class="vibe-opts-theme-data description theme-tags"><strong>Tags:</strong> '.implode(', ', $tags).'</p>';
	$theme_info .= '</div>';



	$tabs['theme_info'] = array(
					'icon' => 'info-sign',
					'title' => __('Theme Information', 'vibe'),
					'content' => $theme_info
					);
	/*
	if(file_exists(trailingslashit(get_stylesheet_directory()).'README.html')){
		$tabs['theme_docs'] = array(
						'icon' => 'book',
						'title' => __('Documentation', 'vibe'),
						'content' => nl2br(file_get_contents(trailingslashit(get_stylesheet_directory()).'README.html'))
						);
	}*///if

	global $VIBE_Options;
	$VIBE_Options = new VIBE_Options($sections, $args, $tabs);
       


}//function
add_action('init', 'setup_framework_options', 0);

/*
 * 
 * Custom function for the callback referenced above
 *
 */
function my_custom_field($field, $value){
	print_r($field);
	print_r($value);

}//function

/*
 * 
 * Custom function for the callback validation referenced above
 *
 */
function validate_callback_function($field, $value, $existing_value){
	
	$error = false;
	$value =  'just testing';
	
	$return['value'] = $value;
	if($error == true){
		$return['error'] = $field;
	}
	return $return;
	
}//function
?>