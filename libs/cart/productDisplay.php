<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/2/2015
 * Time: 1:57 PM
 */

// no direct access
//defined('ABSPATH') or die('Restricted access');

abstract class productSearch {
    static public function getProduct($API, $productID, $itemOpts = array(), $sandbox = false){
        // Check if our Adapter exists.
        if (!class_exists($API."Adapter")){
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

        $finder = new $apiClass();
        // Set the API to work live/sandbox.
        if ($sandbox){
            $finder->_setSandbox();
        }else{
            $finder->_setLive();
        }
        // Set our item ID.
        $finder->_setItemID($searchVal);
        // Set our item options.
        $finder->_setItemOptions($searchOpts);
        // Run the search and get a results obj.
        $result = $finder->getProduct();
        return $result;
    }

    static public function constructResults(){

    }
}

?>