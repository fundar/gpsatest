<?php
/**
* 
*/

namespace WPPFW\HDT\XML;

# Imports
use WPPFW\HDT;

/**
* 
*/
abstract class XMLWriterPrototype extends HDT\WriterPrototype {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $nsPrefix;

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $nsURI;

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $tagName;
	
	/**
	* put your comment there...
	* 
	* @param mixed $tagName
	* @param mixed $namespace
	* @param HDT\IReaderPrototype $readerPrototype
	* @return {XMLWriterPrototype|HDT\IReaderPrototype}
	*/
	public function __construct($tagName, $nsPrefix = null, $nsURI = null, HDT\IReaderPrototype & $readerPrototype = null) {
		# Initialize
		$this->tagName = $tagName;
		$this->nsPrefix =& $nsPrefix;
		$this->nsURI =& $nsURI;
		# HDT Prototype writer
		parent::__construct($readerPrototype);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getNamespacePrefix() {
		return $this->nsPrefix;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getNamespaceURI() {
		return $this->nsURI;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getTagName() {
		return $this->tagName;
	}

	/**
	* put your comment there...
	* 
	*/
	public function initialize() {
		# Create structure
		$parentResult =& $this->getParentResult();
		# Creating array structures!
		$parentResult[$this->getTagName()] =& $this->getResult();
	}

	/**
	* put your comment there...
	* 
	* @param WriterPrototype $instance
	*/
	protected function & readingPrototypeData(HDT\WriterPrototype & $instance) {
		# Inherits current namespace is child instance doesn't has one
		if (!$instance->getNamespacePrefix()) {
			$instance->nsPrefix = $this->getNamespacePrefix();
			$instance->nsURI = $this->getNamespaceURI();
		}
		return parent::readingPrototypeData($instance);
	}
}
