<?php
/**
* 
*/

namespace WPPFW\MVC\Model\State;

# Imports
use WPPFW\Obj\IFactory;

/**
* 
*/
interface IModelStateAdapter {
	
	/**
	* 
	*/
	public function __construct(IFactory & $factory, $modelClass);
	
	/**
	* 
	*/
	public function read();
	
	/**
	* 
	*/
	public function & write(& $data);

}