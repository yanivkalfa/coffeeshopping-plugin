<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 5/21/2015
 * Time: 2:49 AM
 */
class ebay_FindingAPI extends ebayAdapter {
    // API variables
    public $endpoint;               // The eBay Finding API address
    public $searchOptions;          // To hold our options.
    public $headers;                // Holds our communication headers.


    // Basic construct function.
    public function __construct( ){
        parent::__construct();
        // Get and set search options.
        $this->searchOptions = new stdClass();
        $this->searchOptions->searchDescription = "false";          //  Should we search in the description? [true,false]
        $this->searchOptions->entriesPerPage = 10;                  //  [Min: 1. Max: 100. Default: 100.]
        $this->searchOptions->pageToGet = 1;                        //  [Min: 1. Max: 100. Default: 1.]
        $this->searchOptions->filters = array();                    //  Filter our search - Array(array('name' => 'filtername','value' => 'filtervalue','paramName' => 'name','paramValue' => 'value'));
        $this->searchOptions->aspects = array();                    //  Aspect filter - Array("aspectName1" => array("value1", "value2", "value3"...),"aspectName2" => array("value1", "value2", "value3"...)...)
        $this->searchOptions->categories = array();                 //  Categories for the search - Array("categoryID1", "categoryID2", "categoryID3"...)
        $this->searchOptions->outputSelector = array();             //  OutputSelector for the search - Array("OutputSelector1", "OutputSelector2"...)
        $this->searchOptions->sortOrder = "BestMatch";              //  Search results sorting order. [BestMatch, PricePlusShippingHighest, PricePlusShippingLowest, StartTimeNewest]
        $this->searchOptions->searchQuery = "";                     //  Our search query.

        // Default comms header.
        $this->headers = array();
        $this->_setDefaultHeaders();
    }

    /*
     * @func _setSearchQuery()
     *  - Sets our searching query.
     */
    public function _setSearchQuery($searchQuery){
        $this->searchOptions->searchQuery = $searchQuery;
    }
    /*
     * @func _setSearchOptions()
     *  - Sets our searching options.
     */
    public function _setSearchOptions($searchOptions){
        foreach($searchOptions as $optName => $optValue){
            if (isset($this->searchOptions->$optName)){ $this->searchOptions->$optName = $optValue; }
        }
    }

    /*
     * @func _setDefaultHeaders()
     *  - Resets our headers to the default API headers.
     */
    private function _setDefaultHeaders(){
        $this->headers =
            array(
                'X-EBAY-SOA-OPERATION-NAME' =>      '',
                'X-EBAY-SOA-SERVICE-VERSION' =>     '1.13.0',       // Latest - http://developer.ebay.com/devzone/finding/ReleaseNotes.html
                'X-EBAY-SOA-REQUEST-DATA-FORMAT' => 'XML',
                'X-EBAY-SOA-GLOBAL-ID' =>           'EBAY-US',
                'X-EBAY-SOA-SECURITY-APPNAME' =>    $this->appid,
                'Content-Type' =>                   'text/xml;charset=utf-8'
            );
    }

    /*
     * @func _setSandbox()
     *  - Sets our search to use the ebay sandbox url.
     */
    public function _setSandbox(){
        $this->endpoint = "http://svcs.sandbox.ebay.com/services/search/FindingService/v1";
    }

    /*
     * @func _setLive()
     *  - Sets our search to use the ebay live url.
     */
    public function _setLive(){
        $this->endpoint = "http://svcs.ebay.com/services/search/FindingService/v1";
    }

