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

    /**
     * @func getProduct($API, $productID, $itemOpts = array(), $sandbox = false)
     *  - Gets the product details to display in the 'product page view'.
     * @param   string      $API                    -   The name of the API to use (eg. ebay)
     * @param   string      $productID              -   A string representation of the product ID (eg. 123123123)
     * @param   array       $itemOpts [Optional]    -   Holds our product options as specified for the given API.
     * @param   bool        $sandbox  [Optional]    -   Do we use the sandbox or live? (defaults to live)
     * @return  array       $result                 -   An array with 2 keys - result/output.
     *      - Success - result = "OK"
     *                  output = $ObjProduct object ready for constructResults(). [Proper example format found at: ebay_ShoppingAPI::_formatProductOutput()]
     *
     *      - Failure - result = "ERROR",
     *                  output = Error code, codes reference at: utils::getErrorCodeText($errorCode).
     */
    static public function getProduct($API, $productID, $itemOpts = array(), $sandbox = false){
        // Check if our Adapter exists.
        if ( !utils::API_Exists($API) ){
            utils::adminPreECHO("API class (".$API."Adapter) doesn't exists, can't get product!", "getProduct() ERROR:: ");
            return array(
                "result" => "ERROR",
                "output" => utils::getErrorCode("frontEnd", "productView", "getProduct", "2")
            );
        }
        $apiClass = $API."_ShoppingAPI";
        if (!class_exists($apiClass)){
            utils::adminPreECHO("API class ($apiClass) doesn't exists, can't get product!", "getProduct() ERROR:: ");
            return array(
                "result" => "ERROR",
                "output" => utils::getErrorCode("frontEnd", "productView", "getProduct", "2")
            );
        }

        $getter = new $apiClass();
        // Set the API to work live/sandbox.
        if ($sandbox){
            $getter->_setSandbox();
        }else{
            $getter->_setLive();
        }
        // Set our item ID.
        $getter->_setItemID($productID);
        // Set our item options.
        $getter->_setItemOptions($itemOpts);
        // Run the search and get a results obj.
        $result = $getter->getProduct();
        return array(
            "result" => "OK",
            "output" => $result["output"]
        );
    }

    /**
     * @func constructResults($productResults)
     *  - Construction of our product details using our product view template.
     * @param   object  $productResults         - $ObjProduct object as provided by our getProduct() function.
     * @return  string  $productOutput          - HTML ready ul with our product view.
     */
    static public function constructResults($productResults){
        $productOutput = productViewTemplates::getProductView($productResults);
        return $productOutput;
    }

    /**
     * @func displayError($msg)
     *  - Gets the template for our product error.
     * @param   string  $msg                - The msg to place in the error page.
     * @return  string  HTML error page.
     */
    static public function displayError($msg){
        return productViewTemplates::getProductErrorContent($msg);
    }
}

?>