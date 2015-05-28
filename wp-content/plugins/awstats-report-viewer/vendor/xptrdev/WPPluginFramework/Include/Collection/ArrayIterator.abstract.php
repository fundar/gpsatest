<?php
/**
* 
*/

namespace WPPFW\Collection;

/**
* 
*/
class ArrayIterator implements \Iterator {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $array;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $position = 0;
	
	/**
	* put your comment there...
	* 
	* @param mixed $array
	* @return ArrayIterator
	*/
	public function __construct(& $array) {
		# Set arrar refernce.
		$this->array =& $array;
	}

	/**
	* put your comment there...
	* 
	*/
	public function current() {
		return current($this->array);
	}

	/**
	* put your comment there...
	* 
	*/
	public function key() {
		return key($this->array);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function next() {
		# Increase pointer
		$this->position++;
		# Move next
		return next($this->array);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function rewind() {
		# Reset pointer
		$this->position = 0;
		# Reset
		return reset($this->array);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function valid() {
		return ($this->position != count($this->array));
	}

}
