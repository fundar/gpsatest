<?php
/**
* 
*/

namespace WPPFW\Forms\Fields;

/**
* 
*/
class FormArrayField extends FormFieldsList {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $fieldPrototype;
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param IField $fieldPrototype
	* @return {FormArrayField|IField}
	*/
	public function __construct($name, IField & $fieldPrototype) {
		# Init
		$this->fieldPrototype =& $fieldPrototype;
		# Field base
		parent::__construct($name);
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getFieldPrototype() {
		return $this->fieldPrototype;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	public function & setValue($values) {
		# Cast value
		$values = $this->type()->cast($values);
		# Reset fields
		$this->fields = array();
		# Create fields
		foreach ($values as $index => $value) {
			# Clone field
			$field = clone $this->getFieldPrototype();
			# Set field value
			$field->setValue($value);
			# Hold field
			$this->fields[$index] = $field;
		}
		# Chain
		return $this;
	} 
}