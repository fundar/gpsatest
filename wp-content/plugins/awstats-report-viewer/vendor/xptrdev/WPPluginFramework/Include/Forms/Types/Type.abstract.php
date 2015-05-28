<?php
/**
* 
*/

namespace WPPFW\Forms\Types;

/**
* 
*/
abstract class TypeBase implements IType {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $nullCast;
		
	/**
	* put your comment there...
	* 
	* @param mixed $nullCast
	* @return TypeBase
	*/
	public function __construct($nullCast = null) {
		# Initualze
		$this->nullCast = $nullCast ? true : false;
	}

	/**
	* 
	*/
	public function cast($value) {
		# Dont cast uf no null cast and the value is null
		if ($this->nullCast || ($value !== null)) {
			$value = $this->typeCast($value);
		}
		return $value;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	protected abstract function typeCast($value);
}
