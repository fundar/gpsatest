<?php
/**
* 
*/

namespace WPPFW\Services\Dashboard\Ajax;

# Imports
use WPPFW\Services\ServiceObject;
use WPPFW\Services\IReachableServiceObject;
use WPPFW\Http\Url;

/**
* 
*/
class AjaxAccessPoint extends ServiceObject implements IReachableServiceObject {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $name;
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct()	{
		# Use model name
		$this->useModelName();
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function & setName($name) {
		# Set
		$this->name =& $name;
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 	
	*/
	public function getActionUrl() {
		return home_url('wp-admin/admin-ajax.php');
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
	public function getRequestActionName() {
		return "wp_ajax_{$this->getName()}";
	}

	/**
	* put your comment there...
	* 
	*/
	public function getRouteParams() {
		return array('action' => $this->getName());
	}

	/**
	* put your comment there...
	* 
	*/
	public function getUrl() {
		return new Url($this->getActionUrl(), $this->getRouteParams());
	}

	/**
	* put your comment there...
	* 
	*/
	public function & useModelName() {
		# Generate model name
		$this->name = strtolower(str_replace('\\', '-', get_class($this)));
		# Chain
		return $this;
	}

}