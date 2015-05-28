<?php
/**
* 
*/

namespace WPPFW\Plugin;

# Imports
use WPPFW\Obj;
use WPPFW\Services\IServiceFrontFactory;
use WPPFW\MVC\IDispatcher;
use WPPFW\Services\ServiceObject;
use WPPFW\Services\ServiceBase;
use WPPFW\Http\HTTPResponse;

/**
* 
*/
abstract class PluginBase implements IServiceFrontFactory {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $config;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $directory;
	
	/**
	* put your comment there...
	* 
	* @var Obj\Factory
	*/
	protected $factory;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $file;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $fileName;
	
	/**
	* put your comment there...
	* 
	* @var Request
	*/
	protected $inputs;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $name;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $namespace;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $pluginConfig;
	
	/**
	* put your comment there...
	* 
	* @var \HttpResponse
	*/
	protected $response;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	private $url;
	
	/**
	* put your comment there...
	* 
	* @param mixed $file
	* @param mixed $namespace
	* @param mixed $config
	* @return PluginBase
	*/
	protected function __construct($file, PluginConfig $config) {
		# Initialize
		$pluginClass = get_class($this);
		$this->file =& $file;
		$this->fileName = basename($this->file);
		$this->directory = dirname($file);
		$this->name = basename($this->directory);
		$this->config =& $config;
		# getting namespace
		$pluginClassComponents = explode('\\', $pluginClass);
		$this->namespace = new Obj\PHPNamespace(reset($pluginClassComponents), dirname($file));
		$this->inputs = new Request($_GET, $_POST, $_REQUEST);
		$this->response = new HTTPResponse();
		$this->url = plugin_dir_url($file);
		$this->pluginConfig =& $config->getPlugin();
		# Load Plugin Factory
		$this->loadFactory();
		# Push GENARIC/ASBTRACTED Plugin instance into factory to 
		# be used by the Framework
		$factory =& $this->factory();
		$factory->setNamedInstance(__CLASS__, $this);
		# Push Plugin class
		$factory->setNamedInstance(get_class($this), $this);
	}

	/**
	* put your comment there...
	* 
	*/
	public function createSecurityToken() {
		return wp_create_nonce();
	}

	/**
	* put your comment there...
	* 
	* @param ServiceObject $serviceObject
	* @return ServiceObject
	*/
	public function & createServiceFront(ServiceObject & $serviceObject) {
		# Initialize
		$config =& $this->getConfig();
		$proxy =& $serviceObject->getProxy();
		# Load MVC and Service objects
		$this->loadMVCLayerConfiguration();
		# Get service configuration
		$serviceConfig =& $config->getService($serviceObject);
		# Get Front Proxy class
		$frontProxyClass = $serviceConfig['proxy']['frontClass'];
		# Create Front Proxy object
		$frontProxy = new $frontProxyClass();
		# Getting Service MVC configuration (target, sructure, etc...)
		$frontProxy->proxy($this, $serviceConfig);
		# Create Service Object Router.
		$router =& $this->createServiceObjectRouter($serviceObject, $serviceConfig);
		# Create Service Front object
		$serviceFrontClass = $serviceConfig['serviceFront'];
		$serviceFront = new $serviceFrontClass(
			$this->factory(),
			$this->input(),
			$this->getHTTPResponse(),
			$frontProxy->getStructure(), 
			$frontProxy->getTarget(),
			$frontProxy->getNames(),
			$router
		);
		# return servie fron
		return $serviceFront;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $serviceObject
	* @param mixed $serviceConfig
	*/
	public function & createServiceObjectRouter(& $serviceObject, & $serviceConfig = null) {
		# Get Service configuration object if Service object instance is passed
		if ($serviceConfig === null) {
			$serviceConfig =& $this->getConfig()->getServiceHomeProxy($serviceObject);
		}
		# Creating router
		$router = new $serviceConfig['routerClass']($this, $serviceObject, $serviceConfig);
		# Return router
		return $router;
	}

	/**
	* put your comment there...
	* 
	* @param IDispatcher $serviceFront
	* @return IDispatcher
	*/
	public function & dispatch(IDispatcher & $serviceFront) {
		# Dispatch the call
		return $serviceFront->dispatch();
	}

	/**
	* put your comment there...
	* 
	* @return Obj\Factory
	*/
	public function & factory() {
		return $this->factory;
	}

	/**
	* put your comment there...
	* 
	* @return PluginConfig
	*/
	public function & getConfig() {
		return $this->config;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getDirectory() {
		return $this->directory;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getFile() {
		return $this->file;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getFileName() {
		return $this->fileName;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getHTTPResponse() {
		return $this->response;
	}

	/**
	* put your comment there...
	* 
	*/
	public function getName() {
		return $this->name;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getNamespace() {
		return $this->namespace;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getPluginConfig() {
		return $this->pluginConfig;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function getURL() {
		return $this->url;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & input() {
		return $this->inputs;
	}

	/**
	* put your comment there...
	* 
	*/
	protected function loadFactory() {
		# Get Plugin parameters
		$plugin =& $this->getPluginConfig();
		$namespace =& $this->getNamespace();
		$pluginParameters = $plugin['parameters'];
		# Load factory object
		$this->factory = new $pluginParameters['factoryClass'](
			$namespace->getNamespace() . '\\' . $pluginParameters['factoryNamespace']
			);
	}

	/**
	* put your comment there...
	* 
	*/
	protected function loadMVCLayerConfiguration() {
		# INitialize vars
		$config =& $this->getConfig();
		# Load MVC Configuration objects
		$config->loadMVCObjects()
		# Load Services Configuration objects
					 ->loadServices();
	}

}