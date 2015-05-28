<?php
/**
* 
*/

namespace WPPFW\MVC\Model\State;

# Imports
use WPPFW\Obj\IFactory;
use WPPFW\Obj\ClassName;

/**
* 
*/
abstract class WPOptionsModelState implements IModelStateAdapter {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $factory;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $modelClass;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $standardVarName;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $stateVar;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $wpOptionsAdapter;

	/**
	* put your comment there...
	* 
	* @param IFactory $factory
	* @param mixed $modelClass
	* @return WPOptionsModelState
	*/
	public function __construct(IFactory & $factory, $modelClass) {
		# Init vars
		$this->factory =& $factory;
		$this->modelClass =& $modelClass;
		# model class name components
		$modelClassNameParser = new ClassName($modelClass);
		# Standard var name without any side association!
		$this->standardVarName = 'model-state_' . strtolower($modelClassNameParser->getName());
		# Getting Wordpress options adapter
		$this->wpOptionsAdapter =& $factory->get('WPPFW\Database\Wordpress\WordpressOptions');
		# Get state var implemented by the model
		$this->stateVar = $this->getStateVar();
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected abstract function getStateVar();

	/**
	* put your comment there...
	* 
	*/
	protected function getStandardVarName() {
		return $this->standardVarName;
	}

	/**
	* 
	*/
	public function read() {
		# Getting state var value
		return $this->wpOptionsAdapter->get($this->stateVar)->getValue();
	}
	
	/**
	* 
	*/
	public function & write(& $data) {
		# Writinig data to options table
		$this->wpOptionsAdapter->set($this->stateVar->setValue($data));
		# Chain
		return $this;
	}

}