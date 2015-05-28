<?php
/**
* 
*/

namespace WPPFW\MVC\View;

/**
* 
*/
abstract class TemplateView extends Base {
	
	/**
	* put your comment there...
	* 
	*/
	public function __toString() {
		# Return content
		return $this->render();
	}

	/**
	* put your comment there...
	* 
	*/
	protected function getTemplateFilePath() {
		# Initialize
		$target =& $this->mvcTarget();
		$structure =& $this->mvcStructure();
		$namespace =& $structure->getRootNS();
		# Use Action name as layout or use default if overrided!
		$layoutFile = $target->getLayout() ? $target->getLayout() : $target->getAction();
		$layoutExtension = strtolower($target->getFormat());
		# Layout paht compoennet
		$layoutPath[] = $namespace->getPath();
		$layoutPath[] = $structure->getModule();
		$layoutPath[] = $target->getModule();
		$layoutPath[] = $structure->getView();
		$layoutPath[] = $target->getView();
		$layoutPath[] = 'Templates';
		$layoutPath[] = "{$layoutFile}.{$layoutExtension}"; # Layout file
		# Layout file path
		$layoutPath = implode(DIRECTORY_SEPARATOR, $layoutPath);
		# Return path
		return $layoutPath;
	}

	/**
	* put your comment there...
	* 
	*/
	protected function preRender() {;}
	
	/**
	* put your comment there...
	* 
	*/
	protected function render() {
		# Pre redner 
		$this->preRender();
    # Get template file path
    $layoutPath = $this->getTemplateFilePath();
		# Open Output buffer
		ob_start();
		# Get file content
		require $layoutPath;
		$content = ob_get_clean();
		# Returtns
		return $content;
	}
	
}