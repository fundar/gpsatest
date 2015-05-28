<?php
/**
* 
*/

namespace WPPFW\HDT;

/**
* 
*/
abstract class WriterPrototype implements IWriterPrototype {
	
	/**
	* 
	*/
	const TRASNFORM_LAYER_IN = '';

	/**
	* 
	*/
	const TRASNFORM_LAYER_OUT = 'Out';
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $result = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $dataSource = null;
	
	/**
	* put your comment there...
	* 
	* @var IHTDDocument
	*/
	protected $document = null;

	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $instances = array();
	
	/**
	* put your comment there...
	* 
	* @var IWriterPrototype
	*/
	protected $parent = null;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $pipe;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $prototypes = array();
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $readerPrototype = null;

	/**
	* put your comment there...
	* 
	* @param IReaderPrototype $readerPrototype
	* @return {WriterPrototype|IReaderPrototype}
	*/
	public function __construct(IReaderPrototype & $readerPrototype = null) {
		# INitialize reader prototype
		$this->readerPrototype =& $readerPrototype;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param IWriterPrototype $prototype
	* @return WriterPrototype
	*/
	public function & addPrototypeAndChain($name, IWriterPrototype $prototype) {
		# Add prototype
		$this->prototypes[$name] =& $prototype;
		# Chaining
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param IWriterPrototype $prototype
	* @return WriterPrototype
	*/
	public function & addPrototype($name, IWriterPrototype $prototype) {
		# Add prototype
		$this->prototypes[$name] =& $prototype;
		# Chaining
		return $prototype;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getDataSource() {
		return $this->dataSource;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getDocument() {
		return $this->document;
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getInstances() {
		return $this->instances;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getParent() {
		return $this->parent;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getParentResult() {
		return $this->getParent()->getResult();
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getPipe() {
		return $this->pipe;
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function & getPrototype($name) {
		# CHeck existance
		if (!isset($this->prototypes[$name])) {
			####
		}
		# Returns
		return $this->prototypes[$name];
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function & getPrototypeInstance($name) {
		# getting all instances
		$instances =& $this->getPrototypeInstances($name);
		# Get only one.
		return $instances[0];
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function & getPrototypeInstances($name) {
		return $this->instances[$name];
	}

	/**
	* put your comment there...
	* 
	*/
	public function & getReaderPrototype() {
		return $this->readerPrototype;
	}
  
	/**
	* put your comment there...
	* 
	*/
	public function & getResult() {
		return $this->result;
	}
	
	/**
	* put your comment there...
	* 
	* @param IHTDDocument $document
	* @param mixed $pipe
	* @param IWriterPrototype $parent
	* @return {IWriterPrototype|WriterPrototype}
	*/
	public function & load(IHTDDocument & $document, & $pipe = null, IWriterPrototype & $parent = null) {
		# Initialize object
		$this->document =& $document;
		$this->parent =& $parent;
		$this->pipe =& $pipe;
		# Initialize
		$prototypes =& $this->prototypes;
		# Get all prototypes
		foreach ($prototypes as $prototypeName => $prototype) {
			/**
			* put your comment there...
			* 
			* @var IReaderPrototype
			*/
			$readerPrototype = 	$prototype->getReaderPrototype() ?
													$prototype->getReaderPrototype() :
													$document->getDefaultReaderPrototype();
			# Extend
			$this->readingPrototypeData($prototype);
			# Read data. Create write prototype instances only if
			# there is data available (STATIC call on tHE PROTOTYPE that should not BINDED as Instances below!!)
			$dataList = $readerPrototype->query($prototypeName, $this, $prototype);
			$dataList = !empty($dataList) ? $dataList : array();
			# Create instance for every data record available
			foreach ($dataList as $dataSource) {
				/**
				* Create prototype ionstance
				* 
				* @var IWriterPrototype
				*/
				$prototypeInstance = clone $prototype;
				/**
				* Create Reader prototype instance
				* 
				* @var IReaderPrototype
				*/
				$readerPrototypeInstance = clone $readerPrototype;
				$readerPrototypeInstance->bind($this, $prototypeInstance);
				# Set Writer Data source
				$prototypeInstance->setDataSource($dataSource)
				# Set readerprototype
													->setReaderPrototype($readerPrototypeInstance)
				# load prototype
													->load($document, $pipe, $this);
				# Add to prototypes list.
				$this->instances[$prototypeName][] = $prototypeInstance;
			}
		}
		# chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param IHTDDocument $document
	* @param mixed $data
	* @param mixed $pipe
	* @param IWriterPrototype $parent
	* @return {{IWriterPrototype|IWriterPrototype}
	*/
	public function & loadWithData(IHTDDocument & $document, & $data, & $pipe = null, IWriterPrototype & $parent = null) {
		# Set data
		$this->setDataSource($data);
		# Load
		return $this->load($document, $pipe, $parent);
	}
	
	/**
	* put your comment there...
	* 
	* @param mixed $layerName
	* @return WriterPrototype
	*/
	protected function & processPrototypes($layerName) {
		# Initialize
		$instances =& $this->getInstances();
		$beforeTransformmingPluginName = "{$layerName}_beforeInstanceTransform";
		$afterTransformmingPluginName = "{$layerName}_afterInstanceTransform";
		# Get all prototypes instances
		foreach ($instances as $prototypeName => $PrototypeInstances) {
			foreach ($PrototypeInstances as $instance) {
				# Before processing prototype
				if (method_exists($this, $beforeTransformmingPluginName)) {
					$this->$beforeTransformmingPluginName($instance, $prototypeName, $layerName);	
				}
				# Process instance
				$instance->transform($layerName);
				# After processing prototype
				if (method_exists($this, $afterTransformmingPluginName)) {
					$this->$afterTransformmingPluginName($instance, $prototypeName, $layerName);	
				}
			}
		}
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param WriterPrototype $instance
	* @return {WriterPrototype|WriterPrototype}
	*/
	protected function & readingPrototypeData(WriterPrototype & $instance) {
		# Get return instance
		return $instance;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $name
	*/
	public function & removePrototype($name) {
		# Get prototype
		$prototype =& $this->getPrototype($name);
		# Remove it
		unset($this->prototypes[$name]);
		# return prototype
		return $prototype;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $data
	*/
	public function & setDataSource($dataSource) {
		# Set
		$this->dataSource =& $dataSource;
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param IReaderPrototype $readerPrototype
	* @return {IReaderPrototype|WriterPrototype}
	*/
	public function & setReaderPrototype(IReaderPrototype $readerPrototype) {
		# Set
		$this->readerPrototype =& $readerPrototype;
		# Chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $layerName
	* @return WriterPrototype
	*/
	public function & transform($layerName) {
		# Process In
		$this->transformLayer(self::TRASNFORM_LAYER_IN, $layerName);
		# Process Plugged prototype
		$this->processPrototypes($layerName);
		# Process Out
		$this->transformLayer(self::TRASNFORM_LAYER_OUT, $layerName);
		# chain
		return $this;
	}

	/**
	* put your comment there...
	* 
	* @param mixed $direction
	* @param mixed $layerName
	* @return WriterPrototype
	*/
	public function & transformLayer($direction, $layerName) {
		# Layer method name
		$layerMethodName = "{$layerName}{$direction}";
		# Check existance
		if (method_exists($this, $layerMethodName)) {
			# Call layer
			$this->$layerMethodName();			
		}
		# Chain
		return $this;
	}

}