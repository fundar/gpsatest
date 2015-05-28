<?php
/**
* 
*/

namespace WPPFW\MVC\Controller;

# Improts
use WPPFW\MVC;

/**
* 
*/
abstract class Base extends MVC\MVCComponenetsLayer implements IController {

	/**
	* put your comment there...
	* 
	*/
	public function createSecurityToken() {
		# Get Plugin
		$plugin =& $this->factory()->get('WPPFW\Plugin\PluginBase');
		# Create Token
		return $plugin->createSecurityToken();
	}

	/**
	* put your comment there...
	* 
	*/
	public function & dispatch() {
		# Initialize
		$target =& $this->mvcTarget();
		$serviceManager =& $this->mvcServiceManager();
		# Get method name
		$actionMethod = lcfirst($target->getAction()) . 'Action';
		# Check existance
		if (!method_exists($this, $actionMethod)) {
			throw new \Exception('Controller action doesn\'t exists!');
		}
		# Call action
		$result = $this->$actionMethod();
		# Write model(s) state
		foreach ($serviceManager->getModels() as $moduleModels) {
			foreach ($moduleModels as $model) {
				# Write model state
				$model->writeState();
			}
		}
		$this->dispatched();
		# Creating responder
		$responder = $this->getResponder($result);
		# Return responder
		return $responder;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function dispatched() {;}
	
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
	public function & httpResponse() {
		return $this->mvcServiceManager()->httpResponse();
	}

	/**
	* put your comment there...
	* 
	*/
	public function & input() {
		return $this->mvcServiceManager()->input();
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function & getForm($name = null) {
		return $this->mvcServiceManager()->getForm($name);
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $module
	*/
	public function & getModel($name = null, $module = null) {
		return $this->mvcServiceManager()->getModel($name, $module);
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function & getModels() {
		return $this->mvcServiceManager()->getModels();
	}

	/**
	* put your comment there...
	* 
	* @param mixed $result
	*/
	protected abstract function getResponder(& $result);

	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function & getTable($name = null) {
		return $this->mvcServiceManager()->getTable($name);
	}

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
	public function & router() {
		return $this->mvcServiceManager()->router();
	}

}
