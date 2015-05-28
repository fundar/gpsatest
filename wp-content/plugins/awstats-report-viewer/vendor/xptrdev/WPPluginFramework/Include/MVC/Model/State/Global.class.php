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
class GlobalWPOptionsModelState extends WPOptionsModelState {

	/**
	* put your comment there...
	* 
	*/
	protected function getStateVar() {
		return new WPOptionVariable($this->getStandardVarName(), array());
	}
}