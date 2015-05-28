<?php
/**
* 
*/

namespace WPPFW\Plugin;

/**
* 
*/
abstract class ServiceFrontProxy implements IServiceFrontProxy {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $names;
	
	/**
	* put your comment there...
	* 
	* @var PluginBase
	*/
	protected $plugin;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $structure;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $target;
	
	/**
	* put your comment there...
	* 
	* @param mixed $defParams
	* @param mixed $defNames
	* @param mixed $structure
	*/
	protected abstract function createMVCObjects($defParams, $defNames, $structure);
	
	/**
	* 
	*/
	public function & getNames() {
		return $this->names;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getPlugin() {
		return $this->plugin;
	}
	
	/**
	* 
	*/
	public function & getStructure() {
		return $this->structure;
	}
	
	/**
	* 
	*/
	public function & getTarget() {
		return $this->target;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $target
	* @param mixed $names
	* @param mixed $structure
	*/
	protected function & setMVCObjects(& $target, & $names, & $structure) {
		# Set
		$this->target =& $target;
		$this->names =& $names;
		$this->structure =& $structure;
		# Chain
		return $this;
	}
	/**
	* put your comment there...
	* 
	* @param PluginBase $plugin
	* @param mixed $serviceConfig
	* @return MVC\MVCViewParams
	*/
	public function & proxy(PluginBase & $plugin, & $serviceConfig) {
		# Cache plugin instance
		$this->plugin =& $plugin;
		# Initialize
		$proxyObjects =& $serviceConfig['proxy']['objects'];
		$typeConfig =& $serviceConfig['type'];
		# Getting objects defauls
		$defParams =& $proxyObjects[$typeConfig['params']]['params'];
		$defNames =& $proxyObjects[$typeConfig['names']]['params'];
		$structure =& $proxyObjects[$typeConfig['structure']]['params'];
		# Creating objects
		$this->createMVCObjects($defParams, $defNames, $structure);
		# Returns target
		return $this;
	}

} 
