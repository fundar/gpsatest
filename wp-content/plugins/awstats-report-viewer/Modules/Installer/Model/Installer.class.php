<?php
/**
* Installer.class.php
*/

# Define namespace
namespace ARV\Modules\Installer\Model;

# Model base
use WPPFW\MVC\Model\PluginModel;

/**
* Installer model class holds all internal functionality
* required by ARV installer process.
* 
* - Created AWStats reports(s) holder directory
* - Writes a database version number to wordpress options table
* - Discover awstats installatio parameters
* - Passthrough installation parameters to Viewer model
* - Create report for first time when installation success
* - Holds the deafult discover parameters
* 
* @author AHMeD SAiD
*/
class InstallerModel extends PluginModel {

	/**
	* Used to state that the installer has passed CREATE REPORT for the first time.
	*/
	const OPERATION_CREATE_REPORT = 2;
		
	/**
	* Used to state that the Report(s) holder directory step is passed
	*/
	const OPERATION_CREATE_REPORTS_DIRECTORY = 1;
	
	/**
	* State that there is no success operations never passed by the installer
	*/
	const OPERATION_NONE = 0;

	/**
	* State that all operations has been passed and also the installation state/flags
	* has beenm witen to database. This is the last operation 
	*/
	const OPERATION_WRITE_INSTALLATION_FLAGS = 3;
	
	/**
	* holds database version as loaded from Plugin config file
	* see $this->initialize()
	* 
	* @var string
	*/
	private $dbVersion;
	
	/**
	* Path to AWStats discover parameter used for discovering installation params
	* 
	* @var string
	*/
	protected $discoverAWStatsScript;
	
	/**
	* Discover Domain name used for discover installatioln params
	* 
	* @var string
	*/
	protected $discoverDomain;

	/**
	* System user name used to discover installation params
	* 
	* @var string
	*/
	protected $discoverSystemUser;

	/**
	* Index file name to copy from ARV Source code to reports and report
	* directory. The file is to prevent listing report folder and report files from
	* the browser. Report directory name and report files names should
	* be know only tro admins
	* 
	* @var string
	*/
	protected $indexFileName = 'index.php';

	/**
	* Path to AWStats program to be used for generating report
	* 
	* @var string
	*/
	protected $installsParamsAWStatsPath;
	
	/**
	* Path to AWStats build static tool script used
	* for creating static report
	* 
	* @var string
	*/
	protected $installsParamsBuildStaticScript;

	/**
	* Path to awstats configugration file path for the specified Domain
	* 
	* @var string
	*/
	protected $installsParamsConfigFile;
	
	/**
	* AWStats domain
	* 
	* @var string
	*/
	protected $installsParamsDomain;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $installsParamsIconsDirectory;

	/**
	* Installer state object used for evaludating
	* installation state (e.g installed, not installed, etc...)
	* 
	* @var InstallState
	*/
	private $installState;
	
	/**
	* Currently installed version number
	* 
	* @var string
	*/
	protected $installedVersion = '';
	
	/**
	* Latest successed operations process by the model
	* It can be one of the operations constants defined in the class
	* 
	* OPERATION_NONE
	* OPERATION_CREATE_REPORTS_DIRECTORY
	* OPERATION_CREATE_REPORT
	* OPERATION_WRITE_INSTALLATION_FLAGS 
	* 
	* @var OPERATION_[OPERATION]
	*/
	protected $lastOperation = self::OPERATION_NONE;
	
	/**
	* ARV Plugin relative path to index file used
	* for preventing listing report directory files 
	* using thr browser. See $this->initialize()
	* 
	* @var string
	*/
	protected $noListIndexFileRelPath;
	
	/**
	* Wordpress relative path to Reports directory
	* 
	* @see $this->initialize()
	* 
	* @var string
	*/
	protected $reportsDirectoryPath;
	
	/**
	* State that the model state is being reseted.
	* 
	* This is a Flag to be used by the controller
	* to fill discover form with the default parameters, therefore
	* causes installation parameters to be discovered.
	* 
	* @var boolean
	*/
	protected $reset = true;
	
	/**
	* ARV Plugin configuration object
	* 
	* The structure of this array is as defined
	* in the configuration XML file inside the <parameters> tag
	* 
	* So that accessing dbVersion number would be $this->pluginsConfig['parameters']['dbVersion']
	* 
	* @var Array
	*/
	private $pluginConfig;

