<?php
/**
* 
*/

namespace WPPFW\Plugin;

/**
* 
*/
interface IServiceFrontProxy {
	
	/**
	* 
	*/
	public function & getStructure();
	
	/**
	* 
	*/
	public function & getTarget();
	
	/**
	* 
	*/
	public function & proxy(PluginBase & $plugin, & $serviceConfig);
  
} 
