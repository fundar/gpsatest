<?php
/**
* 
*/

namespace WPPFW\Services;

/**
* 
*/
abstract class ServiceObject {
	
	/**
	* put your comment there...
	* 
	* @var IProxy
	*/
	protected $proxy;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $service;
	
	/**
	* put your comment there...
	* 
	* @param IService $service
	* @return IService
	*/
	public function & bind(IService & $service) {
		# Set
		$this->service =& $service;
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getProxy() {
		return $this->proxy;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getService() {
		return $this->service;
	}

	/**
	* put your comment there...
	* 
	* @param IProxy $proxy
	* @return IProxy
	*/
	public function & prime(IProxy & $proxy) {
		# Set proxy
		$this->proxy =& $proxy;
		# Chain
		return $this;
	}

}