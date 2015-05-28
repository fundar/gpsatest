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
class ServiceController extends Base {

	/**
	* put your comment there...
	* 
	* @param mixed $result
	*/
	public function getResponder(& $result) {
		# Initialize
		$structure =& $this->mvcStructure();
		$target =& $this->mvcTarget();
		# Getting responder class components
		$responderClass[] = '';
		$responderClass[] = $structure->getRootNS()->getNamespace();
		$responderClass[] = $structure->getModule(); # Module(s) namespave
		$responderClass[] = $target->getModule();  # Module name
		$responderClass[] = $structure->getController(); # Controller(s) Namespace
		$responderClass[] = implode('', array($target->getFormat(), $structure->getControllerClassId(), 'Responder')); # Responder name
		# Responder class
		$responderClass = implode('\\', $responderClass);
		# Creating Responder
		$responder = new $responderClass($this->httpResponse(), $result);
		# Returning Responder
		return $responder;
	}

} # End class
