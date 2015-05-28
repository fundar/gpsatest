<?php
/**
* 
*/

namespace WPPFW\Forms\HTML\Elements;

/**
* 
*/
class HTMLFormCheckBox extends HTMLFormElement {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $value;
	
	/**
	* put your comment there...
	* 
	* @param mixed $value
	* @param IField $field
	* @param {IField|IFieldLinker} $linker
	* @return {HTMLFormCheckBox|IField|IFieldLinker}
	*/
	public function __construct($value, IField $field = null, IFieldLinker & $linker = null) {
		# Field base
		parent::__construct($field, $linker);
		# Checkbox field value attribute
		$this->value = $value;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getValue() {
		return $this->value;
	}
	
	/**
	* put your comment there...
	* 
	* @param \DOMDocument $document
	* @param {\DOMDocument|\DOMNode} $parent
	* @return {\DOMDocument|\DOMNode|HTMLFormCheckBox}
	*/
	public function & render(\DOMDocument & $document, \DOMNode & $parent)	{
		# Init vars
		$field =& $this->getField();
		$checkedValue = $this->getValue();
		$value = $field->getValue();
		# Create input element
		$input = $document->createElement('input');
		$parent->appendChild($input);
		# Set As Checkbox
		$input->setAttribute('type', 'checkbox');
		# Set name
		$input->setAttribute('name', $field->getName());
		# Set value
		$input->setAttribute('value', $checkedValue);
		# Check if checked
		if ($checkedValue == $value) {
			$input->setAttribute('checked', 'checked');	
		}
		# Chain
		return $this;
	}

}