<?php
/**
* 
*/

namespace WPPFW\Database\Wordpress;

/**
* 
*/
class WordpressOptions {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $prefix;
	
	/**
	* put your comment there...
	* 
	* @param mixed $prefix
	* @return WordpressOptions
	*/
	public function __construct($prefix) {
		# Initialize
		$this->prefix =& $prefix;
	}
	
	/**
	* put your comment there...
	* 
	* @param WPOptionVariable $variable
	* @return {WPOptionVariable|WPOptionVariable}
	*/
	public function get(WPOptionVariable & $variable) {
		# Set value
		$variable->setValue(get_option($this->getOptionFullName($variable), $variable->getValue()));
		# Return var
		return $variable;
	}

	/**
	* put your comment there...
	* 
	* @param WPOptionVariable $name
	* @return {mixed|WPOptionVariable}
	*/
	public function getOptionFullName(WPOptionVariable & $variable) {
		# Getting option full name
		return "{$this->getPrefix()}{$variable}";
	}
	/**
	* put your comment there...
	* 
	*/
	public function getPrefix() {
		return $this->prefix;
	}

	/**
	* put your comment there...
	* 
	* @param WPOptionVariable $varilable
	* @param mixed $value
	* @return WordpressOptions
	*/
	public function & set(WPOptionVariable & $variable) {
		# Updating option
		update_option($this->getOptionFullName($variable), $variable->getValue());
		# Return variable
		return $variable;
	}

}
