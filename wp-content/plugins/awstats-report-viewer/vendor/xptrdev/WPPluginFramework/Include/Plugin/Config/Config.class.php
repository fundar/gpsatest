<?php
/**
* 
*/

namespace WPPFW\Plugin;

# imports
use WPPFW\Plugin\Config\XML;
use WPPFW\Services\ServiceObject;

/**
* 
*/
class PluginConfig {
	
	/**
	* SimpleXMLElement
	* 
	* @var mixed
	*/
	protected $simpleXML;
	
	/**
	* put your comment there...
	* 
	* @var Config\XML\PluginConfigDocument
	*/
	protected $xmlDocument;
	
	/**
	* put your comment there...
	* 
	* @param mixed $xmlData
	* @return PluginConfig
	*/
	public function __construct($xmlData) {
		# Creating XML Document
		$this->xmlDocument = new XML\PluginConfigDocument();
		# Load document.
		$this->load($xmlData);
	}

	/**
	* put your comment there...
	* 
	* @param mixed $xmlData
	*/
	private function load($xmlData) {						
		# SimpleXML Document File
		$this->simpleXML = new \SimpleXMLElement($xmlData);
		
		# Load document
		$rootPrototype =& $this->getHDTXMLDoc()->getRootPrototype();
		$rootPrototype->loadWithData($this->getHDTXMLDoc(), $this->getSimpleXML());
		
		# Resolve namespace.		
		$rootPrototype->transform('initialize');
	}

	/**
	* put your comment there...
	* 
	*/
	public function & loadMVCObjects() {
		# Initialize
		$document =& $this->getHDTXMLDoc();
		$mvcPrototype =& $document->getMVC();
		# Load MVC Objects
		$mvcPrototype->transform('getObject')->transform('getType');
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & loadServices() {
		# Initialize
		$document =& $this->getHDTXMLDoc();
		# Load Services
		$servicesPrototype =& $document->getServices();
		
		$servicesPrototype->transform('getService')
		# Load services objects
										 	->transform('getObject');
		# Chain
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getHDTXMLDoc() {
		return $this->xmlDocument;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getModels() {
		return $this->getHDTXMLDoc()->getModels()->getResult();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getPlugin() {
		return $this->getHDTXMLDoc()->getRootPrototype()->getResult();
	}
	
	/**
	* put your comment there...
	* 
	* @param ServiceObject $serviceObject
	* @return ServiceObject
	*/
	public function & getService(ServiceObject & $serviceObject) {
		return $this->getServiceNamedProxy($serviceObject, get_class($serviceObject->getProxy()));
	}

	/**
	* put your comment there...
	* 
	* @param ServiceObject $serviceObject
	* @param mixed $proxyName
	*/
	public function & getServiceNamedProxy(ServiceObject & $serviceObject, $proxyName) {
		# Initialize
		$hdtDocument =& $this->getHDTXMLDoc();
		$plugin =& $hdtDocument->getRootPrototype();
		$mvc =& $plugin->getPrototypeInstance('mvc');
		$globalObjects =& $mvc->getPrototypeInstance('objects')->getResult();
		$mvcTypes =& $mvc->getPrototypeInstance('types')->getResult();
		# Services list
		$services =& $plugin->getPrototypeInstance('services')->getResult();
		# Service configuration
		$serviceConfig = $services[get_class($serviceObject)];
		# Add requested proxy key as property
		$serviceConfig['proxy'] =& $serviceConfig['proxies'][$proxyName];
		$proxyConfig =& $serviceConfig['proxy'];
		$objects =& $proxyConfig['objects'];
		# Geting type
		$serviceConfig['type'] =& $mvcTypes[$proxyConfig['typeName']];
		# Get all TYPES objects even those are not defined
		# inside the proxy tag.
		foreach ($serviceConfig['type'] as $name => $class) {
			# Add global class if its not already defined inside
			# proxy objects!
			if (!isset($objects[$class])) {
				$objects[$class] =& $globalObjects[$class];
			}
		}
		# Returns
		return $serviceConfig;
	}

	/**
	* put your comment there...
	* 
	* @param ServiceObject $serviceObject
	* @return ServiceObject
	*/
	public function & getServiceHomeProxy(ServiceObject & $serviceObject) {
		# Initialize vars
		$plugin =& $this->getPlugin();
		$services =& $plugin['services'];
		# Get service object configuration
		$serviceConfig =& $services[get_class($serviceObject)];
		# Get service configuration
		return $this->getServiceNamedProxy($serviceObject, $serviceConfig['homeProxy']);
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getSimpleXML() {
		return $this->simpleXML;
	}
	
}