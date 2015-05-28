<?php
/**
* 
*/

namespace WPPFW\Forms\HTML;

use WPPFW\Forms\Fields\FormListField;
use WPPFW\Forms\HTML\Linkers\IFieldLinker;

/**
* 
*/
class HTMLForm extends ElementsCollection {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $form;
	
	/**
	* put your comment there...
	* 
	* @var \DOMDocument
	*/
	protected $htmlDocument;
	
	/**
	* put your comment there...
	* 
	* @param FormListField $form
	* @param {FormListField|IFieldLinker} $linker
	* @return {HTMLForm|FormListField|IFieldLinker}
	*/
	public function __construct(FormListField & $form, IFieldLinker & $linker = null) {
		# Initialize
		$this->form =& $form;
		$this->htmlDocument = new \DOMDocument();
		# Parent collection
		parent::__construct($form, $linker);
	}

	/**
	* 
	* 
	*/
	public function __toString() {
		# Get HTML content string
		$html = $this->getHTMLDocument()->saveXML();
		# Returns
		return $html;
	}

	/**
	* put your comment there...
	* 
	*/
	protected function & addField() {
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getForm() {
		return $this->form;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getHTMLDocument() {
		return $this->htmlDocument;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getValue() {
		return $this->getForm()->getValue();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & renderDoc() {
		# Initialize 
		$document =& $this->getHTMLDocument();
		# Render using internal document
		$this->render($document, $document);
		# Return HTML string
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param \DOMDocument $document
	* @param {\DOMDocument|\DOMNode} $parent
	* @return {\DOMDocument|\DOMElement|\DOMNode}
	*/
	protected function & renderList(\DOMDocument & $document, \DOMNode & $parent) {
		# Create form element
		$formElement = $document->createElement('form');
		$list = $document->createElement('ul');
		# Append for elements
		$formElement->appendChild($list);
		$parent->appendChild($formElement);		
		# Return Form list element
		return $list;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	public function & setValue($value) {
		# Create directory
		$this->createField();
		# Set form values
		$this->getForm()->setValue($value);
		# Link without fields
		
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & validate() {
		# Validate fields
		$this->getForm()->validate();
		# Chain
		return $this;
	}

}