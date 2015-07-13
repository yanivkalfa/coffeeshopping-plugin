<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 7/12/2015
 * Time: 3:49 AM
 */

class ebay_MerchandisingAPI extends ebayAdapter {
    // API variables
    public
        $endpoint,              // The eBay Merchandising API address
        $itemID,
        $categoryID,
        $listingType,
        $maxResults,
        $headers;               // Holds our communication headers.


    // Basic construct function.
    public function __construct( ){
        parent::__construct();
        // Set defaults:
        $this->itemID = null;
        $this->categoryID = null;
        $this->listingType = "FixedPriceItem,StoresFixedPrice";
        $this->maxResults = 20;

        // Default comms header.
        $this->headers = array();
        $this->_setDefaultHeaders();
    }


    public function _setItemID($itemID){
        $this->itemID = $itemID;
    }
    public function _setCategoryID($categoryID){
        $this->categoryID = $categoryID;
    }
    public function _setMaxResults($maxResults){
        $this->maxResults = $maxResults;
    }

    /**
     * @func _setDefaultHeaders()
     *  - Resets our headers to the default API headers.
     */
    private function _setDefaultHeaders(){
        $this->headers =
            array(
                'X-EBAY-SOA-SERVICE-NAME'           =>          'MerchandisingService',
                'X-EBAY-SOA-OPERATION-NAME'         =>          '',
                'X-EBAY-SOA-SERVICE-VERSION'        =>          '1.5.0',               // Latest - http://developer.ebay.com/devzone/finding/ReleaseNotes.html
                'X-EBAY-SOA-GLOBAL-ID'              =>          'EBAY-US',
                'X-EBAY-SOA-SECURITY-APPNAME'       =>          $this->appid,
                'X-EBAY-SOA-REQUEST-DATA-FORMAT'    =>          'XML',
            );
    }

    /**
     * @func _setSandbox()
     *  - Sets our search to use the ebay sandbox url.
     */
    public function _setSandbox(){
        $this->endpoint = "http://svcs.sandbox.ebay.com/MerchandisingService";
    }

    /**
     * @func _setLive()
     *  - Sets our search to use the ebay live url.
     */
    public function _setLive(){
        $this->endpoint = "http://svcs.ebay.com/MerchandisingService?";
    }

    public function getMostWatchedItems(){
        // Build our URL:
        $url = $this->endpoint."OPERATION-NAME=getMostWatchedItems&SERVICE-VERSION=1.5.0&CONSUMER-ID=".$this->appid."&RESPONSE-DATA-FORMAT=XML&REST-PAYLOAD";
        $url .= "&maxResults=".$this->maxResults;
        if ($this->categoryID){
            $url .= "&categoryId=".$this->categoryID;
        }
        $merchandisingRaw = Utils::get_url($url, "GET");

        if ($merchandisingRaw["result"]=="ERROR"){
            Utils::adminPreECHO($merchandisingRaw["output"], "cURL ERROR details:: ");
            return array(
                'result' => "ERROR",
                "output" => Utils::getErrorCode("API", "ebay", "getMostWatchedItems", "1")
            );
        }
        $merchandisingRaw = $merchandisingRaw["output"];

        // Parse our products
        $merchandising = simplexml_load_string($merchandisingRaw);

        // Checks to see if we have any type of failed call.
        if (!isset($merchandising->ack) || $merchandising->ack == "Failure" || $merchandising->ack == "PartialFailure") {
            // Returns an error.
            Utils::adminPreECHO("(".$merchandising->errorMessage->error->errorId.") - ".$merchandising->errorMessage->error->category." - ".$merchandising->errorMessage->error->message."\n", " getSearch() ERROR:: ");
            return array(
                'result' => "ERROR",
                "output" => Utils::getErrorCode("API", "ebay", "getMostWatchedItems", "6")
            );
        }

        // Reformat our results:
        $formatMerchandisingOutput = $this->_formatMerchandisingOutput($merchandising);
        if (count($formatMerchandisingOutput)==0){Utils::adminPreECHO('Empty results from: ebay_MerchandisingAPI::getMostWatchedItems().');}

        // Returns a proper products object.
        return array(
            'result' => "OK",
            "output" => $formatMerchandisingOutput
        );
    }


