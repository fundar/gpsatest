<?php
/**
* 
*/

namespace WPPFW\Services\Dashboard\Menu;

/**
* 
*/
class SubMenu {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $items;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $parent;
	
	/**
	* put your comment there...
	* 
	* @param MenuPage $parentMenu
	* @return {SubMenu|MenuPage}
	*/
	public function __construct(MenuPage & $parentMenu) {
		# Initialize
		$this->parent = $parentMenu;
	}
	
	/**
	* put your comment there...
	* 
	* @param SubMenuPage $subMenuItem
	* @return SubMenuPage
	*/
	public function add(SubMenuPage & $subMenuItem) {
		# Bind to sub menu
		$subMenuItem->setParent($this->getParent());
		# Add to list
		$this->items[] = $subMenuItem;
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getParent() {
		return $this->parent;
	}

}