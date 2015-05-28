<?php
/**
* 
*/

namespace WPPFW\MVC;

/**
* 
*/
class MVCViewParams extends MVCParams {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $layout;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $view;

	/**
	* put your comment there...
	* 
	* @param mixed $module
	* @param mixed $controller
	* @param mixed $action
	* @param mixed $format
	* @param mixed $view
	* @param mixed $layout
	* @return MVCViewParams
	*/
	public function __construct($module, $controller, $action, $format, $view, $layout = null) {
		# INitialize parent
		parent::__construct($module, $controller, $action, $format);
		# Initiaioze
		$this->view =& $view;
		$this->layout =& $layout;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getLayout() {
		return $this->layout;
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
	* @param mixed $format
	* @return MVCParams
	*/
	public function & setLayout($layout) {
		# Set
		$this->layout =& $layout;
		# Chain
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $module
	* @return MVCParams
	*/
	public function & setView($view) {
		# Set
		$this->view =& $view;
		# Chain
		return $this;		
	}


}