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
class ModelPrototype extends XMLWriterPrototype {

	/**
	* put your comment there...
	* 
	*/
	public function getService() {
		# Initialize
		$modelsList =& $this->getParentResult();
		$result =& $this->getResult();
		$reader =& $this->getReaderPrototype();
		# Type attributes
		$attributes = $reader->getAttributesArray();
		# Reading properties
		$result = (array) $this->getDataSource()->children();
		# Add type to types list
		$modelsList[$attributes['id']] =& $result;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function initialize() {}

}
