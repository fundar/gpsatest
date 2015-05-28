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
class ServicesPrototype extends XMLWriterPrototype {

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
	public function initialize() {
		# Create structure
		parent::initialize();
		# Copy namespace attribute
		$this->namespace = (string) $this->getDataSource()->attributes()->namespace;
	}

}
