<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/2/2015
 * Time: 1:49 PM
 */

if (isset($_GET["view-product"]) && !empty($_GET["view-product"]) && isset($_GET["store"]) && !empty($_GET["store"])) {
    // Our product id.
    $productID = (int)$_GET["view-product"];

    // Our options array.
    $itemOpts = array();
    // Handle pagination
    $itemOpts["IncludeSelector"] = (isset($_GET["pg"]) && !empty($_GET["pg"])) ? (int)$_GET["pg"] : 1;

    // Details,Description,ShippingCosts,ItemSpecifics,Variations,Compatibility


}



?>

