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
class ObjectsPrototype extends XMLWriterPrototype {

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
		# Passing objects list to All child objects
		$instance->setObjects($this->getResult());
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
	public function getObjectOut() {
		# Remove fake params
		unset($this->result['params']);
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
	*/
	public function initialize() {
		# Create structure
		parent::initialize();
		# Act as object so that child objects would not fail
		# when trying to inherits parameters
		$this->result['params'] = array();
		# Copy namespace attribute
		$this->namespace = (string) $this->getDataSource()->attributes()->namespace;
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

}
