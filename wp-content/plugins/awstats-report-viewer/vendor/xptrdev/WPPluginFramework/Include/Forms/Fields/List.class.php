<?php
/**
* 
*/

namespace WPPFW\Forms\Fields;

/**
* 
*/
class FormListField extends FormFieldsList {

	/**
	* put your comment there...
	* 
	* @param IField $field
	*/
	public function & add(IField & $field) {
		# Add
		$this->addChain($field);
		# Return element
		return $field;
	}

	/**
	* put your comment there...
	* 
	* @param IField $field
	*/
	public function & addChain(IField & $field) {
		# Add
		$this->fields[$field->getName()] = $field;
		# Return element
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @return IField
	*/
	public function & get($name) {
		return $this->fields[$name];
	}

	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	public function & setValue($values) {
		# Cast 
		$values = $this->type()->cast($values);
		# INitialize
		$fields =& $this->getFields();
		# Set list values.
		foreach ($fields as $index => $field) {
			# Getting field value
			$value = isset($values[$index]) ? $values[$index] : null;
			# Setting field value
			$field->setValue($value);
		}
		# Chain
		return $this;
	}

}