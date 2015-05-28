<?php
/**
* 
*/

namespace WPPFW\Http;

# Imports
use WPPFW\Collection\DataAccess;

/**
* 
*/
class HTTPResponse {
	
	/**
	* put your comment there...
	* 
	* @param mixed $type
	*/
	public function setContentType($type) {
		# Set content type header
		header("Content-Type: {$type}");
		# Chain
		return $this;
	}

}