<?php
/**
* 
*/

namespace WPPFW\Services;

# Imports
use WPPFW\MVC\IDispatcher;

/**
* 
*/
class HookMap {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $hook;

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $serviceFront;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $serviceObject;
	
	/**
	* put your comment there...
	* 
	* @param mixed $hook
	* @param mixed $serviceObject
	* @return HookMap
	*/
	public function __construct(& $hook, & $serviceObject) {
		# Initialize
		$this->hook =& $hook;
		$this->serviceObject =& $serviceObject;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getHook() {
		return $this->hook;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getServiceObject() {
		return $this->serviceObject;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getServiceFront() {
		return $this->serviceFront;
	}

	/**	
	* put your comment there...
	* 
	* @param IDsipatcher $serviceFront
	* @return IDsipatcher
	*/
	public function & setServiceFront(IDsipatcher & $serviceFront) {
		# Set
		$this->serviceFront =& $serviceFront;
		# Chain
		return $this;
	}
}
