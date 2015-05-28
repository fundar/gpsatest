<?php
/**
* 
*/

namespace WPPFW\Plugin\Config\XML;

# Imports
use WPPFW\HDT\XML\XMLWriterPrototype;

/**
* 
*/
class PluginParametersPrototype extends XMLWriterPrototype {
	
	/**
	* put your comment there...
	* 
	*/
	public function initialize() {
		# Creating ctructure
		parent::initialize();
		# getting Plugin parameters
		$this->result = (array) $this->getDataSource()->children();
	}

}
