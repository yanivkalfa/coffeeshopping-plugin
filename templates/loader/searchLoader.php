<?php
/**
* Created by PhpStorm.
* User: SK
* Date: 6/2/2015
* Time: 12:29 PM
*/

/*
 * TODO: Fix conflict -> AvailableTo=IL retrieves WorldWide results with IL excluded.
 */

// Handle our search calls.
if (isset($_GET["search-product"]) && !empty($_GET["search-product"])) {
    // load the search results page.
    loadSearchPage();

}else{
    Utils::adminPreECHO($_GET, "searchLoader() ERROR:: No search value was specified, ");
    $scope = array(
        "errorsText" => Utils::getErrorCode("templateLoader", "productSearch", "missingArgs", "3")
    );
    // No product or unknown store.
    Utils::getTemplate('searchError', $scope);
}



function loadSearchPage(){
    // Our searching keywords.
    $searchVal = sanitize_text_field($_GET["search-product"]); // WP func sanitize_text_field()

    // Our options array.
    $searchOpts = array();
    // Handle pagination
    $searchOpts["pageToGet"] = (isset($_GET["pg"]) && !empty($_GET["pg"])) ? (int)$_GET["pg"] : 1;
    $searchOpts["entriesPerPage"] = (isset($_GET["ppg"]) && !empty($_GET["ppg"])) ? (int)$_GET["ppg"] : 10;

    /*
     * For future advanced search
    */
    // Stores we are using for the search.
    $APIs = (isset($_GET["storesrc"]) && !empty($_GET["storesrc"]))? $_GET["storesrc"] : array("ebay");
    // Handle categories.
    $searchOpts["categories"] = (isset($_GET["pcats"]) && !empty($_GET["pcats"]))? array_map('intval', $_GET["pcats"]) : 0;

    $productPage = get_permalink(get_option("cs_product_p_id"));
    if (!$productPage){
        Utils::adminPreECHO(__("Can't get product page id", 'coffee-shopping' ), __("searchTemplate() ERROR:: ", 'coffee-shopping' ));
        $scope = array(
            "errorsText" => Utils::getErrorCode("templateLoader", "productSearch", "searchAPI", "8")
        );
        Utils::getTemplate('searchError', $scope);
        return;
    }

    // Check if it's a product ID.
    if (count($APIs)==1 && Utils::isProductID($APIs[0], $_GET["search-product"])) {
        // Redirect to product page.
        $productPageLink = $productPage . "?view-product=" . $_GET["search-product"] . "&store=" . $APIs[0];
        wp_redirect($productPageLink, 302);
    }

    // Add our filters.  TODO: Should be loaded from admin panel.
    $searchOpts["filters"] = Array(
        array('name' => 'HideDuplicateItems', 'value' => true),
        array('name' => 'ListingType', 'value' => array('AuctionWithBIN','FixedPrice','StoreInventory')),
        array('name' => 'MinQuantity', 'value' => '1'),
        array('name' => 'AvailableTo', 'value' => 'IL'),
        array('name' => 'PaymentMethod', 'value' => 'PayPal'),
    );

    // Add user advanced search filters:
    if (isset($_GET["conditions"]) && is_array($_GET["conditions"])) {
        $searchOpts["filters"][] = array('name' => 'Condition', 'value' => $_GET["conditions"]);
    }
    // Our sorting order
    $searchOpts["sortOrder"] = (isset($_GET["sortOrder"]) && !empty($_GET["sortOrder"])) ? $_GET["sortOrder"] : "BestMatch";

    // Our mode (sandbox/live)
    $sandbox = false;

    // performs the actual search.
    $result = productSearch::searchALL($APIs, $searchVal, $searchOpts, $sandbox);

    // error checking
    if ($result["result"]=="ERROR"){
        Utils::adminPreECHO(__("productSearch::searchALL(...) failed!", 'coffee-shopping' ), __("searchLoader() ERROR:: ", 'coffee-shopping' ));
        $scope = array(
            "errorsText" => $result["output"]
        );
        Utils::getTemplate('searchError', $scope);

    }else{
        // Output the search results template.
        $scope = array(
            "productPage" => $productPage,
            "searchResults" => $result["output"]
        );
        $scope["searchVal"]             = $_GET["search-product"];
        Utils::getTemplate('search', $scope, 'pages');
    }
}