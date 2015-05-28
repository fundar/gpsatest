<?php
/**
* 
*/

namespace WPPFW\Obj;

/**
* 
*/
class Factory implements IFactory {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $map = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $objects = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $rootNS;
	
	/**
	* put your comment there...
	* 
	* @param mixed $namespace
	* @return Factory
	*/
	public function __construct($namespace)	{
		# Initialize
		$this->rootNS =& $namespace;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $class
	* @param mixed $factoryClass
	*/
	public function addClassMap($class, $factoryClass) {
		# Add
		$this->map[$class] = $factoryClass;
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $class
	* @param mixed $instance
	*/
	public function & setInstance(& $instance) {
		# Add instance
		return $this->setNamedInstance(get_class($instance), $instance);
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $instance
	* @return Factory
	*/
	public function & setNamedInstance($name, & $instance) {
		# Add instance
		$this->objects[$name] =& $instance;
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $class
	*/
	public function & create($class) {
		# Add namepsace to class
		$objectFactoryClass = $this->getFactoryClassName($this->getFactoryClass($class));
		# Creating Factory object
		$objectFactory = new $objectFactoryClass();
		# Creating orignal object
		$object = $objectFactory->getInstance($this);
		# Returning object
		return $object;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $class
	*/
	public function & get($class) {
		# Create object if not already created
		if (!isset($this->objects[$class])) {
			# Caching and Creating object
			$this->objects[$class] = $this->create($class);
		}
		# Return object
		return $this->objects[$class];
	}

	/**
	* put your comment there...
	* 
	* @param mixed $class
	*/
	public function getFactoryClassName($class) {
		return $this->getNamespace() . "\\{$class}";
	}

	/**
	* put your comment there...
	* 
	* @param mixed $class
	*/
	public function getFactoryClass($class) {
		return isset($this->map[$class]) ? $this->map[$class] : null;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getNamespace() {
		return $this->rootNS;
	}
	
}
