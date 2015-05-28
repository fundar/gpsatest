<?php
/**
* 
*/

namespace WPPFW\Forms\Fields;

/**
* 
*/
class FormField extends FormFieldBase {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $value;

	/**
	* 
	*/
	public function getValue() {
		return $this->value;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	public function & setValue($value) {
		# Cast value
		$this->value = $this->type()->cast($value);
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & validate() {
		
		return $this;
	}

}
