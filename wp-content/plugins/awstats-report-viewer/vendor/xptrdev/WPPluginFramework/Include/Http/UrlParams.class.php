<?php
/**
* 
*/

namespace WPPFW\Http;

# Data access
use WPPFW\Collection\DataAccess;

/**
* 
*/
class UrlParams extends DataAccess {
	
	/**
	* put your comment there...
	* 
	* @param mixed $data
	* @return DataAccess
	*/
	public function & merge($data) {
		# Copy all values
		foreach ($data as $name => $value) {
			# Only non empty
			if ($value) {
				$this->data[$name] = $value;	
			}
		}
		# Chain
		return $this;
	}

}