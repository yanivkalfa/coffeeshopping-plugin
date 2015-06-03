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

    static public function getProduct($API, $productID, $itemOpts = array(), $sandbox = false){
        // Check if our Adapter exists.
        if ( !utils::API_Exists($API) ){
            return array(
                "result" => "ERROR",
                "output" => "API class (".$API."Adapter) doesn't exists, can't get product!"
            );
        }
        $apiClass = $API."_ShoppingAPI";
        if (!class_exists($apiClass)){
            return array(
                "result" => "ERROR",
                "output" => "API class ($apiClass) doesn't exists, can't get product!"
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
        utils::preEcho("_setItemID:".$productID);
        $getter->_setItemID($productID);
        // Set our item options.
        $getter->_setItemOptions($itemOpts);
        // Run the search and get a results obj.
        $result = $getter->getProduct();
        return $result;
    }

    static public function constructResults(){

    }
}

?>