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
abstract class MVCResponder implements IMVCResponder {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $httpResponse;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $result;
	
	/***
	* put your comment there...
	* 
	* @param HTTPResponse $httpResponse
	* @param mixed $result
	* @return JSONEncoder
	*/
	public function __construct(HTTPResponse & $httpResponse, & $result) {
		# Initialize
		$this->httpResponse =& $httpResponse;
		$this->result =& $result;
		# Child constructor
		$this->initialize();
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getHttpResponse() {
		return $this->httpResponse;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getResult() {
		return $this->result;
	}

	/**
	* put your comment there...
	* 
	*/
	protected function initialize() {;}
	
}