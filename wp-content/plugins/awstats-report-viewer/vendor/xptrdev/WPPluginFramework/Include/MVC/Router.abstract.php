<?php
/**
* 
*/

namespace WPPFW\MVC;

/**
* 
*/
abstract class RouterBase {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $prefix;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $names;
	
	/**
	* put your comment there...
	* 
	* @param mixed $prefix
	* @param mixed $names
	* @return RouterBase
	*/
	public function __construct($prefix, & $names) {
		# Initializd object vars
		$this->prefix = strtolower($prefix);
		$this->names =& $names;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $paramName
	*/
	public function getParamName($paramName) {
		# Return prefixed param name
		return "{$this->getPrefix()}{$paramName}";
	}

	/**
	* put your comment there...
	* 
	*/
	protected function & getNames() {
		return $this->names;
	}

	/**
	* put your comment there...
	* 
	*/
	protected function & getNamesProperties() {
		# Getting all protected properties!
		$reflection = new \ReflectionClass($this->getNames());
		$properties = $reflection->getProperties(\ReflectionProperty::IS_PROTECTED);
		
		# Returns
		return $properties;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getPrefix() {
		return $this->prefix;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $type
	*/
	protected function getPropertyMethodName($propName, $type) {
		# Upper cas first letter
		$propName = ucfirst($propName);
		# Get property method name.
		return "{$type}{$propName}";
	}

	/**
	* put your comment there...
	* 
	* @param mixed $propName
	*/
	protected function getterMethod($propName) {
		return $this->getPropertyMethodName($propName, 'get');
	}

	/**
	* put your comment there...
	* 
	* @param mixed $propName
	*/
	protected function setterMethod($propName) {
		return $this->getPropertyMethodName($propName, 'set');
	}

}
