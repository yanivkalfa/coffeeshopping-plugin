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

    /**
     * @func getRelatedCategoryItems($API, $itemID = "", $catID = "", $limit = 20, $sandbox = false)
     *  -
     * @param        $API
     * @param string $itemID    - Either itemID |OR| catID must be provided!
     * @param string $catID     - Either itemID |OR| catID must be provided!
     * @param int    $limit
     * @param bool   $sandbox
     * @return array
     */
    static public function getRelatedCategoryItems($API, $itemID = "", $catID = "", $limit = 20, $sandbox = false){
        if ($itemID=="" && $catID==""){
            Utils::adminPreECHO("ERROR: Missing args - itemID/catID must be provided!");
            return array(
                "result" => "ERROR",
                "output" => "ERROR: Missing args - itemID/catID must be provided!"
            );
        }
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

    static public function getProductListByType($options){
        if (!isset($options['type']) || empty($options['type'])) {
            return array(
                "result" => "ERROR",
                "output" => "no type specified!"
            );
        }
        switch ($options['type']){
            case "savedlist":
                if (!isset($options['listname']) || empty($options['listname'])){
                    return array(
                        "result" => "ERROR",
                        "output" => "no listname specified!"
                    );
                }
                $result = ProductsLists::getSavedProductsList($options['listname']);
                break;

            case "mostwatched":
                if (!isset($options['api']) || empty($options['api'])){
                    return array(
                        "result" => "ERROR",
                        "output" => "no api specified!"
                    );
                }
                $catID = (isset($options['catid']))?$options['catid']:"";
                $limit = (isset($options['limit']))?$options['limit']:20;
                $result = productRecommendations::getMostWatchedItems($options['api'], $catID, $limit);
                break;

            case "relateditems":
                if (!isset($options['api']) || empty($options['api'])){
                    return array(
                        "result" => "ERROR",
                        "output" => "no api specified!"
                    );
                }

                $itemID = (isset($options['itemid'])) ? $options['itemid']:"";
                $itemID = (isset($_GET["view-product"])) ? $_GET["view-product"]:$itemID;
                $catID = (isset($options['catid']))?$options['catid']:"";
                $limit = (isset($options['limit']))?$options['limit']:20;
                $result = productRecommendations::getRelatedCategoryItems($options['api'], $itemID, $catID, $limit);
                break;

            case "similaritems":
                if (!isset($options['api']) || empty($options['api'])){
                    return array(
                        "result" => "ERROR",
                        "output" => "no api specified!"
                    );
                }
                if (!isset($options['itemid']) || empty($options['itemid'])){
                    if (!isset($_GET["view-product"]) || empty($_GET["view-product"])){
                        return array(
                            "result" => "ERROR",
                            "output" => "no itemid specified and view-product wasn't found."
                        );
                    }else{
                        $itemID = $_GET["view-product"];
                    }
                }else{
                    $itemID = $options['itemid'];
                }
                $catID = (isset($options['catid']))?$options['catid']:"";
                $limit = (isset($options['limit']))?$options['limit']:20;
                $result = productRecommendations::getSimilarItems($options['api'], $itemID, $catID, $limit);
                break;

            case "specificids":
                if (!isset($options['api']) || empty($options['api'])){
                    return array(
                        "result" => "ERROR",
                        "output" => "no api specified!"
                    );
                }
                if (!isset($options['specificids']) || empty($options['specificids'])){
                    return array(
                        "result" => "ERROR",
                        "output" => "no specificids specified"
                    );
                }
                if (preg_match_all("/\\d+/", $options['specificids'], $specificIDs, PREG_PATTERN_ORDER)){
                    $specificIDs = $specificIDs[0];
                }
                $result = ProductsLists::getProductsByIDs($options['api'], $specificIDs);
                break;
        }

        if ($result["result"]=="ERROR") {
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