<?php
/**
* 
*/

namespace WPPFW\Plugin\Config\XML\Services;

# Imports
use WPPFW\HDT\XML\XMLWriterPrototype;
use WPPFW\HDT\WriterPrototype;

/**
* 
*/
class ServiceProxyPrototype extends XMLWriterPrototype {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $namespace;

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $objects = array();
	
	/**
	* put your comment there...
	* 
	* @param IWriterPrototype $instance
	* @param mixed $layerName
	* @param mixed $prototypeName
	*/
	protected function getObject_beforeInstanceTransform(WriterPrototype & $instance, $layerName, $prototypeName) {
		# Pass this proxy as objects container
		$instance->setObjects($this->objects);
		# Getting Parent object for currently processed instance
		$objectResult =& $instance->getResult();
		$result =& $this->getResult();
		$globalObjects =& $this->getParent() # Service
													 ->getParent() # Services(s)
													 ->getParent() # Plugin
													 ->getPrototypeInstance('mvc') # MVC
													 ->getPrototypeInstance('objects') # Objects
													 ->getResult();
		$result['params'] =& $globalObjects[$objectResult['class']]['params'];
	}

	/**
	* put your comment there...
	* 
	*/
	public function getNamespace() {
		return $this->namespace;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getService() {
		# Parent params
		$service =& $this->getParentResult();
		$reader =& $this->getReaderPrototype();
		$result =& $this->getResult();
		# Reading object attributes;
		$attributesArray = $reader->getAttributesArray();
		# Writing attributes as array elements
		$result = array_merge($result, $attributesArray);
		# Add to proxies list
		$service['proxies'][$result['class']] =& $result;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getServiceOut() {
		# Initialize
		$result =& $this->getResult();
		# Clean up fake params
		unset($result['params']);
		# Add objects list
		$result['objects'] =& $this->objects;
	}

	/**
	* put your comment there...
	* 
	*/
	public function initialize() {
		# Proxy structure / Faking child object when
		# it tries to access parent object
		$this->result = array('params' => array());
		# Namespace
		$this->namespace = (string) $this->getDataSource()->attributes()->namespace;
	}
	
}
