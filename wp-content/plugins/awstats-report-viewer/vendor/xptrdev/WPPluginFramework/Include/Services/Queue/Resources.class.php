<?php
/**
* 
*/

namespace WPPFW\Services\Queue;

/**
* 
*/
abstract class Resources {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $hook;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $hookCallback;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $queue = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $store = array();
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		# Initialize
		$this->hookCallback = array($this, '_wp_enqueue');
		# Bind to hook
		add_action($this->getHook(), $this->getHookCallback());
	}

	/**
	* put your comment there...
	* 
	*/
	public function _wp_enqueue() {
		# Register all stored resources
		$store =& $this->getStore();
		foreach ($store as $resource) {
			# Register by model
			$this->wpRegister($resource);
		}
		# Send all queued resources to queue using child model
		$queue =& $this->getQueue();
		foreach ($queue as $name) {
			# Enqueue by model
			$this->wpEnqueue($name);
		}
	}

	/**
	* put your comment there...
	* 
	* @param Resource $object
	* @return Resource
	*/
	protected function & addStore(Resource & $object) {
		# Add object to queue
		$this->store[$object->getName()] =& $object;
		# chain
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function & enqueueNamedResource($name) {
		# Add to queue
		$this->queue[] = $name;
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $object
	*/
	protected abstract function wpEnqueue($name);

	/**
	* put your comment there...
	* 
	* @param mixed $object
	*/
	protected abstract function wpRegister(& $object);

	/**
	* put your comment there...
	* 
	*/
	public function getHook() {
		return $this->hook;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getHookCallback() {
		return $this->hookCallback;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function & getQueue() {
		return $this->queue;
	}

	/**
	* put your comment there...
	* 
	*/
	protected function & getStore() {
		return $this->store;
	}
	
}
	