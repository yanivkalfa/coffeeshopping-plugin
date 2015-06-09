<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 5/26/2015
 * Time: 2:49 PM
 */

abstract class productViewTemplates {
    /**
     * @func getProductErrorContent($msg)
     *  - Returns a HTML ready formatted search result error to display.
     * @param   string  $msg          - The message to put inside the template.
     * @return  string  HTML error page.
     */
    static public function getProductErrorContent($msg){
        ob_start();
        ?>

        <div class="searchresulterror">
            <?php Utils::pageEcho($msg)?>
        </div>

        <?php
        return ob_get_clean();
    }

    /**
     * @func getProductView($product)
     *  - Returns a HTML ready formatted product page view content.
     * @param   object  $product         - $ObjProduct object.
     * @return  string  HTML product page.
     */
    static public function getProductView($product){
        ob_start();
        ?>
        <script language="javascript" type="text/javascript">
            /**
             * Full gallery example
             *
             * The code below creates the gallery. No editing needed to
             * actual zoom script file ( zoomit.jquery.js )
             *
             */
            // use load event on window to start the script
            jQuery(document).ready( function(){

                var previews 	= jQuery('.zoomItcontainer .full-image a'), // image previews
                    thumbnails 	= jQuery('.zoomItcontainer .gallery-thumbnails a'); // small thumbnails for changing previews

                // start zoom only on visible element
                jQuery('.zoomIt.visible').jqZoomIt({
                    init : function(){ // on zoom init, add class to element
                        jQuery( this ).addClass('zoomIt_loaded');
                    }
                });

                // small navigation thumnails functionality
                jQuery(thumbnails).click(function(e){
                    e.preventDefault();
                    // hide all previews
                    jQuery(previews).removeClass('visible').addClass('hidden');
                    // get key of thumbnail
                    var key = jQuery.inArray( this, thumbnails );
                    // show preview having the same key as small thumbnail
                    jQuery(previews[key]).removeClass('hidden').addClass('visible');
                    // check if preview has loaded class and if not, start zoom and add class
                    if( !jQuery(previews[key]).hasClass('zoomIt_loaded') ){
                        // start zoom
                        jQuery(previews[key]).jqZoomIt();
                        // add zoom loaded class
                        jQuery(previews[key]).addClass('zoomIt_loaded');
                    }
                });

                // large image function
                jQuery(previews).click(function(e){
                    e.preventDefault();
                });

                // Load our carousel.
                var m = jQuery('.galleryContainer').CB_CarouseljQ({
                    change: function(s, obj){
                        jQuery(obj).children("a").click();
                    }
                });

                // handle our shipping options.
                jQuery(".shippingopt").click(function(e){
                    // Set the description text:
                    var shippingdet = "Estimated delivery between " + jQuery(this).data("delmin") + " and " + jQuery(this).data("delmax");
                    shippingdet = shippingdet + "<br />";
                    shippingdet = shippingdet + "Additional item cost: " + jQuery(this).data("additional") + " " + jQuery(this).data("additionalCurrency");
                    if (jQuery(this).data("duty")!=" "){
                        shippingdet = shippingdet + "<br />";
                        shippingdet = shippingdet + "Import charges: " + jQuery(this).data("duty") + " " + jQuery(this).data("dutyCurrency");
                    }
                    jQuery("#shippingcostsdets").html(shippingdet);
                    // Set the price value.
                    jQuery("#shippingprice").html(jQuery(this).data("price") + " " + jQuery(this).data("priceCurrency"));
                    // Set the delivery estimate.
                    jQuery("#deliveryText").html("Between <span id=\"deliverytimefrom\"> " + jQuery(this).data("delmin") + " </span> and <span id=\"deliverytimeto\"> " + jQuery(this).data("delmax") + " </span>");

                    // Update the prices.
                    updateProductPrices();
                });

                // handle our variations.
                var hasVariations = true;
                var variationSets = <?php echo json_encode($product->variationSets);?>;

                jQuery(".varset").change(function(e){
                    var arrayTest = [];
                    var variationsArr = [];
                    var nextIndex = 0;
                    var imglink = "";
                    var assocName = jQuery(this).data("name");
                    var assocVal = jQuery(this).val();
                    variationsArr = Object.keys(variationSets);

                    // Set our searching array for our set.
                    arrayTest[assocName] = assocVal;

                    // Check if we have a picture set for this variation.
                    jQuery(".gallery-thumbnails .item > a[data-assoc=\"" + assocName + "\"][data-assocVal=\"" + assocVal + "\"]").click();

                    // Start from next set on...
                    nextIndex = variationsArr.indexOf(assocName)+1;
                    // Test all sets
                    for (i = nextIndex; i < Object.keys(variationSets).length; i++){
                        // Test all options of this set.
                        jQuery("#varset_" + i + " > option").show().each(function(){
                            arrayTest[variationsArr[i]] = jQuery(this).html();
                            // If we don't have an item with this specific option - hide it.
                            if (searchVariation(arrayTest) == 0) {
                                jQuery(this).hide();
                            }
                        });
                    }

                    // Set our variation details and price.
                    updateProductPrices();
                });

                // Searches for a specific variation set options. Returns 0 or Variation key
                var variations = <?php echo json_encode($product->variations);?>;
                function searchVariation(search){
                    //console.log(search);
                    var itemfound = 0;
                    Object.keys(variations).forEach(function(key){
                        if (itemfound!=0){return false;}
                        var available = true;
                        Object.keys(search).forEach(function(searchkey){
                            if (variations[key]["setInfo"][searchkey] != search[searchkey]) {
                                available = false;
                                return false;
                            }
                        });
                        if (available==true){
                            itemfound = key;
                            return false;
                        }
                    });

                    return itemfound;
                }

                function getCurrentVarSel(){
                    var varArr = [];
                    jQuery(".varset").each(function(){
                        varArr[jQuery(this).data("name")] = jQuery(this).val();
                    });
                    return varArr;
                }

                jQuery("#orderquantity").change(function(e){
                    updateProductPrices();
                });

                function updateProductPrices(){
                    var paypalcomm = parseFloat(10/100).toFixed(2),
                        storecomm = 10/100,
                        minstorecomm = 5,
                        details = [];

                    details["sku"] = "";
                    details["shippingprice"] = parseFloat( jQuery(".shippingopt:checked").data("price") );
                    details["shippingadditional"] = parseFloat( jQuery(".shippingopt:checked").data("additional") );
                    details["shippingduty"] = parseFloat( jQuery(".shippingopt:checked").data("duty") );
                    details["itemprice"] = <?php echo $product->price;?>;
                    details["quantityavail"] = <?php echo $product->quantityAvailable;?>;
                    details["quantitysold"] = <?php echo $product->quantitySold;?>;
                    details["orderquantity"] = parseFloat( jQuery("#orderquantity").val() );

                    if (hasVariations){
                        var variation = searchVariation(getCurrentVarSel());
                        if (variation!="0"){
                            // set variation details.
                            details["itemprice"] = parseFloat( variations[variation]["startPrice"] );
                            details["quantityavail"] = parseFloat( variations[variation]["quantity"] );
                            details["quantitysold"] = parseFloat( variations[variation]["quanitySold"] );
                            details["sku"] = variations[variation]["SKU"];

                        }
                    }
                    if (details["orderquantity"]<1 || details["orderquantity"] > details["quantityavail"]){
                        console.log("Choose quantity or Item not available.");
                    }

                    // Make some calcs.
                    // Sum our shipping costs.
                    if (details["orderquantity"]>1){
                        details["shippingprice"]
                            +=
                            (details["orderquantity"]-1)
                            *
                            details["shippingadditional"];
                    }
                    // Add our duty costs if applicable.
                    if (details["shippingduty"]>0){
                        details["shippingprice"]
                            +=
                            details["shippingduty"];
                    }
                    // Calc item cost by order quantity
                    var itemprice = details["itemprice"]*details["orderquantity"];
                    // Paypal comminsion * item price + shipping costs.
                    details["paypalprice"] = paypalcomm*(itemprice+details["shippingprice"]);
                    // Store comminsion * item price + shipping costs. [if lower then minimum, set to minimum].
                    details["storeprice"] = storecomm*(itemprice+details["shippingprice"]);
                    if (details["storeprice"]<minstorecomm){details["storeprice"] = minstorecomm;}
                    // Final price = item(s) price + shipping + paypal + store
                    details["finalPrice"] = itemprice+details["shippingprice"]+details["paypalprice"]+details["storeprice"];
                    details["totalprice"] = details["finalPrice"]/details["orderquantity"];

                    console.log("After calcs:", details);
                    Object.keys(details).forEach(function(key){
                        jQuery("#" + key).html(parseFloat( details[key] ).toFixed(2));
                    });
                }

            });
        </script>
<div class="productpagecontent">
        <div id="topcontainer">
            <div id="topdetailspanel">
                <div class="productDetailsTitle">
                    <h1><?php Utils::pageEcho($product->title); ?></h1>
                    <h2><?php Utils::pageEcho($product->subtitle); ?></h2>
                </div>
                <div class="productCategoryText">
                <?php echo (isset($product->categoryText) && !empty($product->categoryText))? "<h4> Category: ".$product->categoryText."</h4>": "";?>
                </div>
            </div>
        </div>

        <div id="middlecontainer">

            <div id="toppicturepanel">

                <div class="zoomItcontainer">
                    <div class="full-image">
                        <?php
                            $class = "visible";
                            foreach ($product->pics as $pic){
                                $imgGallery = ebay_Utils::getEbayPicture($pic["picURL"], "400s");
                                $imgBig = ebay_Utils::getEbayPicture($pic["picURL"], "1600s");
                                $assoc = (isset($pic["assoc"])&&!empty($pic["assoc"])) ? "data-assoc=\"".$pic["assoc"]."\"" : "";
                                $assocVal = (isset($pic["assocVal"])&&!empty($pic["assocVal"])) ? "data-assocVal=\"".$pic["assocVal"]."\"" : "";
                                ?>
                        <a href="<?php echo $imgBig;?>" class="zoomIt <?php echo $class;?>" <?php echo $assoc." ".$assocVal; ?>><img src="<?php echo $imgGallery;?>" alt="" /></a>
                                <?php
                                $class = "hidden";
                            }
                        ?>
                    </div>
                    <div class="galleryContainer" align="center">
                        <ul class="gallery-thumbnails">
                            <?php
                            foreach ($product->pics as $pic){
                                $imgThumb = ebay_Utils::getEbayPicture($pic["picURL"], "64s");
                                $assoc = (isset($pic["assoc"])&&!empty($pic["assoc"])) ? "data-assoc=\"".$pic["assoc"]."\"" : "";
                                $assocVal = (isset($pic["assocVal"])&&!empty($pic["assocVal"])) ? "data-assocVal=\"".$pic["assocVal"]."\"" : "";
                                ?>
                            <li class="item"><a href="#" <?php echo $assoc." ".$assocVal; ?>><img src="<?php echo $imgThumb;?>" width="54" alt="" /></a></li>
                                <?php
                            }
                            ?>
                        </ul>
                        <a href="#" class="nav-back"></a>
                        <a href="#" class="nav-fwd"></a>
                    </div>
                </div>

            </div>

            <div id="productchoices">
                <h3>
                    <?php Utils::pageEcho("Make your order"); ?>
                    <?php echo $product->topRatedItem ? " top-rated-item " : "";?>
                    :
                </h3>
                <div id="itemcondition">
                    <div class="inline header">Item condition:</div>
                    <div class="inline"><?php Utils::pageEcho($product->conditionText);?></div>
                </div>

                <div id="itemvariations">
                    <?php
                    $i = 0;
                    foreach ($product->variationSets as $setName => $setVars){
                        ?>
                        <div id="vardiv_<?php echo $i;?>">
                            <div class="inline header"><?php Utils::pageEcho($setName);?>:</div>
                            <div class="inline">
                                <select id="varset_<?php echo $i;?>" name="varset_<?php echo $i;?>" class="varset" data-name="<?php echo $setName;?>">
                                    <option>Select <?php echo $setName;?></option>
                                    <?php
                                    foreach ($setVars as $variation => $variationIMG){
                                        ?>
                                        <option value="<?php echo $variation;?>" rel="<?php Utils::pageEcho($variationIMG);?>"><?php Utils::pageEcho($variation);?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                    <?php
                        $i++;
                    }
                    ?>
                </div>
                <div align="center">
                    <span> Available <span id="quantityavail"><?php Utils::pageEcho($product->quantityAvailable);?></span> </span> / <span> Sold <span id="quantitysold"><?php Utils::pageEcho($product->quantitySold);?></span> </span>
                </div>

                <div id="itemshippingdiv">
                    <div>Shipping options:</div>
                    <div id="shippmentdiv">
                            <?php
                            $i = 0;
                            foreach ($product->shippingDetails->shippingOptions as $shippingOpts){
                                $i++;
                            ?>
                        <div>
                            <div class="radiocol">
                                <input type="radio" class="shippingopt" name="shippingOptions[]" id="<?php echo "shipradio".$i;?>" data-price="<?php echo $shippingOpts["shippingServiceCost"];?>" data-priceCurrency="<?php echo $shippingOpts["shippingServiceCostCurrency"];?>" data-additional="<?php echo $shippingOpts["shippingServiceAdditionalCost"];?>" data-additionalCurrency="<?php echo $shippingOpts["shippingServiceAdditionalCostCurrency"];?>" data-delMin="<?php echo ebay_Utils::getDeliveryTime($shippingOpts["estimatedDeliveryMinTime"]);?>" data-delMax="<?php echo ebay_Utils::getDeliveryTime($shippingOpts["estimatedDeliveryMaxTime"]);?>" data-duty="<?php echo $shippingOpts["importCharge"];?>" data-dutyCurrency="<?php echo $shippingOpts["importChargeCurrency"];?>" >
                            </div>
                            <div class="namecol">
                                <label for="<?php echo "shipradio".$i;?>"><?php Utils::pageEcho($shippingOpts["shippingServiceName"]);?></label>
                            </div>
                            <div class="pricecol">
                                <label for="<?php echo "shipradio".$i;?>"><?php echo $shippingOpts["shippingServiceCost"]. " " . $shippingOpts["shippingServiceCostCurrency"];?></label>
                            </div>
                        </div>
                            <?php
                            }
                            ?>
                        <div id="shippingcostsdets">

                        </div>
                    </div>
                </div>
            </div>
            <hr/>

            <div id="quicksummary">
                <div id="sellerinfo">
                    <div class="inline header"> Seller: </div>
                    <div class="inline">
                        <span> <?php echo $product->sellerInfo["userID"];?></span>,
                        <span> Score: <?php echo $product->sellerInfo["feedbackScore"];?></span>,
                        <span> Positive: <?php echo $product->sellerInfo["feedbackPercent"];?>%</span>
                        <?php echo $product->sellerInfo["topRated"]? "<div class=\"topratedsellerimg\"></div>" : "";?>
                    </div>
                </div>

                <div id="shippinginfo">
                    <div>
                        <div class="inline header"> Shipping from: </div>
                        <div class="inline"><?php Utils::pageEcho($product->location.", ".$product->country);?></div>
                    </div>
                    <div>
                    <div class="inline header"> Delivery time: </div>
                    <div id="deliveryText" class="inline"></div>
                    </div>
                </div>

                <div id="pricinginfo" class="inline">
                    <div>Order summary:</div>
                    <div>
                        <div class="inline header">Item price:</div>
                        <div id="itemprice" class="inline"><?php echo $product->price;?> <?php echo $product->priceCurrency;?></div>
                    </div>
                    <div>
                        <div class="inline header">Shipping price:</div>
                        <div id="shippingprice" class="inline"></div>
                    </div>
                    <div>
                        <div class="inline header">Store comission:</div>
                        <div id="storeprice" class="inline"></div>
                    </div>
                    <div>
                        <div class="inline header">Paypal comission:</div>
                        <div id="paypalprice" class="inline"></div>
                    </div>
                </div>

                <div id="pricingsumm" class="inline">
                    <div>Price per item:</div>
                    <div id="totalprice"></div>
                    <div>Total price:</div>
                    <div id="finalPrice"></div>
                </div>
            </div>

            <div id="ordercontainer">
                <div class="inline">
                    <div>
                        Quantity: <input id="orderquantity" type="number" max="<?php Utils::pageEcho($product->maxItemsOrder);?>" min="1" value="1" />
                    </div>
                </div>
                <div class="inline">
                    <div id="buynowbuttondiv">
                        BUY NOW
                    </div>
                </div>
            </div>


        </div>



        <div id="detailscontainer">
            <div id="itemIDspec" align="left"><?php echo $_GET["store"];?> item number: <?php echo $product->ID;?></div>
            <div id="itemspecs">
                <h3>Item Specifics:</h3>
                <?php
                foreach($product->itemSpecifics as $spec => $value){
                    ?>
                    <div class="inline block">
                        <div class="inline header"><?php echo $spec;?>:</div>
                        <div id="itemprice" class="inline"><?php echo $value;?></div>
                    </div>
                <?php
                }
                ?>
            </div>

            <hr>
            <div id="detailspaenl">
                <?php echo $product->descriptionHTML;?>
            </div>
        </div>

</div>
        <?php
        return ob_get_clean();
    }
}

?>