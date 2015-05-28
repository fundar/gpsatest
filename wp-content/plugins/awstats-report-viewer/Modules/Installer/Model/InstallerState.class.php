<?php
/**
* 
*/

namespace ARV\Modules\Installer\Model;

# Imports
use WPPFW\MVC\Model\State\GlobalWPOptionsModelState;
use WPPFW\Plugin\PluginBase;

/**
* 
*/
class InstallState {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $dbVersion;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $installedVersion;
	
	/**
	* put your comment there...
	* 
	* @param PluginBase $plugin
	* @return {InstallerConfig|PluginBase}
	*/
	public function __construct(PluginBase & $plugin) { 
		# Read Installed model state through State Adapter
		$stateAdapter = new GlobalWPOptionsModelState($plugin->factory(), 'WPCFS\Modules\Installer\Models\InstallerModel');
		$state = $stateAdapter->read();
		$this->installedVersion = isset($state['installedVersion']) ? $state['installedVersion'] : null;
		# Get plugin db version
		$pluginConfig =& $plugin->getConfig()->getPlugin();
		$this->dbVersion = $pluginConfig['parameters']['dbVersion'];
	}

	/**
	* put your comment there...
	* 
	*/
	public function getDBVersion() {
		return $this->dbVersion;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getInstalledVersion() {
		return $this->installedVersion;
	}

	/**
	* put your comment there...
	* 
	*/
	public function isInstalled() {
		return ($this->installedVersion == $this->dbVersion);
	}
	
}