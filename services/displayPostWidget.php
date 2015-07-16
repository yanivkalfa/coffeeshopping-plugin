<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 7/16/2015
 * Time: 3:39 AM
 */

class displayPostWidget extends WP_Widget {

    // Register widget with WordPress.
    function __construct() {
        parent::__construct(
            'displayPostWidget', // Base ID
            __( 'CoffeeShopping posts', 'coffee-shopping' ), // Name
            array( 'description' => __( 'Display posts anywhere', 'coffee-shopping' ), ) // Args
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

        $post = get_post($instance['postid']);
        echo $post->post_content;

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = !empty( $instance['title'] ) ? $instance['title'] : '';
        $postid = !empty( $instance['postid'] ) ? $instance['postid'] : '';

        $posts = get_posts( array("orderby" => "title") );
        ?>

        <p class="displaypostwidget">
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
            </p>

            <p>
                <?php _e( 'Choose the post to display:', 'coffee-shopping' ); ?>
            </p>
            <select class="widefat postselect" id="<?php echo $this->get_field_id( 'postid' ); ?>" name="<?php echo $this->get_field_name( 'postid' ); ?>">
                <?php
                foreach( $posts as $post ){
                    $selected = ($post->ID==$postid)? ' selected="selected"' : "";
                    ?>
                    <option value="<?php echo $post->ID; ?>"<?php echo $selected;?>><?php echo $post->post_title; ?></option>
                <?php } ?>
            </select>
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
        $instance['postid'] = ( ! empty( $new_instance['postid'] ) ) ? strip_tags( $new_instance['postid'] ) : '';

        return $instance;
    }

} // class myCartWidget