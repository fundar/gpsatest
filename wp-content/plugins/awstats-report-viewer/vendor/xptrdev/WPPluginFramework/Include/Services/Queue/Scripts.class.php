<?php
/**
* 
*/

namespace WPPFW\Services\Queue;

/**
* 
*/
abstract class ScriptsQueue extends Resources {
	
	const JQUERY = 'jquery';
	const JQUERY_UI_TABS = 'jquery-ui-tabs';
	
	/**
	* put your comment there...
	* 
	* @param ScriptResource $script
	*/
	public function & add(ScriptResource & $script) {
		# Add to queues list
		return $this->addStore($script);
	}

	/**
	* put your comment there...
	* 
	* @param mixed $object
	*/
	protected function wpEnqueue($name) {
		# Equeue Wordpress script
		wp_enqueue_script($name);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $object
	*/
	protected function wpRegister(& $object) {
		# Polymorphism using Comments!
		/**
		* put your comment there...
		* 
		* @var ScriptResource
		*/
		$script =& $object;
		# Equeue Wordpress script
		wp_register_script(
			$script->getName(), 
			$script->getUrl(), 
			$script->dependencies()->getArray(), 
			$script->getVersion(), 
			$script->getLocation());
	}

}
	