    /**
     * @func buildXMLFilter($filterarray)
     *  - Forms an XML filter from our filter array.
     *  -(for filter details: http://developer.ebay.com/DevZone/finding/CallRef/findItemsAdvanced.html#Request.itemFilter)
     * @param array $filterarray - An array of filter arrays.
     *      eg: array(array('name' => '$filtername','value' => '$filtervalue','paramName' => '$name','paramValue' => '$value'));
     * @return string $xmlfilter - The filter in XML format.
     */
    private function _buildXMLFilter ($filterarray) {
        $xmlfilter = "";
        // Iterate through each filter in the array
        foreach ($filterarray as $itemfilter) {
            $xmlfilter .= "<itemFilter>\n";
            // Iterate through each key in the filter
            foreach($itemfilter as $key => $value) {
                if(is_array($value)) {
                    // If value is an array, iterate through each array value
                    foreach($value as $arrayval) {
                        $xmlfilter .= " <$key>$arrayval</$key>\n";
                    }
                }
                else {
                    if($value != "") {
                        $xmlfilter .= " <$key>$value</$key>\n";
                    }
                }
            }
            $xmlfilter .= "</itemFilter>\n";
        }
        return "$xmlfilter";
    }

    /*
     * @func _buildXMLAspectFilter($aspectFilterArr)
     *  - Creates a proper xml aspect filter to use in API calls.
     * @param      array   $aspectFilterArr
     *  - An array in the format:
     *    Array("aspectName1" => array("value1", "value2", "value3"...),"aspectName2" => array("value1", "value2", "value3"...)...)
     * @return     string  $xmlAspects
     */
    private function _buildXMLAspectFilter($aspectFilterArr){
        $xmlAspects = "";
        // Iterate through each aspect in the array
        foreach ($aspectFilterArr as $aspect => $values) {
            $xmlAspects .= "<aspectFilter>\n";
            $xmlAspects .= "<aspectName>$aspect</aspectName>\n";
            if(is_array($values)) {
                // Iterate through each value for this aspect.
                foreach($values as $value) {
                    $xmlAspects .= "<aspectValueName>$value</aspectValueName>\n";
                }
            }else{
                $xmlAspects .= "<aspectValueName>$values</aspectValueName>\n";
            }
            $xmlAspects .= "</aspectFilter>\n";
        }
        return "$xmlAspects";
    }

    /*
     * @func _buildXMLCategories($categoriesArray)
     *  - Creates a proper XML categories filter for API calls.
     * @param      array    $categoriesArray - simple categories ID array, format: array("categoryID1", "categoryID2", "categoryID3"...)
     */
    private function _buildXMLCategories($categoriesArray){
        $xmlCats = "";
        foreach ($categoriesArray as $categoryID) {
            $xmlCats .= "<categoryId> $categoryID </categoryId>\n";
        }
        return "$xmlCats";
    }

    /*
     * @func _buildXMLOutputSelector($outputSelectorArray)
     *  - Creates a proper XML categories filter for API calls.
     * @param      array    $outputSelectorArray - simple outputSelector array, format: array("outputSelector1", "outputSelector2"...)
     */
    private function _buildXMLOutputSelector($outputSelectorArray){
        $xmlOutput = "";
        foreach ($outputSelectorArray as $outputSelector) {
            $xmlCats .= "<outputSelector> $outputSelector </outputSelector>\n";
        }
        return "$xmlOutput";
    }


