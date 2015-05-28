<?php
/**
* 
*/

namespace WPPFW\MVC;

#Imports
use WPPFW\Obj\PHPNamespace;

/**
* 
*/
class MVCStructure {
	
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
	protected $controllerClassId;

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $model;

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $modelClassId;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $module;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $rootns;
	
	/**
	* put your comment there...
	* 
	* @param PHPNamespace $rootns
	* @param mixed $module
	* @param mixed $controller
	* @param mixed $controllerClassId
	* @param mixed $model
	* @param mixed $modelClassId
	* @return MVCStructure
	*/
	public function __construct(PHPNamespace $rootns, $module, $controller, $controllerClassId, $model, $modelClassId) {
		# Initialize
		$this->rootns =& $rootns;
		$this->module =& $module;
		$this->controller =& $controller;
		$this->controllerClassId =& $controllerClassId;
		$this->model =& $model;
		$this->modelClassId =& $modelClassId;
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
	public function getControllerClassId() {
		return $this->controllerClassId;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getModel() {
		return $this->model;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getModelClassId() {
		return $this->modelClassId;
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
	*/
	public function & getRootNS() {
		return $this->rootns;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function & setController($name) {
		# Set
		$this->controller =& $name;
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function & setControllerClassId($classId) {
		# Set
		$this->controllerClassId =& $classId;
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function & setModule($module) {
		# Set
		$this->module =& $module;
		# Chain
		return $this;
	}

}