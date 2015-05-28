<?php
/**
* 
*/

namespace WPPFW\Services;

# Plugin base
use WPPFW\Plugin\PluginBase;
use WPPFW\Services\IService;

# Class Name Helper
use WPPFW\Obj\ClassName;

/**
* 
*/
abstract class ServiceModule implements IService {

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
	protected $services = array();

	/**
	* put your comment there...
	* 
	* @param PluginBase $plugin
	* @return {ServiceModule|PluginBase}
	*/
	public function __construct(PluginBase & $plugin) {
		# Initialize
		$this->plugin =& $plugin;
		# Initialize services
		$this->initializeServices($plugin, $this->services);
		# Service class name components
		$serviceClassName = new ClassName(get_class($this));
		# Push Service module into Plugin Factory
		$factory =& $plugin->factory();
		$factory->setNamedInstance($serviceClassName->getName(), $this);
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getPlugin() {
		return $this->plugin;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $serviceKey
	* @param mixed $serviceObjectKey
	*/
	public function & getServiceObject($serviceKey, $serviceObjectKey) {
		# Services objects
		$serviceObjects =& $this->services[$serviceKey]->getServiceObjects();
		# Service object instance
		return $serviceObjects[$serviceObjectKey];
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getServices() {
		return $this->services;
	}

	/**
	* put your comment there...
	* 
	* @param PluginBase $plugin
	* @param mixed $services
	*/
	protected abstract function initializeServices(PluginBase & $plugin, & $services);

	/**
	* put your comment there...
	* 
	*/
	public function & start() {
		# Start services
		foreach ($this->getServices() as $service) {
			$service->start();
		}		
		# chain
		return $this;
	}
	
}
