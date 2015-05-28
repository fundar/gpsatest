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
class ServiceObjectViewRouter extends ServiceObjectRouterBase {

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
		$target = new MVC\MVCViewParams(
			$defParams['module'], 
			$defParams['controller'], 
			$defParams['action'], 
			$defParams['format'],
			$defParams['view'],
			$defParams['layout']
			);
		$names = new MVC\MVCViewParams(
			$defNames['module'], 
			$defNames['controller'],
			$defNames['action'],
			$defNames['format'],
			$defNames['view'],
			$defNames['layout']
			);	
	}

	/**
	* put your comment there...
	* 
	* @param MVC\MVCViewParams $target
	* @return {MVC\MVCViewParams|ServiceObjectRouterBase}
	*/
	public function route(MVC\MVCViewParams $target) {
		return $this->gRouter($target);
	}

}