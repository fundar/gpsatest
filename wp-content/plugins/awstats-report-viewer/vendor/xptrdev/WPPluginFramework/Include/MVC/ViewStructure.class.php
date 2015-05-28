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
class MVCViewStructure extends MVCStructure {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $view;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $viewClassId;
	
	/**
	* put your comment there...
	* 
	* @param PHPNamespace $rootns
	* @param mixed $module
	* @param mixed $controller
	* @param mixed $controllerClassId
	* @param mixed $model
	* @param mixed $modelClassId
	* @param mixed $view
	* @param mixed $viewClassId
	* @return MVCViewStructure
	*/
	public function __construct(PHPNamespace $rootns, $module, $controller, $controllerClassId, $model, $modelClassId, $view, $viewClassId) {
		# iNitialize parent
		parent::__construct($rootns, $module, $controller, $controllerClassId, $model, $modelClassId);
		# Initialize
		$this->view =& $view;
		$this->viewClassId =& $viewClassId;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getView() {
		return $this->view;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getViewClassId() {
		return $this->viewClassId;
	}
	
}
	