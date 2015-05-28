<?php
/**
* 
*/

namespace ARV\Modules\Report\Controller\Viewer;

# Imoprts
use WPPFW\MVC\Controller\Controller;

/**
* 
*/
class ViewerController extends Controller {

	/**
	* put your comment there...
	* 
	*/
	public function createAction() {
		# Initialize
		$model =& $this->getModel();
		$router =& $this->router();
		$input =& $this->input();
		$reportForm =& $model->getReportForm();
		# Check if authorized
		$reportForm->setValue($input->get()->getArray());
		if ($reportForm->isAuthorized()) {
			# Generate new report Id
			$model->generateRID()
			# Create new report
			->createReport();
		}
		else {
			# Not authorized
			$model->addError('Not authorized to take such an action!! If you believe this is not true please refresh your page and try again.');
		}
		# Redirect to index, display report
		$this->redirect($router->routeAction());
	}

	/**
	* put your comment there...
	* 
	*/
	public function deleteAction() {
		# Initialize
		$model =& $this->getModel();
		$router =& $this->router();
		$input =& $this->input();
		$reportForm =& $model->getReportForm();
		# Check if authorized
		$reportForm->setValue($input->get()->getArray());
		if ($reportForm->isAuthorized()) {
			# Delete report
			$model->deleteReport();
		}
		else {
			# Not authorized
			$model->addError('Not authorized to take such an action!! If you believe this is not true please refresh your page and try again.');
		}
		# Go to index
		$this->redirect($router->routeAction());
	}
	
	/**
	* put your comment there...
	* 
	*/
	protected function indexAction() {
		# Initialize
		$model =& $this->getModel();
		$input =& $this->input()->get();
		$reportForm =& $model->getReportForm();
		# Renew security token
		$reportForm->getSecurityToken()->setValue($this->createSecurityToken());
		# Point to NoReport Template if no report yet defined
		if (!$model->hasReport()) {
			$this->mvcTarget()->setLayout('NoReport');	
		}
		else {
			# If not report file specifiyied use model state
			if ($file = $input->get('file')) {
				# Display report file as requested
				$model->setReportFile($file);	
			}
		}
		# Return Model
		return $model;
	}

	/**
	* put your comment there...
	* 
	*/
	public function regenerateAction() {
		# Initialize
		$model =& $this->getModel();
		$router =& $this->router();
		$input =& $this->input();
		$reportForm =& $model->getReportForm();
		# Check if authorized
		$reportForm->setValue($input->get()->getArray());
		if ($reportForm->isAuthorized()) {
			# Delete Reoprt
			$model->deleteReport();
			# Generate new reoprt ID
			$model->generateRID()
			# Create Report
			->createReport();
		}
		else {
			# Not authorized
			$model->addError('Not authorized to take such an action!! If you believe this is not true please refresh your page and try again.');
		}
		# Redirect to index
		$this->redirect($router->routeAction());
	}

	/**
	* put your comment there...
	* 
	*/
	public function updateAction() {
		# INitialize
		$router =& $this->router();
		$model =& $this->getModel();
		$input =& $this->input();
		$reportForm =& $model->getReportForm();
		# Check if authorized
		$reportForm->setValue($input->get()->getArray());
		if ($reportForm->isAuthorized()) {
			# Update Report
			$model->updateReport();
		}
		else {
			# Not authorized
			$model->addError('Not authorized to take such an action!! If you believe this is not true please refresh your page and try again.');
		}
		# Go to index
		$this->redirect($router->routeAction());
	}
	
} # End class