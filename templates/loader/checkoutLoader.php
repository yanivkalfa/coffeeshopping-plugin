<?php

if(is_user_logged_in()){
    //$orderId = CartDatabaseHelper::saveCart();
    //wp_redirect( site_url().'/checkout?orderId='.$orderId );

    if( !count($_SESSION['cart']->get())){
        echo 'Your cart is empty you have nothing to do here';
        return;
    }
    $scope = array(
        'orderId' => CartDatabaseHelper::saveCart()
    );
    Utils::getTemplate('checkout',$scope);
}else{
    Utils::getTemplate('loginOrRegister',$scope);
}

