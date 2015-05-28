<?php
/**
* 
*/

namespace WPPFW\Forms\Fields;

/**
* 
*/
interface IField {
	
	/**
	* 
	*/
	public function getName();
	
	/**
	* 
	*/
	public function getValue();
	
	/**
	* 
	*/
	public function & setValue($value);
	
	/**
	* 
	*/
	public function & type();
	
	/**
	* 
	*/
	public function & validate();

}
