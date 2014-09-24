<?php
global $gc_message_bar_options;
 $gc_message_bar_options = array(
			'installed' => array(
                'id' => 'installed',
                'default' => "false",
                'text' => 'Plugin istalled',
                'type' => 'text',
                'group' => 'general',
                'visible' => false
            ),
			'z_index' => array(
                'id' => 'z_index',
                'default' => '0',
                'text' => 'Z-index:',
                'type' => 'number',
                'group' => 'internal_engine',
                'visible' => true,
                'description' => 'The Z-index property of the GC Message Bar. Enter 0 to set default. Default value is 99998'
            ),
            'enable_remote_configuration' => array(
                'id' => 'enable_remote_configuration',
                'default' =>1,
                'text' => 'Enable Remote Configuration:',
                'type' => 'onoff',
                'group' => 'internal_engine',
                'visible' => true,
                'description' => 'Enable connect to MY.GetConversion'
            ),
            'enable_remote_debug' => array(
                'id' => 'enable_remote_debug',
                'default' =>1,
                'text' => 'Enable Remote Debug:',
                'type' => 'onoff',
                'group' => 'internal_engine',
                'visible' => true,
                'description' => 'Enable remote debugging'
            ),
            'metrix_code' => array(
                'id' => 'metrix_code',
                'default' =>"",
                'text' => 'Metrix code:',
                'type' => 'text',
                'group' => 'internal_engine',
                'renderer' => 'Metrix_Code_Renderer',
                'visible' => true,
                'description' => 'Tracking code for MY.GetConversion (Format: XXXXXXX-X)'
            ),
            'cache_directory' => array(
                'id' => 'cache_directory',
                'default' => '',
                'text' => 'CSS Cache Directory:',
                'type' => 'number',
                'group' => 'internal_engine',
                'visible' => true,
                'description' => 'The style cache path directory of the GC Message Bar<br/>Default value: {plugin dir}/cache - Otherwise: {WP install path (ABSPATH)}/&lt;the_value&gt;'
            ),
			
			/*
			 * COMPOSE
			 */
			'action_url' => array(
                'id' => 'action_url',
                'default' =>'http://wordpress.org/extend/plugins/gc-message-bar/',
                'text' => 'Action URL',
                'type' => 'text',
                'group' => 'compose',
                'description' => 'Use absolute or relative path'
                ),
            'action_target' => array(
                'id' => 'action_target',
                'default' =>'1',
                'text' => 'Open In New window',
                'type' => 'checkbox',
                'group' => 'compose'
                ),
			'action_nofollow' => array(
                'id' => 'action_nofollow',
                'default' =>'1',
                'text' => 'No Follow',
                'type' => 'checkbox',
                'group' => 'compose'
                ),
            'action_button_text' => array(
                'id' => 'action_button_text',
                'default' =>'Download',
                'text' => 'Action Button Text',
				'formatting' => true,
                'type' => 'text',
                'group' => 'compose',
                'description' => 'Recommended max length: 24 characters'
                ),
            'message_text' => array(
                'id' => 'message_text',
                'default' =>'Get an awesome sticky message bar!',
                'text' => 'Message Text',
                'type' => 'textarea',
				'formatting' => true,
                'group' => 'compose',
                'description' => 'Recommended max length: 78 characters'
            ),

			
            /* 
             * GENERAL SETTINGS
             */
            'bar_enable' => array(
                'id' => 'bar_enable',
                'default' =>'1',
                'text' => 'Enable GC Message Bar:',
                'type' => 'onoff',
                'group' => 'general'
                ),
			'enable_adminbar' => array(
				'id' => 'enable_adminbar',
				'default' => '1',
				'text' => 'Enable WP Admin Bar Navigation:',
				'type' => 'onoff',
				'group' => 'general'
			),
            'css_handling' => array(
                'id' => 'css_handling',
                'default' =>'1',
                'text' => 'CSS Caching:',
                'type' => 'select',
                'options' => array(
                    '1' => 'Dynamic (Slowest)',
                    '2' => 'Cached (Fastest)',
                    '3' => 'Inline'
                ),
                'group' => 'general',
                'description' => "Select the caching method of the plugin's CSS<br/>DYNAMIC = No cache - INLINE = CSS generated in to HTML - CACHED = Generated static CSS"
            ),
            'content_align' => array(
                'id' => 'content_align',
                'default' =>'2',
                'text' => 'Content Align:',
                'type' => 'select',
                'options' => array(
                    '1' => 'Button at end: message left / button left',
                    '2' => 'Button at end: message center / button center',
                    '3' => 'Button at end: message right / button right',
                    '4' => 'Button at end: message left / button right',
                    '5' => 'Button first: button left / message left',
                    '6' => 'Button first: button center / message center',
                    '7' => 'Button first: button right / message right',
                    '8' => 'Button first: button left / message right'
                ),
                'group' => 'general',
                'description' => 'Text content and button positions'
            ),
			'location' => array(
				'id' => 'location',
				'default' => '1',
				'text' => 'Location:',
				'type' => 'select',
				'options' => array(
					'1' => 'Top of the window',
					'2' => 'Bottom of the window'
				),
				'group' => 'general',
				'description' => 'The Message Bar can be at the top or the bottom of the page'
			),
			'enable_close_button' => array(
				'id' => 'enable_close_button',
				'default' => '1',
				'text' => 'Enable Close Button:',
				'type' => 'onoff',
				'group' => 'general',
				'description' => 'You can disable or enable the Message Bar close button'
			),
			'button_align' => array(
				'id' => 'button_align',
				'default' => '2',
				'text' => 'Close Button Alignment:',
				'type' => 'select',
				'group' => 'general_close',
				'options' => array(
					'1' => 'Left aligned',
					'2' => 'Right aligned'
				),
				'description' => 'You can specify where should we put the close button'
			),
            'state_cookie_time' => array(
                'id' => 'state_cookie_time',
                'default' => '24', 
                'text' => 'State Cookie Time (hours):',
                'type' => 'number',
                'group' => 'general_close',
                'description' => 'This time long will remember if it was previoulsy closed or opened. Type 0 to disable cookies'
            ),
            'default_state' => array(
                'id' => 'default_state',
                'default' => '1',
                'text' => 'Default State:',
                'type' => 'select',
                'group' => 'general_close',
                'options' => array(
                    '1' => 'Opened state',
                    '2' => 'Closed state',
                ),
                'description' => 'You can set the default state of the Bar'
            ),
            'trigger' => array(
                'id' => 'trigger',
                'default' =>'delay',
                'text' => 'Trigger Event:',
                'type' => 'select',
                'options' => array(
                    'delay' => 'On load with time delay (seconds)',
                    'reach_scroll' => 'On a scroll position (pixels)'
                ),
                'group' => 'general',
                'description' => 'The event which triggers the animation'
            ),
            'delay' => array(
                'id' => 'delay',
                'default' =>'1', 
                'text' => 'Appearing delay (seconds):',
                'type' => 'number',
                'group' => 'general_trigger',
                'description' => 'The delay before animation'
            ),
            'reach_scroll' => array(
                'id' => 'reach_scroll',
                'default' =>'100', 
                'text' => 'Scroll Position:',
                'type' => 'number',
                'group' => 'general_trigger',
                'description' => 'The scrolled pixels before animation'
            ),
			'enable_animation' => array(
				'id' => 'enable_animation',
				'default' => '1',
				'text' => 'Enable Animations:',
				'type' => 'onoff',
				'group' => 'general',
				'description' => 'You can disable the built-in jQuery-based animations for safe'
			),
			'enable_shortcode' => array(
				'id' => 'enable_shortcode',
				'default' => '2',
				'text' => 'Enable Shortcodes:',
				'type' => 'onoff',
				'group' => 'general',
				'description' => 'It enables the built-in Wordpress shortcode renderer (for example [caption]My Caption[/caption])'
			),
            'enable_cloaking' => array(
                'id' => 'enable_cloaking',
                'default' => '2',
                'text' => 'Enable Link Cloaking:',
                'type' => 'onoff',
                'group' => 'general'
            ),


            /* 
             * FILTERS
             */
            'only_on_home_screen' => array(
                'id' => 'only_on_home_screen',
                'default' => '2',
                'text' => 'Display Filter:',
                'type' => 'select',
                'options' => array(
                    '2' => 'Not filtered',
                    '1' => 'Only On Home Screen',
                    'displayed_pages_allow' => 'Allow on specified pages',
                    'displayed_pages_deny' => 'Deny on specified pages'
                ),

                'group' => 'filters',
                'description' => 'You can put the Message Bar only to home page'
            ),
            'displayed_pages' => array(
                'id' => 'displayed_pages',
                'default' => '',
                'text' => 'Add Page:',
                'type' => 'text',
                'group' => 'filters_appear_here',
                'renderer' => 'Ajax_Group_Renderer',
                'description' => 'Select the filter type and enter the URL (http://example.com/urltofilter)'
            ),            
            'mobile_devices' => array(
                'id' => 'mobile_devices',
                'default' => '2',
                'text' => 'Device Filter:',
                'type' => 'select',
                'group' => 'filters',
                'description' => 'Filter for devices (Desktop / Mobile)',
                'options' => array(
                    '2' => 'Appears on Desktop and Mobile',
                    '1' => 'Appears on Desktop Only',
                    '3' => 'Appears on Mobile Only'
                )
            ),
            'auth_filter' => array(
                'id' => 'auth_filter',
                'default' => '1',
                'text' => 'Authentication Filter:',
                'type' => 'select',
                'options' => array(
                    '1' => 'Not filtered',
                    '2' => 'Only Logged In',
                    '3' => 'Only Logged Out'
                ),
                'group' => 'filters'
            ),
            /*enables*/
            'role_filter' => array(
                'id' => 'role_filter',
                'default' => '2',
                'text' => 'User Role Filter:',
                'type' => 'onoff',
                'renderer' => 'RoleFilter_Renderer',
                'group' => 'filters',
                'description' => 'Administrator Role can not be disabled'
            ),
            /*enables_list*/
            'role_filter_list' => array(
                'id' => 'role_filter_list',
                'default' => serialize(array()),
                'renderer' => 'RoleFilter_Group_Renderer',
                'group' => 'filters_role_filter'
            ),
            /*group_filter*/
            'groups' => array(
                'id' => 'groups',
                'default' => '2',
                'text' => 'User Group Filter:',
                'type' => 'onoff',
                'renderer' => 'GroupFilter_Renderer',
                'group' => 'filters',
                'description' => 'Required Groups and Groups for WooCommerce plugins'
            ),
            /*group_filter_list*/
            'group_filter_list' => array(
                'id' => 'group_filter_list',
                'default' => serialize(array()),
                'renderer' => 'GroupFilter_Group_Renderer',
                'group' => 'filters_groups'
            ),


            /* 
             * STYLE SETTINGS
             */
			'theme_selector' => array(
                'id' => 'theme_selector',
                'type' => 'select',
                'group' => 'style',
                'description' => 'Recommended max length: 78 characters',
                'storable' => false,
                'renderer' => 'Themeselect_Renderer'
                ),
            'message_font' => array(
                'id' => 'message_font',
                'default' => 'inherit',
                'text' => 'Font type for message:',
                'type' => 'select',
                'options' => array(
                    'inherit' => 'Default body font',
                    'Arial, Helvetica, sans-serif' => 'Arial, Arial, Helvetica',
                    'Arial Black, Gadget, sans-serif' => 'Arial Black, Arial Black, Gadget',
                    'Comic Sans MS, cursive' => 'Comic Sans MS',
                    'Courier New, monospace' => 'Courier New',
                    'Georgia, serif' => 'Georgia',
                    'Impact, Charcoal, sans-serif' => 'Impact, Charcoal',
                    'Lucida Console, Monaco, monospace' => 'Lucida Console, Monaco',
                    'Lucida Sans Unicode, Lucida Grande, sans-serif' => 'Lucida Sans Unicode, Lucida Grande',
                    'Palatino Linotype, Book Antiqua, Palatino, serif' => 'Palatino Linotype, Book Antiqua, Palatino',
                    'Tahoma, Geneva, sans-serif' => 'Tahoma, Geneva',
                    'Times New Roman, Times, serif' => 'Times New Roman, Times',
                    'Trebuchet MS, sans-serif' => 'Trebuchet MS',
                    'Verdana, Geneva, sans-serif' => 'Verdana, Geneva',
                    'Open Sans, sans-serif' => 'Open Sans',
                    'Oswald, sans-serif' => 'Oswald',
                    'Droid Sans, sans-serif' => 'Droid Sans',
                    'Open Sans Condensed, sans-serif' => 'Open Sans Condensed',
                    'Lato, sans-serif' => 'Lato',
                    'PT Sans, sans-serif' => 'PT Sans',
                    'Droid Serif, serif' => 'Droid Serif',
                    'Yanone Kaffeesatz, sans-serif' => 'Yanone Kaffeesatz',
                    'Roboto, sans-serif' => 'Roboto',
                    'Bitter, serif' => 'Bitter'
                ),
                'renderer' => "Fonttype_Select_Renderer",
                'group' => 'style'
            ),
            'message_font_size' => array(
                'id' => 'message_font_size',
                'default' => 'inherit',
                'text' => 'Font size for message:',
                'type' => 'select',
                'options' => array(
                    'inherit' => 'Default body font size',
                    '8px' => '8 pixels',
                    '9px' => '9 pixels',
                    '10px' => '10 pixels',
                    '11px' => '11 pixels',
                    '12px' => '12 pixels',
                    '13px' => '13 pixels',
                    '14px' => '14 pixels',
                    '15px' => '15 pixels',
                    '16px' => '16 pixels',
                    '17px' => '17 pixels',
                    '18px' => '18 pixels',
                    '19px' => '19 pixels',
                    '20px' => '20 pixels'
                ),
                'group' => 'style'
            ),
            'action_button_font' => array(
                'id' => 'action_button_font',
                'default' => 'inherit',
                'text' => 'Font type for action button text:',
                'type' => 'select',
                'options' => array(
                    'inherit' => 'Default body font',
                    'Arial, Helvetica, sans-serif' => 'Arial, Arial, Helvetica',
                    'Arial Black, Gadget, sans-serif' => 'Arial Black, Arial Black, Gadget',
                    'Comic Sans MS, cursive' => 'Comic Sans MS',
                    'Courier New, monospace' => 'Courier New',
                    'Georgia, serif' => 'Georgia',
                    'Impact, Charcoal, sans-serif' => 'Impact, Charcoal',
                    'Lucida Console, Monaco, monospace' => 'Lucida Console, Monaco',
                    'Lucida Sans Unicode, Lucida Grande, sans-serif' => 'Lucida Sans Unicode, Lucida Grande',
                    'Palatino Linotype, Book Antiqua, Palatino, serif' => 'Palatino Linotype, Book Antiqua, Palatino',
                    'Tahoma, Geneva, sans-serif' => 'Tahoma, Geneva',
                    'Times New Roman, Times, serif' => 'Times New Roman, Times',
                    'Trebuchet MS, sans-serif' => 'Trebuchet MS',
                    'Verdana, Geneva, sans-serif' => 'Verdana, Geneva',
                    'Open Sans, sans-serif' => 'Open Sans',
                    'Oswald, sans-serif' => 'Oswald',
                    'Droid Sans, sans-serif' => 'Droid Sans',
                    'Open Sans Condensed, sans-serif' => 'Open Sans Condensed',
                    'Lato, sans-serif' => 'Lato',
                    'PT Sans, sans-serif' => 'PT Sans',
                    'Droid Serif, serif' => 'Droid Serif',
                    'Yanone Kaffeesatz, sans-serif' => 'Yanone Kaffeesatz',
                    'Roboto, sans-serif' => 'Roboto',
                    'Bitter, serif' => 'Bitter'
                ),
                'renderer' => "Fonttype_Select_Renderer",
                'group' => 'style'
            ),
            'action_button_font_size' => array(
                'id' => 'action_button_font_size',
                'default' => 'inherit',
                'text' => 'Font size for action button text:',
                'type' => 'select',
                'options' => array(
                    'inherit' => 'Default body font size',
                    '8px' => '8 pixels',
                    '9px' => '9 pixels',
                    '10px' => '10 pixels',
                    '11px' => '11 pixels',
                    '12px' => '12 pixels',
                    '13px' => '13 pixels',
                    '14px' => '14 pixels',
                    '15px' => '15 pixels',
                    '16px' => '16 pixels',
                    '17px' => '17 pixels',
                    '18px' => '18 pixels',
                    '19px' => '19 pixels',
                    '20px' => '20 pixels'
                ),
                'group' => 'style'
            ),
            'background_color' => array(
                'id' => 'background_color',
                'default' =>'#0074a4',
                'text' => 'Background Color:',
                'type' => 'color',
                'group' => 'style'
            ),
            'background_color2' => array(
                'id' => 'background_color2',
                'default' =>'#008dbe',
                'text' => 'Background Color 2 (gradient):',
                'type' => 'color',
                'group' => 'style'
            ),
            'message_color' => array(
                'id' => 'message_color',
                'default' =>'#ffffff',
                'text' => 'Message Text Color:',
                'type' => 'color',
                'group' => 'style'
            ),
            'action_button_color' => array(
                'id' => 'action_button_color',
                'default' =>'#50aa38', 
                'text' => 'Action Button Color:',
                'type' => 'color',
                'group' => 'style'
            ),
            'action_button_color2' => array(
                'id' => 'action_button_color2',
                'default' =>'#50aa38',
                'text' => 'Action Button Color 2 (gradient):',
                'type' => 'color',
                'group' => 'style'
            ),
            'action_button_border_color' => array(
                'id' => 'action_button_border_color',
                'default' =>'#6cc552', 
                'text' => 'Action Button Border Color:',
                'type' => 'color',
                'group' => 'style'
            ),
            'action_button_text_color' => array(
                'id' => 'action_button_text_color',
                'default' =>'#ffffff',
                'text' => 'Action Button Text Color:',
                'type' => 'color',
                'group' => 'style'
            ),
            'action_button_hover' => array(
                'id' => 'action_button_hover',
                'default' =>'#36921f', 
                'text' => 'Action Button Hover Color:',
                'type' => 'color',
                'group' => 'style'
            ),
            'action_button_hover2' => array(
                'id' => 'action_button_hover2',
                'default' =>'#36921f', 
                'text' => 'Action Button Hover Color 2 (gradient):',
                'type' => 'color',
                'group' => 'style'
            ),
            'action_button_hover_border_color' => array(
                'id' => 'action_button_hover_border_color',
                'default' =>'#59b340', 
                'text' => 'Action Button Hover Border Color:',
                'type' => 'color',
                'group' => 'style'
            ),
            'action_button_hover_text_color' => array(
                'id' => 'action_button_hover_text_color',
                'default' =>'#ffffff', 
                'text' => 'Action Button Hover Text Color:',
                'type' => 'color',
                'group' => 'style'
            ),
            'text_shadow' => array(
                'id' => 'text_shadow',
                'default' =>'1', 
                'text' => 'Text Shadow:',
                'type' => 'onoff',
                'group' => 'style'
            ),
            'message_shadow' => array(
                'id' => 'message_shadow',
                'default' =>'2', 
                'text' => 'Message Text Shadow:',
                'type' => 'darklight',
                'options' => array(
                    "1" => 'light',
                    "2" => 'dark',
                ),                
                'group' => 'styling_shadow'
            ),
            'button_shadow' => array(
                'id' => 'button_shadow',
                'default' =>'2', 
                'text' => 'Action Button Shadow:',
                'type' => 'darklight',
                'options' => array(
                    "1" => 'light',
                    "2" => 'dark',
                ),
                'group' => 'styling_shadow'
            ),
            'button_hover_shadow' => array(
                'id' => 'button_hover_shadow',
                'default' =>'2', 
                'text' => 'Action Button Hover Shadow:',
                'type' => 'darklight',
                'options' => array(
                    "1" => 'light',
                    "2" => 'dark',
                ),                
                'group' => 'styling_shadow'
            ),
            'action_button_corner_radius' => array(
                'id' => 'action_button_corner_radius',
                'default' => '3', 
                'text' => 'Action Button Corner Radius:',
                'type' => 'number',
                'group' => 'style'
            ),
            'close_icon_color' => array(
                'id' => 'close_icon_color',
                'default' =>'1', 
                'text' => 'Close Icon Color:',
                'type' => 'darklight',
				'options' => array(
					'1' => 'light',
					'2' => 'dark'
				),
                'group' => 'style'
            ),
            'bar_shadow' => array(
                'id' => 'bar_shadow',
                'default' =>'1',
                'text' => 'Message Bar Shadow:',
                'type' => 'onoff',
                'group' => 'style'
            ),

        );