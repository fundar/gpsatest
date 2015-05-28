<?php
/**
* 
*/

namespace WPPFW\Forms\Fields;

# Imports
use WPPFW\Forms\Types\TypeArray;

/**
* 
*/
abstract class FormFieldsList extends FormFieldBase {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $fields;

	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @return FormIntegerField
	*/
	public function __construct($name) {
		# Form field object
		parent::__construct($name, new TypeArray());
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getFields() {
		return $this->fields;
	}
	
	/**
	* 
	*/
	public function getValue() {
		# Initialize 
		$value = array();
		$fields =& $this->getFields();
		# Aggregate fields value
		foreach ($fields as $index => $field) {
			# Get value for every fied inside the fields list.
			$value[$index] = $field->getValue();
		}
		# Return value
		return $value;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & validate() {
		# INitialize
		$fields =& $this->getFields();
		# Validate fields
		foreach ($fields as $field) {
			# Validate field
			$field->validate();
		}
		# Chain
		return $this;
	}

}