<?php

$checkoutPage = get_permalink(get_option("cs_checkout_p_id"));
if (!$checkoutPage){Utils::adminPreECHO("Can't get checkout page id", "cartLoader.php ERROR:: ");}

$referredFrom = (isset($_SERVER["HTTP_REFERER"]) && !empty($_SERVER["HTTP_REFERER"]))? $_SERVER["HTTP_REFERER"] : esc_url( home_url( '/' ) );

$scope = array(
    "checkoutPage" => $checkoutPage,
    "referredFrom" => $referredFrom
);

Utils::getTemplate('cart', $scope, 'pages');
