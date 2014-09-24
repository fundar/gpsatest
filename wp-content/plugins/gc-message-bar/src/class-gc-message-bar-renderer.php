<?php
if(class_exists("Gc_Message_Bar_Renderer")){
	return;
}
class Gc_Message_Bar_Renderer extends Gc_MessageBar_Abstract_Renderer{
    protected $options;
	const GC_HEIGHT = 40;

    public function __construct($namespace, $controller){
        parent::__construct($namespace,$controller);
		$this->options = $controller->options;
	}
	
	public function render($params) {
		$content = "<script type=\"text/javascript\">var gc_height = ".self::GC_HEIGHT."; gc_status = \"close\"; gc_animating = false;</script>";
		$renderer = new Gc_Message_Bar_Content_Renderer($this->options, $this->namespace); 
		$content .= $renderer->render();
		echo $content;
	}
	
	public function render_no_anim() {
		$content = '';
		$renderer = new Gc_Message_Bar_Content_Renderer($this->options, $this->namespace); 
		$content .= $renderer->render_no_anim();

		echo $content;
	}
}
