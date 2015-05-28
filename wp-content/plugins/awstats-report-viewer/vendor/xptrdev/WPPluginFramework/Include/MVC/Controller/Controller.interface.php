<?php
/**
* 
*/

namespace WPPFW\MVC\Controller;

/**
* 
*/
interface IController {
	
	/**
	* 
	*/
	public function & dispatch();
	
	/**
	* 
	*/
	public function & getModel($name = null);
	
	/**
	* 
	*/
	public function & getTable($name = null);

}
