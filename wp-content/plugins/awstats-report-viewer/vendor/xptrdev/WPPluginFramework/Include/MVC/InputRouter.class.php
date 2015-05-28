<?php
/**
* 
*/

namespace WPPFW\MVC;

#Imports
use WPPFW\Collection\IDataAccess;

/**
* 
*/
class MVCRequestParamsRouter extends RouterBase {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $inputs;

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $outParams;
	
	/**
	* put your comment there...
	* 
	* @param mixed $prefix
	* @param IDataAccess $inputs
	* @param {IDataAccess|MVCParams} $defaults
	* @param {IDataAccess|MVCParams|MVCParams} $names
	* @param {IDataAccess|MVCParams|MVCParams|MVCParams} $outParams
	* @return {MVCRequestParamsRouter|IDataAccess|MVCParams|MVCParams|MVCParams}
	*/
	public function __construct($prefix, IDataAccess & $inputs, MVCParams & $names, MVCParams & $outParams) {
		# Initialize parent
		parent::__construct($prefix, $names);
		# Initialize
		$this->inputs =& $inputs;
		$this->outParams =& $outParams;
		# Get names properties
		$properties = $this->getNamesProperties();
		# Getting inputs
		foreach ($properties as $property) {
			# Property name
			$name = $property->getName();
			# Getting getter method name
			$getter = $this->getterMethod($name);
			$setter = $this->setterMethod($name);
			# Bring from inputs only if it has source name and
			# the source name is found within the inputs!
			$inputName = $names->$getter();
			if ($inputName && (($inputValue = $inputs->get($this->getParamName($inputName))) !== null)) {
				$outParams->$setter($inputValue);
			}
		}
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getInputs() {
		return $this->inputs;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getNames() {
		return parent::getNames();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getOutParams() {
		return $this->outParams;
	}
	
}