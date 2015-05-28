<?php
/**
* 
*/

namespace WPPFW\Forms\HTML;

use WPPFW\Forms\HTML\Elements\IElement;

/**
* 
*/
abstract class ElementsCollection extends Elements\HTMLFormNode {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $elements = array();
	
	/**
	* put your comment there...
	* 
	* @param IElement $element
	*/
	public function add(IElement & $element) {
		# Add
		$this->addChain($element);
		# Return element
		return $element;
	}

	/**
	* put your comment there...
	* 
	*/
	protected function & addField() {
		# Initialize
		$linker =& $this->getLinker();
		# Create Form FIELD!
		$linker->create($this);
		# Chain
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param IElement $element
	*/
	public function addChain(IElement & $element) {
		# Set linker if not has been set
		if (!$element->getLinker()) {
			$element->setLinker($this->getLinker());
		}
		# Add element
		$this->elements[$element->getField()->getName()] =& $element;
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	protected function & addElementsFields() {
		# INitialize
		$elements =& $this->getElements();
		# Create Fields structure
		foreach ($elements as $element) {
			# Associate with parent
			$element->setParent($this);
			# Add field at correspodning collection field
			$element->createField();
		}
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	protected function & createField() {
		# Add collection field
		$this->addField();
		# Create Fields structure
		$this->addElementsFields();
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getElements() {
		return $this->elements;
	}
	
	/**
	* put your comment there...
	* 
	* @param \DOMDocument $document
	* @param {\DOMDocument|\DOMNode} $parent
	* @return {\DOMDocument|\DOMNode|ElementsCollection}
	*/
	public function & render(\DOMDocument & $document, \DOMNode & $parent) {
		# Render element
		$listElement =& $this->renderList($document, $parent);
		# Render  collection
		$this->renderElements($document, $listElement);
		# Chain
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param \DOMDocument $document
	* @param {\DOMDocument|\DOMNode} $parent
	* @return {\DOMDocument|\DOMNode|ElementsCollection}
	*/
	protected function & renderElements(\DOMDocument & $document, \DOMNode & $parent) {
		# Rendering elements
		foreach ($this->getElements() as $element) {
			# Render list's element
			$element->render($document, $parent);
		}
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param \DOMDocument $document
	* @param {\DOMDocument|\DOMNode} $parent
	*/
	protected abstract function & renderList(\DOMDocument & $document, \DOMNode & $parent);
	
}