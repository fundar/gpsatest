<?php
/**
* 
*/

namespace WPPFW\HDT;

/**
* 
*/
abstract class HDTDocument implements IHTDDocument {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $defaultReaderPrototype;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $rootPrototype;
	
	/**
	* put your comment there...
	* 
	* @param IReaderPrototype $readerPrototype
	* @return {HDTDocument|IReaderPrototype}
	*/
	public function __construct(IReaderPrototype & $readerPrototype = null) {
		# Initialize
		$this->defaultReaderPrototype =& $readerPrototype;
		# Define model prototype
		$this->rootPrototype =& $this->definePrototypes();
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected abstract function definePrototypes();
	
	/**
	* put your comment there...
	* 
	*/
	public function & getDefaultReaderPrototype() {
		return $this->defaultReaderPrototype;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getRootPrototype() {
		return $this->rootPrototype;
	}
	
	/**
	* put your comment there...
	* 
	* @param IReaderPrototype $readerPrototype
	* @return {HDTDocument|IReaderPrototype}
	*/
	public function & setDefaultReaderPrototype(IReaderPrototype & $readerPrototype) {
		# Set
		$this->defaultReaderPrototype =& $readerPrototype;
		# Chain
		return $this;
	}

}
