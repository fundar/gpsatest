<?php
/**
* 
*/

namespace ARV\Services\Installer\MenuPages\Installer;

# Menu Page Service Framework
use WPPFW\Services\Dashboard\Menu\MenuPage;

/**
* 
*/
class Page extends MenuPage {
	
	/**
	* put your comment there...
	* 
	*/
	public function __construct() {
		parent::__construct('AWStats Report Viewer Installer', 'Install', 'administrator');
	}

}
