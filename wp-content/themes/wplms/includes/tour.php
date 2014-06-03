<?php

/**
 * FILE: tour.php 
 * Author: Mr.Vibe 
 * Credits: www.VibeThemes.com
 * Project: WPLMS 
 * 
 */
 
add_action( 'admin_enqueue_scripts', 'tour_enqueue_scripts' );
function tour_enqueue_scripts() {
    wp_enqueue_style( 'wp-pointer' );
    wp_enqueue_script( 'wp-pointer' );
    add_action( 'admin_print_footer_scripts', 'vibe_tour_print_footer_scripts' );
}

function tour_config(){
$tour_config =  array(
         0 => array(
                                            'css' => '#toplevel_page_vest_options',
                                            'title' => 'Congratulations!',
                                            'description' => __('You\'ve just installed WPLMS WordPress Theme by VibeThemes! Click on continue to view a Quick introduction of this theme\'s core functionality.','vibe'),
                                            'edge' => 'bottom',
                                            'align' => 'middle',
                                            'button' => 'Start Tour',
                                            'close' => 'window.location = "themes.php?page=install-required-plugins";'
                                            ),
        1 => array(
                                            'css' => '#tgmpa-plugins',
                                            'title' => 'Install Plugins!',
                                            'description' => __('Begin Installing necessary plugins.<br />1. Premium Revolution Slider plugin <br />2. Vibe Importer plugin (* Only required for Sample data import).<br /><br /> <strong>NOTE : This is a modified version of WP Default Importer. Please deactivate and delete WordPress Importer plugin before activating Vibe WordPress Importer plugin.</strong> ','vibe'),
                                            'edge' => 'top',
                                            'align' => 'middle',
                                            'button' => 'Start Tour',
                                            'close' => 'window.location = "admin.php?page=vest_options&tab=0";'
                                            ),
        2 => array(
                                            'css' => '#contextual-help-link',
                                            'title' => 'Support & Documentation',
                                            'description' => __('1. <strong>Support Links </strong> : How/Where to get help from ? <br /><br />2. <strong>Documentation & Important links</strong> : Links to online Documentation and Important Bug fixes.<br /><br />3. <strong>Upgrades</strong> : What All has been fixed in the Latest Version <br /><br />4. <strong>Notices</strong> : Important upgrades and features coming up in next version. ','vibe'),
                                            'edge' => 'top',
                                            'align' => 'right',
                                            'close' => 'window.location = "admin.php?page=vest_options&tab=0";',
                                            'custom_css' => '.wp-pointer-top .wp-pointer-arrow {left:280px !important;}'
                                            ),
    3 => array(
                                            'css' => '#sampleinstall',
                                            'title' => 'Sample Data Install',
                                            'description' => __('Click to Install Sample data. It will auto generate sample Posts,Pages, Media and Portfolio to make your WordPress Installation look <strong>similar</strong> to the theme demo.','vibe'),
                                            'edge' => 'left',
                                            'align' => 'middle',
                                            'close' => 'window.location = "admin.php?page=vest_options&tab=0"'
                                            ),
   4 => array(
                                            'css' => '.hr h3.description',
                                            'title' => 'Enter Themeforest credentials',
                                            'description' => __('Enter your Themeforest purchase credentials, this is necessary for auto-updates and helps us in validating your purchase.','vibe'),
                                            'edge' => 'left',
                                            'align' => 'middle',
                                            'close' => 'window.location = "admin.php?page=vest_options&tab=1"'
                                            ), 
    5 => array(
                                            'css' => '#1_section_group_li_a',
                                            'title' => 'General Settings',
                                            'description' => '1. Enable/Disable Responsiveness of the theme, in case you want the theme look same on all view ports (Mobiles and Desktops)<br /><br />2. Select the Theme Layout, Boxed or Wide Mode.<br /><br />3. Select the Content Area Layout, Opt for a standard 960px or for a wider 1170px content area.',
                                            'edge' => 'left',
                                            'align' => 'middle',
                                            'close' => 'window.location = "admin.php?page=vest_options&tab=2"'
                                            ), 
    6 => array(
                                            'css' => '#2_section_group_li_a',
                                            'title' => 'Header Settings',
                                            'description' => __('1. Select Header Style.<br /><br />2. Upload a high resolution logo.<br /><br />3. Upload a 16x16px favicon. <br /><br />4. Fix Navigation on Top as user scrolls down.<br /><br />5. Show this content on right side of header.<br /><br />6. Show content above logo area on the left, for top right menu go to Appearance -> Menus -> Location -> Top Menu .<br /><br />7. Switch to show Header sidebar can be triggered from "+" icon .<br /><br />8. Select the sidebar layout and add widgets to this sidebar from Appearance -> Widgets.','vibe'),
                                            'edge' => 'left',
                                            'align' => 'middle',
                                            'close' => 'window.location = "admin.php?page=vest_options&tab=3"'
                                            ), 
    7 => array(                             'css' => '#3_section_group_li_a',
                                            'title' => 'Listings Manager',
                                            'description' => __('1. Create New Fields to be shown in Listing Pages / Archives<br /><br />2. Set Currency for price field.<br /><br />3. Select unit for Area.<br /><br />4. Select Google map zoom level.<br /><br />5. Set Contact submissions email : Subject. These submissions are made via contact forms in individual listings.<br /><br />6. Select/Create a page for Listings Search results.','vibe'),
                                            'edge' => 'left',
                                            'align' => 'middle',
                                            'close' => 'window.location = "admin.php?page=vest_options&tab=4"'
                                            ),                                         
    
    8 => array(                             'css' => '#4_section_group_li_a',
                                            'title' => 'Sidebar Manager',
                                            'description' => __('1. Create New sidebars<br /><br />2. Set default Sidebar for Custom Post Type. This sidebar is shown in Post Type Category/Tags/Search pages.','vibe'),
                                            'edge' => 'left',
                                            'align' => 'middle',
                                            'close' => 'window.location = "admin.php?page=vest_options&tab=5"'
                                            ), 
    
    9 => array(
                                            'css' => '#5_section_group_li_a',
                                            'title' => 'Custom Post Type Generator',
                                            'description' => __('Create New Custom Post Types.','vibe'),
                                            'edge' => 'left',
                                            'align' => 'middle',
                                            'close' => 'window.location = "admin.php?page=vest_options&tab=6"'
                                            ), 
    10 => array(
                                            'css' => '#6_section_group_li_a',
                                            'title' => 'Fonts Manager',
                                            'description' => __('1. Select Fonts from 640+ Google Web Fonts to be included in the Theme<br /><br />2. Refresh Google fonts list.<br /><br />3. Add New custom fonts in Child theme & include them in the font list shown in Theme Customizer.','vibe'),
                                            'edge' => 'left',
                                            'align' => 'middle',
                                            'close' => 'window.location = "admin.php?page=vest_options&tab=7"'
                                            ), 
    11 => array(
                                            'css' => '#7_section_group_li_a',
                                            'title' => 'Customizer',
                                            'description' => __('Manage Theme Customizer, Import/Export customizer settings.','vibe'),
                                            'edge' => 'left',
                                            'align' => 'middle',
                                            'close' => 'window.location = "admin.php?page=vest_options&tab=8"'
                                            ),     
    12 => array(
                                            'css' => '#8_section_group_li_a',
                                            'title' => 'Pagebuilder Manager',
                                            'description' => __('1. Delete Pagebuilder saved layouts. <br /><br />2. Import/Export Pagebuilder Sample Layouts to any other WP installations.','vibe'),
                                            'edge' => 'left',
                                            'align' => 'middle',
                                            'close' => 'window.location = "admin.php?page=vest_options&tab=9"'
                                            ),
    13 => array(
                                            'css' => '#9_section_group_li_a',
                                            'title' => 'Social Information',
                                            'description' => __('1. Add Social Social Media Icons & Information used in Footer. <br /><br />2. Set Social Icons Type, select social icons appearance out of 6 given choices. <br /><br />3. Show Tooltip on Social Icons.<br /><br />4. Set Social Sharing Icons shown on blog posts.<br /><br />5. Enable/Disable <strong>Likes</strong> in Posts & Portfolio.','vibe'),
                                            'edge' => 'left',
                                            'align' => 'middle',
                                            'close' => 'window.location = "admin.php?page=vest_options&tab=10"'
                                            ),
     14 => array(
                                            'css' => '#10_section_group_li_a',
                                            'title' => 'Custom Connect',
                                            'description' => __('1. Show Featured Media Metabox in WP Admin interface while editing Posts/Pages/Portfolio etc of selected Post Types. <br /><br />2. Show Settings Metabox in WP Admin interface while editing  Posts/Pages/Portfolio etc of selected Post Types.<br /><br />3. Show PageBuilder in WP Admin interface while editing Posts/Pages/Portfolio etc of selected Post Types<br /><br />4. Show Custom CSS Changes Metabox in Admin interface while editing  Posts/Pages/Portfolio etc of selected Post Types.<br /><br />5. Show Subheader Metabox in editor interface. Change Subheader image, add a sub title to heading etc..<br /><br />6. Show In-Page Menu Metabox in Admin interface while editing  Posts/Pages/Portfolio etc of selected Post Types.<br /><br />7. Set Excerpt Length for Large Width Category.<br /><br />8. Set Excerpt Length for Small Width Category.','vibe'),
                                            'edge' => 'left',
                                            'align' => 'middle',
                                            'close' => 'window.location = "admin.php?page=vest_options&tab=11"'
                                            ),
    15 => array(
                                            'css' => '#11_section_group_li_a',
                                            'title' => 'SEO Settings',
                                            'description' => __('1. Recommended SEO Plugins. <br /><br />2. Setup Image Alt format for images not having Alt.<br /><br />3. Force Set Image alt for all images.<br /><br />4. Setup Image Title format for images not having Title.<br /><br />5. Force Set Image title for all images<br /><br />6. Remove Windows Live Writer link.<br /><br />7. Set DataBase Optimization frequency.','vibe'),
                                            'edge' => 'left',
                                            'align' => 'middle',
                                            'close' => 'window.location = "admin.php?page=vest_options&tab=12"'
                                            ),
  16 => array(
                                            'css' => '#12_section_group_li_a',
                                            'title' => 'Footer',
                                            'description' => __('1. Set Footer Column Layout. <br /><br />2. Set First/Second/Third/Footer Footer Column Sidebar.<br /><br />3. Add Social Media Icons to show in Footer.<br /><br />4. Write Copyright text, shown in footer.<br /><br />5. Paste Google analytics code to track Page views,visits analytics in Google Analytics','vibe'),
                                            'edge' => 'left',
                                            'align' => 'middle',
                                            'close' => 'window.location = "admin.php?page=vest_options&tab=13"'
                                            ),   
  17 => array(
                                            'css' => '#13_section_group_li_a',
                                            'title' => 'Miscellaneous',
                                            'description' => __('1. Enable/Disabled Breadcrumbs throughout the theme<br /><br />2. Show Advanced Search below this search results.<br /><br />3. Set Thumbnail Effect for Pagebuilder thumbnail blocks<br /><br />4. Link Featured Image/Media to the post<br /><br />5. Set Default image.<br /><br />6. Set 404 Page<br /><br />7. Set Gallery thumbnail slider controls and delay.','vibe'),
                                            'edge' => 'left',
                                            'align' => 'middle',
                                            'close' => 'window.location = "admin.php?page=vest_options&tab=import_export_default"'
                                            ),  
    18 => array(
                                            'css' => '#import_export_default_section_group_li_a',
                                            'title' => 'Import/Export Settings',
                                            'description' => __('Import/Export WPLMSVibe Options panel settings','vibe'),
                                            'edge' => 'left',
                                            'align' => 'middle',
                                            'close' => 'window.location = "admin.php?page=vest_options&tab=0"'
                                            ), 
    19 => array(
                                            'css' => '#vibe-opts-header .vibe-save',
                                            'title' => 'Save',
                                            'description' => __('Save Changes<br /><strong>End of Tour !</strong>','vibe'),
                                            'edge' => 'right',
                                            'align' => 'middle',
                                            'close' => 'window.location = "admin.php?page=vest_options&tab=0"'
                                            ),    
);

return $tour_config;
}
//array( "edge" => "'.$tour['position'].'", "align" => "'.$tour['align'].'" ),
function vibe_tour_print_footer_scripts() { 
 
    if(isset($_GET['tour']) && $_GET['tour'] == 'start'){
       update_option('tour_number',0);
    }

        $tour_number=0;
    if(get_option('tour_number') <21){
        $tour_number = get_option('tour_number');
    
   
       
   $tour_config  = tour_config();
   $js ='';
   $tour = $tour_config[$tour_number];
   $tour_number ++;
     $js .='$("'.$tour['css'].'").pointer({
         content: "'.((isset($tour['title']) && $tour['title'])?'<h3>'.$tour['title'].'</h3>':'').'<p>'.$tour['description'].'</p>",
         position: {
            edge: "'.$tour['edge'].'",
            align: "'.$tour['align'].'"
            },
         buttons:function (event, t) {
					button = jQuery(\'<a id="pointer-continue" style="margin-left:5px;" class="button-secondary">\' + \'Continue\' + \'</a><a id="pointer-close" style="margin-left:5px;float:right;width:20px;position: absolute;top: 15px;right: 10px;">\' + \'<i class="icon-cancel-2"></i>\' + \'</a>\');
					button.bind(\'click.pointer\', function () {
						t.element.pointer(\'close\');
					});
                                        
					return button;
				},   
         close: function(event){
                $("#pointer-continue").live("click", function(){
                     $.ajax({
	              type: "POST",
	              url: ajaxurl,
                      data: {   action: "tour_number", 
                                n: '.($tour_number+1).'
                            },
	              cache: false,
	              success: function (html) {
	                  '.$tour['close'].'  
	              }
                });
            }); 
          }
         }).pointer("open");
';
     
     
?>
   <script type="text/javascript">
    jQuery(document).ready( function($) { 
        <?php echo $js; ?>
    });
   </script>
<?php
 }
 }
 
 add_action('admin_head', 'tour_Css');
 function tour_Css(){
 $tour_number=0; 
 if(!get_option('tour_number'))
     add_option('tour_number',0);
 
    if(get_option('tour_number') <20){
        $tour_number = get_option('tour_number');
    }else 
        $tour_number = 0;
    
    $tour_config  = tour_config();
    $tour = $tour_config[$tour_number];
    if(isset($tour['custom_css'])){
       echo '<style>
                   '.$tour['custom_css'].' 
             </style>';
       
   }
 }