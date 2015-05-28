<?php
/**
* 
*/

namespace WPPFW\MVC\View;

# Imports
use WPPFW\MVC;

/**
* 
*/
abstract class Base extends MVC\MVCComponenetsLayer implements MVC\IMVCResponder {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $result;
	
	/**
	* put your comment there...
	* 
	* @param MVC\IMVCServiceManager $serviceManager
	* @param mixed $result
	* @return Base
	*/
	public function __construct(MVC\IMVCServiceManager & $serviceManager, & $result) {
		# Unit intialization
		parent::__construct($serviceManager);
		# Initialize
		$this->result =& $result;
		# Initialize view
		$this->initialize();
	}

	/**
	* put your comment there...
	* 
	*/
	public function & factory() {
		return $this->mvcServiceManager()->factory();
	}

	/**
	* put your comment there...
	* 
	*/
	protected function initialize() {;}
	
	/**
	* put your comment there...
	* 
	*/
	public function & mvcStructure() {
		return $this->mvcServiceManager()->structure();
	}

	/**
	* put your comment there...
	* 
	*/
	public function & mvcTarget() {
		return $this->mvcServiceManager()->target();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & result() {
		return $this->result;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & router() {
		return $this->mvcServiceManager()->router();
	}

}

