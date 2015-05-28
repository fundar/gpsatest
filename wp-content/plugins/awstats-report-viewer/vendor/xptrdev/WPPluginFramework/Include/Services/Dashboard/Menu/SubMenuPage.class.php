<?php
/**
* 
*/

namespace WPPFW\Services\Dashboard\Menu;

/**
* 
*/
class SubMenuPage extends MenuPageBase {
	
	/**
	* put your comment there...
	* 
	* @param mixed $callback
	*/
	protected function addMenuItem(& $callback) {
		# Add menu
		return add_submenu_page(
			$this->getParent()->getHookSlug(),
			$this->getPageTitle(), 
			$this->getTitle(), 
			$this->getCapability(), 
			$this->getSlug(), 
			$callback
		);
	}

}