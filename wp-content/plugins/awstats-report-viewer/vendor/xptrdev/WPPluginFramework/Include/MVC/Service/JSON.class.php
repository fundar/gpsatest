<?php
/**
* 
*/

namespace WPPFW\MVC\Service;

# Imports
use WPPFW\MVC\IMVCResponder;
use WPPFW\Http\HTTPResponse;

/**
* 
*/
class JSONEncoder extends MVCResponder {
	
	/**
	* put your comment there...
	* 
	*/
	protected function initialize() {
		# Set http response header
		$this->getHttpResponse()->setContentType('text/json');
	}

	/**
	* put your comment there...
	* 
	*/
	public function __toString() {
		return json_encode($this->getResult());
	}
	
}