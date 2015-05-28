<?php
/**
* 
*/

namespace WPPFW\Obj;

/**
* 
*/
class Register {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected static $instances = array();

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $register = array();
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @return Register
	*/
	public function __construct() {}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public static function & create($name) {
		# Instantiate if not already instantiated
		if (isset(self::$instances[$name])) {
			self::$instances = new Register();
		}
		# Return instance
		return self::$instances[$name];
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public static function & getInstance($name)	{
		# Initialize
		$instance = null;
		# Check existance
		if (isset(self::$instances[$name])) {
			# Return instance
			$instance = self::$instances[$name];
		}
		# Chain
		return $instance;
	}

}