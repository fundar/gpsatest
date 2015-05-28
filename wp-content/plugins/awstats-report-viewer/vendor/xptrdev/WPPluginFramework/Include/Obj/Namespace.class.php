<?php
/**
* 
*/

namespace WPPFW\Obj;

/**
* 
*/
class PHPNamespace {
	
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
	protected $path;

	/**
	* put your comment there...
	* 
	* @param mixed $namespace
	* @param mixed $path
	* @return PHPNamespace
	*/
	public function __construct($namespace, $path) {
		# Initialize
		$this->namespace =& $namespace;
		$this->path =& $path;
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
	public function getPath() {
		return $this->path;
	}
	
} 
