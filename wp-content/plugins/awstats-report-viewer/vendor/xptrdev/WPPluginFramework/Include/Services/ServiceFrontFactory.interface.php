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
interface IServiceFrontFactory {

	/**
	* 
	*/
	public function & createServiceFront(ServiceObject & $serviceObject);
	
	/**
	* 
	*/
	public function & dispatch(IDispatcher & $serviceFront);
	
}
