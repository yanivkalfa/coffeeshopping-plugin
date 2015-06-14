<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/1/2015
 * Time: 9:01 PM
 */

class myCartWidget extends WP_Widget {

    // Register widget with WordPress.
    function __construct() {
        parent::__construct(
            'myCartWidget', // Base ID
            __( 'CoffeeShopping my cart', 'text_domain' ), // Name
            array( 'description' => __( 'My cart widget', 'text_domain' ), ) // Args
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
        echo $args['before_widget'];
        $cart =  $_SESSION['cart']->getStats();
        $cart['page'] = isset($instance['page']) ? $instance['page'] : 'cart';
        Utils::getTemplate('cartHead', $cart);
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $page = ! empty( $instance['page'] ) ? $instance['page'] : __( 'Cart Page', 'text_domain' );
    ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'page' ); ?>"><?php _e( 'Cart Page:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'page' ); ?>" name="<?php echo $this->get_field_name( 'page' ); ?>" type="text" value="<?php echo esc_attr( $page ); ?>">
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
        $instance['page'] = ( ! empty( $new_instance['page'] ) ) ? strip_tags( $new_instance['page'] ) : '';

        return $instance;
    }

} // class myCartWidget

?>