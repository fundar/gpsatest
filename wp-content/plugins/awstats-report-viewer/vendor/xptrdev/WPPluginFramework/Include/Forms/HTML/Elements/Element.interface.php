<?php
/**
* 
*/

namespace WPPFW\Forms\HTML\Elements;

# Imports
use WPPFW\Forms\HTML\Linkers\IFieldLinker;

/**
* 
*/
interface IElement {

	/**
	* 
	*/
	public function & getField();
	
	/**
	* 
	*/
	public function & getLinker();
	
	/**
	* 
	*/
	public function & getParent();
	
	/**
	* 
	*/
	public function & render(\DOMDocument & $doc, \DOMNode & $parent);
	
	/**
	* 
	*/
	public function & setLinker(IFieldLinker & $linker);
	
}
