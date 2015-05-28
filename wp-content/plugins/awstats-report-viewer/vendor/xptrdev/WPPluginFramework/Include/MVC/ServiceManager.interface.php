<?php
/**
* 
*/

namespace WPPFW\MVC;

/**
* 
*/
interface IMVCServiceManager {

	/**
	* 
	*/
	public function & getController();
	
	/**
	* 
	*/
	public function & factory();

	/**
	* 
	*/
	public function & input();
	
	/**
	* 
	*/
	public function & getForm($name = null);

	/**
	* 
	*/
	public function & getModel($name = null);
	
	/**
	* 
	*/
	public function & getTable($name = null);

	/**
	* 
	*/
	public function & names();
	
	/**
	* 
	*/
	public function & router();
	
	/**
	* 
	*/
	public function & structure();
	
	/**
	* 
	*/
	public function & target();

}