<?php
/**
* 
*/

namespace WPPFW\Plugin;

# Imports
use \WPPFW\MVC;

/**
* 
*/
class MVCRequestInputFrontProxy extends ServiceFrontProxy {
	
	/**
	* put your comment there...
	* 
	* @param mixed $defParams
	* @param mixed $defNames
	* @param mixed $structure
	* @return MVC\MVCParams
	*/
	protected function createMVCObjects($defParams, $defNames, $structure) {
		# Initialize
		$plugin =& $this->getPlugin();
		$inputs =& $plugin->input();
		$namespace =& $plugin->getNamespace();
		# Creating objects
		$params = new MVC\MVCParams(
			$defParams['module'], 
			$defParams['controller'], 
			$defParams['action'], 
			$defParams['format']
			);
		$structure = new MVC\MVCStructure(
			$namespace, 
			$structure['module'], 
			$structure['controller'],
			$structure['controllerClassId'],
			$structure['model'],
			$structure['modelClassId']
			);
		$names = new MVC\MVCParams(
			$defNames['module'], 
			$defNames['controller'], 
			$defNames['action'], 
			$defNames['format']
			);
		# Reading inputs
		$inputsReader = new MVC\MVCRequestParamsRouter($namespace->getNamespace(), $inputs->request(), $names, $params);
		# Get target
		$target = $inputsReader->getOutParams();
		# Send object back
		$this->setMVCObjects($target, $names, $structure);
		# Chaining
		return $this;
	}
}