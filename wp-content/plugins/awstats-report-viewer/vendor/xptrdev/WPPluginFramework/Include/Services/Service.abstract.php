<?php
/**
* 
*/

namespace WPPFW\Services;

/**
* 
*/
abstract class ServiceBase implements IService {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $hoohMap = array();

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $proxy;
		
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $responder;

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $serviceObjects = array();
	
	/**
	* put your comment there...
	* 
	* @var Services\IServiceFrontFactory
	*/
	protected $serviceFront;

	/**
	* put your comment there...
	* 
	* @var Services\IServiceFrontFactory
	*/
	protected $serviceFrontFactory;
	
	/**
	* put your comment there...
	* 
	* @param Services\IServiceFrontFactory $serviceFront
	* @param mixed $menuPages
	* @return Service
	*/
	public function __construct(IServiceFrontFactory & $serviceFrontFactory, $serviceObjects) {
		# Initialize
		$this->serviceFrontFactory =& $serviceFrontFactory;
		$this->serviceObjects =& $serviceObjects;
	}
	
	/**
	* put your comment there...
	* 
	* @param ServiceObject $serviceObject
	* @return ServiceObject
	*/
	protected function & bindServiceObject(ServiceObject & $serviceObject) {
		# Bind to service
		$serviceObject->bind($this);
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param ProxyBase $proxy
	* @return {ProxyBase|ServiceBase}
	*/
	public function & createServiceFront(IProxy & $proxy) {
		# Initialize
		$serviceFrontFactory =& $this->serviceFrontFactory;
		# Get service object
		$serviceObject =& $this->getCurrentFilterSrvObject();
		# Prime service object
		$serviceObject->prime($proxy);
		# Create service object
		$this->serviceFront =& $serviceFrontFactory->createServiceFront($serviceObject);
		# Chaining
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & dispatch() {
		# Initialize4
		$serviceFrontFactory =& $this->getServiceFrontFactory();
		# Dispatch
		$this->responder = $serviceFrontFactory->dispatch($this->getServiceFront());
		# Chaining
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	protected function & getCurrentFilterSrvObject() {
		return $this->hoohMap[current_filter()];
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function & getHookCallback($name) {
		# Get callback, you might cache them if desired!
		$callback = array($this, "_wp_{$name}");
		# Returns
		return $callback;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getResponder() {
		return $this->responder;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getServiceFront() {
		return $this->serviceFront;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getServiceFrontFactory() {
		return $this->serviceFrontFactory;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getServiceObjects() {
		return $this->serviceObjects;
	}

	/**
	* put your comment there...
	* 
	*/
	public function response() {
		echo $this->getResponder();
	}

}