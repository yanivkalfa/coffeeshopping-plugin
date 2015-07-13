<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 7/12/2015
 * Time: 3:26 AM
 */

$IDs = array(
    '180562166931',
    '161501858072',
    '261924928318',
    '351439540986',
    '231600677590',
    '281616040726',
    '121574740162',
    '291458059551',
    '321794593161',
    '391185228764',
    '181787459656',
    '171430828138'
); // TODO:: load from admin.
$store = 'ebay'; // TODO:: load from admin.

$products = ProductsLists::getProductsByIDs($store, $IDs);
// Output results if we have any proper ones, else display errors.
if (!$products) {
    // Failed to get the products.
    Utils::adminPreECHO(__( "homeLoader::ProductLists::getProductsByIDs(...) failed!", 'coffee-shopping' ), __( "homeLoader ERROR:: ", 'coffee-shopping' ));
    $scope = array(
        "errorsText" => "Failed to load the products, please check your details!"
    );
    Utils::getTemplate('ProductsListError', $scope);

} else {

    // Everything is OK - Load the featured products template.
    $scope = array(
        'products' => $products,
    );
    Utils::getTemplate('ProductsList', $scope);
}