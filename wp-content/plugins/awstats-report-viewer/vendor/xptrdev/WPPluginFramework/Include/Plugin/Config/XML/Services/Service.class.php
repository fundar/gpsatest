<?php
/**
* 
*/

namespace WPPFW\Plugin\Config\XML\Services;

# Imports
use WPPFW\HDT\XML\XMLWriterPrototype;

# Class Name Helper
use WPPFW\Obj\ClassName;

/**
* 
*/
class ServicePrototype extends XMLWriterPrototype {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $namespace;
	
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
		$reader =& $this->getReaderPrototype();
		$servicesList =& $this->getParentResult();
		$result =& $this->getResult();
		# Reading object attributes;
		$attributesArray = $reader->getAttributesArray();
		# Add class namespace
		$attributesArray['serviceObjectClass'] = $this->getNamespace() . '\\' . $attributesArray['serviceObjectClass'];
		# Set service front namespace
		$ServiceObjectClassName = new ClassName($attributesArray['serviceObjectClass']);
		$attributesArray['serviceFront'] = "{$ServiceObjectClassName->getNamespace()}\\{$attributesArray['serviceFront']}";
		# Writing attributes as array elements
		$result = array_merge($result, $attributesArray);
		# Add to services list
		$servicesList[$attributesArray['serviceObjectClass']] =& $result;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function initialize() {
		# Create structure
		$this->result = array('proxies' => array());
		# Copy namespace attribute
		$parent =& $this->getParent();
		$this->namespace = $parent->getNamespace();
	}

}
