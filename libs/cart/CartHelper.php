<?php
/*
* @ Cart helper
*/

abstract class CartHelper {

    public static function instantiateCart($savedCart = NULL){
        //unset($_SESSION['cart']);
        if(!isset($_SESSION['cart']) || (isset($_SESSION['cart']) && $_SESSION['cart']->ID)){
            $cartStatus = CSCons::get('cartStatus') ?: array();
            if(is_user_logged_in()){
                if(!$savedCart){
                    $current_user = wp_get_current_user();
                    $savedCart = CartDatabaseHelper::getCart($current_user->ID);
                }
                if(isset($_SESSION['cart']) && $savedCart['status'] === $cartStatus['saved']['name']) {
                    return;
                }
            }
            $products = isset($savedCart['ID']) ? CartDatabaseHelper::getCartProduct($savedCart['ID']) : NULL;
            $_SESSION['cart'] = new Cart($savedCart, $products);
        }
        //Utils::preEcho($_SESSION['cart']);
    }

    public static function initCart ($cart = array(), $products = array()){
        return new Cart($cart, $products);
    }

    public static function getCurrentStoreId (){
        return 0;
    }


}