<?php
/**
* 
*/

namespace WPPFW\Collection;

/**
* 
*/
class DataAccess implements IDataAccess {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $data = array();
	
	/**
	* put your comment there...
	* 
	* @param mixed $data
	* @return DataAccess
	*/
	public function __construct(& $data = null) {
		# Initialize
		if ($data !== null) {
			$this->data =& $data;	
		}
	}
  
  /**
  * put your comment there...
  * 
  * @param mixed $value
  */
  public function & append($value) {
  	# Add
		$this->data[] =& $value;
		# Chain
		return $this;
  }

	/**
	* 
	*/
	public function get($name) {
		return isset($this->data[$name]) ? $this->data[$name] : null;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getArray() {
		return $this->data;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $data
	*/
	public function & merge($data) {
		# Copy all values
		foreach ($data as $name => $value) {
			$this->data[$name] = $value;
		}
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param mixed $value
	*/
	public function & set($name, & $value) {
		# Setting value
		$this->data[$name] =& $value;
		# Chain
		return $this;
	}

}