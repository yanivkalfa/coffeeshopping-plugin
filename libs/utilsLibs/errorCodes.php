<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/4/2015
 * Time: 4:37 AM
 */

$errorCategories = array(
    "API",
    "frontEnd",
    "backEnd",
);
$errorSubCategories = array(
    "ebay",
    "aliexp",
    "productView",
    "productSearch",
);
$errorSubCategoryTypes = array(
    "getSearch",
    "getProduct",
    "getShippingCosts",
    "getProduct",
    "getProducts",
    "searchAPI",
    "searchALL",
);



$errorCodesHandler = array(
    "0" => "improper search string",
    "1" => "cURL Communication error",
    "2" => "Failed to get the requested item details",
    "3" => "",
    "4" => "It seems like this item's listing is inactive",
    "5" => "Failed to get the requested item(s) details",
    "6" => "Failed to get the search results",
);

?>