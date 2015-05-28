<?php
/**
* 
*/

namespace WPPFW\Forms;

/**
* 
*/
class Form extends Fields\FormListField {

	/**
	* put your comment there...
	* 
	* @param mixed $values
	* @return FormListField
	*/
	public function & setValue($values) {
		# Get form values.
		return parent::setValue($values[$this->getName()]);
	}

}