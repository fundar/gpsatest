<?php
/**
* 
* 
*/

namespace WPPFW\HDT;

/**
* 
*/
interface IReaderPrototype {
	
	/**
	* 
	*/
	public function & bind(IWriterPrototype $parent, IWriterPrototype $writer);

	/**
	* 
	*/
	public function & query($prototypeName, IWriterPrototype & $parent, IWriterPrototype & $writer);
	
}
