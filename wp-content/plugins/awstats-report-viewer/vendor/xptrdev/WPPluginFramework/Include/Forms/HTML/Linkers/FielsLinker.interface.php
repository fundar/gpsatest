<?php
/**
* 
*/

namespace WPPFW\Forms\HTML\Linkers;

use WPPFW\Forms\HTML\Elements\IElement;
/**
* 
*/
interface IFieldLinker {
	
	/**
	* 
	*/
	public function create(IElement & $element);
	
	/**
	* 
	*/
	public function link(IElement & $element);

}
