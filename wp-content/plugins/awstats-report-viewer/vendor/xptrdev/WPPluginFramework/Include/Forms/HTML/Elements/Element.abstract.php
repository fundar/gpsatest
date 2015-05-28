<?php
/**
* 
*/

namespace WPPFW\Forms\HTML\Elements;

# Form
use WPPFW\Forms\Fields\IField;

/**
* 
*/
abstract class HTMLFormElement extends HTMLFormNode {
	
	/**
	* put your comment there...
	* 
	*/
	protected function & createField() {
		# Initialize
		$linker =& $this->getLinker();
		# Create Form FIELD!
		$linker->create($this);
		# Chain
		return $this;
	}

}