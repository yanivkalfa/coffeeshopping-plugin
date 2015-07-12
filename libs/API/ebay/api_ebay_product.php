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
        $this->itemOptions->toGet = "0";                    //  The unique item ID(s) to get info for. [for multiple items use a simple array(ID, ID, ID...)]

        $this->itemOptions->getShippingOpts = true;         //  Get extended shipping options.
        // Default comms header.
        $this->headers = array();
        $this->_setDefaultHeaders();

    }

    /*
     * @func _setGetShippingOpts()
     *  - sets our option for getting extended shipping options info.
     */
    public function _setGetShippingOpts($bool){
        $this->itemOptions->getShippingOpts = $bool;
    }

    /*
     * @func _settoGet()
     *  - Sets our item ID for the query.
     */
    public function _setGetter($toGet){
        $this->itemOptions->toGet = $toGet;
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
                'X-EBAY-API-VERSION' => '897',                      // Latest - http://developer.ebay.com/devzone/shopping/docs/ReleaseNotes.html
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
     * @func _BuildXMLItemList($toGets)
     *  - Builds a proper XML toGet list for ebay's API.
     * @param mixed $toGet - string/int/array of toGet(s).
     * @return string
     */
    public function _BuildXMLItemList($toGet){
        if (!is_array($toGet)){
            return "<ItemID>".$toGet."</ItemID>\n";
        }
        $xmllist = "";
        // Iterate through each filter in the array
        foreach ($toGet as $itemID) {
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
     *                          - OK - Products object. [as returned by _formatProductOutput()]
     *                          - ERROR - Error msg.
     * @depends     class   Utils
     */
    public function getProduct(){
        // Create the XML request to be POSTed
        $xmlrequest  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $xmlrequest .= "<GetSingleItemRequest xmlns=\"urn:ebay:apis:eBLBaseComponents\">\n";
        if (!empty($this->itemOptions->IncludeSelector)){
            $xmlrequest .= "<IncludeSelector>".implode(",", $this->itemOptions->IncludeSelector)."</IncludeSelector>\n";
        }
        $xmlrequest .= $this->_BuildXMLItemList($this->itemOptions->toGet)."\n";
        $xmlrequest .= "</GetSingleItemRequest>\n";

        // Set our headers properly
        $this->headers["X-EBAY-API-CALL-NAME"] = "GetSingleItem";

        // Make the call to eBay.
        $itemDetailsRaw = Utils::get_url($this->endpoint, "POST", $this->_formCurlHeaders($this->headers), $xmlrequest);
        if ($itemDetailsRaw["result"]=="ERROR"){
            Utils::adminPreECHO($itemDetailsRaw["output"], "cURL ERROR details:: ");
            return array(
                'result' => "ERROR",
                "output" => Utils::getErrorCode("API", "ebay", "getProduct", "1")
            );
        }
        $itemDetailsRaw = $itemDetailsRaw["output"];

        // Parse our result into an object.
        $itemDetails = simplexml_load_string($itemDetailsRaw);

        // Checks to see if we have any type of failed call.
        if ($itemDetails->ack == "Failure" || $itemDetails->ack == "PartialFailure") {
            // Returns an error.
            Utils::adminPreECHO("(".$itemDetails->errorMessage->error->errorId.") - ".$itemDetails->errorMessage->error->category." - ".$itemDetails->errorMessage->error->message."\n", " getProduct() ERROR:: ");
            return array(
                'result' => "ERROR",
                "output" => Utils::getErrorCode("API", "ebay", "getProduct", "2")
            );
        }

        // Make sure our item listing is "Active".
        if ($itemDetails->Item->ListingStatus != "Active"){
            // Returns an error.
            return array(
                'result' => "ERROR",
                "output" => Utils::getErrorCode("API", "ebay", "getProduct", "4")
            );
        }


        if ($this->itemOptions->getShippingOpts) {
            // get the shipping costs for this product
            $shippingDetails = $this->getShippingCosts();
            // Checks to see if we have any type of failed call.
            if ($shippingDetails["result"] == "ERROR") {
                // Returns an error.
                return array(
                    'result' => "ERROR",
                    "output" => $shippingDetails["output"] // Just pass on the error from prev func.
                );
            }
        }

        // format our product properly.
        $itemDetailsProp = $this->_formatProductOutput($itemDetails->Item);

        if ($this->itemOptions->getShippingOpts) {
            // add our shipping costs to our proper object.
            $itemDetailsProp->shippingDetails = $shippingDetails["output"];
        }

        // Returns a proper products object.
        return array(
            'result' => "OK",
            "output" => $itemDetailsProp
        );
    }

    /**
     * @func getShippingCosts()
     *  - Gets a specific product shipping details.
     *  @return     array
     *                  [result] -  OK/ERROR
     *                  [output]
     *                          - OK - shippingObject object. [as returned by _formatShippingCosts()]
     *                          - ERROR - Error msg.
     * @depends     class   Utils
     */
    public function getShippingCosts(){
        // Create the XML request to be POSTed.
        $xmlrequest  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $xmlrequest .= "<GetShippingCostsRequest xmlns=\"urn:ebay:apis:eBLBaseComponents\">\n";
        $xmlrequest .= $this->_BuildXMLItemList($this->itemOptions->toGet)."\n";
        $xmlrequest .= "<DestinationCountryCode>IL</DestinationCountryCode>\n";
        $xmlrequest .= "<IncludeDetails>true</IncludeDetails>\n";
        $xmlrequest .= "</GetShippingCostsRequest>​\n";

        // Set our headers properly
        $this->headers["X-EBAY-API-CALL-NAME"] = "GetShippingCosts";

        // Make the call to eBay.
        $shippingDetailsRaw = Utils::get_url($this->endpoint, "POST", $this->_formCurlHeaders($this->headers), $xmlrequest);
        if ($shippingDetailsRaw["result"]=="ERROR"){
            Utils::adminPreECHO($shippingDetailsRaw["output"], "cURL ERROR details:: ");
            return array(
                'result' => "ERROR",
                "output" => Utils::getErrorCode("API", "ebay", "getShippingCosts", "1")
            );
        }
        $shippingDetailsRaw = $shippingDetailsRaw["output"];

        // Parse our result into an object.
        $shippingDetails = simplexml_load_string($shippingDetailsRaw);
        // Checks to see if we have any type of failed call.
        if ($shippingDetails->Ack == "Failure" || $shippingDetails->Ack == "PartialFailure") {
            // Returns an error.
            $errorsText = "";
            foreach ($shippingDetails->Errors as $Error){
                $errorsText .= "ERROR:: (".$Error->ErrorCode.") - ".$Error->ErrorClassification." - ".$Error->LongMessage."\n";
            }
            Utils::adminPreECHO($errorsText, "getShippingCosts() ERROR:: ");

            return array(
                'result' => "ERROR",
                "output" => Utils::getErrorCode("API", "ebay", "getShippingCosts", "2")
            );

        }elseif(!isset($shippingDetails->ShippingDetails->InternationalShippingServiceOption)){
            // No error but EMPTY results -> doesn't ship to IL.
            Utils::adminPreECHO("There are no 'InternationalShippingServiceOption' options available.", "getShippingCosts() ERROR:: ");
            return array(
                'result' => "ERROR",
                "output" => Utils::getErrorCode("API", "ebay", "getShippingCosts", "2")
            );
        }


        // Returns a proper shipping details object.
        return array(
            'result' => "OK",
            "output" => $this->_formatShippingCosts($shippingDetails->ShippingDetails)
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
        $xmlrequest .= $this->_BuildXMLItemList($this->itemOptions->toGet)."\n";
        $xmlrequest .= "</GetMultipleItemsRequest>\n";

        // Set our headers properly
        $this->headers["X-EBAY-API-CALL-NAME"] = "GetMultipleItems";

        // Make the call to eBay.
        $itemDetailsRaw = Utils::get_url($this->endpoint, "POST", $this->_formCurlHeaders($this->headers), $xmlrequest);
        if ($itemDetailsRaw["result"]=="ERROR"){
            Utils::adminPreECHO($itemDetailsRaw["output"], "cURL ERROR details:: ");
            return array(
                'result' => "ERROR",
                "output" => Utils::getErrorCode("API", "ebay", "getProducts", "1")
            );
        }
        $itemDetailsRaw = $itemDetailsRaw["output"];

        // Parse our result into an object.
        $itemDetails = simplexml_load_string($itemDetailsRaw);

        // Checks to see if we have any type of failed call.
        if ($itemDetails->ack == "Failure" || $itemDetails->ack == "PartialFailure") {
            // Returns an error.
            Utils::adminPreECHO("(".$itemDetails->errorMessage->error->errorId.") - ".$itemDetails->errorMessage->error->category." - ".$itemDetails->errorMessage->error->message."\n", " getProduct() ERROR:: ");
            return array(
                'result' => "ERROR",
                "output" => Utils::getErrorCode("API", "ebay", "getProducts", "2")
            );
        }

        // Iterate over all items and properly form them. (No shipping details!)
        $itemDetailsProp = [];
        $index = 0;
        foreach($itemDetails->Item as $Item){
            // Make sure our item listing is "Active".
            if ($Item->ListingStatus != "Active"){
                continue;
            }

            if ($this->itemOptions->getShippingOpts) {
                // get the shipping costs for this product
                $this->_setGetter($Item->ItemID);
                $shippingDetails = $this->getShippingCosts();
                // Checks to see if we have any type of failed call.
                if ($shippingDetails["result"] == "ERROR") {
                    // Returns an error.
                    return array(
                        'result' => "ERROR",
                        "output" => $shippingDetails["output"] // Just pass on the error from prev func.
                    );
                }
            }

            // format our product properly.
            $itemDetailsProp[$index] = $this->_formatProductOutput($Item);

            if ($this->itemOptions->getShippingOpts) {
                // add our shipping costs to our proper object.
                $itemDetailsProp[$index]->shippingDetails = $shippingDetails["output"];
            }
            $index++;
        }

        // Returns a proper products object.
        return array(
            'result' => "OK",
            "output" => $itemDetailsProp
        );
    }

    /**
     * @func _formatProductOutput($productOutput)
     *  - Creates a "proper" product object to display in our product page.
     * @param       object      $productOutput       - As returned by ebay's 'GetSingleItemRequest' XML API call.
     * @return      object      $ObjProduct          - Product object.
     *                                              Array examples:
     *                                              $ObjProduct->variationSets["Color"]["Rose"] = "Link to image"
     *                                              $ObjProduct->variationSets["Color"]["Pink"] = "Link to image"
     *                                              $ObjProduct->variationSets["Compatible Model"]["HTC"] = null; (false for 'isset()')
     *
     *                                              $ObjProduct->variations[0]["SKU"] = HQ7531
     *                                              $ObjProduct->variations[0]["startPrice"] = 12.3
     *                                              $ObjProduct->variations[0]["setInfo"]["COLOR"] = "Rose"
     *                                              $ObjProduct->variations[0]["setInfo"]["Compatible Model"] = "HTC"
     *
     */
    public function _formatProductOutput($productOutput){
        $ObjProduct = new stdClass();
        $ObjProduct->ID                     =     (string)  $productOutput->ItemID;
        $ObjProduct->title                  =     (string)  $productOutput->Title;
        $ObjProduct->subtitle               =     (string)  $productOutput->Subtitle;
        $ObjProduct->storeLink              =     (string)  $productOutput->ViewItemURLForNaturalSearch;
        $ObjProduct->categoryText           =     (string)  $productOutput->PrimaryCategoryName;
        $ObjProduct->price                  =     (string)  $productOutput->CurrentPrice;
        $ObjProduct->priceCurrency          =     (string)  $productOutput->CurrentPrice["currencyID"];
        // Get exchange rates.
        Utils::addExchangeKeys($ObjProduct, array("price"));

        $ObjProduct->country                =     (string)  $productOutput->Country;
        $ObjProduct->location               =     (string)  $productOutput->Location;
        $ObjProduct->quantityAvailable      =     (string)  $productOutput->Quantity;
        $ObjProduct->quantitySold           =     (string)  $productOutput->QuantitySold;
        $ObjProduct->timeLeft               =     parent::getPrettyTimeFromEbayTime((string)$productOutput->TimeLeft); // Limited timed offer.
        $ObjProduct->availableTill          =     (string)  $productOutput->EndTime;                // Availability until (eg. format: 2015-07-01T09:01:10.000Z)
        $ObjProduct->listingType            =     (string)  $productOutput->ListingType;            // If 'AuctionWithBIN' -> Rush buy before someone bids. [our types: AuctionWithBIN,FixedPrice,StoreInventory]
        $ObjProduct->handlingTime           =     (string)  $productOutput->HandlingTime;           // Number of days until shipment - int.
        $ObjProduct->conditionText          =     (string)  $productOutput->ConditionDisplayName;
        $ObjProduct->orderLimit             =     (string)  $productOutput->QuantityThreshold;      // How many items can you order at once.
        $ObjProduct->topRatedItem           =     (string)  $productOutput->TopRatedListing;

        $ObjProduct->sellerInfo             =     array(
            "userID"                =>  (string)  $productOutput->Seller->UserID,                   // User name.
            "feedbackRating"        =>  (string)  $productOutput->Seller->FeedbackRatingStar,       // Star color. (green, yellow etc)
            "feedbackScore"         =>  (string)  $productOutput->Seller->FeedbackScore,            // int for feedbacks.
            "feedbackPercent"       =>  (string)  $productOutput->Seller->PositiveFeedbackPercent,  // Positive %.
            "topRated"              =>  (string)  $productOutput->Seller->TopRatedSeller,           // Is he top rated? (Boolean)
        );

        // Set the pictures:
        $ObjProduct->pics                   =     array();
        foreach($productOutput->PictureURL as $pic){
            $ObjProduct->pics[]     =   Array(
                "picURL" => (string)  $pic,                                                         // The link to the image.
            );
        }

        // Get the item specifics.
        $ObjProduct->itemSpecifics          =     array();
        if (isset($productOutput->ItemSpecifics->NameValueList)){

            foreach($productOutput->ItemSpecifics->NameValueList as $spec){
                $ObjProduct->itemSpecifics[(string)$spec->Name] = implode(", ", (array)$spec->Value);
            }
        }

        $ObjProduct->returnPolicy           =     array(
            "refund"                =>  (string)  $productOutput->ReturnPolicy->Refund,             // types: Money Back, MoneyBackOrExchange, MoneyBackOrReplacement
            "returnsWithin"         =>  (string)  $productOutput->ReturnPolicy->ReturnsWithin,      // String time (eg. 30 Days)
            "returnsAccepted"       =>  (string)  $productOutput->ReturnPolicy->ReturnsAccepted,    // String (eg. Returns Accepted)
            "description"           =>  (string)  $productOutput->ReturnPolicy->Description,        // A description of the return policy for the item.
            "shippingCostPaidBy"    =>  (string)  $productOutput->ReturnPolicy->ShippingCostPaidBy, // String (eg. Buyer/Seller)
        );

        // Get the variations for this item.
        $ObjProduct->variationSets          = array();
        $ObjProduct->variations             = array();
        if (isset($productOutput->Variations->VariationSpecificsSet->NameValueList)){
            // Get our variation sets.
            foreach($productOutput->Variations->VariationSpecificsSet->NameValueList as $variationSet){
                foreach($variationSet->Value as $setVal){
                    $ObjProduct->variationSets[(string)$variationSet->Name][(string)$setVal] = null;
                }
            }
            foreach($productOutput->Variations->Pictures as $variationPicSet){ // Associating pics.
                foreach($variationPicSet->VariationSpecificPictureSet as $PicDetails){
                    $ObjProduct->variationSets[(string)$variationPicSet->VariationSpecificName][(string)$PicDetails->VariationSpecificValue] = (string)$PicDetails->PictureURL;
                    // Also add the variation pictures to our main gallery.
                    $ObjProduct->pics[] = Array(
                        "picURL"    =>  (string)    $PicDetails->PictureURL,                        // The link to the image.
                        "assoc"     =>  (string)    $variationPicSet->VariationSpecificName,        // Variation set association.
                        "assocVal"  =>  (string)    $PicDetails->VariationSpecificValue             // Variation set specific value association.
                    );
                }
            }

            // Get our actual variations and their details.
            $varIndex = 0;
            foreach($productOutput->Variations->Variation as $variation){
                $ObjProduct->variations[$varIndex] = array(
                    "SKU"                           =>  (string)    $variation->SKU,
                    "price"                         =>  (string)    $variation->StartPrice,
                    "priceCurrency"                 =>  (string)    $variation->StartPrice["currencyID"],
                    "quantity"                      =>  (string)    $variation->Quantity,
                    "quantitySold"                  =>  (string)    $variation->SellingStatus->QuantitySold
                );
                // get exchange rates:
                Utils::addExchangeKeys($ObjProduct->variations[$varIndex], array("price"));

                // Associate our variation with it's SET(s).
                foreach($variation->VariationSpecifics->NameValueList as $variationSet){
                    $ObjProduct->variations[$varIndex]["setInfo"][(string)$variationSet->Name] = (string)$variationSet->Value;
                }
                $varIndex++;
            }


        }

        // Add this last cause it's freakin' annoying on debug.
        $ObjProduct->descriptionHTML        =     (string)  Utils::cleanDescriptionHTML(($productOutput->Description));

        return $ObjProduct;
    }

    public function _formatShippingCosts($shippingObject){
        $ObjShipping = new stdClass();
        $ObjShipping->shippingOptions                       =   array();
        $ObjShipping->internationalInsuranceCost            =   (string)    $shippingObject->InternationalInsuranceCost;
        $ObjShipping->internationalInsuranceCostCurrency    =   (string)    $shippingObject->InternationalInsuranceCost["currencyID"];
        $ObjShipping->internationalInsuranceOption          =   (string)    $shippingObject->InternationalInsuranceOption;

        $index = 0;
        foreach ($shippingObject->InternationalShippingServiceOption as $option){
            $ObjShipping->shippingOptions[$index] = array(
                "name"                      =>  (string)    $option->ShippingServiceName,
                "priority"                  =>  (string)    $option->ShippingServicePriority,                               // int.
                "deliveryMin"               =>  array(
                    "date"  =>      ebay_Utils::getDeliveryTime((string)$option->EstimatedDeliveryMinTime),                 // Date in the format - "D. M."
                    "days"  =>      ebay_Utils::getDeliveryTimeDiff((string)$option->EstimatedDeliveryMinTime),             // Date in the format - "D. M."
                ),
                "deliveryMax"               =>  array(
                    "date"  =>      ebay_Utils::getDeliveryTime((string)$option->EstimatedDeliveryMaxTime),                 // Date in the format - "D. M."
                    "days"  =>      ebay_Utils::getDeliveryTimeDiff((string)$option->EstimatedDeliveryMaxTime),             // Date in the format - "D. M."
                ),
                // All price modifiers:
                "price"                     =>  (string)    $option->ShippingServiceCost,                                       // int. [same for all]
                "priceCurrency"             =>  (string)    $option->ShippingServiceCost["currencyID"],                         // 3 letters ID. [same for all]
                "additional"                =>  (string)    $option->ShippingServiceAdditionalCost,
                "additionalCurrency"        =>  (string)    $option->ShippingServiceAdditionalCost["currencyID"],
                "duty"                      =>  (string)    $option->ImportCharge,
                "dutyCurrency"              =>  (string)    $option->ImportCharge["currencyID"],
                "insurance"                 =>  (string)    $option->ShippingInsuranceCost,
                "insuranceCurrency"         =>  (string)    $option->ShippingInsuranceCost["currencyID"],
            );
            // get exchange rates:
            Utils::addExchangeKeys($ObjShipping->shippingOptions[$index],  Array("price", "additional", "duty", "insurance"));

            $index++;
        }

        return $ObjShipping;
    }
}

?>