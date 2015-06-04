<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/4/2015
 * Time: 4:37 AM
 */

$errorCategories = array(
    "API"           => 0,
    "frontEnd"      => 1,
    "backEnd"       => 2,
);
$errorSubCategories = array(
    "ebay"          => 0,
    "aliexp"        => 1,
    "productView"   => 2,
    "productSearch" => 3,
);
$errorSubCategoryTypes = array(
    "finding"       => 0,
    "product"       => 1,
    "getProduct"    => 2,
    "searchAPI"     => 3,
    "searchALL"     => 4,
);



$errorCodesHandler = array(
    "0" => "improper search string",
    "1" => "cURL Communication error",
    "2" => "Failed to get the requested item details",
    "3" => "Failed to get the product's shipping details",
    "4" => "It seems like this item's listing is inactive",
    "5" => "Failed to get the requested item(s) details",
    "6" => "Failed to get the search results",
);

?>