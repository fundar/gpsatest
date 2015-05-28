<?php
/**
* 
*/

namespace WPPFW\Obj;

/**
* 
*/
class ClassName {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $className;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $name;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $namespace;
	
	/**
	* put your comment there...
	* 
	* @param mixed $className
	* @return ClassNameParser
	*/
	public function __construct($className) {
		# Initialize
		$this->className =& $className;
		# In order to safly use file system functions with namespace
		# we replace \\ with DIRECTORY_SEPARATOR
		$classNameAsFile = str_replace('\\', DIRECTORY_SEPARATOR, $className);
		# Obtain name
		$this->name = basename($classNameAsFile);
		# Obtain namespace name
		$this->namespace = str_replace(DIRECTORY_SEPARATOR, '\\', dirname($classNameAsFile));
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getClassName() {
		return $this->className;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getName() {
		return $this->name;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getNamespace() {
		return $this->namespace;
	}
	
}