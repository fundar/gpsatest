<?php
/**
* 
*/

namespace ARV\Modules\Installer\Model\Forms;

# Forms Framework
use WPPFW\Forms;

/**
* 
*/
class ResetParametersForm extends Forms\SecureForm {
	
	/**
	* 
	* 
	*/
	public function __construct() {
		# Form name
		parent::__construct('reset', 'stoken');
	}
	
}