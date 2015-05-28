<?php
/**
* 
*/

namespace WPPFW\HDT\XML;

# Imports
use WPPFW\HDT;

/**
* 
*/
class SimpleXMLReaderPrototype extends HDT\ReaderPrototype {

	/**
	* put your comment there...
	* 
	* @param \SimpleXMLElement $node
	* @return \SimpleXMLElement
	*/
	public function & getAttributesArray() {
		# Initialize
		$writer =& $this->getWriter();
		$node =& $writer->getDataSource();
		# Cast to array
		$attributesArray = (array) $node->attributes();
		# Get array values
		return $attributesArray['@attributes'];
	}

	/**
	* put your comment there...
	* 
	* @param mixed $prototypeName
	* @param IWriterPrototype $parent
	* @param {IWriterPrototype|IWriterPrototype} $writer
	* @return {IWriterPrototype|IWriterPrototype}
	*/
	public function & query($prototypeName, HDT\IWriterPrototype & $parent, HDT\IWriterPrototype & $writer) {
		# Initialize
		$tagName = $writer->getTagName();
		$nsPrefix = $writer->getNamespacePrefix();
		$nsUri = $writer->getNamespaceURI();
		/**
		* put your comment there...
		* 
		* @var SimpleXMLElement
		*/
		$parentNode =& $parent->getDataSource();
		# Register namespace
		$parentNode->registerXPathNamespace($nsPrefix, $nsUri);
		# Query
		$dataList = $parentNode->xpath("{$nsPrefix}:{$tagName}");
		# Return data list
		return $dataList;
	}

}