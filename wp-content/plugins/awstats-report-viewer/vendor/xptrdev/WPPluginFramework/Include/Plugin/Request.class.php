<?php
/**
* 
*/

namespace WPPFW\Plugin;

# Imports
use WPPFW\Http\HTTPRequest;
use WPPFW\Collection;

/**
* 
*/
class Request extends HTTPRequest {
	
	/**
	* 
	*/
	const WORDPRESS_CHANNEL_NAME = 'wordpress';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $channels;
	
	/**
	* put your comment there...
	* 
	* @param mixed $get
	* @param mixed $post
	* @param mixed $request
	* @return Request
	*/
	public function __construct(& $get, & $post, & $request)	{
		# INitialize parent
		parent::__construct($get, $post, $request);
		# Define Wordpress input channel.
		$this->addChannel(self::WORDPRESS_CHANNEL_NAME, new Collection\DataAccess());
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param IDataAccess $object
	*/
	public function & addChannel($name, Collection\IDataAccess $channel) {
		# Add if not alreasdy added
		if (!isset($this->channels[$name])) {
			$this->channels[$name] =& $channel;
		}
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function & getChannel($name) {
		# Check existance
		if (!isset($this->channels[$name])) {
			throw new \Exception('Channel not found');
		}
		# Return channel
		return $this->channels[$name];
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function & setChannel($name, Collection\IDataAccess & $channel) {
		# Get old channel
		$currentChannel =& $this->getChannel($name);
		# Set to new
		$this->channels[$name] =& $channel;
		# Return old channel
		return $currentChannel;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & Wordpress() {
		return $this->getChannel(__FUNCTION__);
	}

}
