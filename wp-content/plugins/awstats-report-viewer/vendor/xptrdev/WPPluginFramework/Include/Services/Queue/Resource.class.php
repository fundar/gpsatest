<?php
/**
* 
*/

namespace WPPFW\Services\Queue;

/**
* 
*/
class Resource {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $dependencies;
	
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
	protected $url;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $version;
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $url
	* @param mixed $version
	* @return Resource
	*/
	public function __construct($name, $url, $version = null)  {
		# Initialize
		$this->name =& $name;
		$this->url =& $url;
		$this->version =& $version;
		$this->dependencies = new \WPPFW\Collection\DataAccess();
	}

	/**
	* put your comment there...
	* 
	* @return \WPPFW\Collection\DataAccess
	*/
	public function & dependencies() {
		return $this->dependencies;
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
	public function getUrl() {
		return $this->url;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getVersion() {
		return $this->version;
	}

}
	