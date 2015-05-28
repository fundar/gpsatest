<?php
/**
* 
*/

namespace WPPFW\Forms;

/**
* 
*/
class SecureForm extends Form {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $securityTokenName;
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $securityTokenName
	* @return SecureForm
	*/
	public function __construct($name, $securityTokenName) {
		# INitialize parent
		parent::__construct($name);
		# Add security token field
		$this->addChain(new Fields\FormSecurityTokenField($securityTokenName));
		# Hold security token field name
		$this->securityTokenName = $securityTokenName;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getSecurityToken() {
		return $this->get($this->getSecurityTokenName());
	}

	/**
	* put your comment there...
	* 
	*/
	public function getSecurityTokenName() {
		return $this->securityTokenName;
	}

	/**
	* put your comment there...
	* 
	*/
	public function isAuthorized() {
		return wp_verify_nonce($this->getSecurityToken()->getValue());
	}

}