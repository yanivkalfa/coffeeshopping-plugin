<?php


if(is_user_logged_in()) {

    // Handle submits:
    if (isset($_POST["submit"]) && $_POST["submit"]=="1") {handle_submits();}

    // Show the page.
    $scope = array();
    // Get the user:
    $scope["user"] = wp_get_current_user();
    // Get saved addresses
    $addressesIds = get_user_meta($scope["user"]->ID, 'address_id');
    if(isset($addressesIds) && !empty($addressesIds)){
        // getting saved address.
        foreach($addressesIds as $key => $address){
            $address = CartDatabaseHelper::getAddress($address);
            if(!empty($address)){
                $scope['addresses'][$key] = $address;
            }
        }
    }

    Utils::getTemplate('myAccount', $scope, 'pages');

}



function handle_submits(){
    // Handle our submits.

}


