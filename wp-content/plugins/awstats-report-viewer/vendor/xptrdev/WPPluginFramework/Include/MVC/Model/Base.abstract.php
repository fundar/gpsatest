<?php
/**
* 
*/

namespace WPPFW\MVC\Model;

# Imports
use WPPFW\MVC;
use WPPFW\Database\Wordpress\WPOptionVariable;
use WPPFW\Forms\Form;

/**
* 
*/
abstract class ModelBase extends MVC\MVCComponenetsLayer {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $config;

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $errorCodes = array();

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $errorMessages = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $params;

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $stateAdapter;
	
	/**
	* put your comment there...
	* 
	* @param MVC\IMVCComponentsLayer $serviceManager
	* @return {ModelBase|MVC\IMVCComponentsLayer}
	*/
	public function __construct(MVC\IMVCServiceManager & $serviceManager) {
		# MVC layer
		parent::__construct($serviceManager);
		# Model params
		$this->params = new \WPPFW\Collection\DataAccess();
		# Next layer initialization
		$this->initialize();
		# Load config
		$this->config =& $this->loadConfig();
		# Create State adapter object associated with this model
		$this->stateAdapter = new $this->config['stateType']($this->factory(), get_class($this));
		# Read state
		$this->readState();
		# After read state initialization
		$this->initialized();
	}

	/**
	* put your comment there...
	* 
	* @param mixed $message
	* @param mixed $code
	*/
	public function & addError($message, $code = null) {
		# Store error message
		$this->errorMessages[] =& $message;
		# Map the error code at the same offset on errorCodes var
		$this->errorCodes[] =& $code;
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & clearErrors() {
		# CLear error messages
		$this->errorMessages = array();
		# CLear error codes
		$this->errorCodes = array();
		# Chaining
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @return WPPFW\Obj\IFactory
	*/
	public function & factory() {
		return $this->mvcServiceManager()->factory();
	}

	/**
	* put your comment there...
	* 
	*/
	public function getCleanErrors() {
		# Get errors copy
		$errorMessages = $this->errorMessages;
		# Clear errors
		$this->clearErrors();
		# Return errors
		return $errorMessages;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $index
	*/
	public function & getError($index) {
		# Get error or null if not exists
		$errorMessage = $this->errorMessages[$index] ? $this->errorMessages[$index] : null;
		# Return error object
		return $errorMessage;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getErrorCodes() {
		return $this->errorCodes;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getErrors() {
		return $this->errorMessages;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $modelClass
	* @param MVC\IMVCComponentsLayer $serviceManager
	*/
	public static function & getInstance($modelClass, MVC\IMVCServiceManager & $serviceManager) {
		# Creating model
		$model = new $modelClass($serviceManager);
		# Returns
		return $model;
	}

	/**
	* put your comment there...
	* 
	*/
	protected function & getStateAdapter() {
		return $this->stateAdapter;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function hasErrors() {
		return !empty($this->errorMessages);
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected abstract function & loadConfig();
	
	/**
	* put your comment there...
	* 
	*/
	protected function initialize() {;}
	
	/**
	* put your comment there...
	* 
	*/
	protected function initialized() {;}
	
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
	public function & params() {
		return $this->params;
	}

	/**
	* put your comment there...
	* 
	* @param ModelBase $model
	*/
	public function & PipeErrors(ModelBase & $model) {
		# Get reference to error messages and codes so
		# that all errors writen to this model would
		# be redirected to the passed model
		$this->errorMessages =& $model->errorMessages;
		$this->errorCodes =& $model->errorCodes;
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	public function readState() {
		# Initialize vars
		$stateAdapter =& $this->getStateAdapter();
		# Copying state data to current instance
		foreach ($stateAdapter->read() as $propName => $value) {
			# Set value
			$this->$propName = $value;
		}
	}

	/**
	* put your comment there...
	* 
	*/
	public function & router() {
		return $this->mvcServiceManager()->router();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function writeState() {
		# Initialize vars
		$stateVars = array();
		$stateAdapter =& $this->getStateAdapter();
		$moduleClassReflection = new \ReflectionClass($this);
		# Copy all protected properties
		$statePropperties = $moduleClassReflection->getProperties(\ReflectionProperty::IS_PROTECTED);
		foreach ($statePropperties as $property) {
			# Getting property name
			$propertyName = $property->getName();
			# get value.
			$stateVars[$propertyName] =& $this->$propertyName;
		}
		# Write to state adapter
		$stateAdapter->write($stateVars);
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param Form $form
	*/
	public function validate(Form & $form) {
		# Get all form fields
		$fields = $form->getFields();
		# Recusively validate all fields
		
		return true;
	}

}

