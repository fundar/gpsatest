<?php
/**
* 
*/

namespace WPPFW\MVC\Model;

/**
* 
*/
abstract class PluginModel extends ModelBase {
	
	/**
	* put your comment there...
	* 
	*/
	protected function & loadConfig() {
		# Initialize vars
		$factory =& $this->factory();
		$plugin =& $factory->get('WPPFW\Plugin\PluginBase');
		$models =& $plugin->getConfig()->getModels();
		# load model configurarion from Plugin configuration file
		$modelConfig =& $models[get_class($this)];
		# Returns confgiuration
		return $modelConfig;
	}

}