    public function getRelatedCategoryItems(){
        // Build our URL:
        $url = $this->endpoint."OPERATION-NAME=getRelatedCategoryItems&SERVICE-NAME=MerchandisingService&SERVICE-VERSION=1.5.0&CONSUMER-ID=".$this->appid."&RESPONSE-DATA-FORMAT=XML&REST-PAYLOAD";
        $url .= "&maxResults=".$this->maxResults;
        if ($this->categoryID){
            $url .= "&categoryId=".$this->categoryID;
        }
        if ($this->itemID){
            $url .= "&itemId=".$this->itemID;
        }
        $merchandisingRaw = Utils::get_url($url, "GET");

        if ($merchandisingRaw["result"]=="ERROR"){
            Utils::adminPreECHO($merchandisingRaw["output"], "cURL ERROR details:: ");
            return array(
                'result' => "ERROR",
                "output" => Utils::getErrorCode("API", "ebay", "getRelatedCategoryItems", "1")
            );
        }
        $merchandisingRaw = $merchandisingRaw["output"];

        // Parse our products
        $merchandising = simplexml_load_string($merchandisingRaw);

        // Checks to see if we have any type of failed call.
        if (!isset($merchandising->ack) || $merchandising->ack == "Failure" || $merchandising->ack == "PartialFailure") {
            // Returns an error.
            Utils::adminPreECHO("(".$merchandising->errorMessage->error->errorId.") - ".$merchandising->errorMessage->error->category." - ".$merchandising->errorMessage->error->message."\n", " getSearch() ERROR:: ");
            return array(
                'result' => "ERROR",
                "output" => Utils::getErrorCode("API", "ebay", "getRelatedCategoryItems", "6")
            );
        }

        // Reformat our results:
        $formatMerchandisingOutput = $this->_formatMerchandisingOutput($merchandising);
        if (count($formatMerchandisingOutput)==0){Utils::adminPreECHO('Empty results from: ebay_MerchandisingAPI::getRelatedCategoryItems().');}

        // Returns a proper products object.
        return array(
            'result' => "OK",
            "output" => $formatMerchandisingOutput
        );
    }


    public function getSimilarItems(){
        // Build our URL:
        $url = $this->endpoint."OPERATION-NAME=getSimilarItems&SERVICE-NAME=MerchandisingService&SERVICE-VERSION=1.5.0&CONSUMER-ID=".$this->appid."&RESPONSE-DATA-FORMAT=XML&REST-PAYLOAD";
        $url .= "&listingType=FixedPriceItem,StoresFixedPrice";
        $url .= "&maxResults=".$this->maxResults;
        if ($this->categoryID){
            $url .= "&categoryId=".$this->categoryID;
        }
        if ($this->itemID){
            $url .= "&itemId=".$this->itemID;
        }
        $merchandisingRaw = Utils::get_url($url, "GET");

        if ($merchandisingRaw["result"]=="ERROR"){
            Utils::adminPreECHO($merchandisingRaw["output"], "cURL ERROR details:: ");
            return array(
                'result' => "ERROR",
                "output" => Utils::getErrorCode("API", "ebay", "getSimilarItems", "1")
            );
        }
        $merchandisingRaw = $merchandisingRaw["output"];

        // Parse our products
        $merchandising = simplexml_load_string($merchandisingRaw);

        // Checks to see if we have any type of failed call.
        if (!isset($merchandising->ack) || $merchandising->ack == "Failure" || $merchandising->ack == "PartialFailure") {
            // Returns an error.
            Utils::adminPreECHO("(".$merchandising->errorMessage->error->errorId.") - ".$merchandising->errorMessage->error->category." - ".$merchandising->errorMessage->error->message."\n", " getSearch() ERROR:: ");
            return array(
                'result' => "ERROR",
                "output" => Utils::getErrorCode("API", "ebay", "getSimilarItems", "6")
            );
        }

        // Reformat our results:
        $formatMerchandisingOutput = $this->_formatMerchandisingOutput($merchandising);
        if (count($formatMerchandisingOutput)==0){Utils::adminPreECHO('Empty results from: ebay_MerchandisingAPI::getSimilarItems().');}

        // Returns a proper products object.
        return array(
            'result' => "OK",
            "output" => $formatMerchandisingOutput
        );
    }