	/**
	* Clear Model state.
	* 
	* The method is to clear both discover and installation parameters.
	* It would also set $this->reset flag tgo TRUE, stat5e that the model is in reset state
	* 
	* @return InstallerModel Chaining, returning $this
	*/
	public function clearState() {
		# Clear state
		$this->discoverAWStatsScript = null;
		$this->discoverDomain = null;
		$this->discoverSystemUser = null;
		$this->installsParamsAWStatsPath = null;
		$this->installsParamsBuildStaticScript = null;
		$this->installsParamsConfigFile = null;
		$this->installsParamsIconsDirectory = null;
		# Set reset to false
		$this->reset = true;
		# Chain
		return $this;
	}

	/**
	* Create AWStats report for the first time.
	* 
	* The method is to set Report Viewer model with 
	* the insatallation parameters and request report creation for the first
	* time.
	* 
	* @return InstallerModel Chaining, returning $this
	*/
	public function & createReport() {
		# Initialize
		$reportModel =& $this->getReportViewerModel();
		# Pass installation-parameters to Report Viewer / required for building report
		$reportModel->setAWStatsParameters(
			$this->getInstallsParamsDomain(),
			$this->getInstallsParamsAWstatsScriptPath(),
			$this->getInstallsParamsBuildStaticScript(),
			$this->getInstallsParamsConfigFile(),
			$this->getInstallsParamsIconsDirectory()
		)
		# Pass installation parametes to report model / Required for creating report
		->setInstallationParameters(
			$this->getReportsDirectoryPath(),
			$this->getNoListIndexFileRelPath()
		);
		# Pipe Viewer Model errors to write to Installer Model
		$reportCreated = $reportModel->pipeErrors($this)
		# Generate new report unique id
		->generateRID()
		# Create/Build AWStats report for the first time
		->createReport();
		# Check weather if report created successful
		if ($reportCreated) {
			# Set as passed operation
			$this->lastOperation = self::OPERATION_CREATE_REPORT;
		}
		# Chain
		return $this;
	}

	/**
	* Create reports(s) holder directory.
	* 
	* The method is to create the folder that would hold
	* the report folder inside.
	* 
	* It would also complain by the following errors:
	* 
	* - Cannot create reports directoy as there is no permissions
	* - Couldn't create reports directory
	* - Couldn't copy index file.
	* 
	* @return InstallerModel Chaining, returning $this
	*/
	public function createReportsDirectory() {
		# Initialize
		$reportModel =& $this->getReportViewerModel();
		$plugin =& $this->factory()->get('ARV\Plugin');
		# Process only if no operation performed before
		if (!$this->lastOperation) {
			# Getting absolute path to reports directory
			$reportsDirectory = $reportModel->buildReportsDirectoryAbsolutePath($this->getReportsDirectoryPath());
			# Make sure we can create directory
			$reportsDirectoryParent = dirname($reportsDirectory);
			if (is_readable($reportsDirectoryParent) && is_writable($reportsDirectoryParent)) {
				# Try to create directory
				if (file_exists($reportsDirectory) || mkdir($reportsDirectory, 0755))	{
					# Copy index file to reorts directory if not already exists
					$desIndexFilePath = $reportsDirectory . DIRECTORY_SEPARATOR . $this->getIndexFileName();
					$srcIndexFilePath =  $plugin->getDirectory() . DIRECTORY_SEPARATOR . $this->getNoListIndexFileRelPath();
					# Creating directory index file
					if (file_exists($desIndexFilePath) || copy($srcIndexFilePath, $desIndexFilePath)) {
						# Set as last operation
						$this->lastOperation = self::OPERATION_CREATE_REPORTS_DIRECTORY;
					}
					else {
						# Report problem
						$this->addError("Could not create reports directory default index file: {$desIndexFilePath}");	
					}
				}
				else {
					# Report Problem
					$this->addError("Could not create reports directory: {$reportsDirectory}");
				}
			}
			else {
				# Report problem
				$this->addError("No enough permission to create reports holder directory: {$reportsDirectory}");
			}
		}
		# Chain
		return $this;
	}

