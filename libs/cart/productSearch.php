<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 5/25/2015
 * Time: 10:13 PM
 */

// no direct access
//defined('ABSPATH') or die('Restricted access');

/*
 * Search Options list:
 * [Provide in an array["optName" => "optValue"], eg. Array("searchDescription" => falue);
 *      searchDescription = "false";      //  Should we search in the description? [true,false]
        entriesPerPage = 10;              //  [Min: 1. Max: 100. Default: 100.]
        pageToGet = 1;                    //  [Min: 1. Max: 100. Default: 1.]
        filters = array();                //  Filter our search - Array(array('name' => 'filtername','value' => 'filtervalue','paramName' => 'name','paramValue' => 'value'));
        aspects = array();                //  Aspect filter - Array("aspectName1" => array("value1", "value2", "value3"...),"aspectName2" => array("value1", "value2", "value3"...)...)
        categories = array();             //  Categories for the search - Array("categoryID1", "categoryID2", "categoryID3"...)
        sortOrder = "BestMatch";          //  Search results sorting order. [BestMatch, PricePlusShippingHighest, PricePlusShippingLowest]
        searchQuery = "";                 //  Our search query.
 */
class productSearch {
    public $searchVal = "";                             // Holds our search value.
    public $activeAPIs = array();                       // Which APIs are in use.
    public $sandbox = false;                            // Do we use sandbox or live.
    public $searchOpts = array();                       // Holds our search options as specified for APIs.

    private function _includeAPIs($APIs){
        foreach($APIs as $api){
            require_once ABSPATH . "/wp-content/themes/" . get_template() . "/lib/apis/api_".$api."_adapter.php";
        }
    }

    public function __construct(){
        // Vars should be loaded from wp settings/get/post of page.
        $this->searchVal = "samsung galaxy gear";
        $this->searchOpts = array();
        $this->activeAPIs = array("ebay");
        $this->sandbox = false;

        // Actual construct.
        $this->_includeAPIs($this->activeAPIs); // include our active APIs.
    }

    public function performSearch(){
        echo "searching for: ".$this->searchVal;
        $searchResults = array();

        // perform the search for each active API we have.
        foreach($this->activeAPIs as $API){
            // Check if our FindingAPI exists.
            if (!class_exists($API."_FindingAPI")){continue;}
            $apiClass = $API."_FindingAPI";
            $finder = new $apiClass();
            // Set the API to work live/sandbox.
            if ($this->sandbox){
                $finder->_setSandbox();
            }else{
                echo "SET LIVE NOW BIATCH";
                $finder->_setLive();
                echo "LIVE IS SET MOFO";
            }
            // Set our search query.
            $finder->_setSearchQuery($this->searchVal);
            // Set our search options.
            $finder->_setSearchOptions($this->searchOpts);
            // Run the search and get a results obj.
            $result = $finder->getSearch();
            if ($result["result"]=="OK"){
                $searchResults[] = $result["output"];
            }else{
                $searchResults[] = "$API-".$result["output"];
            }
        }
        return $searchResults;
    }

    // Construction
    public function constructSearchResults(){

    }
}

/*
$src = new productSearch();
$src->searchOpts = array("pageToGet" => 1);
$src->searchVal = "samsung galaxy gear";
$src->search()
*/

?>
