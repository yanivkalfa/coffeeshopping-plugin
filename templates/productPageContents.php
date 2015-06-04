<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/2/2015
 * Time: 1:49 PM
 */

if (
    (isset($_GET["view-product"]) && !empty($_GET["view-product"]))
    &&
    (isset($_GET["store"]) && !empty($_GET["store"]) && utils::API_Exists($_GET["store"]))
    )
{
    // Sanitize our product id and store name.
    $productID = $_GET["view-product"];
    $store = $_GET["store"];

    // Our options array.
    $itemOpts = array();
    // Requested details.
    $itemOpts["IncludeSelector"] = explode(",", "Details,Description,ItemSpecifics,Variations,Compatibility");
    $sandbox = false;

    // performs the actual request.
    $result = productView::getProduct($store, $productID, $itemOpts, $sandbox);

    // Output results if we have any proper ones, else display errors.
    if ($result[""] == ERROR){
        echo productView::DisplayError($result);
    }

}



?>
