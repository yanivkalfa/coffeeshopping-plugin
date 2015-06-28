<?php
/*
* @ Cart helper
*/

abstract class CartHelper {

    public static function initCart ($cart = array(), $products = array()){
        return new Cart($cart, $products);
    }

    public static function getCurrentStoreId (){
        return 'store_0';
    }


}