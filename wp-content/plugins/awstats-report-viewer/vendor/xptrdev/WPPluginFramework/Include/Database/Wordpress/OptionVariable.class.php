<?php
/**
* 
*/

namespace WPPFW\Database\Wordpress;

/**
* 
*/
class WPOptionVariable {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $name;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $value;
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $default
	* @return Variable
	*/
	public function __construct($name, $default = null) {
		# INitialize
		$this->name =& $name;
		$this->value =& $default;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function __toString() {
		return $this->name;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getName() {
		return $this->name;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getValue() {
		return $this->value;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $value
	* @return WPOptionVariable
	*/
	public function & setValue($value) {
		# Set
		$this->value =& $value;
		# Chain
		return $this;
	}

} # End class