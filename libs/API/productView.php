<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/2/2015
 * Time: 1:57 PM
 */

// no direct access
//defined('ABSPATH') or die('Restricted access');

abstract class productView {

    /*
     * @Uses _get_product_s to get a single product.
    */
    static public function getProduct($API, $productID, $itemOpts = array(), $sandbox = false){
        return self::_get_product_s($API, $productID, $itemOpts, $sandbox);
    }
    /*
     * @Uses _get_product_s to retrieve multiple products.
    */
    static public function getProducts($API, $productIDs, $itemOpts = array(), $sandbox = false){
        return self::_get_product_s($API, $productIDs, $itemOpts, $sandbox);
    }

    /**
     * @func getProduct($API, $productID, $itemOpts = array(), $sandbox = false)
     *  - Gets the product details to display in the 'product page view'.
     * @param   string      $API                    -   The name of the API to use (eg. ebay)
     * @param   string      $toGet              -   A string representation of the product ID (eg. 123123123)
     * @param   array       $itemOpts [Optional]    -   Holds our product options as specified for the given API.
     * @param   bool        $sandbox  [Optional]    -   Do we use the sandbox or live? (defaults to live)
     * @return  array       $result                 -   An array with 2 keys - result/output.
     *      - Success - result = "OK"
     *                  output = $ObjProduct object ready for constructResults(). [Proper example format found at: ebay_ShoppingAPI::_formatProductOutput()]
     *
     *      - Failure - result = "ERROR",
     *                  output = Error code, codes reference at: Utils::getErrorCodeText($errorCode).
     */
    static private function _get_product_s($API, $toGet, $itemOpts = array(), $sandbox = false){
        // Check if our Adapter exists.
        if ( !Utils::API_Exists($API) ){
            Utils::adminPreECHO("API class (".$API."Adapter) doesn't exists, can't get product!", "getProduct() ERROR:: ");
            return array(
                "result" => "ERROR",
                "output" => Utils::getErrorCode("frontEnd", "productView", "getProduct", "2")
            );
        }
        $apiClass = $API."_ShoppingAPI";
        if (!class_exists($apiClass)){
            Utils::adminPreECHO("API class ($apiClass) doesn't exists, can't get product!", "getProduct() ERROR:: ");
            return array(
                "result" => "ERROR",
                "output" => Utils::getErrorCode("frontEnd", "productView", "getProduct", "2")
            );
        }

        $getter = new $apiClass();
        // Set the API to work live/sandbox.
        if ($sandbox){
            $getter->_setSandbox();
        }else{
            $getter->_setLive();
        }
        // Set our item ID(s).
        $getter->_setGetter($toGet);
        // Set our item options.
        $getter->_setItemOptions($itemOpts);
        // Run the search and get a results obj.
        if (!is_array($toGet)) {
            $result = $getter->getProduct();
        }else{
            $result = $getter->getProducts();
        }
        // Error checking
        if ($result["result"]=="ERROR"){
            return array(
                "result" => "ERROR",
                "output" => $result["output"]
            );
        }

        return array(
            "result" => "OK",
            "output" => $result["output"]
        );
    }
}

?>