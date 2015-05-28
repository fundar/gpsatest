<?php
/**
* 
*/

namespace ARV\Modules\Installer\Controller\Installer;

# Controller Framework
use WPPFW\MVC\Controller\Controller;

# Installation form
use ARV\Modules\Installer\Model\Forms\AWStatsDiscoverParametersForm;
use ARV\Modules\Installer\Model\Forms\InstallationParametersForm;
use ARV\Modules\Installer\Model\Forms\ResetParametersForm;

# Models
use ARV\Modules\Installer\Model\InstallerModel;

/**
* 
*/
class InstallerController extends Controller {

	/**
	* Discover AWStats Parameters action.
	* 
	* The action is initially check whether if the awstats parameters has been
	* discovered yet. If not yet discovered or has been reseted it would automatically discover
	* awstats parameters and save it at model state.
	* 
	* It also serves the discover request taken by user through
	* discvoer form.
	* 
	* Discover Parameters is an array holds the data as following:
	* - scriptPath => PATH TO AWStats program
	* - domain => DOMAIN NAME used to discover AWStats configuration file name
	* - systemUser => Host System User used for discovering paths
	* 
	* @param array Discover parameters
	* 
	* @return void
	*/
	protected function indexAction() {
		# Initialize
		$input =& $this->input();
		$discoverInstallationParams = true;
		$discoverForm = new AWStatsDiscoverParametersForm();
		$installationForm = new InstallationParametersForm();
		$resetForm = new ResetParametersForm();
		/**
		* put your comment there...
		* 
		* @var InstallerModel
		*/
		$installerModel =& $this->getModel();
		# Initial discover or user submission discover
		if (!$input->isPost()) {
			# Set Model Discover parameters if not yet set
			if (!$installerModel->isReady()) {
				# Set default data
				$installerModel->setDiscoverParameters(
					$installerModel->getDefaultDiscoverAWStatsScriptPath(),
					$installerModel->getDefaultDiscoverDomain(),
					$installerModel->getDefaultDiscoverSystemUser()
				)
				# Discover installation parameters
				# Fill Installation parameters form
				->discoverInstallationParameters($installationForm)
				# Save discovered parameters @ model state
				->setInstallationParametersForm($installationForm)
				# Enter ready state
				->enterReadyState();
			}
			else {
				# Fill installation parameters form from model state
				$installerModel->readInstallationParameters($installationForm);
				# Validate installation parameters form so that
				# view template could display invalid errors.
				# this is useful when returned back from install action
				# when the installation form is invalidated!
				$installationForm->validate();
			}
			# Fill discover form
			$discoverForm->getAWStatsScript()->setValue($installerModel->getDiscoverAWStatsScriptPath());
			$discoverForm->getDomain()->setValue($installerModel->getDiscoverDomain());
			$discoverForm->getSystemUser()->setValue($installerModel->getDiscoverSystemUser());
		}
		else {
			# Fill discover form
			$discoverForm->setValue($input->post()->getArray());
			# Check if authorized
			if ($discoverForm->isAuthorized()) {
				# Validate discover form
				if ($discoverInstallationParams = $discoverForm->validate()) {
					# Set discover parameters through user form
					$installerModel->setDiscoverParameters(
						$discoverForm->getAWStatsScript()->getValue(),
						$discoverForm->getDomain()->getValue(),
						$discoverForm->getSystemUser()->getValue()
					)
					# Discover installation parameters
					->discoverInstallationParameters($installationForm)
					# Save discovered parameters @ model state
					->setInstallationParametersForm($installationForm);
				}
			}
			else {
				 # Token not authorized
				$installerModel->addError('Not authorized to take such an action!! If you believe this is not true please refresh your page and try again.');
			}
		}
		# Re-new Form security tokens
		$securityToken = $this->createSecurityToken();
		$discoverForm->getSecurityToken()->setValue($securityToken);
		$installationForm->getSecurityToken()->setValue($securityToken);
		$resetForm->getSecurityToken()->setValue($securityToken);
		# Pass Discover form to view
		return (object) array(
			'discoverForm' => $discoverForm, 
			'installationForm' => $installationForm,
			'resetForm' => $resetForm
		);
	}

	/**
	* Take awstats installation parameters, validate, save them
	* ,finalize the installation process and finally display the success
	* screen.
	* 
	* AWStats installatyion parameters:
	* domain => Domain name to create awstats report for
	* scriptPath => Path to awstats program
	* buildStaticPath => Path to awstats build static script
	* configFile => Path to awstats config file for the specified domain
	* iconsDir => Path to awstats src icons directory
	* 
	* @param array AWStats installation parameters 
	* @return void
	*/
	protected function installAction() {
		# Initialize
		$installationForm = new InstallationParametersForm();
		$input =& $this->input();
		$route =& $this->router();
		/**
		* put your comment there...
		* 
		* @var ARV\Modules\Installer\Model\InstallerModel
		*/
		$installerModel =& $this->getModel();
		# Read installation parameters values
		$installationForm->setValue($input->post()->getArray());
		# Save installation parameters at model state
		$installerModel->setInstallationParametersForm($installationForm);
		# Check if authorized
		if ($installationForm->isAuthorized()) {
			# Validate install parameters
			if ($installationForm->validate()) {
				# Create Report holder directory
				$installerModel->createReportsDirectory()
				# Create Report
				->createReport()
				# Write installation flags to database state
				->done();
				# If all installation steps passed successfully then start to create report
				# for the first time
				if (!$installerModel->isAllProcessed()) {
					# Get back to installation form and show error messages
					# produced by the instllation model
					$this->redirect($route->routeAction());				
				}
			}
			else {
				# Go to index action, display form errors, allow
				# user to repeat.
				$this->redirect($route->routeAction());
			}
		}
		else {
			# Not authorized
			$installerModel->addError('Not authorized to take such an action!! If you believe this is not true please refresh your page and try again.');
			# Go to index action, display form errors, allow
			# user to repeat.
			$this->redirect($route->routeAction());
		}
		# Display success page
		return $installationForm;
	}

	/**
	* Reset both discover parameters and Installation parameters
	* to defaults that comes with ARV installer.
	* 
	* The action is to remove all saved model state for both
	* discvoer and installation parameters and redirect back to index action
	* therefore detecting that no parameters has been discovered yet causing 
	* index action to set discover parametrs from internal default values as defined 
	* in the model and therefore discover installation parameters.
	* 
	* @return void
	*/
	protected function resetAction() {
		# Initialize
		$model =& $this->getModel();
		$router =& $this->router();
		$input =& $this->input();
		$resetForm = new ResetParametersForm();
		# Fill reset form 
		$resetForm->setValue($input->post()->getArray());
		# Check if authorized
		if ($resetForm->isAuthorized()) {
			# Reset model state.
			$model->clearState();
		}
		else {
			# Report error
			$model->addError('Not authorized to take such an action!! If you believe this is not true please refresh your page and try again.');
		}
		# Discover installation parameters by using default disocoverig (recycle)
		$this->redirect($router->routeAction());
	}

} # End class