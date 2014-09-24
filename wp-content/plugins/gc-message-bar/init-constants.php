<?php
if(!defined('GC_MESSAGE_BAR_LIB_PATH')){
	define('GC_MESSAGE_BAR_LIB_PATH','vendor'.DIRECTORY_SEPARATOR.'gc-message-bar'.DIRECTORY_SEPARATOR);	
	define('GC_MESSAGE_BAR_ABS_LIB_PATH',plugin_dir_path( __FILE__ ) . GC_MESSAGE_BAR_LIB_PATH);
}
if(!defined('GC_MESSAGE_BAR_NAME')){
	define('GC_MESSAGE_BAR_NAME','gc_message_bar');
	define('GC_MESSAGE_BAR_TYPE','gc-message-bar');
	define('GC_MESSAGE_BAR_NS', 'gc_message_bar_');	

	define('GC_MESSAGE_BAR_SL_CONFIG', 'config');
	define('GC_MESSAGE_BAR_SL_OPTION_REPOSITORY', 'option_repository');
	define('GC_MESSAGE_BAR_SL_OPTION_STORE', 'option_store');
	define('GC_MESSAGE_BAR_SL_SETTING_STORE', 'setting_store');
	define('GC_MESSAGE_BAR_SL_EVENT_MANAGER', 'event_manager');
}
