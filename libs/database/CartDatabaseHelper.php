<?php
/*
* @ Cart Database queiry
*/

abstract class CartDatabaseHelper {


    /**
     * getCart by user id
     *
     * @param {number} $userId
     * @return bool|array
     */
    public static function getCart($userId){
        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_carts';
        return $wpdb->get_row("SELECT * FROM $table_name WHERE `user_id` = '$userId' AND `status` NOT IN('paid', 'storage', 'at_store', 'delivered') ORDER BY `create_date` DESC", ARRAY_A);
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

        echo '$cart';
        Utils::preEcho($cart);
        // checking if we have an id, if we do it means we are editing if we don't its a new cart.
        if(empty($cart['ID'])){
            echo '<br>new<br>';
            // inserting new address and using the id for cart's address_id
            $cart['address_id'] = self::insertItem((array)$cart['address'], 'cs_addresses');
            // inserting new cart keeping the cartId
            $cartId = self::insertItem(Utils::arrayPluck($cart, $keep), 'cs_carts');
        }else{
            echo '<br>edit<br>';
            // else we are updating excising cart.
            // updating address - since we already have the id in the cart no need to change that.
            self::updateItem((array)$cart['address'], 'cs_addresses');

            // updating cart keeping the cartId
            $cartId = self::updateItem(Utils::arrayPluck($cart, $keep), 'cs_carts');
        }

        echo '$cartId';
        Utils::preEcho($cartId);

        // checking if we got cartid from previous insert or update,
        // and if original cart id is set which mean we are editing - so we delete all products related to the cart.
        if($cartId !== false && !empty($cart['ID'])){
            echo 'deleted : <br>';
            $deleted =  self::deleteItem(array('cart_id' => $cartId), 'cs_cart_products');
            Utils::preEcho($deleted);
        }

        echo '$products';
        // iterating over cart product.
        foreach($_SESSION['cart']->get() as $key => $product){
            Utils::preEcho($product);
            // creating a new array from the product class
            $productArr = (array)$product;

            // setting car_id to the id we retrieved from update/insert
            $productArr['cart_id'] = $cartId;

            // serializing price_modifiers for later use
            $productArr['price_modifiers'] = serialize($productArr['price_modifiers']);

            //inserting product to db
            self::insertItem($productArr, 'cs_cart_products');
        }



        // un setting session cart so next time used comes in it will either create a new cart(in-case he completed the previous cart or use this cart)
        unset($_SESSION['cart']);
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








/* ------------------- DEAD OR UNUSED CODE ---------------- */
/*
 */
/* ------------------- DEAD OR UNUSED CODE ---------------- */






