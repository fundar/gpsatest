<?php
/**
* 
*/

namespace WPPFW\Plugin\Config\XML\Services;

# Imports
use WPPFW\HDT\XML\XMLWriterPrototype;

/**
* 
*/
class TypePrototype extends XMLWriterPrototype {

	/**
	* put your comment there...
	* 
	*/
	public function getType() {
		# Initialize
		$typesList =& $this->getParentResult();
		$result =& $this->getResult();
		$reader =& $this->getReaderPrototype();
		# Type attributes
		$attributes = $reader->getAttributesArray();
		# Reading properties
		$result = (array) $this->getDataSource()->children();
		# Add type to types list
		$typesList[$attributes['name']] =& $result;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function initialize() {}

}
