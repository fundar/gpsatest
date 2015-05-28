<?php
/**
* 
*/

namespace WPPFW\Plugin;

# MVC Router interface
use WPPFW\MVC;
use WPPFW\Services\IReachableServiceObject;
use WPPFW\Obj\CastObject;

/**
* 
*/
abstract class ServiceObjectRouterBase extends MVC\RouterBase implements MVC\IMVCRouter {
	
	/**
	* put your comment there...
	* 
	* @var MVC\MVCParams
	*/
	protected $emptyTarget;

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $plugin;
	
	/**
	* put your comment there...
	* 
	* @var ServiceObject
	*/
	protected $serviceObject;
	
	/**
	* put your comment there...
	* 
	* @var MVC\MVCParams
	*/
	protected $target;
	
	/**
	* put your comment there...
	* 
	* @param PluginBase $Plugin
	* @param {PluginBase|ServiceObject} $serviceObject
	* @param mixed $serviceConfig
	* @return ServiceObjectRouterBase
	*/
	public function __construct(PluginBase & $Plugin, IReachableServiceObject & $serviceObject, & $serviceConfig) {
		# Initialize vars
		$this->plugin =& $Plugin;
		$this->serviceObject =& $serviceObject;
		# Getting MVC Names and Target structures
		$this->createMVCStructures($serviceConfig, $names, $this->target);
		# Router base
		parent::__construct($Plugin->getNamespace()->getNamespace(), $names);
		# Create Empty Instance from Target Prototype
		$this->createEmptyParamsObject();
	}

	/**
	* put your comment there...
	* 
	*/
	public function __toString() {
		return (string) $this->homeUrl();
	}

	/**
	* put your comment there...
	* 
	*/
	protected function createEmptyParamsObject() {
		# Clonning target
		$this->emptyTarget = clone $this->target;
		# Getting all params object properties
		$namesProperties =& $this->getNamesProperties();
		# Build request params array
		foreach ($namesProperties as $property) {
			# Property name
			$name = $property->getName();
			# Get setter name
			$setterName = $this->setterMethod($name);
			# Empty properties
			$this->emptyTarget->$setterName(null);
		}
	}

	/**
	* put your comment there...
	* 
	* @param mixed $serviceConfig
	* @param mixed $names
	* @param mixed $target
	*/
	protected abstract function createMVCStructures(& $serviceConfig, & $names, & $target);

	/**
	* put your comment there...
	* 
	* @param mixed $moduleName
	* @param mixed $serviceObjectnName
	*/
	public function & findRouter($moduleName, $serviceObjectName) {
		# Initialize vars
		$plugin =& $this->plugin();
		$factory =& $plugin->factory();
		# Get Service Module instance
		/* @TODO: Get Letteral Module from Config File */
		$module =& $factory->get("{$moduleName}Module");
		# Get Service object
		$serviceObject = $module->$serviceObjectName();
		# Creating router
		$router = $plugin->createServiceObjectRouter($serviceObject);
		# Returns router
		return $router;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function homeUrl() {
		return $this->getServiceObject()->getUrl();
	}

	/**
	* put your comment there...
	* 
	*/
	public function getActionUrl() {
		return $this->getServiceObject()->getActionUrl();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getRouteParams() {
		return $this->getServiceObject()->getRouteParams();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getServiceObject() {
		return $this->serviceObject;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getTarget() {
		return $this->target;
	}
	
	/**
	* put your comment there...
	* 
	* @param MVCViewParams $target
	*/
	protected function gRouter($target) {
		# Initialize vars
		$requestParams = array();
		$names =& $this->getNames();
		# Getting all names
		$namesProperties =& $this->getNamesProperties();
		# Build request params array
		foreach ($namesProperties as $property) {
			# Property name
			$name = $property->getName();
			# Get getter name
			$getterName = $this->getterMethod($name);
			# Create request param, use property prefixed name as key and target as value
			$requestParams[$this->getParamName($names->$getterName())] = $target->$getterName();
		}
		# Service URL
		$url = $this->getServiceObject()->getUrl();
		# Add parameters
		$url->params()->merge($requestParams);
		# Chain
		return $url;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & plugin() {
		return $this->plugin;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $action
	*/
	public function routeAction($action = null) {
		# Get params object copy.
		$target = clone $this->emptyTarget;
		# Change action
		$target->setAction($action);
		# Return route
		return $this->gRouter($target);
	}

}