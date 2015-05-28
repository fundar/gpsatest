<?php
/**
* 
*/

namespace WPPFW\Forms\Types;

/**
* 
*/
class TypeInteger extends TypeBase {
	
	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	protected function typeCast($value) {
		# Cast
		return (int) $value;
	}
	
}
