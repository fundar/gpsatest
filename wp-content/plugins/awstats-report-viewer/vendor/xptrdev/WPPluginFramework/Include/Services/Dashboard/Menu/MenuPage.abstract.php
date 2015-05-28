<?php
/**
* 
*/

namespace WPPFW\Services\Dashboard\Menu;

# Imports
use WPPFW\Services\ServiceObject;
use WPPFW\Services\IReachableServiceObject;
use WPPFW\Http\Url;

/**
* 
*/
abstract class MenuPageBase
extends ServiceObject
implements IMenuPage, IReachableServiceObject {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $capability;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $hookSlug;
	
	/**
	* 
	* 
	* @var mixed
	*/
	protected $parent;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $pageTitle;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $slug;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $title;
	
	/**
	* put your comment there...
	* 
	* @param mixed $title
	* @param mixed $pageTitle
	* @param mixed $capability
	* @param mixed $icon
	* @return MenuPageBase
	*/
	public function __construct($title, $pageTitle, $capability, $icon = null) {
		# Initialize
		$this->title = $title;
		$this->pageTitle = $pageTitle;
		$this->capability = $capability;
		$this->icon = $icon;
		# Generate slug
		$this->slug = strtolower(str_replace('\\', '-', get_class($this)));
	}

	/**
	* put your comment there...
	* 
	*/
	public final function & add(& $callback) {
		# Add menu item, store hook name
		$this->hookSlug = $this->addMenuItem($callback);
		# Chain
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $callback
	*/
	protected abstract function addMenuItem(& $callback);

	/**
	* put your comment there...
	* 
	*/
	public function getCapability() {
		return $this->capability;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getHookSlug() {
		return $this->hookSlug;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getPageTitle() {
		return $this->pageTitle;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getParent() {
		return $this->parent;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getRouteParams() {
		return array('page' => $this->getSlug());
	}

	/**
	* put your comment there...
	* 
	*/
	public function getSlug() {
		return $this->slug;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getTitle() {
		return $this->title;
	}

  /**
  * put your comment there...
  * 
  */
  public function getUrl() {
		# Return URL to service object
		return new Url(home_url('wp-admin/admin.php'), $this->getRouteParams());
  }

	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	public function setCapability($capability) {
		# Initialize
    $this->capability = $capability;
		# Chain
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	public function setPageTitle($pageTitle) {
		# Initialize
    $this->pageTitle = $pageTitle;
		# Chain
		return $this;		
	}

	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	public function setParent($parent) {
		# Initialize
    $this->parent = $parent;
		# Chain
		return $this;		
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $value
	*/
	public function setTitle($title) {
		# Initialize
    $this->title = $title;
		# Chain
		return $this;
	}
		
}