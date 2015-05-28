<?php
/**
* 
*/

namespace WPPFW\Forms\HTML\Elements;

/**
* 
*/
class HTMLFormTextBox extends HTMLFormElement {
	
	/**
	* put your comment there...
	* 
	* @param \DOMDocument $document
	* @param {\DOMDocument|\DOMNode} $parent
	* @return {\DOMDocument|\DOMNode|HTMLFormTextBox}
	*/
	public function & render(\DOMDocument & $document, \DOMNode & $parent)	{
		# INitialize
		$field =& $this->getField();
		# Create input element
		$input = $document->createElement('input');
		# Set as type text
		$input->setAttribute('type', 'text');
		# Set value
		$input->setAttribute('value', $field->getValue());
		# Set name
		$input->setAttribute('name', $field->getName());
		# Append to doc
		$parent->appendChild($input);
		# Chain
		return $this;
	}

}