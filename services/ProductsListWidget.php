<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/1/2015
 * Time: 9:01 PM
 */

class ProductsListWidget extends WP_Widget {

    // Register widget with WordPress.
    function __construct() {
        parent::__construct(
            'ProductsListWidget', // Base ID
            __( 'CoffeeShopping Lists', 'coffee-shopping' ), // Name
            array( 'description' => __( 'Products List widget', 'coffee-shopping' ), ) // Args
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
        if (!isset($instance['type'])){
            return false;
        }

        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
        }

        $result = productRecommendations::getProductListByType($instance);
        if ($result["result"]!="OK") {
            // Failed to get the products.
            Utils::adminPreECHO(__( "widget::productRecommendations::getProductListByType(...) failed!", 'coffee-shopping' ), __( "widget ERROR:: ", 'coffee-shopping' ));
            Utils::adminPreECHO($result);
            $scope = array(
                "errorsText" => "Failed to load the products, please check your details!"
            );
            Utils::getTemplate('ProductsListError', $scope);

        } else {
            if (count($result["output"])==0){
                Utils::adminPreECHO("Results 'OK' but nothing retrieved from server!");
                return false;
            }

            // Everything is OK - Load the featured products template.
            $scope = array(
                'products' => $result["output"],
            );
            Utils::getTemplate('ProductsList', $scope);
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
        // Get defaults:
        $title = !empty( $instance['title'] ) ? $instance['title'] : '';
        $type = !empty( $instance['type'] ) ? $instance['type'] : 'savedlist';
        $api = !empty( $instance['api'] ) ? $instance['api'] : 'ebay';
        $limit = !empty( $instance['limit'] ) ? $instance['limit'] : 20;
        $catid = !empty( $instance['catid'] ) ? $instance['catid'] : 0;
        $itemid = !empty( $instance['itemid'] ) ? $instance['itemid'] : "";
        $listname = !empty( $instance['listname'] ) ? $instance['listname'] : "";
        $listnames = ProductsLists::getSavedProductsLists();
        $specificids = !empty( $instance['specificids'] ) ? $instance['specificids'] : "";

        $Opts = array(
            "savedlist" => array("listname_option"),
            "mostwatched" => array("api_option","category_option","limit_option"),
            "relateditems" => array("api_option","category_option","limit_option", "item_option"),
            "similaritems" => array("api_option","category_option","limit_option", "item_option"),
            "specificids" => array("api_option","ids_option"),
        );


        ?>
        <style type="text/css">
            .productlistwidget div{margin: 10px 0px;}
        </style>
        <div class="productlistwidget">

            <div>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
            </div>

            <div id="commonopts">
                <div>
                    <label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e( 'List type:' ); ?></label>
                    <select class="widefat typeselect" id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>">
                        <option value="savedlist"<?php echo ($type=="savedlist")? ' selected="selected"':""?>>Saved list</option>
                        <option value="mostwatched"<?php echo ($type=="mostwatched")? ' selected="selected"':""?>>Most watched items</option>
                        <option value="relateditems"<?php echo ($type=="relateditems")? ' selected="selected"':""?>>Related items</option>
                        <option value="similaritems"<?php echo ($type=="similaritems")? ' selected="selected"':""?>>Similar items</option>
                        <option value="specificids"<?php echo ($type=="specificids")? ' selected="selected"':""?>>Specific IDs</option>
                    </select>
                </div>

                <div id="api_option" class="widgetopt<?php echo (!in_array("api_option",$Opts[$type])) ? " displaynone":"";?>">
                    <label for="<?php echo $this->get_field_id( 'api' ); ?>"><?php _e( 'Store API:' ); ?></label>
                    <select class="widefat api" id="<?php echo $this->get_field_id( 'api' ); ?>" name="<?php echo $this->get_field_name( 'api' ); ?>" value="<?php echo esc_attr( $api ); ?>">
                        <option value="ebay" selected="selected">eBay</option>
                        <option value="aliexp" disabled="disabled">AliExpress</option>
                    </select>
                </div>

                <div id="category_option" class="widgetopt<?php echo (!in_array("category_option",$Opts[$type])) ? " displaynone":"";?>">
                    <label for="<?php echo $this->get_field_id( 'catid' ); ?>"><?php _e( 'Category ID:' ); ?></label>
                    <select class="widefat catid" id="<?php echo $this->get_field_id('catid'); ?>" name="<?php echo $this->get_field_name('catid'); ?>" value="<?php echo esc_attr( $catid ); ?>">
                        <option value="">...</option>
                        <?php
                        foreach (CSCons::get("ebayCategories") as $e_catid => $e_cat){
                            $selected = ($e_catid==$catid)? ' selected="selected"' : "";
                            ?>
                            <option value="<?php echo esc_attr( $e_catid ); ?>"<?php echo $selected;?>><?php echo $e_cat;?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>

                <div id="item_option" class="widgetopt<?php echo (!in_array("item_option",$Opts[$type])) ? " displaynone":"";?>">
                    <label for="<?php echo $this->get_field_id( 'itemid' ); ?>"><?php _e( 'Item ID:' ); ?></label>
                    <input class="widefat itemid" id="<?php echo $this->get_field_id( 'itemid' ); ?>" name="<?php echo $this->get_field_name( 'itemid' ); ?>" type="text" value="<?php echo esc_attr( $itemid ); ?>">
                    *if not provided uses visible product id if exists.
                </div>

                <div id="listname_option" class="widgetopt<?php echo (!in_array("listname_option",$Opts[$type])) ? " displaynone":"";?>">
                    <label for="<?php echo $this->get_field_id( 'listname' ); ?>"><?php _e( 'List name:' ); ?></label>
                    <select class="widefat listname" id="<?php echo $this->get_field_id( 'listname' ); ?>" name="<?php echo $this->get_field_name( 'listname' ); ?>" value="<?php echo esc_attr( $listname ); ?>">
                        <option><?php _e("List name", "coffee-shopping")?></option>
                        <?php
                        foreach ($listnames as $list){
                            $list = $list["listname"];
                            $selected = ($list==$listname)? ' selected="selected"' : "";
                            ?>
                            <option value="<?php echo esc_attr( $list ); ?>"<?php echo $selected;?>><?php echo $list;?></option>
                        <?php
                        }
                        ?>
                    </select>

                </div>

                <div id="ids_option" class="widgetopt<?php echo (!in_array("ids_option",$Opts[$type])) ? " displaynone":"";?>">
                    <label for="<?php echo $this->get_field_id( 'specificids' ); ?>"><?php _e( 'Specific IDs:' ); ?></label>
                    <textarea class="widefat specificids" id="<?php echo $this->get_field_id( 'specificids' ); ?>" name="<?php echo $this->get_field_name( 'specificids' ); ?>" rows="3">
                        <?php echo $specificids; ?>
                    </textarea>
                </div>

                <div id="limit_option" class="widgetopt<?php echo (!in_array("limit_option",$Opts[$type])) ? " displaynone":"";?>">
                    <label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Limit: (1-20)' ); ?></label>
                    <input class="widefat limit" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="text" value="<?php echo esc_attr( $limit ); ?>">
                </div>
            </div>


            <div id="listnamediv">
                <div>
                    <div>*Loading specific IDs is slow and is <b>NOT</b> recommended!</div>
                    You can also use this shortcode to display this list:
                    <div id="shortcodediv" style="font-weight: bold;color:#666;">

                    </div>
                </div>
            </div>
        </div>
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
        $instance['type'] = ( ! empty( $new_instance['type'] ) ) ? strip_tags( $new_instance['type'] ) : '';
        $instance['api'] = ( ! empty( $new_instance['api'] ) ) ? strip_tags( $new_instance['api'] ) : '';
        $instance['limit'] = ( ! empty( $new_instance['limit'] ) ) ? strip_tags( $new_instance['limit'] ) : '';
        $instance['catid'] = ( ! empty( $new_instance['catid'] ) ) ? strip_tags( $new_instance['catid'] ) : '';
        $instance['itemid'] = ( ! empty( $new_instance['itemid'] ) ) ? strip_tags( $new_instance['itemid'] ) : '';
        $instance['listname'] = ( ! empty( $new_instance['listname'] ) ) ? strip_tags( $new_instance['listname'] ) : '';
        $instance['specificids'] = ( ! empty( $new_instance['specificids'] ) ) ? strip_tags( $new_instance['specificids'] ) : '';

        return $instance;
    }

} // class ProductsListWidget
