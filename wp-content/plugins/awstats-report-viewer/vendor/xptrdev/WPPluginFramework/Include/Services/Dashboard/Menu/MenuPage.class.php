<?php
/**
* 
*/

namespace WPPFW\Services\Dashboard\Menu;

/**
* 
*/
class MenuPage extends MenuPageBase {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $icon;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $position;
	
	/**
	* put your comment there...
	* 
	* @param mixed $callback
	*/
	protected function addMenuItem(& $callback) {
		# Add menu
		return add_menu_page(
			$this->getPageTitle(), 
			$this->getTitle(), 
			$this->getCapability(), 
			$this->getSlug(), 
			$callback,
			$this->getIcon(),
			$this->getPosition()
		);
	}

	/**
	* put your comment there...
	* 
	*/
	public function getIcon() {
		return $this->icon;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getPosition() {
		return $this->position;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	public function setIcon($icon) {
		# Initialize
    $this->icon = $icon;
		# Chain
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	public function setPosition($position) {
		# Initialize
    $this->position = $position;
		# Chain
		return $this;
	}

}