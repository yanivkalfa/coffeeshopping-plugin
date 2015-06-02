<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/1/2015
 * Time: 8:30 PM
 */

class searchWidget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'searchWidget', // Base ID
            __( 'CoffeeShopping search', 'text_domain' ), // Name
            array( 'description' => __( 'Store searching widget', 'text_domain' ), ) // Args
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

        $searchFormAction = (!empty($instance['searchformaction'])) ? $instance['searchformaction'] : '/search';
        ?>

        <form role="search" method="get" id="searchform" class="form-inline" action="<?php echo esc_url( home_url( $searchFormAction ) ); ?>">
            <input type="text" name="search-product" id="search-product" class="searchinput" placeholder="<?php esc_attr_e( 'Search', 'rt_gantry_wp_lang' ); ?>" value="<?php echo wp_kses( get_query_var('search-product'), null ); ?>"/>

            <input type="submit" class="btn btn-primary" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'rt_gantry_wp_lang' ); ?>" />

        </form>

        <?php


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
        $searchFormAction = ! empty( $instance['searchformaction'] ) ? $instance['searchformaction'] : __( 'Form action', 'text_domain' );

        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'searchformaction' ); ?>"><?php _e( 'Form action:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'searchformaction' ); ?>" name="<?php echo $this->get_field_name( 'searchformaction' ); ?>" type="text" value="<?php echo esc_attr( $searchFormAction ); ?>">
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
        $instance['searchformaction'] = ( ! empty( $new_instance['searchformaction'] ) ) ? strip_tags( $new_instance['searchformaction'] ) : '';

        return $instance;
    }

} // class searchWidget


/* ------------------- DEAD OR UNUSED CODE ---------------- */


/*
            We should have some "advanced search" option to use categories/stores. meanwhile - open search.

            <select title="<?php esc_attr_e( 'Select a category for search', 'rt_gantry_wp_lang' ); ?>" class="" size="1" id="pcats" name="pcats">
                    <option selected="selected" value="0">All Categories</option>
                    <option value="20081">Antiques</option>
                    <option value="550">Art</option>
                    <option value="2984">Baby</option>
                    <option value="267">Books</option>
                    <option value="12576">Business &amp; Industrial</option>
                    <option value="625">Cameras &amp; Photo</option>
                    <option value="15032">Cell Phones &amp; Accessories</option>
                    <option value="11450">Clothing, Shoes &amp; Accessories</option>
                    <option value="11116">Coins &amp; Paper Money</option>
                    <option value="1">Collectibles</option>
                    <option value="58058">Computers/Tablets &amp; Networking</option>
                    <option value="293">Consumer Electronics</option>
                    <option value="14339">Crafts</option>
                    <option value="237">Dolls &amp; Bears</option>
                    <option value="11232">DVDs &amp; Movies</option>
                    <option value="6000">eBay Motors</option>
                    <option value="45100">Entertainment Memorabilia</option>
                    <option value="172008">Gift Cards &amp; Coupons</option>
                    <option value="26395">Health &amp; Beauty</option>
                    <option value="11700">Home &amp; Garden</option>
                    <option value="281">Jewelry &amp; Watches</option>
                    <option value="11233">Music</option>
                    <option value="619">Musical Instruments &amp; Gear</option>
                    <option value="1281">Pet Supplies</option>
                    <option value="870">Pottery &amp; Glass</option>
                    <option value="10542">Real Estate</option>
                    <option value="316">Specialty Services</option>
                    <option value="888">Sporting Goods</option>
                    <option value="64482">Sports Mem, Cards &amp; Fan Shop</option>
                    <option value="260">Stamps</option>
                    <option value="1305">Tickets &amp; Experiences</option>
                    <option value="220">Toys &amp; Hobbies</option>
                    <option value="3252">Travel</option>
                    <option value="1249">Video Games &amp; Consoles</option>
                    <option value="99">Everything Else</option>
            </select>

            <label for="storesrca_ebay"><?php esc_attr_e( 'eBay', 'rt_gantry_wp_lang' ); ?>
            <input type="checkbox" id="storesrca_ebay" name="storesrc[]" value="ebay">&nbsp;</label>&nbsp;
            <label for="storesrc_ali"><?php esc_attr_e( 'AliExpress', 'rt_gantry_wp_lang' ); ?>
            <input type="checkbox" id="storesrc_ali" name="storesrc[]" value="aliexpress">&nbsp;</label>&nbsp;
            */


/* ------------------- DEAD OR UNUSED CODE ---------------- */
?>