    /**
     * @func _formatSearchOutput($searchOutput)
     *  - Creates a "proper" search object to display our results.
     * @param   object      $merchandisingOutput       - As returned by ebay XML API calls.
     * @return  object      $ObjMerchandising          - Merchandising results object:
     *                                              array of items fit for product list display.
     */
    public function _formatMerchandisingOutput($merchandisingOutput){
        $ObjMerchandising = array();
        $index = 0;
        foreach ($merchandisingOutput->itemRecommendations->item as $item){
            $ObjMerchandising[$index]  = array(
                "productID"         => (string)$item->itemId,
                "store"             => (string)"ebay",
                "title"             => (string)$item->title,
                "image"             => (string)$item->imageURL,
                "price"             => (isset($item->currentPrice)) ? (string)$item->currentPrice : (string)$item->buyItNowPrice,
                "priceCurrency"     => (isset($item->currentPrice["currencyId"])) ? (string)$item->currentPrice["currencyId"] : (string)$item->buyItNowPrice["currencyId"],
                "shipping"          => (string)$item->shippingCost,
                "shippingCurrency"  => (string)$item->shippingCost["currencyId"],
                "listname"          => "",
            );
            // get exchange rates:
            Utils::addExchangeKeys($ObjMerchandising[$index],  Array("price", "shipping"));
            $index++;
        }
        return $ObjMerchandising;
    }

}
?>



<?php

/*

EBAY APPEARS TO HAVE ISSUES WITH THE XML VERSION!
::: getMostWatchedItemsRequest :::

        $xmlrequest  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $xmlrequest .= "<getMostWatchedItemsRequest xmlns=\"http://www.ebay.com/marketplace/services\">\n";
        if ($this->categoryID){
            $xmlrequest .= "<categoryId>".$this->categoryID."</categoryId>\n";
        }
        $xmlrequest .= "<maxResults>".$this->maxResults."</maxResults>\n";
        $xmlrequest .= "</getMostWatchedItemsRequest>\n";

        // Set our headers properly
        $this->headers["X-EBAY-SOA-OPERATION-NAME"] = "getMostWatchedItems";

        // Make the call to eBay.
        $merchandisingRaw = Utils::get_url($this->endpoint, "POST", $this->_formCurlHeaders($this->headers), $xmlrequest);
*/


/*

EBAY APPEARS TO HAVE ISSUES WITH THE XML VERSION!
::: getRelatedCategoryItemsRequest :::

        $xmlrequest  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $xmlrequest .= "<getRelatedCategoryItemsRequest xmlns=\"http://www.ebay.com/marketplace/services\">\n";
        if ($this->categoryID){
            $xmlrequest .= "<categoryId>".$this->categoryID."</categoryId>\n";
        }
        if ($this->itemID){
            $xmlrequest .= "<itemId>".$this->itemID."</itemId>\n";
        }
        $xmlrequest .= "<maxResults>".$this->maxResults."</maxResults>\n";
        $xmlrequest .= "</getRelatedCategoryItemsRequest>\n";

        // Set our headers properly
        $this->headers["X-EBAY-SOA-OPERATION-NAME"] = "getRelatedCategoryItems";

        // Make the call to eBay.
        $merchandisingRaw = Utils::get_url($this->endpoint, "POST", $this->_formCurlHeaders($this->headers), $xmlrequest);

 */


/*
 *
EBAY APPEARS TO HAVE ISSUES WITH THE XML VERSION!
::: getSimilarItemsRequest :::

        $xmlrequest  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $xmlrequest .= "<getSimilarItemsRequest xmlns=\"http://www.ebay.com/marketplace/services\">\n";
        if ($this->categoryID){
            $xmlrequest .= "<categoryId>".$this->categoryID."</categoryId>\n";
        }
        if ($this->itemID){
            $xmlrequest .= "<itemId>".$this->itemID."</itemId>\n";
        }
        $xmlrequest .= "<listingType>".$this->listingType."</listingType>\n";
        $xmlrequest .= "<maxResults>".$this->maxResults."</maxResults>\n";
        $xmlrequest .= "</getSimilarItemsRequest>\n";

        // Set our headers properly
        $this->headers["X-EBAY-SOA-OPERATION-NAME"] = "getSimilarItems";

        // Make the call to eBay.
        $merchandisingRaw = Utils::get_url($this->endpoint, "POST", $this->_formCurlHeaders($this->headers), $xmlrequest);

 */
?>