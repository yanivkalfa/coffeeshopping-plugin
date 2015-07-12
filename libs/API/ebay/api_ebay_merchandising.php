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
        $this->endpoint = "http://svcs.ebay.com/MerchandisingService";
    }

    public function getMostWatchedItems(){
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
        // Returns a proper products object.
        return array(
            'result' => "OK",
            "output" => $this->_formatMerchandisingOutput($merchandising)
        );
    }


    public function getRelatedCategoryItems(){
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
        // Returns a proper products object.
        return array(
            'result' => "OK",
            "output" => $this->_formatMerchandisingOutput($merchandising)
        );
    }


    public function getSimilarItems(){
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
        // Returns a proper products object.
        return array(
            'result' => "OK",
            "output" => $this->_formatMerchandisingOutput($merchandising)
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
        $ObjMerchandising = new stdClass();
        $ObjMerchandising->item = array();
        $index = 0;
        foreach ($merchandisingOutput->itemRecommendations->item as $item){
            $ObjMerchandising->item[$index]  = array(
                "productID"         => $item->itemId,
                "store"             => "ebay",
                "title"             => $item->title,
                "image"             => $item->imageURL,
                "price"             => $item->currentPrice ,
                "priceCurrency"     => $item->currentPrice["currencyId"],
                "shipping"          => $item->shippingCost,
                "shippingCurrency"  => $item->shippingCost["currencyId"],
                "listname"          => "",
            );
            // get exchange rates:
            Utils::addExchangeKeys($ObjMerchandising->item[$index],  Array("price", "shipping"));
            $index++;
        }
        return $ObjMerchandising;
    }

}
?>