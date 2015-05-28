<?php
/**
* 
*/

namespace WPPFW\Services\Dashboard\Menu;

# Imports
use WPPFW\Services\ServiceBase;

/**
* 
*/
class MenuService extends ServiceBase {
	
	/**
	* put your comment there...
	* 
	*/
	public function _wp_addMenu() {
		# Initialize
		$menuCallback = $this->getHookCallback('menuCallback');
		$loadCallback = $this->getHookCallback('pageLoad');
		$menuPages =& $this->getServiceObjects();
		# Add all menu pages
		foreach ($menuPages as $index => $menuPage) {
			# Bind Service object
			$this->bindServiceObject($menuPage);
			# Add menu item
			$hookSlug = $menuPage->add($menuCallback)->getHookSlug();
			# Bind to page load event
			$loadHook = "load-{$hookSlug}";
			add_action($loadHook, $loadCallback);
			# Add to map
			$this->hoohMap[$loadHook] =& $menuPages[$index];
		}
		return;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function _wp_menuCallback() {
		$this->response();
	}
  
  /**
  * put your comment there...
  * 
  */
  public function _wp_pageLoad() {
		# Load service front 
		$this->createServiceFront(new Proxy());
		# Dispatch
		$this->dispatch();
  }

	/**
	* put your comment there...
	* 
	*/
	public function & start() {
		# Start service
		add_action('admin_menu', $this->getHookCallback('addMenu'));
		# Chains
		return $this;
	}
	
}  # End class
