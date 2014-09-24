<?php

/**
 * FILE: customizer.php 
 * Author: Mr.Vibe 
 * Credits: www.VibeThemes.com
 * Project: WPLMS
 */

include_once 'class.php';

//REgisterig Theme Settings/Cusomizer

function vibe_customizer_setup() {
  $customize = get_option('vibe_customizer');
  if(!isset($customize)){
      add_option('vibe_customizer','');
  }
}

// add some settings and such

add_action('customize_register', 'vibe_customize');
add_action('after_setup_theme','vibe_customizer_setup');



function vibe_customize($wp_customize) {

    require_once(dirname(__FILE__) . '/config.php');
/*====================================================== */
/*===================== SECTIONS ====================== */
/*====================================================== */
    $i=164; // Show sections after the WordPress default sections
    if(isset($vibe_customizer) && is_Array($vibe_customizer)){
        foreach($vibe_customizer['sections'] as $key=>$value){
            $wp_customize->add_section( $key, array(
            'title'          => $value,
            'priority'       => $i,
        ) );
            $i = $i+4;
        }
    }
    

/*====================================================== */
/*================= SETTINGS & CONTROLS ================== */
/*====================================================== */
if(isset($vibe_customizer) && is_array($vibe_customizer))
    foreach($vibe_customizer['controls'] as $section => $settings){ $i=1;
        foreach($settings as $control => $type){
            $i=$i+2;
            /*====== REGISTER SETTING =========*/
            $wp_customize->add_setting( 'vibe_customizer['.$control.']', array(
                                                'label'         => $type['label'],
                                                'type'           => 'option',
                                                'capability'     => 'edit_theme_options',
                                                'default'       => $type['default']
                                            ) );
            
            switch($type['type']){
                case 'color':
                        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $control, array(
                        'label'   => $type['label'],
                        'section' => $section,
                        'settings'   => 'vibe_customizer['.$control.']',
                        'priority'       => $i
                        ) ) );            
                    break;
                case 'image':
                        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $control, array(
                            'label'   => $type['label'],
                            'section' => $section,
                            'settings'   => 'vibe_customizer['.$control.']',
                            'priority'       => $i
                        ) ) );
                    break;
                case 'select':
                        $wp_customize->add_control( $control, array(
                                'label'   => $type['label'],
                                'section' => $section,
                                'settings'   => 'vibe_customizer['.$control.']',
                                'priority'       => $i,
                                'type'    => 'select',
                                'choices'    => $type['choices']
                        
                                ) );
                    break;
                case 'text':
                        $wp_customize->add_control( $control, array(
                                'label'   => $type['label'],
                                'section' => $section,
                                'settings'   => 'vibe_customizer['.$control.']',
                                'priority'       => $i,
                                'type'    => 'text',
                                ) );
                    break;
                case 'slider':
                        $wp_customize->add_control( new Vibe_Customize_Slider_Control( $wp_customize, $control, array(
                                'label'   => $type['label'],
                                'section' => $section,
                                'settings'   => 'vibe_customizer['.$control.']',
                                'priority'       => $i,
                                'type'    => 'slider',
                                ) ) );
                    break;
                case 'textarea':
                        $wp_customize->add_control( new Vibe_Customize_Textarea_Control( $wp_customize, $control, array(
                                'label'   => $type['label'],
                                'section' => $section,
                                'settings'   => 'vibe_customizer['.$control.']',
                                'priority'       => $i,
                                'type'    => 'textarea',
                                ) ) );
                    break;
            }
        }
    }
}

add_action('customize_controls_print_styles', 'vibe_customize_css');

function vibe_customize_css(){
    wp_enqueue_style('customizer_css',VIBE_URL.'/includes/customizer/customizer.css');
}

add_action('customize_controls_print_scripts', 'vibe_customize_scripts');
function vibe_customize_scripts(){
    wp_enqueue_script('customizer_js',VIBE_URL.'/includes/customizer/customizer.js',array('jquery','jquery-ui-core','jquery-ui-slider'));
}
?>
