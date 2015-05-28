<?php
/**
* 
* 
*/

namespace WPPFW\HDT;

/**
* 
*/
interface IWriterPrototype {

	/**
	* 
	*/
	public function & load(IHTDDocument & $document, & $pipe = null, IWriterPrototype & $parent = null);
	
	/**
	* 
	*/
	public function & getDataSource();
	
	/**
	* 
	*/
	public function & getPipe();
	
	/**
	* 
	*/
	public function & getReaderPrototype();
	
	/**
	* 
	*/
	public function & transform($layerName);
	
	/**
	* 
	*/
	public function & setDataSource($data);
	
	/**
	* 
	*/
	public function & setReaderPrototype(IReaderPrototype $readerPrototype);
	
}