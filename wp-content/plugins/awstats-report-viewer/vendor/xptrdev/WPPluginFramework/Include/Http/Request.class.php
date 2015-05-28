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
class HTTPRequest {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $get;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $post;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $request;

	/**
	* put your comment there...
	* 
	* @param mixed $get
	* @param mixed $post
	* @param mixed $request
	* @return HTTPRequest
	*/
	public function __construct(& $get, & $post, & $request) {
		# Initialize
		$this->get = new DataAccess($get);
		$this->post = new DataAccess($post);
		$this->request = new DataAccess($request);
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & get() {
		return $this->get;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function isPost() {
		return ($_SERVER['REQUEST_METHOD']	== 'POST');
	}

	/**
	* put your comment there...
	* 
	*/
	public function & post() {
		return $this->post;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & request() {
		return $this->request;
	}
		
}
