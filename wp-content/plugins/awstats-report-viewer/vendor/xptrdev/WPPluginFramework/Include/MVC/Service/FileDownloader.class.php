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
class FileDownloader extends MVCResponder {
	
	/**
	* put your comment there...
	* 
	*/
	protected function initialize() {
		# Set http response header
		$httpResponse =& $this->getHttpResponse();
		/// header("Content-Desposition");
	}

	/**
	* put your comment there...
	* 
	*/
	public function __toString() {
		return $this->getResult();
	}
	
}