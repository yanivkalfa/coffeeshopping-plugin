<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/1/2015
 * Time: 9:01 PM
 */

class featuredProductsWidget extends WP_Widget {

    // Register widget with WordPress.
    function __construct() {
        parent::__construct(
            'featuredProductsWidget', // Base ID
            __( 'CoffeeShopping featured', 'text_domain' ), // Name
            array( 'description' => __( 'Featured products widget', 'text_domain' ), ) // Args
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
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
        }

        $myCartWidgetPageLink = get_permalink(get_option("cs_cart_p_id"));
        if (!$myCartWidgetPageLink){
            Utils::adminPreECHO("Can't get search page link", "myCartWidget.php ERROR:: ");
            echo Utils::getErrorCode("frontEnd", "widget", "myCartWidget", "7");
            return;
        }

        $cart = array(
            'productCount' => 0
        );
        if(isset($_SESSION['cart'])){
            $cart =  $_SESSION['cart']->getStats();
        }

        $cart['page'] = $myCartWidgetPageLink;
        Utils::getTemplate('myCartWidget', $cart);

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
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
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

        return $instance;
    }

} // class featuredProductsWidget

?>