<?php
/**
* 
*/

namespace WPPFW\Forms\Types;

/**
* 
*/
class TypeString extends TypeBase {
	
	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	protected function typeCast($value) {
		# Cast
		return (string) $value;
	}
	
}
