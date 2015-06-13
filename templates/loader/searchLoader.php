<?php
/**
* Created by PhpStorm.
* User: SK
* Date: 6/2/2015
* Time: 12:29 PM
*/

/*
 * TODO: construct the paging links and per-page display dropdown.
 */

// Handle our search calls.
if (isset($_GET["search-product"]) && !empty($_GET["search-product"])) {
    // Our searching keywords.
    $searchVal = sanitize_text_field($_GET["search-product"]); // WP func sanitize_text_field()

    // Our options array.
    $searchOpts = array();
    // Handle pagination
    $searchOpts["pageToGet"] = (isset($_GET["pg"]) && !empty($_GET["pg"])) ? (int)$_GET["pg"] : 1;

    /*
     * For future advanced search
    */
    // Stores we are using for the search.
    $APIs = (isset($_GET["storesrc"]) && !empty($_GET["storesrc"]))? $_GET["storesrc"] : array("ebay");
    // Handle categories.
    $searchOpts["categories"] = (isset($_GET["pcats"]) && !empty($_GET["pcats"]))? array_map('intval', $_GET["pcats"]) : 0;

    /*
     * TODO: Should be loaded from admin panel.
    */
    // Add our filters.
    $searchOpts["filters"] = Array(
        array('name' => 'ListingType', 'value' => array('AuctionWithBIN','FixedPrice','StoreInventory'), 'paramName' => 'name', 'paramValue' => 'value'),
        array('name' => 'MinQuantity', 'value' => '1', 'paramName' => 'name', 'paramValue' => 'value'),
        array('name' => 'AvailableTo', 'value' => 'IL', 'paramName' => 'name', 'paramValue' => 'value'),
        array('name' => 'PaymentMethod', 'PayPal' => 'IL', 'paramName' => 'name', 'paramValue' => 'value'),
        //array('name' => 'Condition','value' => array('1000', '1500', '1750', '2000'),'paramName' => 'name','paramValue' => 'value'),
    );
    // Our sorting order
    $searchOpts["sortOrder"] = "PricePlusShippingLowest";
    // Our mode (sandbox/live)
    $sandbox = false;

    // performs the actual search.
    $result = productSearch::searchALL($APIs, $searchVal, $searchOpts, $sandbox);

    // error checking
    if ($result["result"]=="ERROR"){
        $errorsText = "";

        Utils::adminPreECHO("productSearch::searchALL(...) failed!", "searchLoader() ERROR:: ");
        $scope = array(
            "errorsText" => $errorsText
        );
        Utils::getTemplate('searchError', $scope);

    }else{
        // Output the search results template.
        $scope = array(
            "searchResults" => $result["result"]
        );
        Utils::getTemplate('search', $scope);


    }
}else{
    Utils::adminPreECHO("No search value was specified, \$_GET[\"search-product\"]='".$_GET["search-product"]."'", "searchLoader() ERROR:: ");
    $scope = array(
        "errorsText" => Utils::getErrorCode("templateLoader", "productSearch", "missingArgs", "3")
    );
    // No product or unknown store.
    Utils::getTemplate('searchError', $scope);
}

?>