	/**
	* Use Model discover parameters for discovering awstats installation paramaters,
	* fill the passed form with the discovred values 
	* 
	* @param Forms\InstallationParametersForm Installation for to fill with the discovered data.
	* @return InstallerModel Chaining, returning $this
	*/
	public function discoverInstallationParameters(Forms\InstallationParametersForm & $form) {
		# Initiaolize
		$installsParamsAWStatsPath = $this->getDiscoverAWStatsScriptPath();
		$discoverDomain = $this->getDiscoverDomain();
		$awstats = new \ARV\Modules\Report\Model\AWStats($installsParamsAWStatsPath);
		# AWStats src path
		$awstatsSrcPath = "/usr/local/cpanel/src/3rdparty/gpl/awstats-{$awstats->getVersion()}";
		# Config File path
		$currentFileFiles = explode(DIRECTORY_SEPARATOR, __FILE__);
		$homeDir = $currentFileFiles[1];
		$installsParamsConfigFile = "/{$homeDir}/{$this->getDiscoverSystemUser()}/tmp/awstats/awstats.{$this->getDiscoverDomain()}.conf";
		# Build static script path
		$installsParamsBuildStaticScript = "{$awstatsSrcPath}/tools/awstats_buildstaticpages.pl";
		# Icons Directory path
		$installsParamsIconsDirectory = "{$awstatsSrcPath}/wwwroot/icon";
		# Fill Form with discovered data
		$form->getAWStatsScriptPath()->setValue($installsParamsAWStatsPath);
		$form->getBuildStaticPath()->setValue($installsParamsBuildStaticScript);
		$form->getConfigFilePath()->setValue($installsParamsConfigFile);
		$form->getIconsDirPath()->setValue($installsParamsIconsDirectory);
		$form->getDomain()->setValue($discoverDomain);
		# Chain
		return $this;
	}

	/**
	* Finalize the installation process by writing installation
	* flags to database.
	* 
	* This method should be called only when all operation has been processed
	* It won't effect calling this method if not all operations passed.
	* 
	* The method sets $this->installedVersion to equal $this->dbVersion
	* therefore force InstallerState Model to detect plugin installed.
	* 
	* @return Installer Chaining, returning $this
	*/
	public function & done() {
		# Write database version / Mark as insalled
		# Do that only if passed CREATE REPORT OPERATION
		if ($this->lastOperation == self::OPERATION_CREATE_REPORT) {
			# Write version number
			$this->installedVersion = $this->dbVersion;	
			# Set as last operation
			$this->lastOperation = self::OPERATION_WRITE_INSTALLATION_FLAGS;
		}
		# Chain
		return $this;
	}

	/**
	* State that the model is out of reset state.
	* 
	* Its simply sets $this->reset = false
	* 
	* @return Installer Chaining, returning $this
	*/
	public function enterReadyState() {
		# Unreset
		$this->reset = false;
		# Chain
		return $this;
	}

	/**
	* Get last successfuly executed operation.
	* 
	* The method returns last successfuly executed operation that is 
	* represent the current model operation
	* 
	* @return string Returns one of the Installer::OPERATION_[OPERAATION] constants
	*/
	public function getCurrentOperation() {
		return $this->lastOperation;
	}

	/**
	* Get Plugin database version as defined in
	* Plugin configuratrion file
	* 
	* @return string Plugin database version
	*/
	public function getDBVersion() {
		return $this->dbVersion;
	}
	
	/**
	* Get default (defined by ARV Plugin) AWStats program path
	* 
	* @return string Path to awstats path
	*/
	public function getDefaultDiscoverAWStatsScriptPath() {
		return '/usr/local/cpanel/3rdparty/bin/awstats.pl';
	}

	/**
	* Get Current server Domain.
	* 
	* It uses $_SERVER['HTTP_HOST'] as current Domain
	* 
	* @return string Returns $_SERVER['HTTP_HOST'] 
	*/
	public function getDefaultDiscoverDomain() {
		return $_SERVER['HTTP_HOST'];
	}

	/**
	* Get Default discover parameter system user.
	* 
	* The method is using current process user name as
	* current system user name.
	* 
	* @return string Current PHP process user
	*/
	public function getDefaultDiscoverSystemUser() {
		return get_current_user();
	}

	/**
	* Get Path to AWStats script discover parameter
	* 
	* @return string Path to AWStats prog path
	*/
	public function getDiscoverAWStatsScriptPath() {
		return $this->discoverAWStatsScript;
	}

	/**
	* Get Domain name discover parameter
	* 
	* @return string Domain name
	*/
	public function getDiscoverDomain() {
		return $this->discoverDomain;
	}

	/**
	* Get System user discover parameter
	* 
	* @return string System user discover parameter
	*/
	public function getDiscoverSystemUser() {
		return $this->discoverSystemUser;
	}

	/**
	* Get index file name used for disallowing listing
	* reports and report directories
	* 
	* @return string Index file name, currently its hard-coded
	*/
	public function getIndexFileName() {
		return $this->indexFileName;
	}
	
	/**
	* Get currently installed version number
	* 
	* @return string Installed Version number
	*/
	public function getInstalledVersion() {
		return $this->installedVersion;
	}
	
	/**
	* Get path to AWStats prog installation parameter
	* 
	* @return string Absolute path to AWStats prog
	*/
	public function getInstallsParamsAWstatsScriptPath() {
		return $this->installsParamsAWStatsPath;
	}
	
