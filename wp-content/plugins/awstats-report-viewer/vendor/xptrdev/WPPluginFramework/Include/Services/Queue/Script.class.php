<?php
/**
* 
*/

namespace WPPFW\Services\Queue;

/**
* 
*/
abstract class ScriptResource extends Resource {
	
	/**
	* 
	*/
	CONST FOOTER = true;
	
	/**
	* 
	*/
	CONST HEADER = FALSE;
	
	/**
	* 
	*/
	protected $location;
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $url
	* @param mixed $version
	* @param mixed $location
	* @return Script
	*/
	public function __construct($name, $url, $version = null, $location) {
		# Resource object
		parent::__construct($name, $url, $version, $location);
		# Initialize
		$this->location =& $location;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getLocation() {
		return $this->location;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $location
	* @return Script
	*/
	public function & setLocation($location) {
		# Set
		$this->location =& $location;
		# Chain
		return $this;
	}
}