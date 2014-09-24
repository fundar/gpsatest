<?php
require_once( plugin_dir_path( __FILE__ ) . 'options-gc-message-bar.php');
require_once( plugin_dir_path( __FILE__ ) . 'themes-gc-message-bar.php');
$gc_message_bar_namespace = "gc_message_bar_";

Gc_MessageBar_Service_Locator::set("option_store", Gc_MessageBar_CF::create("Option_Store"));
$repository = Gc_MessageBar_CF::create("Option_Repository_Factory")->get_instance();
$repository->add_namespace($gc_message_bar_namespace);
$repository->add_configuration($gc_message_bar_namespace,$gc_message_bar_options);

$themeRepository = Gc_MessageBar_CF::create("Theme_Repository_Factory")->get_instance();
$themeRepository->init_themes($gc_message_bar_themes);
