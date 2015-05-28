<?php
/**
* 
*/

namespace WPPFW\Plugin\Config\XML\Objects;

# Imports
use WPPFW\HDT\XML\XMLWriterPrototype;
use WPPFW\HDT\WriterPrototype;

/**
* 
*/
class ObjectPrototype extends XMLWriterPrototype {

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
	protected $objects;

	/**
	* put your comment there...
	* 
	* @param IWriterPrototype $instance
	* @param mixed $layerName
	* @param mixed $prototypeName
	*/
	protected function getObject_beforeInstanceTransform(WriterPrototype & $instance, $layerName, $prototypeName) {
		# Passing Objects lists reference only to child objects
		switch (get_class($instance)) {
			case get_class($this);
				# Passing objects list to All child objects
				$instance->setObjects($this->getObjects());			
			break;
		}
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
	public function getObject() {
		# Parent params
		$parentResult =& $this->getParentResult();
		$objectsList =& $this->getObjects();
		$result =& $this->getResult();
		# Merge parent object
		$result['params'] = array_merge($result['params'], $parentResult['params']);
		# Add to objects list
		$objectsList[$result['class']] =& $result;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getObjects() {
		return $this->objects;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $objects
	*/
	public function & setObjects(& $objects) {
		# Set
		$this->objects =& $objects;
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	public function initialize() {
		# Create structure
		$this->result = array('params' => array());
		# Initializ
		$parent =& $this->getParent();
		$reader =& $this->getReaderPrototype();
		$result =& $this->getResult();
		# Copy namespace attribute
		$this->namespace = $parent->getNamespace();
		# Load object attributes
		$attributesArray = $reader->getAttributesArray();
		# Getting full class name
		$attributesArray['class'] = $this->getNamespace() . '\\' . $attributesArray['class'];
		# Writing attributes as array elements
		$result = array_merge($result, $attributesArray);
	}

}
