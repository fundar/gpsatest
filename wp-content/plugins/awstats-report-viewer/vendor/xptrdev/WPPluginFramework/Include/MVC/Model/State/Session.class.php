<?php
/**
* 
*/

namespace WPPFW\MVC\Model\State;

# Imports
use WPPFW\Database\Wordpress\WPOptionVariable;

/**
* 
*/
class SessionWPOptionsModelState extends WPOptionsModelState {

	/**
	* put your comment there...
	* 
	*/
	protected function getStateVar() {
		return new WPOptionVariable($this->getStandardVarName() . '-session:' . session_id(), array());
	}
}