<?php

/**
 * FILE: config.php 
 * Author: Mr.Vibe 
 * Credits: www.VibeThemes.com
 * Project: WPLMS
 */

global $vibe_options;
$google_fonts = vibe_get_option('google_fonts');
$fonts=array();
if(isset($google_fonts)&& is_array($google_fonts))
foreach($google_fonts as $font){
    $fonts[$font]=$font;
}



                    
$vibe_customizer = array(
    'sections' => array(
                    'theme'=>'Theme',
                    'header'=>'Header',
                    'typography'=>'Typography',
                    'body'=>'Body',
                    'footer'=>'Footer',
                    ),
    'controls' => array(
        'theme' => array( 
                            'primary_bg' => array(
                                                'label' => 'Theme Primary Color',
                                                'type'  => 'color',
                                                'default' => '#78c8c9'
                                                ),
                            'primary_color' => array(
                                                    'label' => 'Theme Primary Text Color',
                                                    'type'  => 'color',
                                                    'default' => '#FFF'
                                                    ),  
                            ),
        'header' => array(  
                            'logo_size' => array(
                                                'label' => 'Logo size (height in px)',
                                                'type'  => 'slider',
                                                'default' => '48'
                                                ),
                            'header_top_bg' => array(
                                                'label' => 'Header Top Background Color',
                                                'type'  => 'color',
                                                'default' => '#232b2d'
                                                ),
                            'header_top_color' => array(
                                                    'label' => 'Header Top Text Color',
                                                    'type'  => 'color',
                                                    'default' => '#FFF'
                                                    ),  
                            'header_bg' => array(
                                                    'label' => 'Header Background Color',
                                                    'type'  => 'color',
                                                    'default' => '#313b3d'
                                                    ),  
                            'header_color' => array(
                                                    'label' => 'Header Text / Menu Text Color',
                                                    'type'  => 'color',
                                                    'default' => '#FFF'
                                                    ),  
                            'nav_bg' => array(
                                                    'label' => 'Sub Navigation Background Color',
                                                    'type'  => 'color',
                                                    'default' => '#48575a'
                                                    ), 
                            'nav_color' => array(
                                                    'label' => 'Sub-Nav Text Color',
                                                    'type'  => 'color',
                                                    'default' => '#FFF'
                                                    ),  

                            ),

        'typography' => array(
            
                            'h1_font' => array(
                                                            'label' => 'H1 Font Family',
                                                            'type'  => 'select',
                                                            'choices' => $fonts,
                                                            'default' => ''
                                                            ),
                              'h1_font_weight' => array(
                                                            'label' => 'H1: Font Weight',
                                                            'type'  => 'select',
                                                            'choices' => array(
                                                                '100'=>'100 : Lighter',
                                                                '200'=>'200 : Light',
                                                                '300'=>'300 : Light',
                                                                '400'=>'400 : Normal',
                                                                '600'=>'600 : Bold',
                                                                '700'=>'700 : Bolder',
                                                                '800'=>'800 : Bolder'
                                                            ),
                                                            'default' => '400'
                                                            ),                               
                             'h1_color' => array(
                                                            'label' => 'H1 Font Color',
                                                            'type'  => 'color',
                                                            'default' => '#474747'
                                                            ),
                             'h1_size' => array(
                                                            'label' => 'H1 Font Size (in px)',
                                                            'type'  => 'slider',
                                                            'default' => '28'
                                                            ),       
                             
                             'h2_font' => array(
                                                            'label' => 'H2 Font Family',
                                                            'type'  => 'select',
                                                            'choices' => $fonts,
                                                            'default' => ''
                                                            ),   
                               'h2_font_weight' => array(
                                                            'label' => 'H2: Font Weight',
                                                            'type'  => 'select',
                                                            'choices' => array(
                                                                '100'=>'100 : Lighter',
                                                                '200'=>'200 : Light',
                                                                '300'=>'300 : Light',
                                                                '400'=>'400 : Normal',
                                                                '600'=>'600 : Bold',
                                                                '700'=>'700 : Bolder',
                                                                '800'=>'800 : Bolder'
                                                            ),
                                                            'default' => '400'
                                                            ),                              
                             'h2_color' => array(
                                                            'label' => 'H2 Font Color',
                                                            'type'  => 'color',
                                                            'default' => '#474747'
                                                            ),
                             'h2_size' => array(
                                                            'label' => 'H2 Font Size (in px)',
                                                            'type'  => 'slider',
                                                            'default' => '22'
                                                            ),  
                             'h3_font' => array(
                                                            'label' => 'H3 Font Family',
                                                            'type'  => 'select',
                                                            'choices' => $fonts,
                                                            'default' => ''
                                                            ),  
                             'h3_font_weight' => array(
                                                            'label' => 'H3: Font Weight',
                                                            'type'  => 'select',
                                                            'choices' => array(
                                                                '100'=>'100 : Lighter',
                                                                '200'=>'200 : Light',
                                                                '300'=>'300 : Light',
                                                                '400'=>'400 : Normal',
                                                                '600'=>'600 : Bold',
                                                                '700'=>'700 : Bolder',
                                                                '800'=>'800 : Bolder'
                                                            ),
                                                            'default' => '400'
                                                            ),                               
                             'h3_color' => array(
                                                            'label' => 'H3 Font Color',
                                                            'type'  => 'color',
                                                            'default' => '#474747'
                                                            ),
                             'h3_size' => array(
                                                            'label' => 'H3 Font Size (in px)',
                                                            'type'  => 'slider',
                                                            'default' => '18'
                                                            ),     
                            'h4_font' => array(
                                                            'label' => 'H4 Font Family',
                                                            'type'  => 'select',
                                                            'choices' => $fonts,
                                                            'default' => ''
                                                            ),  
                             'h4_font_weight' => array(
                                                            'label' => 'H4: Font Weight',
                                                            'type'  => 'select',
                                                            'choices' => array(
                                                                '100'=>'100 : Lighter',
                                                                '200'=>'200 : Light',
                                                                '300'=>'300 : Light',
                                                                '400'=>'400 : Normal',
                                                                '600'=>'600 : Bold',
                                                                '700'=>'700 : Bolder',
                                                                '800'=>'800 : Bolder'
                                                            ),
                                                            'default' => '400'
                                                            ),                               
                             'h4_color' => array(
                                                            'label' => 'H4 Font Color',
                                                            'type'  => 'color',
                                                            'default' => '#474747'
                                                            ),
                             'h4_size' => array(
                                                            'label' => 'H4 Font Size (in px)',
                                                            'type'  => 'slider',
                                                            'default' => '16'
                                                            ),    
                             'h5_font' => array(
                                                            'label' => 'H5 Font Family',
                                                            'type'  => 'select',
                                                            'choices' => $fonts,
                                                            'default' => ''
                                                            ),   
                             'h5_font_weight' => array(
                                                            'label' => 'H5: Font Weight',
                                                            'type'  => 'select',
                                                            'choices' => array(
                                                                '100'=>'100 : Lighter',
                                                                '200'=>'200 : Light',
                                                                '300'=>'300 : Light',
                                                                '400'=>'400 : Normal',
                                                                '600'=>'600 : Bold',
                                                                '700'=>'700 : Bolder',
                                                                '800'=>'800 : Bolder'
                                                            ),
                                                            'default' => '400'
                                                            ),                               
                             'h5_color' => array(
                                                            'label' => 'H5 Font Color',
                                                            'type'  => 'color',
                                                            'default' => '#474747'
                                                            ),
                             'h5_size' => array(
                                                            'label' => 'H5 Font Size (in px)',
                                                            'type'  => 'slider',
                                                            'default' => '14'
                                                            ),     
                             'h6_font' => array(
                                                            'label' => 'H6 Font Family',
                                                            'type'  => 'select',
                                                            'choices' => $fonts,
                                                            'default' => ''
                                                            ),   
                             'h6_font_weight' => array(
                                                            'label' => 'H6: Font Weight',
                                                            'type'  => 'select',
                                                            'choices' => array(
                                                                '100'=>'100 : Lighter',
                                                                '200'=>'200 : Light',
                                                                '300'=>'300 : Light',
                                                                '400'=>'400 : Normal',
                                                                '600'=>'600 : Bold',
                                                                '700'=>'700 : Bolder',
                                                                '800'=>'800 : Bolder'
                                                            ),
                                                            'default' => '400'
                                                            ),                               
                             'h6_color' => array(
                                                            'label' => 'H6 Font Color',
                                                            'type'  => 'color',
                                                            'default' => '#474747'
                                                            ),
                             'h6_size' => array(
                                                            'label' => 'H6 Font Size (in px)',
                                                            'type'  => 'slider',
                                                            'default' => '12'
                                                            ),    
                                                          
            ),

        'body' => array( 
                            'body_bg' => array(
                                                'label' => 'Body Background Color',
                                                'type'  => 'color',
                                                'default' => '#f6f6f6'
                                                ),
                            'content_bg' => array(
                                                    'label' => 'Content Area Background Color',
                                                    'type'  => 'color',
                                                    'default' => '#FFF'
                                                    ), 
                            'content_color' => array(
                                                    'label' => 'Content Area Text Color',
                                                    'type'  => 'color',
                                                    'default' => '#444'
                                                    ),                          
                            ),
        'footer' => array( 
                            'footer_bg' => array(
                                                'label' => 'Footer Background Color',
                                                'type'  => 'color',
                                                'default' => '#313b3d'
                                                ),
                            'footer_color' => array(
                                                    'label' => 'Footer Text Color',
                                                    'type'  => 'color',
                                                    'default' => '#FFF'
                                                    ), 
                            'footer_heading_color' => array(
                                                    'label' => 'Footer Heading Color',
                                                    'type'  => 'color',
                                                    'default' => '#FFF'
                                                    ), 
                            'footer_bottom_bg' => array(
                                                    'label' => 'Bottom Footer Text Color',
                                                    'type'  => 'color',
                                                    'default' => '#232b2d'
                                                    ), 
                            'footer_bottom_color' => array(
                                                    'label' => 'Bottom Footer Text Color',
                                                    'type'  => 'color',
                                                    'default' => '#FFF'
                                                    ),                                                  
                            ),

    ),
);

?>
