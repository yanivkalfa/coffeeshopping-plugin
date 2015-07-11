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
$listname = "recommended";

//insertSavedProductsList($IDs, $store, $listname);
//getSavedProductsList("recommended");
//function getSavedProductsList($listname){}

if (isset($IDs) && !empty($IDs) && isset($store) && !empty($store)) {
    // performs the actual request.
    $itemOpts = array(
        'IncludeSelector' => explode(",", "Details")
    );
    $result = productView::getProducts($store, $IDs, $itemOpts);

    // Output results if we have any proper ones, else display errors.
    if ($result["result"] == "ERROR") {
        // Failed to get the products.
        Utils::adminPreECHO(__( "featuredProductsWidget::productView::getProducts(...) failed!", 'coffee-shopping' ), __( "featuredProductsWidget ERROR:: ", 'coffee-shopping' ));
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
        $scope["exchangeCurrency"]      = "ILS"; // TODO:: get from admin panel.
        $scope["exchangeExtension"]     = "Exch"; // TODO:: get from admin panel
        $scope["store"]                 = "ebay"; // TODO:: get from admin panel

        Utils::getTemplate('featuredProductsWidget', $scope);
    }

}else{
    // No products or bad store.
    Utils::adminPreECHO(__( "No featured product IDs where set!", 'coffee-shopping' ), __( "featuredProductsWidget ERROR:: ", 'coffee-shopping' ));
    $scope = array(
        "errorsText" => Utils::getErrorCode("templateLoader", "widget", "missingArgs", "9")
    );
    Utils::getTemplate('featuredProductsWidgetError', $scope);
}

