<?php
/*
* @ Cart Database queiry
*/

abstract class CartDatabaseHelper extends SuperDatabaseHelper {

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
                              WHERE `user_id` = '$userId' AND `status` NOT IN ('".$cartStatus['delivered']['name']."', '".$cartStatus['saved']['name']."')
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
    public static function getDeliveredCarts($userId){
        global $wpdb;
        $cartStatus = CSCons::get('cartStatus') ?: array();
        $table_name = $wpdb->prefix . 'cs_carts';
        $carts = $wpdb->get_results("SELECT * FROM $table_name WHERE `user_id` = '$userId' AND `status` = '".$cartStatus['delivered']['name']."' ORDER BY `create_date` DESC", ARRAY_A);
        return self::generateCart($carts);
    }

    /**
     * getCart address id by card id
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
     * getCart by Address id
     *
     * @param {number} $cartId
     * @return bool|array
     */
    public static function getCartByAddressId($address_id){
        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_carts';
        return $wpdb->get_var("SELECT `ID` FROM $table_name WHERE `address_id` = '$address_id'");
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
        return $wpdb->get_row("SELECT * FROM $table_name WHERE `user_id` = '$userId' AND `status` = '".$cartStatus['saved']['name']."' ORDER BY `create_date` DESC", ARRAY_A);
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
     * user checked out -> created account -> cart get saved to db and session is cleared.
     *
     * @return bool
     */
    public static function saveCart() {
        if(!isset($_SESSION['cart'])) return false;
        // array that is used to filter the properties on cart class to fit the DB fields
        $keep = array('ID','user_id','deliver_to','address_id','payment_method','payment_amount','purchase_location','status','note','create_date', 'delivered_date');
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
}