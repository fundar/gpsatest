<?php
/**
* 
*/

namespace ARV\Modules\Report\Model\Forms;

# Forms Framework
use WPPFW\Forms;

/**
* 
*/
class ReportForm extends Forms\SecureForm {
	
	/**
	* 
	* 
	*/
	public function __construct() {
		# Form name
		parent::__construct('reportForm', 'stoken');
	}
	
}