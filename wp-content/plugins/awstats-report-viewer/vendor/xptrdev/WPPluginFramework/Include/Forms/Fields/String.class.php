<?php
/**
* 
*/

namespace WPPFW\Forms\Fields;

# Important
use WPPFW\Forms\Types\TypeString;

/**
* 
*/
class FormStringField extends FormField {
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @return FormIntegerField
	*/
	public function __construct($name) {
		# Form field object
		parent::__construct($name, new TypeString());
	}

}