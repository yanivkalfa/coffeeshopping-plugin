<?php

$checkoutPage = get_permalink(get_option("cs_checkout_p_id"));
if (!$checkoutPage){Utils::adminPreECHO("Can't get checkout page id", "cartLoader.php ERROR:: ");}

$referredFrom = $_SERVER["HTTP_REFERER"];

$scope = array(
    "checkoutPage" => $checkoutPage,
    "referredFrom" => $referredFrom
);

Utils::getTemplate('cart', $scope, 'pages');
