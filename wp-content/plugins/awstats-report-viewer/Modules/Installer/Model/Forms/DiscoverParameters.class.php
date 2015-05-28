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
class AWStatsDiscoverParametersForm extends Forms\SecureForm {
	
	/**
	* 
	* 
	*/
	public function __construct() {
		# Form name
		parent::__construct('discover', 'stoken');
		# AWStats Script path
		$this->addChain(new Forms\Fields\FormStringField('scriptPath'))
		# Domain Name
		->addChain(new Forms\Fields\FormStringField('domain'))
		# System User
		->addChain(new Forms\Fields\FormStringField('systemUser'));
	}

	/**
	* put your comment there...
	* 
	*/
	public function getAWStatsScript() {
		return $this->get('scriptPath');
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getDomain() {
		return $this->get('domain');
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getSystemUser() {
		return $this->get('systemUser');
	}

}
