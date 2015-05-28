<?php
/**
* 
*/

namespace WPPFW\Obj;

/**
* 
*/
interface IFactory {

	/**
	* 
	*/
	public function create($class);

	/**
	* 
	*/
	public function get($class);

}