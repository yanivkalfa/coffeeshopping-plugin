<?php


$checkoutPage = get_permalink(get_option("cs_checkout_p_id"));
if (!$checkoutPage){Utils::adminPreECHO("Can't get checkout page id", "cartLoader.php ERROR:: ");}

$scope = array(
    "checkoutPage" => $checkoutPage,
);

Utils::getTemplate('cart', $scope, 'pages');
