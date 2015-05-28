<?php
/**
* WordpressOptions.class.php
*/

# Define namespace
namespace ARV\Factory;

# Imports
use WPPFW\Obj;

# Original object
use WPPFW\Database\Wordpress;

/**
* Factoring Wordpress Database Options Table
* 
* @author AHMeD SAiD 
*/
class WordpressOptions {

	/**
	* Creating new WPPFW\Database\Wordpress\WordpressOptions object
	* configued for ARV Plugin
	* 
	* @param Obj\Factory $factory
	* @return Wordpress\WordpressOptions
	*/
	public function getInstance(Obj\Factory & $factory) {
		# Getting Plugin instance.
		$plugin =& $factory->get('WPPFW\Plugin\PluginBase');
		# Return Wordpress options object instance
		return new Wordpress\WordpressOptions(strtolower($plugin->getNamespace()->getNamespace() . '-'));
	}

}
