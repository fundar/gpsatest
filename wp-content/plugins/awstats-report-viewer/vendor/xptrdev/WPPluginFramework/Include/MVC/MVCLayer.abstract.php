<?php
/**
* 
*/

namespace WPPFW\MVC;

# Imports
use WPPFW\MVC\IMVCServiceManager;

/**
* 
*/
abstract class MVCComponenetsLayer implements IMVCComponentsLayer {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $serviceManager;
	
	/**
	* put your comment there...
	* 
	* @param IMVCServiceManager $factory
	* @return {MVCComponenetsLayer|IMVCServiceManager}
	*/
	public function __construct(IMVCServiceManager & $serviceManager) {
		# Initialize
		$this->serviceManager =& $serviceManager;
	}
	
	/**
	* put your comment there...
	* 
	* @return \WPPFW\MVC\MVCDispatcher
	*/
	protected function & mvcServiceManager() {
		return $this->serviceManager;
	}
	
}

