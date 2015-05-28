<?php
/**
* 
*/

namespace WPPFW\MVC;

#Imports
use WPPFW\Collection\IDataAccess;

/**
* 
*/
class MVCViewRequestParamsRouter extends MVCRequestParamsRouter {
	
	/**
	* put your comment there...
	* 
	* @param mixed $prefix
	* @param IDataAccess $inputs
	* @param {IDataAccess|MVCViewParams} $names
	* @param {IDataAccess|MVCViewParams|MVCViewParams} $outParams
	* @return {MVCViewRequestParamsRouter|IDataAccess|MVCViewParams|MVCViewParams}
	*/
	public function __construct($prefix, IDataAccess & $inputs, MVCViewParams & $names, MVCViewParams & $outParams) {
		# All based on parent.
		parent::__construct($prefix, $inputs, $names, $outParams);
	}
	
}