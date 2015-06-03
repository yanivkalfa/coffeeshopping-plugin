<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 5/21/2015
 * Time: 2:50 AM
 */

class ebay_ShoppingAPI extends ebayAdapter {
    // API variables
    public $endpoint;               // The eBay Shopping API address
    public $itemOptions;            // To hold our options.
    public $headers;                // Holds our communication headers.

    // Basic construct function.
    public function __construct(){
        parent::__construct();
        // Get and set search options.
        $this->itemOptions = new stdClass();
        $this->itemOptions->IncludeSelector = array();      //  Simple array of selectors to request for the item. (empty by default)
                                                            //          [Details, Description, TextDescription, ShippingCosts, ItemSpecifics, Variations, Compatibility]
        $this->itemOptions->itemID = "0";                   //  The unique item ID(s) to get info for. [for multiple items use a simple array(ID, ID, ID...)]

        // Default comms header.
        $this->headers = array();
        $this->_setDefaultHeaders();

    }

    /*
     * @func _setItemID()
     *  - Sets our item ID for the query.
     */
    public function _setItemID($itemID){
        $this->itemOptions->itemID = $itemID;
    }
    /*
     * @func _setItemOptions()
     *  - Sets our item request options.
     */
    public function _setItemOptions($itemOptions){
        foreach($itemOptions as $optName => $optValue){
            if (isset($this->itemOptions->$optName)){ $this->itemOptions->$optName = $optValue; }
        }
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
            return "<ItemID>".$itemIDs."</ItemID>\n";
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
        utils::preEcho($xmlrequest);
        $itemDetailsRaw = Utils::get_url($this->endpoint, "POST", $this->_formCurlHeaders($this->headers), $xmlrequest);

        // Parse our result into an object.
        $itemDetails = simplexml_load_string($itemDetailsRaw);

        // Checks to see if we have any type of failed call.
        if ($itemDetails->ack == "Failure" || $itemDetails->ack == "PartialFailure") {
            // Returns an error.
            return array(
                'result' => "ERROR",
                "output" => "ERROR:: (".$itemDetails->errorMessage->error->errorId.") - ".$itemDetails->errorMessage->error->category."\nERROR-MESSAGE:".$itemDetails->errorMessage->error->message."\n"
            );
        }
        // Returns a proper products object.
        return array(
            'result' => "OK",
            "output" => $this->_formatProductOutput($itemDetails->Item)
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
        $xmlrequest .= "<ItemID>".$this->_BuildXMLItemList($this->itemOptions->itemID)."</ItemID>\n";
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
                "output" => "ERROR:: (".$itemDetails->errorMessage->error->errorId.") - ".$itemDetails->errorMessage->error->category."\nERROR-MESSAGE:".$itemDetails->errorMessage->error->message."\n"
            );
        }
        // Make sure our item listing is "Active".
        if ($itemDetails->Item->ListingStatus != "Active"){
            // Returns an error.
            return array(
                'result' => "ERROR",
                "output" => "It seems like this item's listing is inactive."."\n"
            );
        }
        // Returns a proper products object.
        return array(
            'result' => "OK",
            "output" => $this->_formatProductOutput($itemDetails->Item)
        );
    }

    /**
     * @func _formatProductOutput($productOutput)
     *  - Creates a "proper" product object to display in our product page.
     * @param   object      $productOutput       - As returned by ebay's 'GetSingleItemRequest' XML API call.
     * @return  object      $ObjProduct          - Product object.
     *                                              Array examples:
     *                                              $ObjProduct->variationSets["Color"]["Rose"] = "Link to image"
     *                                              $ObjProduct->variationSets["Color"]["Pink"] = "Link to image"
     *                                              $ObjProduct->variationSets["Compatible Model"]["HTC"] = null; (false for 'isset()')
     *
     *                                              $ObjProduct->variations[HQ7531]["SKU"] = HQ7531
     *                                              $ObjProduct->variations[HQ7531]["startPrice"] = 12.3
     *                                              $ObjProduct->variations[HQ7531]["setInfo"]["COLOR"] = "Rose"
     *                                              $ObjProduct->variations[HQ7531]["setInfo"]["Compatible Model"] = "HTC"
     *
     */
    public function _formatProductOutput($productOutput){
        $ObjProduct = new stdClass();
        $ObjProduct->ID                     =     (string)  $productOutput->ItemID;
        $ObjProduct->title                  =     (string)  $productOutput->Title;
        $ObjProduct->descriptionHTML        =     (string)  $productOutput->Description;
        $ObjProduct->storeLink              =     (string)  $productOutput->ViewItemURLForNaturalSearch;
        $ObjProduct->pics                   =     (array)   $productOutput->PictureURL;
        $ObjProduct->categoryText           =     (string)  $productOutput->PrimaryCategoryName;
        $ObjProduct->price                  =     (string)  $productOutput->ConvertedCurrentPrice;
        $ObjProduct->priceCurrency          =     (string)  $productOutput->convertedCurrentPrice["currencyId"];
        $ObjProduct->country                =     (string)  utils::getCountryFromCode((string)$productOutput->Country);
        $ObjProduct->location               =     (string)  $productOutput->Location;
        $ObjProduct->quantityAvailable      =     (string)  $productOutput->Quantity;
        $ObjProduct->quantitySold           =     (string)  $productOutput->QuantitySold;
        $ObjProduct->timeLeft               =     parent::getPrettyTimeFromEbayTime((string)$productOutput->TimeLeft); // Limited timed offer.
        $ObjProduct->availableTill          =     (string)  $productOutput->EndTime;            // Availability until (eg. format: 2015-07-01T09:01:10.000Z)
        $ObjProduct->listingType            =     (string)  $productOutput->ListingType;        // If 'AuctionWithBIN' -> Rush buy before someone bids. [our types: AuctionWithBIN,FixedPrice,StoreInventory]
        $ObjProduct->handlingTime           =     (string)  $productOutput->HandlingTime;       // Number of days until shipment - int.
        $ObjProduct->conditionText          =     (string)  $productOutput->ConditionDisplayName;
        $ObjProduct->maxItemsOrder          =     (string)  $productOutput->QuantityThreshold;  // How many items can you order at once.
        $ObjProduct->topRatedItem           =     (string)  $productOutput->TopRatedListing;

        $ObjProduct->sellerInfo             =     array(
            "userID"                =>  (string)  $productOutput->Seller->UserID,                   // User name.
            "feedbackRating"        =>  (string)  $productOutput->Seller->FeedbackRatingStar,       // Star color. (green, yellow etc)
            "feedbackScore"         =>  (string)  $productOutput->Seller->FeedbackScore,            // int for feedbacks.
            "feedbackPercent"       =>  (string)  $productOutput->Seller->PositiveFeedbackPercent,  // Positive %.
            "topRated"              =>  (string)  $productOutput->Seller->TopRatedSeller,           // Is he top rated? (Boolean)
        );

        $ObjProduct->returnPolicy             =     array(
            "refund"                =>  (string)  $productOutput->ReturnPolicy->Refund,             // types: Money Back, MoneyBackOrExchange, MoneyBackOrReplacement
            "returnsWithin"         =>  (string)  $productOutput->ReturnPolicy->ReturnsWithin,      // String time (eg. 30 Days)
            "returnsAccepted"       =>  (string)  $productOutput->ReturnPolicy->ReturnsAccepted,    // String (eg. Returns Accepted)
            "description"           =>  (string)  $productOutput->ReturnPolicy->Description,        // A description of the return policy for the item.
            "shippingCostPaidBy"    =>  (string)  $productOutput->ReturnPolicy->ShippingCostPaidBy, // String (eg. Buyer/Seller)
        );

        // Get the variations for this item.
        if (isset($productOutput->Variations->VariationSpecificsSet->NameValueList)){
            $ObjProduct->variationSets      = array();
            $ObjProduct->variations         = array();

            // Get our variation sets.
            foreach($productOutput->Variations->VariationSpecificsSet->NameValueList as $variationSet){
                foreach($variationSet->Value as $setVal){
                    $ObjProduct->variationSets[(string)$variationSet->Name][(string)$setVal] = null;
                }
            }
            foreach($productOutput->Variations->Pictures as $variationPicSet){ // Associating pics.
                foreach($variationPicSet->VariationSpecificPictureSet as $PicDetails){
                    $ObjProduct->variationSets[(string)$variationPicSet->VariationSpecificName][(string)$PicDetails->VariationSpecificValue] = (string)$PicDetails->PictureURL;
                }
            }

            // Get our actual variations and their details.
            foreach($productOutput->Variations->Variation as $variation){
                $ObjProduct->variations[(string)$variation->SKU] = array(
                    "SKU"           =>  (string)    $variation->SKU,
                    "startPrice"    =>  (string)    $variation->StartPrice,
                    "quantity"      =>  (string)    $variation->Quantity,
                    "quanitySold"   =>  (string)    $variation->SellingStatus->QuantitySold
                );
                // Associate our variation with it's SET(s).
                foreach($variation->VariationSpecifics->NameValueList as $variationSet){
                    $ObjProduct->variations[(string)$variation->SKU]["setInfo"][(string)$variationSet->Name] = (string)$variationSet->Value;
                }
            }


        }

        return $ObjProduct;
    }
}

?>