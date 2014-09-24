<?php
global $gc_message_bar_admin_layout;
$gc_message_bar_admin_layout = array(
	"general" => array(
		"title" => "General Settings",
		"id"    => "general_settings",
		"option_group" => "general",
		"sub_groups" => array(
			"enable_close_button" => array(
				"title" => "Closing Settings",
				"id"    => "close_settings",
				"option_group" => "general_close",
				'params' => array(
					'css_class' => "before-itemgroup after-itemgroup"
				),
				'parent_option_state' => array(
                    "1" => "visible",
                    "2" => "hidden"
                )

			),
			"trigger" => array(
				"title" => "Triggering Settings",
				"id"    => "trigger_settings",
				"option_group" => "general_trigger",
				'params' => array(
					'css_class' => "before-itemgroup after-itemgroup"
				),
				'parent_option_state' => array(
                    "1" => "delay",
                    "2" => "reach_scroll"
                ),
                'options_visibility' => array(
                	"delay" => array(
                		"delay" => "show",
                		"reach_scroll" => "hidden"
                	),
                	"reach_scroll" => array(
                		"delay" => "hidden",
                		"reach_scroll" => "show"
                	)
                )
			)
		)
	),
	"filters" => array(
		"title" => "Filters",
		"id"    => "filters",
		"option_group" => "filters",
		"sub_groups" => array(
			"role_filter" => array(
				"title" => "Enable For User Roles",
				"id"    => "enable_settings",
				"option_group" => "filters_role_filter",
				'params' => array(
					'css_class' => "before-itemgroup after-itemgroup"
				),
				'parent_option_state' => array(
                    "1" => "show",
                    "2" => "hidden"
                )
			),
			"groups" => array(
				"title" => "Enable For User Groups",
				"id"    => "groups_settings",
				"option_group" => "filters_groups",
				'params' => array(
					'css_class' => "before-itemgroup after-itemgroup"
				),
				'parent_option_state' => array(
                    "1" => "show",
                    "2" => "hidden"
                )
			),
			"only_on_home_screen" => array(
				"title"	=> "Display Filter",
				"id"	=> "only_on_home_screen_settings",
				"option_group"	=> "filters_appear_here",
				'params'	=> array(
					'css_class' => "before-itemgroup after-itemgroup"
				),
				'parent_option_state'	=> array(
					"1" => "hidden",
					"2"	=> "hidden",
					"3"	=> "displayed_pages_allow",
					"4"	=> "displayed_pages_deny"
				),
				'options_visibility' => array(
                	"displayed_pages_allow" => array(
                		"displayed_pages" => "show"
                	),
                	"displayed_pages_deny" => array(
                		"displayed_pages" => "show"
                	)
                )
			)
		)
	),
    "compose" => array(
        "title" => "Compose Message",
		"id"    => "compose_message",
		"option_group" => "compose",
		"renderer" => "Gc_Message_Bar_Options_Compose_Container_Renderer"
    ),
	"style" => array(
		"title" => "Style Settings",
		"id"    => "style_settings",
		"option_group" => "style",
        "sub_groups" => array(
            "text_shadow" => array(
                "title" => "Text Shadow Settings",
                "id"    => "text_shadow_settings",
                "option_group" => "styling_shadow",
                'params' => array(
                    'css_class' => "before-itemgroup after-itemgroup"
                ),
                'parent_option_state' => array(
                    "1" => "visible",
                    "2" => "hidden"
                )
            )
        )
	)
);