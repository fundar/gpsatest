<?php
/**
* 
*/

namespace ARV\Services\Dashboard\MenuPages\Viewer;

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
		parent::__construct('AWStats Report Viewer', 'AWStats Viewer', 'administrator');
	}

}
