<?php
/**
* 
*/

namespace WPPFW\Http;

/**
* 
*/
class Url {

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $params;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $url;
	
	/**
	* put your comment there...
	* 
	* @param mixed $url
	* @param mixed $params
	* @return Url
	*/
	public function __construct($url = null, $params = null) {
		# Initialize object vars
		$this->url =& $url;
		$this->params = new UrlParams($params);
	}

	/**
	* put your comment there...
	* 
	*/
	public function __toString() {
		return "{$this->url}?" . http_build_query($this->params()->getArray(), null, '&');
	}

	/**
	* put your comment there...
	* 
	*/
	public function & params() {
		return $this->params;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getUrl() {
		return $this->url;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $params
	*/
	public function & setUrl($url) {
		# Set
		$this->url =& $url;
		# Chain
		return $this;
	}

}