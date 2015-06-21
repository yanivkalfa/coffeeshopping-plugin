<?php

if(is_user_logged_in()){
    $scope = array();
    if(isset($_POST['saveAddress'])){

        //$orderId = CartDatabaseHelper::saveCart();
        //wp_redirect( site_url().'/checkout?orderId='.$orderId );

        if( !count($_SESSION['cart']->get())){
            echo 'Your cart is empty you have nothing to do here';
            return;
        }
        $scope = array(
            'orderId' => CartDatabaseHelper::saveCart()
        );
    }else{

    }



    Utils::getTemplate('checkout',$scope, 'pages');
}else{
    Utils::getTemplate('loginOrRegister',$scope);
}

