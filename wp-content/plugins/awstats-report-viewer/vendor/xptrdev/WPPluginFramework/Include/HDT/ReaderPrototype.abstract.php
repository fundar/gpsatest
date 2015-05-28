<?php
/**
* 
*/

namespace WPPFW\HDT;

/**
* 
*/
abstract class ReaderPrototype implements IReaderPrototype {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $parent;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $writer;
	
	/**
	* put your comment there...
	* 
	* @param IWriterPrototype $parent
	* @param {IWriterPrototype|IWriterPrototype} $writer
	* @return {IWriterPrototype|IWriterPrototype|ReaderPrototype}
	*/
	public function & bind(IWriterPrototype $parent, IWriterPrototype $writer) {
		# Initialize
		$this->parent =& $parent;
		$this->writer =& $writer;
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getParent()	{
		return $this->parent;
	}
		
	/**
	* put your comment there...
	* 
	*/
	public function & getWriter() {
		return $this->writer;
	}
	
}