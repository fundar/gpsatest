<?php
/**
* 
*/

namespace WPPFW\MVC;

# Imports
use \WPPFW\Plugin\Request as RequestInput;
use WPPFW\Obj\IFactory;
use WPPFW\Http\HTTPResponse;

/**
* 
*/
class MVCViewDispatcher extends MVCDispatcher {

	/**
	* put your comment there...
	* 
	* @param IFactory $factory
	* @param {IFactory|RequestInput} $input
	* @param {HTTPResponse|IFactory|RequestInput} $response
	* @param {HTTPResponse|IFactory|MVCViewStructure|RequestInput} $structure
	* @param {HTTPResponse|IFactory|MVCViewParams|MVCViewStructure|RequestInput} $target
	* @param {HTTPResponse|IFactory|MVCViewParams|MVCViewParams|MVCViewStructure|RequestInput} $names
	* @param {HTTPResponse|IFactory|IMVCRouter|MVCViewParams|MVCViewParams|MVCViewStructure|RequestInput} $router
	* @return {MVCViewDispatcher|HTTPResponse|IFactory|IMVCRouter|MVCViewParams|MVCViewParams|MVCViewStructure|RequestInput}
	*/
	public function __construct(IFactory & $factory,
															RequestInput & $input, 
															HTTPResponse & $response,
															MVCViewStructure & $structure, 
															MVCViewParams & $target,
															MVCViewParams & $names,
															IMVCRouter & $router) {
		# Direct to parent
		parent::__construct($factory, $input, $response, $structure, $target, $names, $router);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & dispatch() {
		# Initialize
		$target =& $this->target();
		# If not controller specified get it from view
		if (!$target->getController()) {
			$target->setController($target->getView());
		}
		# Dispatching
		return parent::dispatch($target);
	}

}
	