<?php

$checkoutPage = get_permalink(get_option("cs_checkout_p_id"));
if (!$checkoutPage){Utils::adminPreECHO(__( "Can't get checkout page id", 'coffee-shopping' ), __( "cartLoader.php ERROR:: ", 'coffee-shopping' ));}

$referredFrom = (isset($_SERVER["HTTP_REFERER"]) && !empty($_SERVER["HTTP_REFERER"]))? $_SERVER["HTTP_REFERER"] : esc_url( home_url( '/' ) );

$scope = array(
    "checkoutPage" => $checkoutPage,
    "referredFrom" => $referredFrom
);

Utils::getTemplate('cart', $scope, 'pages');
