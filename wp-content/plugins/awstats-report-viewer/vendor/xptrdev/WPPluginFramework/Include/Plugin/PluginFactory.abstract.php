<?php
/**
* 
*/

namespace WPPFW\Plugin;

# Imports
use WPPFW\Obj\Factory as FactoryBase;

/**
* 
*/
abstract class PluginFactory extends FactoryBase {
	
	/**
	* put your comment there...
	* 
	* @param mixed $namespace
	* @return PluginFactory
	*/
	public function __construct($namespace) {
		# Factory base
		parent::__construct($namespace);
		# Load Map.
		$this->createMap();
	}

	/**
	* put your comment there...
	* 
	*/
	protected abstract function createMap();

}