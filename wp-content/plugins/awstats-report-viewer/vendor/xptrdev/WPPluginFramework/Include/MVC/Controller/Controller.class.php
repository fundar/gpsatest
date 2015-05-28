<?php
/**
* 
*/

namespace WPPFW\MVC\Controller;

# imports
use WPPFW\MVC;
use WPPFW\Obj\IFactory;

/**
* 
*/
abstract class Controller extends Base {
	
	/**
	* put your comment there...
	* 
	* @var MVC\MVCViewParams
	*/
	private $redirect;
	
	/**
	* put your comment there...
	* 
	*/
	protected function dispatched() {
		# Check if redirected!
		if ($this->redirect) {
			# Redirect!
			header("Location: {$this->redirect}");
		}
	}

	/**
	* put your comment there...
	* 
	* @param mixed $result
	*/
	public function getResponder(& $result) {
		# Initialize
		$structure =& $this->mvcStructure();
		$target =& $this->mvcTarget();
		$serviceManager =& $this->mvcServiceManager();
		# Getting view class components
		$viewClass[] = '';
		$viewClass[] = $structure->getRootNS()->getNamespace();
		$viewClass[] = $structure->getModule(); # Module(s) namespave
		$viewClass[] = $target->getModule();  # Module name
		$viewClass[] = $structure->getView(); # View(s) Namespace
		$viewClass[] = $target->getView();
		$viewClass[] = implode('', array($target->getView(), $target->getFormat(), $structure->getViewClassId())); # Controller name
		# View class
		$viewClass = implode('\\', $viewClass);
		# Creating view
		$view = new $viewClass($serviceManager, $result);
		# Returning view
		return $view;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $location
	*/
	protected function redirect($location) {
		# Set redirect target
		$this->redirect =& $location;
	}

} # End class
