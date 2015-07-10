<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/1/2015
 * Time: 8:30 PM
 */

class templateLoaderWidget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'templateLoaderWidget', // Base ID
			__( 'CoffeeShopping templates', 'coffee-shopping' ), // Name
			array( 'description' => __( 'Load a theme template', 'coffee-shopping' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo str_ireplace("templateloaderwidget", $instance['templatename']."widget", $args['before_widget']);

		if ( ! empty( $instance['templatename'] ) ) {
			Utils::getTemplate($instance['templatename'], null, "theme/");
		}

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$templatename = ! empty( $instance['templatename'] ) ? $instance['templatename'] : '';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'templatename' ); ?>"><?php _e( 'Template:','coffee-shopping' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'templatename' ); ?>" name="<?php echo $this->get_field_name( 'templatename' ); ?>" type="text" value="<?php echo esc_attr( $templatename ); ?>">
		</p>
	<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['templatename'] = ( ! empty( $new_instance['templatename'] ) ) ? strip_tags( $new_instance['templatename'] ) : '';
		return $instance;
	}

}


/* ------------------- DEAD OR UNUSED CODE ---------------- */

/* ------------------- DEAD OR UNUSED CODE ---------------- */
?>