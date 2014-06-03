<?php
	
// logooos Widget
class logooos_widget extends WP_Widget
{
	function logooos_widget() {
		$widget_options = array(
		'classname'		=>		'logooos-widget',
		'description' 	=>		'Logos Widget'
		);
		
		parent::WP_Widget('logooos-widget', 'Logos', $widget_options);
	}
	
	function widget( $args, $instance ) {
		extract ( $args, EXTR_SKIP );
		$title = ( $instance['title'] ) ? $instance['title'] : '';
		$shortcode = ( $instance['shortcode'] ) ? $instance['shortcode'] : '[logooos]';
		echo $before_widget;
		
		if($title!='')
		{
			echo $before_title . $title . $after_title; 
		}
		
		echo do_shortcode( $shortcode );
		
		echo $after_widget;
		
	}
	
	function form( $instance ) {
		?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>" >Title:</label> 
                <input id="<?php echo $this->get_field_id('title'); ?>"
                        class="widefat"
                        name="<?php echo $this->get_field_name('title'); ?>"
                        value="<?php echo esc_attr( $instance['title'] ); ?>" type="text" />
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('shortcode'); ?>" >Shortcode:</label>
                <input id="<?php echo $this->get_field_id('shortcode'); ?>"
                        class="widefat"
                        name="<?php echo $this->get_field_name('shortcode'); ?>"
                        value="<?php echo esc_attr( $instance['shortcode'] ); ?>" type="text" />
            
        </p>
		
		<?php
	}
	
}
	
function logooos_widget_init() {
	register_widget("logooos_widget");
}
add_action('widgets_init','logooos_widget_init');

?>