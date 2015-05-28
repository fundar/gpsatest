<?php
/**
* 
*/

namespace WPPFW\Forms\HTML\Linkers;

# Imports
use WPPFW\Forms\HTML\Elements\IElement;

/**
* 
*/
class FieldLinker implements IFieldLinker {
	
	/**
	* put your comment there...
	* 
	* @param IElement $element
	* @return IElement
	*/
	public function create(IElement & $element) {
		# Initiaize
		$parent =& $element->getParent();
		# Add current element field to parent element field
		$parent->getField()->add($element->getField());
	}
	
	/**
	* put your comment there...
	* 
	* @param IElement $element
	* @return IElement
	*/
	public function link(IElement & $element) {
		
	}

}
