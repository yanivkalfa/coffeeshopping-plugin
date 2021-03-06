<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 5/25/2015
 * Time: 10:13 PM
 */

// no direct access
//defined('ABSPATH') or die('Restricted access');

abstract class productSearch {
    /**
     * @func search_API($API, $searchVal [$searchOpts = array(), $sandbox = false])
     *  - performs a search on specific API with the given specified details.
     * @param   string    $API                    - The API to use for the search.
     * @param   string    $searchVal              - Holds our search value.
     * @param   array     $searchOpts [Optional]  - Holds our search options as specified for the given API.
     * @param   boolean   $sandbox    [Optional]  - Do we use the sandbox or live? (defaults to live)
     * @return  array     $result                 - An array with 2 keys - result/output.
     *      - Success - result = "OK"
     *                  output = $ObjSearch object ready for constructResults(). [Proper example format found at: ebay_FindingAPI::_formatSearchOutput()]
     *                  $ObjSearch          - Search results object:
     *                                              int count.
     *                                              array paginationOutput ("pageNumber", "entriesPerPage", "totalPages", "totalEntries")
     *                                              array item ("ID", "image", "title", "subtitle", "price", "priceCurrency",
     *                                                          "shippingType", "locationInfo", "isTopSeller", "categoryText", "conditionText")
     *
     *      - Failure - result = "ERROR",
     *                  output = Error code, codes reference at: Utils::getErrorCodeText($errorCode).
     */
    static public function searchAPI($API, $searchVal, $searchOpts = array(), $sandbox = false){
        // Check if our Adapter exists.
        if ( !Utils::API_Exists($API) ){
            Utils::adminPreECHO(sprintf( __( 'API class (%1$s Adapter) doesn\'t exists, can\'t search!', 'coffee-shopping' ), $API ), __( "searchAPI() ERROR:: ", 'coffee-shopping' ));
            return array(
                "result" => "ERROR",
                "output" => Utils::getErrorCode("frontEnd", "productSearch", "searchAPI", "6")
            );
        }
        $apiClass = $API."_FindingAPI";
        if (!class_exists($apiClass)){
            Utils::adminPreECHO(sprintf( __( 'API class (%1$s) doesn\'t exists, can\'t search!', 'coffee-shopping' ), $apiClass ), __( "searchAPI() ERROR:: ", 'coffee-shopping' ));
            return array(
                "result" => "ERROR",
                "output" => Utils::getErrorCode("frontEnd", "productSearch", "searchAPI", "6")
            );
        }

        $finder = new $apiClass();
        // Set the API to work live/sandbox.
        if ($sandbox){
            $finder->_setSandbox();
        }else{
            $finder->_setLive();
        }
        // Set our search query.
        $finder->_setSearchQuery($searchVal);
        // Set our search options.
        $finder->_setSearchOptions($searchOpts);
        // Run the search and get a results obj.
        $result = $finder->getSearch();
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
     * @func searchALL($APIs, $searchVal, $searchOpts = array(), $sandbox = false)
     *  - performs a search on multiple APIs with the given specified details.
     * @param   array     $APIs                   - The APIs to use for the search in a simple array. eg array("ebay", "amazon")
     * @param   string    $searchVal              - Holds our search value.
     * @param   array     $searchOpts [Optional]  - Holds our search options as specified for the given API.
     * @param   boolean   $sandbox    [Optional]  - Do we use the sandbox or live? (defaults to live)
     * @return  object    $result                 - An array with 2 keys - result/output.
     *      - Success - result = "OK"
     *                  output = $ObjSearch object ready for constructSearchResults().
     *                  $ObjSearch          - Search results object:
     *                                              int count.
     *                                              array paginationOutput ("pageNumber", "entriesPerPage", "totalPages", "totalEntries")
     *                                              array item ("ID", "image", "title", "subtitle", "price", "priceCurrency",
     *                                                          "shippingType", "locationInfo", "isTopSeller", "categoryText", "conditionText")
     *
     *      - Failure - result = "ERROR",
     *                  output = Error code, codes reference at: Utils::getErrorCodeText($errorCode).
     */
    static public function searchALL($APIs, $searchVal, $searchOpts = array(), $sandbox = false){
        $searchResults = new stdClass();
        $searchResults->count =                 array();
        $searchResults->paginationOutput =      array();
        $searchResults->items =                 array();
        $searchResults->status =                array();
        $searchResults->errors =                array();

        // Keep track of our results, to see if we have any.
        $hasResults = false;
        // perform the search for each active API we have.
        foreach($APIs as $API){
            $result = productSearch::searchAPI($API, $searchVal, $searchOpts, $sandbox);
            $searchResults->status["$API"] = $result["result"];
            if ($result["result"]=="OK"){
                if ($result["output"]->count > 0){
                $hasResults = true;
                // Store our count for current API.
                $searchResults->count["$API"] = $result["output"]->count;
                // Store our pagination of that API.
                $searchResults->paginationOutput["$API"] = $result["output"]->paginationOutput;
                // Store our items per API.
                $searchResults->items["$API"] = $result["output"]->item;
                }
            }else{
                $searchResults->errors["$API"] = $result["output"];
            }
        }
        if ($hasResults){
            return array(
                "result" => "OK",
                "output" => $searchResults
            );
        }else{
            Utils::adminPreECHO($searchResults, __( "searchAPI() ERROR:: ", 'coffee-shopping' ));
            return array(
                "result" => "ERROR",
                "output" => Utils::getErrorCode("frontEnd", "productSearch", "searchALL", "6")
            );
        }

    }

    static public function _addExchangeRates(&$ObjSearch){
        $exchCurrency = EXCH_CURRENCY;
        $exchanger = new currencyExchange();
        foreach($ObjSearch->items as $API => $items){
            foreach($ObjSearch->items[$API] as $item){
                $ObjSearch->items[$API][$item]["priceExch"] = $exchanger->exchangeRateConvert($item['priceCurrency'], $item['price'], $exchCurrency);
            }
        }
    }
}

/* ------------------- DEAD OR UNUSED CODE ---------------- */

/*
 * Search Options list:
 * [Provide in an array["optName" => "optValue"], eg. Array("searchDescription" => falue);
 *      searchDescription = "false";      //  Should we search in the description? [true,false]
        entriesPerPage = 10;              //  [Min: 1. Max: 100. Default: 100.]
        pageToGet = 1;                    //  [Min: 1. Max: 100. Default: 1.]
        filters = array();                //  Filter our search - Array(array('name' => 'filtername','value' => 'filtervalue','paramName' => 'name','paramValue' => 'value'));
        aspects = array();                //  Aspect filter - Array("aspectName1" => array("value1", "value2", "value3"...),"aspectName2" => array("value1", "value2", "value3"...)...)
        categories = array();             //  Categories for the search - Array("categoryID1", "categoryID2", "categoryID3"...)
        outputSelector = array();         //  OutputSelector for the search - Array("OutputSelector1", "OutputSelector2"...)
        sortOrder = "BestMatch";          //  Search results sorting order. [BestMatch, PricePlusShippingHighest, PricePlusShippingLowest]
        searchQuery = "";                 //  Our search query.
*/

/*
// Vars should be loaded from wp settings/get/post of page.
        $this->searchVal = "samsung galaxy gear";
        $this->searchOpts = array();
        $this->activeAPIs = array("ebay");
        $this->sandbox = false;

$src = new productSearch();
$src->searchOpts = array("pageToGet" => 1);
$src->searchVal = "samsung galaxy gear";
echo constructSearchResults($src->search());
*/


/* ------------------- DEAD OR UNUSED CODE ---------------- */

?>



