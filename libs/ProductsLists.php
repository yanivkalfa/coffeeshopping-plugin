<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 7/12/2015
 * Time: 1:56 AM
 */

abstract class ProductsLists {

    /**
     * @func insertSavedProductsList($IDs, $store, $listname)
     *  - Gets and inserts a list of products to the saved products database list.
     * @param $IDs          -   array   -   an array of id's to get.
     * @param $store        -   string  -   an API identifier.
     * @param $listname     -   string  -   to which list should we insert these.
     * @return bool
     */
    public static function insertSavedProductsList($IDs, $store, $listname){
        // Get the products:
        $result = self::getProductsByIDs($store, $IDs);
        if(!$result){
            // Failed to get the products.
            Utils::adminPreECHO(__( "insertSavedProductsList::self::getProductsByIDs(...) failed!", 'coffee-shopping' ), __( "insertSavedProductsList ERROR:: ", 'coffee-shopping' ));
            Utils::adminPreECHO($result["output"]);
        }else{
            foreach($result as $product) {
                $result = SavedProductsHelper::insertSavedProduct($product);
                if (!$result) {
                    Utils::adminPreECHO($productsArr, "Failed to insert the saved product to the DB, product:");
                    return false;
                }
            }
        }
        return true;
    }


    public static function getSavedProductsList($listname){
        return SavedProductsHelper::getSavedProducts("listname", array($listname));
    }

    public static function getProductsByIDs($store, $IDs){
        // Our options array. //TODO:: when we'll have more APIs, add a function to get these properly.
        $itemOpts = array(
            'IncludeSelector' => explode(",", "Details")
        );

        // performs the actual request.
        $result = productView::getProducts($store, $IDs, $itemOpts);

        // Output results if we have any proper ones, else display errors.
        if ($result["result"] == "ERROR") {
            // Failed to get the products.
            Utils::adminPreECHO(__( "getProductsByIDs::productView::getProducts(...) failed!", 'coffee-shopping' ), __( "getProductsByIDs ERROR:: ", 'coffee-shopping' ));
            return false;
        } else {
            $productsArr = [];
            $index = 0;
            foreach($result["output"] as $product){
                $productsArr[$index] = array(
                    "productID"         => $product->ID,
                    "store"             => $store,
                    "title"             => $product->title,
                    "image"             => $product->pics[0]["picURL"],
                    "price"             => $product->price,
                    "priceCurrency"     => $product->priceCurrency,
                    "shipping"          => $product->shippingDetails->shippingOptions[0]["price"],
                    "shippingCurrency"  => $product->shippingDetails->shippingOptions[0]["priceCurrency"],
                    "listname"          => "",
                );
                Utils::addExchangeKeys($productsArr[$index], array("price", "shipping"));
                $index++;
            }

            return $productsArr;
        }
    }


}