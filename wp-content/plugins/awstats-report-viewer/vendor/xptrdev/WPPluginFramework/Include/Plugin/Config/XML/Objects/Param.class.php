<?php
/**
* 
*/

namespace WPPFW\Plugin\Config\XML\Objects;

# Imports
use WPPFW\HDT\XML\XMLWriterPrototype;

/**
* 
*/
class ObjectParamPrototype extends XMLWriterPrototype {
	
	/**
	* put your comment there...
	* 
	*/
	public function getObject() {
		# Initialize
		$node =& $this->getDataSource();
		$reader =& $this->getReaderPrototype();
		$object =& $this->getParent();
		$objectResult =& $object->getResult();
		# Get attributes as array
		$attributes = $reader->getAttributesArray();
		# Push to parent object
		$objectResult['params'][$attributes['name']] = $attributes['value'];
	}

	/**
	* put your comment there...
	* 
	*/
	public function initialize() {}

}
