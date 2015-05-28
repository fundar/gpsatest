<?php
/**
* 
*/

namespace ARV\Modules\Report\Model;

/**
* 
*/
class AWStats {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $awstatsProg;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $envVars = array('GATEWAY_INTERFACE');
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $envVarsCache = array();
	
	/**
	* put your comment there...
	* 
	* @param mixed $awstatsProg
	* @return AWStats
	*/
	public function __construct($awstatsProg) {
		# Initialize
		$this->awstatsProg =& $awstatsProg;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $buildStaticProg
	* @param mixed $configDir
	* @param mixed $domain
	* @param mixed $outputDir
	*/
	public function & buildStatic($buildStaticProg, $configDir, $domain, $outputDir) {
		# Clear CGI vars so that awstats.pl script
		# would act as runing from command line
		# This is requiored for build static to work
		$this->clearCGIEnvironmentVars();
		# Run awstats.pl prog, get version number from first time
		exec("{$buildStaticProg} -update -config={$domain} -configDir={$configDir} -dir={$outputDir} -awstatsprog={$this->getAWStatsProg()}");
		# Reset environment vars back
		$this->resetEnvironmentVars();
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	protected function clearCGIEnvironmentVars() {
		# Cache envoirnment variables
		foreach ($this->envVars as $envVarName) {
			# Cache var value
			$this->envVarsCache[$envVarName] = $_ENV[$envVarName];
			# Clear var
			putenv("{$envVarName}=");
		}
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getAWStatsProg() {
		return $this->awstatsProg;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getVersion() {
		# Clear CGI vars so that awstats.pl script
		# would act as runing from command line
		# This is requiored for build static to work
		$this->clearCGIEnvironmentVars();
		# Run awstats.pl prog, get version number from first time
		exec($this->getAWStatsProg(), $awstatsHelpLines);
		$awstatsFirstLinePrts = explode(' ', $awstatsHelpLines[0]);
		# Get version number
		$version = $awstatsFirstLinePrts[2];
		# Reset environment vars back
		$this->resetEnvironmentVars();
		# Return version
		return $version;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function resetEnvironmentVars() {
		# Reset environment vars
		foreach ($this->envVarsCache as $name => $value) {
			# Reset environment var value
			putenv("{$name}={$value}");
		}
		# Clear cache
		$this->envVarsCache = array();
		# Chain
		return $this;
	}

}
