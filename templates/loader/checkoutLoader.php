<?php

$checkoutPage = get_permalink(get_option("cs_checkout_p_id"));
if (!$checkoutPage){Utils::adminPreECHO("Can't get checkout page id", "CheckoutLoader.php ERROR:: ");}
$myAccountPage = get_permalink(get_option("cs_myAccount_p_id"));
if (!$myAccountPage){Utils::adminPreECHO("Can't get register page id", "loginLoader.php ERROR:: ");}


if(is_user_logged_in()){

    //if we saved cart and redirected with orderId
    if(isset($_GET['orderId']) && !empty($_GET['orderId'])){
        // create scope
        $scope = array(
            'orderId' => $_GET['orderId'],
            'checkoutPage' => $checkoutPage,
            'myAccountPage' => $myAccountPage,
        );
        // loading checkout template
        Utils::getTemplate('checkout', $scope, 'pages');
        return;
    }

    // if we got to the page with empty cart with load empty cart error
    if( !count($_SESSION['cart']->get())){
        Utils::getTemplate('emptyCartError');
        return;
    }

    // if save details was clicked
    if(isset($_POST['saveAddress'])){
        // checking if we have address_id else redirecting with form error
        if(!isset($_POST['address_id']) || empty($_POST['address_id'])) {
            wp_redirect( $checkoutPage.'?formError=address_id' );
            return;
        }

        // Checking if user selected new address and we did not get a new address then redirecting error.
        if( $_POST['address_id'] === 'newAddress' &&
            (!isset($_POST['address']) || empty($_POST['address']))
        ) {
            wp_redirect( $checkoutPage.'?formError=address' );
            return;
        }


        // is address id shipToStore then $deliver_to is set to store id, and address is empty
        if($_POST['address_id'] === 'shipToStore') {
            $deliver_to = CartHelper::getCurrentStoreId();
            $address = false;

            // is address id newAddress then $deliver_to get the home flag, and the new address is used
        }else if($_POST['address_id'] === 'newAddress'){
            $deliver_to = 'home';
            $address = $_POST['address'];
        }else{

            // is address id and id then $deliver_to get the home flag, and we are fetching the address from the db.
            $deliver_to = 'home';
            $address = CartDatabaseHelper::getAddress($_POST['address_id']);
        }

        // setting purchase_location to the store id.
        $_SESSION['cart']->purchase_location = CartHelper::getCurrentStoreId(); // TODO:: need to change so it will check if it was done at home.

        // setting deliver_to
        $_SESSION['cart']->deliver_to = $deliver_to;

        // if new address
        if($_POST['address_id'] === 'newAddress'){
            // instantiating new address
            $address = new Address($address);

            // validating the new address
            $error = $address->validateAddress();
            // if we have errors reporting them
            if($error) {
                wp_redirect( $checkoutPage.'?formError=address&field='.$error['name'] );
                return;
            }
            // set cart address
            $_SESSION['cart']->setAddress($address);
        }

        // save cart and kill session
        $cartId = CartDatabaseHelper::saveCart();

        // if new address add it to user's saved session
        if($_POST['address_id'] === 'newAddress') {
            $address_id = CartDatabaseHelper::getCartAddressId($cartId);
            $user = wp_get_current_user();
            add_user_meta($user->ID, 'address_id', $address_id);
        }

        // redirect to checkout with id.
        wp_redirect( $checkoutPage.'?orderId='.$cartId );
        return;
    } else {
        // first stage of checkout - need to handle address.

        // getting user
        $user = wp_get_current_user();

        // getting saved addresses ids
        $addressesIds = get_user_meta($user->ID, 'address_id');
        if(isset($addressesIds) && !empty($addressesIds)){
            // getting saved address.
            foreach($addressesIds as $key => $address){
                $scope['addresses'][$key] = CartDatabaseHelper::getAddress($address);
            }
        }

        // Add our pages to the scope.
        $scope['checkoutPage'] = $checkoutPage;
        $scope['myAccountPage'] = $myAccountPage;

        Utils::getTemplate('checkout',$scope, 'pages');
    }
}else{
    $scope = array(
        'referrer' => $checkoutPage,
        'checkoutPage' => $checkoutPage,
        'myAccountPage' => $myAccountPage,
    );
    Utils::getTemplate('loginOrRegister',$scope);
}

