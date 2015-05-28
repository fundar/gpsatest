<?php
/**
* 
*/

namespace WPPFW\Obj;

/**
* 
*/
class CastObject {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $object;
	
	/**
	* put your comment there...
	* 
	* @param mixed $object
	* @return ObjectArray
	*/
	public function __construct(& $object) {
		# Set
		$this->object =& $object;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $object
	*/
	public static function getInstance(& $object) {
		return new CastObject($object);
	}

	/**
	* put your comment there...
	* 
	* @param mixed $object
	* @return {ObjectArray|mixed}
	*/
	public function & getArray() {
		# Init vars
		$array = array();
		# Use PHP cast.
		$objectArray = (array) $this->getObject();
		# Clean up Key name.
		foreach ($objectArray as $badName => $value) {
			# Clean name
			$name = preg_replace('/\W+/', '', $badName);
			# Add to array
			$array[$name] = $value;
		}
		return $array;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getObject() {
		return $this->object;
	}
	
}