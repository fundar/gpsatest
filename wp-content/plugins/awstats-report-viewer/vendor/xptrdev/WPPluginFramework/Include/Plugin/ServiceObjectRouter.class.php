<?php
/**
* 
*/

namespace WPPFW\Plugin;

# MVC Router interface
use WPPFW\MVC;

/**
* 
*/
class ServiceObjectRouter extends ServiceObjectRouterBase {

	/**
	* put your comment there...
	* 
	* @param mixed $serviceConfig
	* @param mixed $names
	* @param mixed $target
	*/
	protected function createMVCStructures(& $serviceConfig, & $names, & $target) {
		# INitialize vars
		$type =& $serviceConfig['type'];
		$serviceObjects =& $serviceConfig['proxy']['objects'];
		$defNames = $serviceObjects[$type['names']]['params'];
		$defParams = $serviceObjects[$type['params']]['params'];
		# Creating objects
		$target = new MVC\MVCParams(
			$defParams['module'], 
			$defParams['controller'], 
			$defParams['action'], 
			$defParams['format']
			);
		$names = new MVC\MVCParams(
			$defNames['module'], 
			$defNames['controller'], 
			$defNames['action'], 
			$defNames['format']
			);	
	}

	/**
	* put your comment there...
	* 
	* @param MVC\MVCViewParams $target
	* @return {MVC\MVCViewParams|ServiceObjectRouterBase}
	*/
	public function route(MVC\MVCParams $target) {
		return $this->gRouter($target);
	}

}