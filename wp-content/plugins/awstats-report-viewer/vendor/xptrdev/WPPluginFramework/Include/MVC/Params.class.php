<?php
/**
* 
*/

namespace WPPFW\MVC;

/**
* 
*/
class MVCParams {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $action;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $controller;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $format;

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $module;
	
	/**
	* put your comment there...
	* 
	* @param mixed $module
	* @param mixed $controller
	* @param mixed $view
	* @param mixed $action
	* @return MVCParams
	*/
	public function __construct($module, $controller, $action, $format) {
		# Initialize
		$this->module =& $module;
		$this->controller =& $controller;
		$this->action =& $action;
		$this->format =& $format;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getAction() {
		return $this->action;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getController() {
		return $this->controller;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getFormat() {
		return $this->format;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getModule() {
		return $this->module;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $action
	* @return MVCParams
	*/
	public function & setAction($action) {
		# Set
		$this->action =& $action;
		# Chain
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $controller
	* @return MVCParams
	*/
	public function & setController($controller) {
		# Set
		$this->controller =& $controller;		
		# Chain
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $format
	* @return MVCParams
	*/
	public function & setFormat($format) {
		# Set
		$this->format =& $format;
		# Chain
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $module
	* @return MVCParams
	*/
	public function & setModule($module) {
		# Set
		$this->module =& $module;
		# Chain
		return $this;		
	}

}