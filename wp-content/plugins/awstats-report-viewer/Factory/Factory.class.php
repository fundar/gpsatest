<?php
/**
* Factory.class.php
*/

# Define Namespace
namespace ARV;

# Imports
use WPPFW\Plugin\PluginFactory;

/**
* ARV Plugin base and currently the only object factory
* 
* The class is to provide objects factory and objects storage
* Its used for interconnectios between different comonents and between
* Plugins and Plugins Framework
* 
* @author AHMeD SAiD 
*/
class Factory extends PluginFactory {
	
	/**
	* CReate class maps for Framework class to
	* be constructed through ARV Plugin
	* 
	* @return void
	*/
	protected function createMap() {
		# Create Map.
		$this->addClassMap('WPPFW\Database\Wordpress\WordpressOptions', 'WordpressOptions');
	}

}