<?php
/**
* 
*/

namespace WPPFW\Forms\Fields;

# Important
use WPPFW\Forms\Types\IType;
use WPPFW\Forms\IForm;

/**
* 
*/
abstract class FormFieldBase implements IField {
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $filters;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $form;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $name;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $rules;
	
	/**
	* put your comment there...
	* 
	* @var mixed
	*/
	protected $type;
	
	/**
	* put your comment there...
	* 
	* @param mixed $name
	* @param IType $type
	* @return {FormField|IType}
	*/
	public function __construct($name, IType $type) {
		# Initialize
		$this->name =& $name;
		$this->type =& $type;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & getForm() {
		return $this->form;
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
	* @param IForm $form
	* @return IForm
	*/
	protected function & setForm(IForm & $form) {
		# Set form
		$this->form =& $form;
		# Chain
		return $this;
	}
	
	/**
	* put your comment there...
	* 
	*/
	public function & type() {
		return $this->type;
	}

}
