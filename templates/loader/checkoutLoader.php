<?php

if(is_user_logged_in()){

    if( !count($_SESSION['cart']->get())){
        echo 'Your cart is empty you have nothing to do here';
        return;
    }

    $scope = array();
    if(isset($_POST['saveAddress'])){

        //$orderId = CartDatabaseHelper::saveCart();
        //wp_redirect( site_url().'/checkout?orderId='.$orderId );
        $scope = array(
            'orderId' => CartDatabaseHelper::saveCart()
        );


    } else {


        $user = wp_get_current_user();
        $address_id = get_user_meta($user->ID, 'address_id');
        if(isset($address_id) && !empty($address_id)){
            $scope['address'] = CartDatabaseHelper::getAddress($address_id);
        }


    }



    Utils::getTemplate('checkout',$scope, 'pages');
}else{
    Utils::getTemplate('loginOrRegister',$scope);
}

