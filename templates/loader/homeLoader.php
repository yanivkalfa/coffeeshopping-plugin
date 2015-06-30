<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/30/2015
 * Time: 1:40 AM
 */

$IDs = array(
    '180562166931',
    '161501858072',
    '400709543922',
    '331276240951',
    '261667912103',
    '281616040726',
    '111680859264',
    '291458059551',
    '321794593161',
    '400840728084'
); // TODO:: load from admin.
$store = 'ebay'; // TODO:: load from admin.

// Our options array.
$itemOpts = array(
    'IncludeSelector' => explode(",", "Details")
);

if (isset($IDs) && !empty($IDs) && isset($store) && !empty($store)) {
    // performs the actual request.
    $result = productView::getProducts($store, $IDs, $itemOpts);

    // Output results if we have any proper ones, else display errors.
    if ($result["result"] == "ERROR") {
        // Failed to get the products.
        Utils::adminPreECHO("featuredProductsWidget::productView::getProducts(...) failed!", "featuredProductsWidget ERROR:: ");
        $scope = array(
            "errorsText" => $result["output"]
        );
        Utils::getTemplate('featuredProductsWidgetError', $scope);

    } else {

        // Everything is OK - Load the featured products template.
        $scope = array(
            'products' => $result["output"],
            'title' => '', // TODO:: load from admin.
        );
        Utils::getTemplate('featuredProductsWidget', $scope);
    }

}else{
    // No products or bad store.
    Utils::adminPreECHO("No featured product IDs where set!", "featuredProductsWidget ERROR:: ");
    $scope = array(
        "errorsText" => Utils::getErrorCode("templateLoader", "widget", "missingArgs", "9")
    );
    Utils::getTemplate('featuredProductsWidgetError', $scope);
}

