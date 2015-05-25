<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 5/21/2015
 * Time: 2:50 AM
 */

class ebay_ShoppingAPI extends ebay_adapter {
    // API variables
    public $endpoint;               // The eBay Shopping API address
    public $itemOptions;            // To hold our options.
    public $headers;                // Holds our communication headers.

    // Basic construct function.
    public function __construct(){
        parent::__construct();
        // Get and set search options.
        $this->itemOptions = new stdClass();
        $this->itemOptions->IncludeSelector = array();      //  Simple array of selectors to request for the item. (empty by default) [Details, Description, TextDescription, ShippingCosts, ItemSpecifics, Variations, Compatibility]
        $this->itemOptions->itemID = "0";                   //  The unique item ID to get info for.

        // Default comms header.
        $this->headers = array();
        $this->_setDefaultHeaders();

    }

    /*
    * @func _setDefaultHeaders()
    *  - Resets our headers to the default API headers.
    */
    private function _setDefaultHeaders(){
        $this->headers =
            array(
                'X-EBAY-API-CALL-NAME' => '',
                'X-EBAY-API-VERSION' => '921',                      // Latest - http://developer.ebay.com/devzone/shopping/docs/ReleaseNotes.html
                'X-EBAY-API-REQUEST-ENCODING' => 'XML',
                'X-EBAY-API-APP-ID' => $this->appid,
                'Content-Type' => 'text/xml;charset=utf-8',
            );
    }
    /*
     * @func _setSandbox()
     *  - Sets our search to use the ebay sandbox url.
     */
    public function _setSandbox(){
        $this->endpoint = "http://open.api.sandbox.ebay.com/shopping";
    }

    /*
     * @func _setLive()
     *  - Sets our search to use the ebay live url.
     */
    public function _setLive(){
        $this->endpoint = "http://open.api.ebay.com/shopping";
    }

    /*
     * @func _BuildXMLItemList($itemIDs)
     *  - Builds a proper XML itemIDs list for ebay's API.
     * @param mixed $itemIDs - string/int/array of ItemID(s).
     * @return string
     */
    public function _BuildXMLItemList($itemIDs){
        if (!is_array($itemIDs)){
            return "<ItemID>".$itemID."</ItemID>\n";
        }
        $xmllist = "";
        // Iterate through each filter in the array
        foreach ($itemIDs as $itemID) {
            $xmllist .= "<ItemID>".$itemID."</ItemID>\n";
        }
        return $xmllist;
    }

    /**
     * @func getProduct()
     *  - Gets a specific product's details.
     *  @return     array
     *                  [result] -  OK/ERROR
     *                  [output]
     *                          - OK - Products object.
     *                          - ERROR - Error msg.
     */
    public function getProduct(){
        // Create the XML request to be POSTed
        $xmlrequest  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $xmlrequest .= "<GetSingleItemRequest xmlns=\"urn:ebay:apis:eBLBaseComponents\">\n";
        if (!empty($this->itemOptions->IncludeSelector)){
            $xmlrequest .= "<IncludeSelector>".implode(",", $this->itemOptions->IncludeSelector)."</IncludeSelector>\n";
        }
        $xmlrequest .= "<ItemID>".$this->itemOptions->itemID."</ItemID>\n";
        $xmlrequest .= "</GetSingleItemRequest>\n";

        // Set our headers properly
        $this->headers["X-EBAY-API-CALL-NAME"] = "GetSingleItem";

        // Make the call to eBay.
        $itemDetailsRaw = get_url($this->endpoint, "POST", $this->_formCurlHeaders($this->headers), $xmlrequest);

        // Parse our result into an object.
        $itemDetails = simplexml_load_string($itemDetailsRaw);

        // Checks to see if we have any type of failed call.
        if ($itemDetails->ack == "Failure" || $itemDetails->ack == "PartialFailure") {
            // Returns an error.
            return array(
                'result' => "ERROR",
                "output" => "ERROR:: (".$search->errorMessage->error->errorId.") - ".$search->errorMessage->error->category."\nERROR-MESSAGE:".$search->errorMessage->error->message."\n"
            );
        }
        // Returns a proper products object.
        return array(
            'result' => "OK",
            "output" => $itemDetails
        );
    }

    /**
     * @func getProducts()
     *  - Gets a specific product's details.
     *  @return     array
     *                  [result] -  OK/ERROR
     *                  [output]
     *                          - OK - Products object.
     *                          - ERROR - Error msg.
     */
    public function getProducts(){
        // Create the XML request to be POSTed
        $xmlrequest  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $xmlrequest .= "<GetMultipleItemsRequest xmlns=\"urn:ebay:apis:eBLBaseComponents\">\n";
        if (!empty($this->itemOptions->IncludeSelector)){
            $xmlrequest .= "<IncludeSelector>".implode(",", $this->itemOptions->IncludeSelector)."</IncludeSelector>\n";
        }
        $xmlrequest .= "<ItemID>"._BuildXMLItemList($this->itemOptions->itemID)."</ItemID>\n";
        $xmlrequest .= "</GetMultipleItemsRequest>\n";

        // Set our headers properly
        $this->headers["X-EBAY-API-CALL-NAME"] = "GetMultipleItems";

        // Make the call to eBay.
        $itemDetailsRaw = Utils::get_url($this->endpoint, "POST", $this->_formCurlHeaders($this->headers), $xmlrequest);

        // Parse our result into an object.
        $itemDetails = simplexml_load_string($itemDetailsRaw);

        // Checks to see if we have any type of failed call.
        if ($itemDetails->ack == "Failure" || $itemDetails->ack == "PartialFailure") {
            // Returns an error.
            return array(
                'result' => "ERROR",
                "output" => "ERROR:: (".$search->errorMessage->error->errorId.") - ".$search->errorMessage->error->category."\nERROR-MESSAGE:".$search->errorMessage->error->message."\n"
            );
        }
        // Returns a proper products object.
        return array(
            'result' => "OK",
            "output" => $itemDetails
        );
    }


}

?>