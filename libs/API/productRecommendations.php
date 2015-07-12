<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 7/12/2015
 * Time: 5:09 AM
 */

abstract class productRecommendations {

    static public function getMostWatchedItems($API, $catID = "", $limit = 20, $sandbox = false){
        // Check if our Adapter exists.
        if ( !Utils::API_Exists($API) ){
            Utils::adminPreECHO(sprintf( __( 'API class (%1$s Adapter) doesn\'t exists, can\'t get product!', 'coffee-shopping' ), $API ), __( "getMostWatchedItems() ERROR:: ", 'coffee-shopping' ));
            return array(
                "result" => "ERROR",
                "output" => Utils::getErrorCode("frontEnd", "productRecommendations", "getMostWatchedItems", "2")
            );
        }
        $apiClass = $API."_MerchandisingAPI";
        if (!class_exists($apiClass)){
            Utils::adminPreECHO(sprintf( __( 'API class (%1$s) doesn\'t exists, can\'t get product!', 'coffee-shopping' ), $apiClass ), __( "getMostWatchedItems() ERROR:: ", 'coffee-shopping' ));
            return array(
                "result" => "ERROR",
                "output" => Utils::getErrorCode("frontEnd", "productRecommendations", "getMostWatchedItems", "2")
            );
        }

        $getter = new $apiClass();
        // Set the API to work live/sandbox.
        if ($sandbox){
            $getter->_setSandbox();
        }else{
            $getter->_setLive();
        }
        // Set our category ID.
        if (!empty($catID)) {
            $getter->_setCategoryID($catID);
        }
        $getter->_setMaxResults($limit);

        // get a results obj.
        $result = $getter->getMostWatchedItems();

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

    static public function getRelatedCategoryItems($API, $itemID = "", $catID = "", $limit = 20, $sandbox = false){
        // Check if our Adapter exists.
        if ( !Utils::API_Exists($API) ){
            Utils::adminPreECHO(sprintf( __( 'API class (%1$s Adapter) doesn\'t exists, can\'t get product!', 'coffee-shopping' ), $API ), __( "getRelatedCategoryItems() ERROR:: ", 'coffee-shopping' ));
            return array(
                "result" => "ERROR",
                "output" => Utils::getErrorCode("frontEnd", "productRecommendations", "getRelatedCategoryItems", "2")
            );
        }
        $apiClass = $API."_MerchandisingAPI";
        if (!class_exists($apiClass)){
            Utils::adminPreECHO(sprintf( __( 'API class (%1$s) doesn\'t exists, can\'t get product!', 'coffee-shopping' ), $apiClass ), __( "getRelatedCategoryItems() ERROR:: ", 'coffee-shopping' ));
            return array(
                "result" => "ERROR",
                "output" => Utils::getErrorCode("frontEnd", "productRecommendations", "getRelatedCategoryItems", "2")
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
        if (!empty($itemID)) {
            $getter->_setItemID($itemID);
        }
        // Set our category ID.
        if (!empty($catID)) {
            $getter->_setCategoryID($catID);
        }
        $getter->_setMaxResults($limit);

        // get a results obj.
        $result = $getter->getRelatedCategoryItems();

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

    static public function getSimilarItems($API, $itemID, $catID = "", $limit = 20, $sandbox = false){
        // Check if our Adapter exists.
        if ( !Utils::API_Exists($API) ){
            Utils::adminPreECHO(sprintf( __( 'API class (%1$s Adapter) doesn\'t exists, can\'t get product!', 'coffee-shopping' ), $API ), __( "getRelatedCategoryItems() ERROR:: ", 'coffee-shopping' ));
            return array(
                "result" => "ERROR",
                "output" => Utils::getErrorCode("frontEnd", "productRecommendations", "getRelatedCategoryItems", "2")
            );
        }
        $apiClass = $API."_MerchandisingAPI";
        if (!class_exists($apiClass)){
            Utils::adminPreECHO(sprintf( __( 'API class (%1$s) doesn\'t exists, can\'t get product!', 'coffee-shopping' ), $apiClass ), __( "getRelatedCategoryItems() ERROR:: ", 'coffee-shopping' ));
            return array(
                "result" => "ERROR",
                "output" => Utils::getErrorCode("frontEnd", "productRecommendations", "getRelatedCategoryItems", "2")
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
        $getter->_setItemID($itemID);
        // Set our category ID.
        if (!empty($catID)) {
            $getter->_setCategoryID($catID);
        }
        $getter->_setMaxResults($limit);

        // get a results obj.
        $result = $getter->getSimilarItems();

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