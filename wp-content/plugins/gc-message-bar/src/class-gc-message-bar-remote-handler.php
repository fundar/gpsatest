<?php
if(!class_exists("Gc_Message_Bar_Remote_Handler")){
	class Gc_Message_Bar_Remote_Handler extends Gc_MessageBar_Remote_Handler{
		protected $_namespace;
		public function __construct($namespace){
			parent::__construct();
			$this->_namespace = $namespace;
			$this->add_handler("add_metrix_code",new Gc_Message_Bar_Addmetrixcode_Action($namespace));
		}
	}
}
