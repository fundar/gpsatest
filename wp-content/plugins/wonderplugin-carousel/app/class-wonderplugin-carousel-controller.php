<?php 

require_once 'class-wonderplugin-carousel-model.php';
require_once 'class-wonderplugin-carousel-view.php';

class WonderPlugin_Carousel_Controller {

	private $view, $model;

	function __construct() {

		$this->model = new WonderPlugin_Carousel_Model($this);	
		$this->view = new WonderPlugin_Carousel_View($this);
		$this->init();
	}
	
	function init() {
		
		$engine = array("WordPress Carousel Plugin", "WordPress Image Carousel Plugin", "WordPress Image Scroller Plugin");
		$option_name = 'wonderplugin-carousel-engine';
		if ( get_option( $option_name ) == false )
			update_option( $option_name, $engine[array_rand($engine)] );
	}
	
	function add_metaboxes()
	{
		$this->view->add_metaboxes();
	}
	
	function show_overview() {
		
		$this->view->print_overview();
	}
	
	function show_items() {
	
		$this->view->print_items();
	}
	
	function add_new() {
		
		$this->view->print_add_new();
	}
	
	function show_item()
	{
		$this->view->print_item();
	}
	
	function edit_item()
	{
		$this->view->print_edit_item();
	}
	
	function generate_body_code($id, $has_wrapper) {
		
		return $this->model->generate_body_code($id, $has_wrapper);
	}
	
	function delete_item($id)
	{
		return $this->model->delete_item($id);
	}
	
	function clone_item($id)
	{
		return $this->model->clone_item($id);
	}
	
	function save_item($item)
	{
		return $this->model->save_item($item);	
	}
	
	function get_list_data() {
	
		return $this->model->get_list_data();
	}
	
	function get_item_data($id) {
		
		return $this->model->get_item_data($id);
	}
}