	/**
	* 
	* 
	*/
	public function getInstallsParamsBuildStaticScript() {
		return $this->installsParamsBuildStaticScript;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getInstallsParamsDomain() {
		return $this->installsParamsDomain;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getInstallsParamsIconsDirectory() {
		return $this->installsParamsIconsDirectory;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getInstallsParamsConfigFile() {
		return $this->installsParamsConfigFile;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getNoListIndexFileRelPath() {
		return $this->noListIndexFileRelPath;
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function & getReportViewerModel() {
		return $this->mvcServiceManager()->getModel('Viewer', 'Report');
	}

	/**
	* put your comment there...
	* 
	*/
	public function getReportsDirectoryPath() {
		return $this->reportsDirectoryPath;
	}

	/**
	* put your comment there...
	* 
	*/
	public function isAllProcessed() {
		return ($this->lastOperation == self::OPERATION_WRITE_INSTALLATION_FLAGS);
	}

	/**
	* put your comment there...
	* 
	* @param mixed $operation
	*/
	public function isCurrentOperation($operation) {
		return ($this->getCurrentOperation() == $operation);
	}

  /**
  * put your comment there...
  * 
  */
  protected function initialize() {
  	# Getting Plugin configuration
  	$factory =& $this->factory();
  	$plugin =& $factory->get('ARV\Plugin');
		$this->pluginConfig =& $plugin->getPluginConfig();
		$this->dbVersion = $this->pluginConfig['parameters']['dbVersion'];
		$this->installState = new InstallState($plugin);
		$this->reportsDirectoryPath 	= 'wp-content' . DIRECTORY_SEPARATOR . 'arv-reports';
		$this->noListIndexFileRelPath = 'Modules' . DIRECTORY_SEPARATOR . 
																		'Installer' . DIRECTORY_SEPARATOR . 
																		'Model' . DIRECTORY_SEPARATOR . 
																		'Installer' . DIRECTORY_SEPARATOR . $this->getIndexFileName();
  }

	/**
	* put your comment there...
	* 	
	*/
	public function isInstalled() {
		return $this->installState->isInstalled();
	}

	/**
	* put your comment there...
	* 
	*/
	public function isReady() {
		return !$this->reset;
	}

	/**
	* put your comment there...
	* 
	* @param Forms\InstallationParametersForm $form
	* @return {Forms\InstallationParametersForm|InstallerModel}
	*/
	public function & readInstallationParameters(Forms\InstallationParametersForm & $form) {
		# Fill form from stored state
		$form->getDomain()->setValue($this->getInstallsParamsDomain());
		$form->getAWStatsScriptPath()->setValue($this->getInstallsParamsAWstatsScriptPath());
		$form->getBuildStaticPath()->setValue($this->getInstallsParamsBuildStaticScript());
		$form->getConfigFilePath()->setValue($this->getInstallsParamsConfigFile());
		$form->getIconsDirPath()->setValue($this->getInstallsParamsIconsDirectory());
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $scriptPath
	* @param mixed $domainName
	* @param mixed $systemUser
	* @return InstallerModel
	*/
	public function setDiscoverParameters($scriptPath, $domainName, $systemUser) {
		# Set
		$this->discoverAWStatsScript =& $scriptPath;
		$this->discoverDomain =& $domainName;
		$this->discoverSystemUser =& $systemUser;
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $domain
	* @param mixed $scriptPath
	* @param mixed $buildStaticPath
	* @param mixed $configFilePath
	* @param mixed $iconsDir
	* @return InstallerModel
	*/
	public function setInstallationParameters($domain, $scriptPath, $buildStaticPath, $configFilePath, $iconsDir) {
		# Set
		$this->installsParamsDomain =& $domain;
		$this->installsParamsAWStatsPath =& $scriptPath;
		$this->installsParamsBuildStaticScript =& $buildStaticPath;
		$this->installsParamsConfigFile =& $configFilePath;
		$this->installsParamsIconsDirectory =& $iconsDir;
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param Forms\AWStatsDiscoverParametersForm $form
	* @return {Forms\AWStatsDiscoverParametersForm|InstallerModel}
	*/
	public function setInstallationParametersForm(Forms\InstallationParametersForm & $form) {
		# Set and Chain
		return $this->setInstallationParameters(
		  $form->getDomain()->getValue(),	
			$form->getAWStatsScriptPath()->getValue(),
			$form->getBuildStaticPath()->getValue(),
			$form->getConfigFilePath()->getValue(),
			$form->getIconsDirPath()->getValue()
		);
	}

}
