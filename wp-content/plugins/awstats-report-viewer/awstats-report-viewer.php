<?php
/**
* Plugin Name: AWStats Report Viewer
* Plugin URI: http://wp-arv.xptrdev.com
* Author: AHMeD SAiD
* Author URI: http://xptrdev.com
* Version: 0.7
* Description: View CPanel's AWStats report via Wordpress Dashboard page.
* License: GPL2
*/

# ARV Namespace
namespace ARV;

# Initialize third-party libs and ARV autoloaders
require 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

# Constants
const NO_DIRECT_ACCESS_MESSAGE = 'Access Denied';

# Wordpres Plugin Framework
use WPPFW\Plugin\PluginBase;

# Installer Module that will get all the other modules loaded
use ARV\Services\InstallerModule;

/**
* ARV Plugin Bootstrap Class.
* 
* The class is to load services modules that start up
* Wordpress binding process. Those modules then get triggered once
* Wordpress binded hook is triggered
* 
* @author AHMeD SAiD 
*/
class Plugin extends PluginBase {
	
	/**
	* Holds ARV Plugin instance
	* 
	* @var Plugin
	*/
	protected static $instance;
	
	/**
	* Bootstrap ARV Plugin
	* 
	* return void
	*/
	protected function __construct() {
		# Plugin base
		parent::__construct(__FILE__, new Config\Plugin());
		# Only admin side is used in this Plugin
		if (is_admin()) {
			# Dashboad Service Module
			$installerModule = new  Services\InstallerModule($this);
			$installerModule->start();
		}		
	}

	/**
	* Run ARV Plugin if not alreayd running
	* 
	* This methos is to construct a new ARV\Plugin instance if not already
	* instantiated.
	* 
	* @return PLugin
	*/
	public static function run() {
		# Create if not yet created
		if (!self::$instance) {
			self::$instance = new Plugin();
		}
		# Return instance
		return self::$instance;
	}

}

# Run The Plugin
Plugin::run();