<?php
/**
* 
*/

namespace WPPFW\Forms\HTML\Elements;

# Form
use WPPFW\Forms\Fields\IField;
use WPPFW\Forms\HTML\Linkers\IFieldLinker;

/**
* 
*/
abstract class HTMLFormNode implements IElement {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $field;
	
	/**
	* put your comment there...
	* 
	* @var IFieldLinker
	*/
	protected $linker;
	
	/**
	* put your comment there...
	* 
	* @var IElement
	*/
	protected $parent;
	
	/**
	* put your comment there...
	* 
	* @param IField $field
	* @param {IField|IFieldLinker} $linker
	* @return {HTMLFormNode|IField|IFieldLinker}
	*/
	public function __construct(IField $field = null, IFieldLinker & $linker = null) {
		# Initialize
		$this->field =& $field;
		$this->linker =& $linker;
	}

	/**
	* put your comment there...
	* 
	*/
	protected abstract function & createField();
	
	/**
	* put your comment there...
	* 
	*/
	public function & getField() {
		return $this->field;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getLinker() {
		return $this->linker;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getParent() {
		return $this->parent;
	}

	/**
	* put your comment there...
	* 
	* @param IFieldLinker $linker
	* @return IFieldLinker
	*/
	public function & setLinker(IFieldLinker & $linker) {
		# Set
		$this->linker =& $linker;
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param IElement $parent
	* @return {HTMLFormElement|IElement}
	*/
	protected function & setParent(IElement & $parent) {
		# Set parent
		$this->parent =& $parent;
		# Chain
		return $this;
	}

}
