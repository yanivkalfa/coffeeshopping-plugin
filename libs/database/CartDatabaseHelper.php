<?php
/*
* @ Cart Database queiry
*/

abstract class CartDatabaseHelper {



    public static function generateCart($carts){
        foreach($carts as $key => $cart){
            $products = self::getCartProduct($cart['ID']);
            $carts[$key] = CartHelper::initCart($cart, $products);
        }
        return $carts;
    }

    /**
     * getUnfinishedCarts by user id
     *
     * @param {number} $cartId
     * @return bool|array
     */
    public static function getUnfinishedCarts($userId){
        global $wpdb;
        $cartStatus = CSCons::get('cartStatus') ?: array();
        $table_name = $wpdb->prefix . 'cs_carts';
        $currentCart = self::getCart($userId);
        if($currentCart){
            $currentCart = array($currentCart);
        }else{
            $currentCart = array();
        }

        $unfinishedCarts = $wpdb->get_results("SELECT * FROM $table_name
                              WHERE `user_id` = '$userId' AND `status` NOT IN ('".$cartStatus['delivered']."', '".$cartStatus['saved']."')
                              ORDER BY `status` ASC , `create_date` DESC", ARRAY_A);

        if(!$unfinishedCarts){
            $unfinishedCarts = array();
        }
        $carts = array_merge ($currentCart, $unfinishedCarts);
        return self::generateCart($carts);
    }

    /**
     * getDeliveredCarts by user id
     *
     * @param {number} $cartId
     * @return bool|array
     */
    //".$cartStatus['saved']."
    public static function getDeliveredCarts($userId){
        global $wpdb;
        $cartStatus = CSCons::get('cartStatus') ?: array();
        $table_name = $wpdb->prefix . 'cs_carts';
        $carts = $wpdb->get_results("SELECT * FROM $table_name WHERE `user_id` = '$userId' AND `status` = '".$cartStatus['delivered']."' ORDER BY `create_date` DESC", ARRAY_A);
        return self::generateCart($carts);
    }

    /**
     * getCartAddressId by user id
     *
     * @param {number} $cartId
     * @return bool|array
     */
    public static function getCartAddressId($cartId){
        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_carts';
        return $wpdb->get_var("SELECT `address_id` FROM $table_name WHERE `ID` = '$cartId'", ARRAY_A);
    }

    /**
     * getCart by user id
     *
     * @param {number} $userId
     * @return bool|array
     */
    public static function getCart($userId){
        global $wpdb;
        $cartStatus = CSCons::get('cartStatus') ?: array();
        $table_name = $wpdb->prefix . 'cs_carts';
        return $wpdb->get_row("SELECT * FROM $table_name WHERE `user_id` = '$userId' AND `status` = '".$cartStatus['saved']."' ORDER BY `create_date` DESC", ARRAY_A);
    }

    /**
     * getAddress by addressId
     *
     * @param {number} $userId
     * @return bool|array
     */
    public static function getAddress($addressId){
        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_addresses';
        return $wpdb->get_row("SELECT * FROM $table_name WHERE `ID` = '$addressId'", ARRAY_A);
    }

    /**
     * get cart products by cart id
     *
     * @param {number} $cartId
     * @return bool|array
     */
    public static function getCartProduct($cartId){
        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_cart_products';
        if($results = $wpdb->get_results("SELECT * FROM $table_name WHERE `cart_id` = '$cartId' ORDER BY `ID` ASC", ARRAY_A)) {
            foreach($results as $key => $modification) {
                $results[$key]['price_modifiers'] = unserialize($modification['price_modifiers']);
                $results[$key]['selected_variant'] = unserialize($modification['selected_variant']);
            }
        }

        return $results;
    }

    /**
     * get cart address by cart id
     *
     * @param {number} $cartId
     * @return bool|array
     */
    public static function getCartAddress($cartId){
        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_addresses';
        return $wpdb->get_row("SELECT * FROM $table_name WHERE `ID` = '$cartId'", ARRAY_A);
    }

    /**
     * user checked out -> created account -> cart get saved to db and session is cleared.
     *
     * @return bool
     */
    public static function saveCart() {
        if(!isset($_SESSION['cart'])) return false;
        // array that is used to filter the properties on cart class to fit the DB fields
        $keep = array('ID','user_id','deliver_to','address_id','payment_method','purchase_location','status','create_date');
        // turning the cart class into an array.
        $cart = (array)$_SESSION['cart'];

        // checking if we have an id, if we do it means we are editing if we don't its a new cart.
        if(empty($cart['ID'])){
            // inserting new cart keeping the cartId
            $cartId = self::insertItem(Utils::arrayPluck($cart, $keep), 'cs_carts');
        }else{

            // updating cart keeping the cartId
            $cartId = self::updateItem(Utils::arrayPluck($cart, $keep), 'cs_carts');
        }

        // checking if we got cartid from previous insert or update,
        // and if original cart id is set which mean we are editing - so we delete all products related to the cart.
        if($cartId !== false && !empty($cart['ID'])){
            $deleted =  self::deleteItem(array('cart_id' => $cartId), 'cs_cart_products');
        }

        // iterating over cart product.
        foreach($_SESSION['cart']->get() as $key => $product){

            // creating a new array from the product class
            $productArr = (array)$product;

            // setting car_id to the id we retrieved from update/insert
            $productArr['cart_id'] = $cartId;

            // serializing price_modifiers for later use
            $productArr['price_modifiers'] = serialize($productArr['price_modifiers']);

            // serializing selected_variant for later use
            $productArr['selected_variant'] = serialize($productArr['selected_variant']);

            //inserting product to db
            self::insertItem($productArr, 'cs_cart_products');

        }

        // un setting session cart so next time used comes in it will either create a new cart(in-case he completed the previous cart or use this cart)
        unset($_SESSION['cart']);

        return $cartId;
    }


    /**
     * basic abstract function that insert item to db.
     * @param {array} $item
     * @param {string} $toTable
     * @return false|number
     */
    public static function insertItem($item, $toTable) {
        if(!isset($item)) return false;

        global $wpdb;
        $table_name = $wpdb->prefix . $toTable;
        return $wpdb->insert( $table_name, $item) ? $wpdb->insert_id : false ;
    }

    /**
     * basic abstract function that update an item on db.
     * @param {array} $item
     * @param {string} $toTable
     * @return false|number
     */
    public static function updateItem($item, $toTable) {
        if(!isset($item)) return false;

        global $wpdb;
        $table_name = $wpdb->prefix . $toTable;
        return $wpdb->update($table_name, $item, array( 'ID' => $item['ID'] )) !== false ? $item['ID'] : false;
    }

    /**
     * basic abstract function that delete an item from db.
     * @param {array} $item
     * @param {string} $toTable
     * @return false|number
     */
    public static function deleteItem($item, $toTable) {
        if(!isset($item)) return false;

        global $wpdb;
        $table_name = $wpdb->prefix . $toTable;
        return $wpdb->delete($table_name, $item);
    }
}


/*
        // updating address - since we already have the id in the cart no need to change that.
        self::updateItem((array)$cart['address'], 'cs_addresses');
        // inserting new address and using the id for cart's address_id

        */


