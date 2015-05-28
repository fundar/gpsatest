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
class CurrentUserWPOptionsModelState extends WPOptionsModelState {

	/**
	* put your comment there...
	* 
	*/
	protected function getStateVar() {
		return new WPOptionVariable($this->getStandardVarName() . '-userid:' . get_current_user_id(), array());
	}
}