    /**
     * @func getSearch()
     *  - performs a search on ebay's servers using their API.
     *  @return     array
     *                  [result] -  OK/ERROR
     *                  [output]
     *                          - OK - Products object.
     *                          - ERROR - Error msg.
     */
    public function getSearch(){
        if (!isset($this->searchOptions->searchQuery) || strlen($this->searchOptions->searchQuery)<2){
            // No proper search query - return error.
            return array(
                'result' => "ERROR",
                "output" => "ERROR:: (internal) improper search string"
            );
        }

        // Create the XML request to be POSTed
        $xmlrequest  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $xmlrequest .= "<findItemsAdvancedRequest xmlns=\"http://www.ebay.com/marketplace/search/v1/services\">\n";
        if (!empty($this->searchOptions->aspects)){
            $xmlrequest .= $this->_buildXMLAspectFilter($this->searchOptions->aspects);
        }
        if (!empty($this->searchOptions->categories)) {
            $xmlrequest .= $this->_buildXMLCategories($this->searchOptions->categories);
        }
        if (!empty($this->searchOptions->filters)) {
            $xmlrequest .= $this->_buildXMLFilter($this->searchOptions->filters);
        }
        $xmlrequest .= "<descriptionSearch>".$this->searchOptions->searchDescription."</descriptionSearch>\n";
        $xmlrequest .= "<keywords>".$this->searchOptions->searchQuery."</keywords>\n";
        if (!empty($this->searchOptions->outputSelector)) {
            $xmlrequest .= $this->_buildXMLOutputSelector($this->searchOptions->outputSelector);
        }
        $xmlrequest .= "<paginationInput>\n";
        $xmlrequest .= "<entriesPerPage>".$this->searchOptions->entriesPerPage."</entriesPerPage>\n";
        $xmlrequest .= "<pageNumber>".$this->searchOptions->pageToGet."</pageNumber>\n";
        $xmlrequest .= "</paginationInput>\n";
        $xmlrequest .= "<sortOrder>".$this->searchOptions->sortOrder."</sortOrder>\n";
        $xmlrequest .= "</findItemsAdvancedRequest>\n";

        // Set our headers properly
        $this->headers["X-EBAY-SOA-OPERATION-NAME"] = "findItemsAdvanced";

        // Make the call to eBay.
        $searchRaw = Utils::get_url($this->endpoint, "POST", $this->_formCurlHeaders($this->headers), $xmlrequest);

        // Parse our products
        $search = simplexml_load_string($searchRaw);

        // Checks to see if we have any type of failed call.
        if ($search->ack == "Failure" || $search->ack == "PartialFailure") {
            // Returns an error.
            return array(
                'result' => "ERROR",
                "output" => "ERROR:: (".$search->errorMessage->error->errorId.") - ".$search->errorMessage->error->category."\nERROR-MESSAGE:".$search->errorMessage->error->message."\n"
            );
        }
        // Returns a proper products object.
        return array(
            'result' => "OK",
            "output" => $this->_formatSearchOutput($search)
        );
    }

    /**
     * @func _formatSearchOutput($searchOutput)
     *  - Creates a "proper" search object to display our results.
     * @param   object      $searchOutput       - As returned by ebay's 'findItemsAdvanced' XML API call.
     * @return  object      $ObjSearch          - Search results object:
     *                                              int count.
     *                                              array paginationOutput ("pageNumber", "entriesPerPage", "totalPages", "totalEntries")
     *                                              array item ("ID", "image", "title", "subtitle", "price", "priceCurrency",
     *                                                          "shippingType", "locationInfo", "isTopSeller", "categoryText", "conditionText")
     */
    public function _formatSearchOutput($searchOutput){
        $ObjSearch = new stdClass();
        $ObjSearch->count =     (int)$searchOutput->searchResult["count"];
        $ObjSearch->paginationOutput = array(
            "pageNumber" =>     (int)$searchOutput->paginationOutput->pageNumber,
            "entriesPerPage" => (int)$searchOutput->paginationOutput->entriesPerPage,
            "totalPages" =>     (int)$searchOutput->paginationOutput->totalPages,
            "totalEntries" =>   (int)$searchOutput->paginationOutput->totalEntries,
        );
        if ($ObjSearch->count == 0){
            return $ObjSearch;
        }
        $ObjSearch->item = array();
        foreach ($searchOutput->searchResult->item as $item){
            $ObjSearch->item[] = array(
                "ID" =>             (int)$item->itemId,
                "image" =>          (string)$item->galleryURL,
                "title" =>          (string)$item->title,
                "subtitle" =>       (string)$item->subtitle,
                "price" =>          (string)$item->sellingStatus->convertedCurrentPrice,
                "priceCurrency" =>  (string)$item->sellingStatus->convertedCurrentPrice["currencyId"],
                "shippingType" =>   (string)$item->shippingInfo->shippingType,
                "locationInfo" =>   (string)$item->location,
                "isTopSeller" =>    (string)$item->topRatedListing,
                "categoryText" =>   (string)$item->primaryCategory->categoryName,
                "conditionText" =>  (string)$item->condition->conditionDisplayName
            );
        }
        return $ObjSearch;
    }

}
?>