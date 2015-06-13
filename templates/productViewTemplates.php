<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 5/26/2015
 * Time: 2:49 PM
 *
 */

abstract class productViewTemplates {

    static private function _addExchangeRates(&$product, $exchCurrency = "ILS", $priceExtension = "Exch"){
        $exchanger = new currencyExchange();

        // Add exchanged price.
        $product->{'price'.$priceExtension} = $exchanger->exchangeRateConvert($product->priceCurrency, $product->price, $exchCurrency);

        // Add exchanged prices to all shipping options:
        $costsArray = Array("price", "additional", "duty", "insurance");
        foreach ($product->shippingDetails->shippingOptions as $shippingOptKey => $shippingOpts){
            foreach($costsArray as $key){
                // Get the converted price for each entry.
                $product->shippingDetails->shippingOptions[$shippingOptKey][$key.$priceExtension] =
                    $exchanger->exchangeRateConvert($shippingOpts[$key."Currency"], $shippingOpts[$key], $exchCurrency);
                // Get the conversion symbol for each entry.
                $product->shippingDetails->shippingOptions[$shippingOptKey][$key."Symbol"] = Utils::getCurrencySymbol($shippingOpts[$key."Currency"]);
            }
        }

        // Add exchanged prices to all variants
        foreach($product->variations as $varName => $variant){
            $product->variations[$varName]["price".$priceExtension] = $exchanger->exchangeRateConvert($product->priceCurrency, $variant["price"], $exchCurrency);
            $product->variations[$varName]["priceSymbol"] = Utils::getCurrencySymbol($product->priceCurrency);
        }

    }


    static private function _prepareProduct(&$product){
        // Clean our products HTML for display.
        $product->descriptionHTML        =     Utils::cleanDescriptionHTML(($product->descriptionHTML));
        // Change country codes into a normal name.
        $product->country                =     Utils::getCountryFromCode($product->country);
    }

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
        $exchangeExtension = "Exch";
        $exchangeCurrency = "ILS";
        productViewTemplates::_addExchangeRates($product, $exchangeCurrency, $exchangeExtension);
        productViewTemplates::_prepareProduct($product);

        ob_start();
        ?>
        <script language="javascript" type="text/javascript">
            // Set some vars.
            <?php
                $itemPricing = array(
                    "price" => $product->price,
                    "priceCurrency" => $product->priceCurrency,
                    "priceSymbol" => Utils::getCurrencySymbol($product->priceCurrency),
                    "price".$exchangeExtension => $product->{'price'.$exchangeExtension},
                    "priceSymbol".$exchangeExtension => Utils::getCurrencySymbol($exchangeCurrency),
                    "exchextension" => $exchangeExtension,
                );
            ?>
            var itemPricing = <?php echo json_encode($itemPricing);?>;
            var shippingOpts = <?php echo json_encode($product->shippingDetails->shippingOptions);?>;
            var selectedShipping = 0; // proper indexes start from 0.
            var variationSets = <?php echo json_encode($product->variationSets);?>;
            var variations = <?php echo json_encode($product->variations);?>;
            var selectedVariant = 0; // default search result.
            var exchExtension = "<?php echo $exchangeExtension; ?>";
            var exchSymbol = "<?php echo Utils::getCurrencySymbol($exchangeCurrency);?>";
            var exchCurrency = "<?php echo $exchangeCurrency;?>";

            jQuery(document).ready( function(){
                /*
                        ZoomIt BEGIN/
                */
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
                /*
                        /END ZoomIt
                */

                // handle our shipping options.
                jQuery(".shippingopt").click(function(e){
                    // Get the chosen index.
                    selectedShipping = jQuery(this).data("index");
                    updateShippingOpt();
                    updateProductPrices();
                });

                 // handle our variations.
                jQuery(".varset").change(function(e){
                    updateSelectedVariant(jQuery(this));
                });


                // Handle our quantity changes.
                jQuery("#orderquantity").change(function(e){
                    updateQuantity();
                    updateProductPrices();
                });

                // Handle our onHover popUps.
                jQuery("#itemprice, #shippingprice").mouseover(function(){
                    displayPopExchangeRates(jQuery(this));
                }).mouseout(function(){
                    jQuery("#exchangeDisplayDiv").hide();
                });

                // Handle togglers.
                jQuery("#exchangeDisplayDiv").hide();
                jQuery("#quicksummary").hide();
                jQuery("#orderdetailstogg").click(function(e){toggleID(this, "#quicksummary", e)});
                jQuery("#DebugProductOutput").hide();
                jQuery("#debugOutPutTogg").click(function(e){toggleID(this, "#DebugProductOutput", e)});
                // Scroll our details panel to the left to display contents in case of need.
                jQuery("#detailspaenl").scrollLeft(0);

                // ON-LOAD - get the defaults.
                updateShippingOpt();
                updateProductPrices();

                function toggleID(togg, id, e){
                    e.preventDefault();
                    if (jQuery(togg).html()=="-") {
                        jQuery(id).hide();
                        jQuery(togg).html("+");
                    }else{
                        jQuery(id).show();
                        jQuery(togg).html("-");
                    }
                }

                function displayPopExchangeRates(jqRef){
                    if (jQuery("#exchangeDisplayDiv").is(":visible")){return;}
                    var originalPrice = 0;
                    var exchangePrice = 0;
                    var originalCurrSymbol = 0;

                    switch (jqRef.attr("id")) {
                        case "itemprice":
                            originalPrice = parseFloat(itemPricing["price"]).toFixed(2);
                            exchangePrice = parseFloat(itemPricing["price" + exchExtension]).toFixed(2);
                            originalCurrSymbol = itemPricing["priceSymbol"];
                            if (selectedVariant != 0){
                                originalPrice = parseFloat(variations[selectedVariant]["price"]).toFixed(2);
                                exchangePrice = parseFloat(variations[selectedVariant]["price" + exchExtension]).toFixed(2);
                                originalCurrSymbol = variations[selectedVariant]["priceSymbol"];
                            }
                            break;

                        case "shippingprice":
                            originalPrice = parseFloat(shippingOpts[selectedShipping]["price"]).toFixed(2);
                            exchangePrice = parseFloat(shippingOpts[selectedShipping]["price" + exchExtension]).toFixed(2);
                            originalCurrSymbol = shippingOpts[selectedShipping]["priceSymbol"];
                            break;
                    }
                    var exchangeRate = parseFloat(exchangePrice/originalPrice).toFixed(2);

                    // No need to display anything if price is 0;
                    if (originalPrice==0){return;}

                    // Update dom.
                    jQuery("#originalPrice").html(originalPrice);
                    jQuery("#originalCurrSymbol, #originalCurrSymbol2").html(originalCurrSymbol);
                    jQuery("#exchangeRate").html(exchangeRate);

                    // Show popup and place it above our elems.
                    var position = jqRef.position();
                    jQuery("#exchangeDisplayDiv").css({
                        top:    position.top   -    jQuery("#exchangeDisplayDiv").height(),
                        left:   position.left  -    jQuery("#exchangeDisplayDiv").width()+jqRef.width()+40
                    }).show();
                }

                // Searches for a specific variation set options. Returns 0 or Variation KEY
                // @param array Array(variation set name => value);
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

                function setSelectedVariant(varArr){
                    // Set default value.
                    varArr = (typeof varArr !== 'undefined') ? varArr : getCurrentVarSel();
                    selectedVariant       = searchVariation(varArr);
                }

                function updateSelectedVariant(jqRef){
                    var assocName       = jqRef.data("name");
                    var assocVal        = jqRef.val();

                    // Get our current variant.
                    var varArr = []; varArr[assocName] = assocVal;
                    selectedVariant       = searchVariation(varArr);

                    // Set variation details.
                    jQuery("#quantityavail").html(parseFloat( variations[selectedVariant]["quantity"]).toFixed(0));
                    jQuery("#quantitysold").html(parseFloat( variations[selectedVariant]["quantitySold"]).toFixed(0));

                    // Set our variation pricing.
                    updateProductPrices();

                    // Sets the picture for our variation (if available).
                    jQuery(".gallery-thumbnails .item > a[data-assoc=\"" + assocName + "\"][data-assocval=\"" + assocVal + "\"]").click();

                    // Set the variation available sets.
                    updateVariationSets(assocName);
                }

                function updateVariationSets(currentSet){
                    var variationsArr   = Object.keys(variationSets);                   // Get our keys.
                    var nextIndex       = variationsArr.indexOf(currentSet)+1;          // Start from next set on...
                    // Test all sets and hide non existing variations.
                    for (i = nextIndex; i < Object.keys(variationSets).length; i++){
                        // Test all options of this set.
                        jQuery("#varset_" + i + " > option").show().each(function(){
                            var varArr = [];
                            varArr[variationsArr[i]] = jQuery(this).html();
                            // If we don't have an item with this specific option - hide it.
                            if (searchVariation(varArr) == 0) {
                                jQuery(this).hide();
                            }
                        });
                    }
                }

                function updateQuantity(){
                    var jqRef = jQuery("#orderquantity");
                    var quantityavail = jQuery("#quantityavail").html();
                    if (selectedVariant != 0){
                        quantityavail = parseFloat(variations[selectedVariant]["quantity"]);
                    }
                    if (jqRef.val() < 1){
                        jqRef.effect("highlight", 1500);
                        jqRef.val(quantityavail);
                        return;
                    }
                    if(jqRef.val() > quantityavail){
                        jqRef.effect("highlight", 1500);
                        jqRef.val(quantityavail);
                    }
                }

                function updateShippingOpt(){
                    // Set the description text:
                    var shippingDet = [];
                    if (shippingOpts[selectedShipping]["deliveryMin"]["date"]){
                        shippingDet.push("Estimated delivery " +
                            "<b>" +
                            shippingOpts[selectedShipping]["deliveryMin"]["date"] +
                            "</b> and <b>" +
                            shippingOpts[selectedShipping]["deliveryMax"]["date"] +
                            "</b>" +
                            " <span id=\"shippingdays\">(" +
                            shippingOpts[selectedShipping]["deliveryMin"]["days"] +
                            "-" +
                            shippingOpts[selectedShipping]["deliveryMax"]["days"] +
                            ") </span>"
                        );
                    }else{
                        shippingDet.push("Estimated delivery varies for items shipped from an international location");
                    }
                    if (shippingOpts[selectedShipping]["additional"]){
                        if (shippingOpts[selectedShipping]["additional"]=="0.0"){
                            shippingDet.push("FREE Shipping for additional items!");
                        }else {
                            shippingDet.push("Additional item cost: " + exchSymbol + parseFloat(shippingOpts[selectedShipping]["additional" + exchExtension]).toFixed(2));
                        }
                    }
                    if (shippingOpts[selectedShipping]["insurance"]){
                        shippingDet.push("Shipping insurance cost: " + exchSymbol + parseFloat(shippingOpts[selectedShipping]["insurance" + exchExtension]).toFixed(2));
                    }
                    if (shippingOpts[selectedShipping]["duty"]){
                        shippingDet.push("Import duty cost: " + exchSymbol + parseFloat(shippingOpts[selectedShipping]["duty" + exchExtension]).toFixed(2));
                    }
                    // Display the shipping details.
                    jQuery("#shippingcostsdets").html(shippingDet.join("<br />"));
                    // Set the price value.
                    jQuery("#shippingprice").html(exchSymbol + parseFloat(shippingOpts[selectedShipping]["price" + exchExtension]).toFixed(2));
                }

                function updateProductPrices(){
                    var exchDetails = getProductPricesDetails(exchExtension);

                    // Check for errors.
                    if (Object.keys(exchDetails).length==0){return;}

                    // Display the prices in their container.
                    Object.keys(exchDetails).forEach(function(key){
                        // On page output.
                        var priceOutput = (isNaN(parseFloat(exchDetails[key]))) ? "-" : exchSymbol + parseFloat(exchDetails[key]).toFixed(2);
                        jQuery("#" + key).html(priceOutput);
                    });
                }

                function getProductPricesDetails(pricetype){
                    // Make sure the user have chosen his shipping option before we go on.
                    if (selectedShipping==-1){
                        jQuery("#shippmentdiv").effect("highlight", 1500);
                        return [];
                    }

                    // Set default value.
                    pricetype = (typeof pricetype !== 'undefined') ? pricetype : "";

                    // Load these from admin panel.
                    var paypalcomm = parseFloat(3.5/100);
                    var storecomm = 10/100;
                    var minstorecomm = 5;
                    // Get shipping details.
                    var shippingprice       = parseFloat( shippingOpts[selectedShipping]["price" + pricetype]);
                    var shippingadditional  = parseFloat( shippingOpts[selectedShipping]["additional" + pricetype]);
                    var shippingduty        = parseFloat( shippingOpts[selectedShipping]["duty" + pricetype]);
                    var shippinginsurance   = parseFloat( shippingOpts[selectedShipping]["insurance" + pricetype]);

                    // Get item pricing details.
                    var itemprice           = parseFloat( itemPricing["price" + pricetype] );
                    // If we have a specific variant use it's details.
                    if (selectedVariant!=0){
                        // set variation details.
                        itemprice           = parseFloat( variations[selectedVariant]["price" + pricetype] );
                    }
                    var orderquantity       = parseFloat( jQuery("#orderquantity").val() );

                    // Make some calcs.
                    // Sum our shipping costs.
                    shippingprice += (orderquantity>1) ? (orderquantity-1)*shippingadditional : 0;
                    // Add our duty costs if applicable.
                    shippingprice += (shippingduty>0) ? shippingduty : 0;
                    // Add our insurance costs if applicable.
                    shippingprice += (shippinginsurance>0) ? shippinginsurance : 0;

                    // Calc item cost by order quantity.
                    var allitemsprice = itemprice*orderquantity;
                    var outputArr = [];
                    // Single item price, before any modifiers.
                    outputArr["itemprice"] = itemprice;
                    // Total shipping costs - shipping+(additional*quantity)+duty+insurance.
                    outputArr["shippingprice"] = shippingprice;
                    // Store comminsion * item price + shipping costs. [if lower then minimum, set to minimum].
                    outputArr["storeprice"] = (storecomm*(allitemsprice+shippingprice)>minstorecomm) ? storecomm*(allitemsprice+shippingprice) : minstorecomm;
                    // Paypal comminsion * item price + shipping costs + store commision.
                    outputArr["paypalprice"] = paypalcomm*(allitemsprice+shippingprice+outputArr["storeprice"]);
                    // Final price = item(s) price + shipping + paypal + store.
                    outputArr["finalPrice"] = allitemsprice+shippingprice+outputArr["paypalprice"]+outputArr["storeprice"];
                    // Total price per item = final price/quantity.
                    outputArr["totalprice"] = outputArr["finalPrice"]/orderquantity;

                    return outputArr;
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
                                $assocVal = (isset($pic["assocVal"])&&!empty($pic["assocVal"])) ? "data-assocval=\"".$pic["assocVal"]."\"" : "";
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
                                $assocVal = (isset($pic["assocVal"])&&!empty($pic["assocVal"])) ? "data-assocval=\"".$pic["assocVal"]."\"" : "";
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
                    $varSetCnt = 0;
                    foreach ($product->variationSets as $setName => $setVars){
                        ?>
                        <div id="vardiv_<?php echo $varSetCnt;?>">
                            <div class="inline header"><?php Utils::pageEcho($setName);?>:</div>
                            <div class="inline">
                                <select id="varset_<?php echo $varSetCnt;?>" name="varset_<?php echo $varSetCnt;?>" class="varset" data-name="<?php echo $setName;?>">
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
                        $varSetCnt++;
                    }
                    ?>
                </div>
                <?php if ($varSetCnt>0){ ?>
                <div align="center">
                    <span> Available <span id="quantityavail"><?php Utils::pageEcho($product->quantityAvailable);?></span> </span> / <span> Sold <span id="quantitysold"><?php Utils::pageEcho($product->quantitySold);?></span> </span>
                </div>
                <?php } ?>

                <div id="itemshippingdiv">
                    <div>Shipping options:</div>
                    <div id="shippmentdiv">
                            <?php
                            $i = 0;
                            foreach ($product->shippingDetails->shippingOptions as $shippingOpts){
                                $selected = ($i==0)?"checked=\"checked\"": "";
                            ?>
                        <div>
                            <div class="radiocol">
                                <input type="radio" class="shippingopt" name="shippingOptions[]" id="<?php echo "shipradio".$i;?>" data-index="<?php echo $i;?>"
                                    <?php echo $selected;?>>
                            </div>
                            <div class="namecol">
                                <label for="<?php echo "shipradio".$i;?>"><?php Utils::pageEcho($shippingOpts["name"]);?></label>
                            </div>
                            <div class="pricecol">
                                <label for="<?php echo "shipradio".$i;?>"> <?php echo $shippingOpts["price".$exchangeExtension]." ".Utils::getCurrencySymbol($exchangeCurrency);?> </label>
                            </div>
                        </div>
                            <?php
                                $i++;
                            }
                            ?>
                        <div id="shippingcostsdets">

                        </div>
                    </div>
                </div>
            </div>
            <hr/>

            <div>Order details [<a href="#" id="orderdetailstogg">+</a>]</div>
            <div id="quicksummary" class="inline">
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
                </div>

                <div id="pricinginfo" class="inline">
                    <div>Pricing summary:</div>
                    <div>
                        <div class="inline header">Item price:</div>
                        <div id="itemprice" class="inline"> <?php echo $itemPricing["priceSymbol".$exchangeExtension].$itemPricing["price".$exchangeExtension];?> </div>
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

                <div id="pricingsumm" class="inline" align="center">
                    <div>Price per item:</div>
                    <div id="totalprice"> <?php echo $itemPricing["priceSymbol".$exchangeExtension].$itemPricing["price".$exchangeExtension];?> </div>
                </div>

                <div id="exchangeDisplayDiv">
                    <div id="exchangeDivCont">
                        <div id="pop_origprice">
                            <div class="popinline header"> Original price: </div>
                            <div class="popinline">
                                <span id="originalCurrSymbol"></span><span id="originalPrice"></span>
                            </div>
                        </div>
                        <div id="pop_rate">
                            <div class="popinline header"> Exchange rate: </div>
                            <div class="popinline">
                                <?php echo Utils::getCurrencySymbol($exchangeCurrency);?><span id="exchangeRate"></span>
                                =
                                <span id="originalCurrSymbol2"></span>1
                            </div>
                        </div>
                        <div id="pop_provider" align="center">
                            *Exchange rates supplied by<br/>
                            'European Central Bank'
                        </div>
                    </div>
                    <div id="pop_bottom"></div>
                </div>
            </div>



            <div id="ordercontainer" class="inline">
                <?php if ($varSetCnt==0){ ?>
                    <div align="center">
                        <span> Available <span id="quantityavail"><?php Utils::pageEcho($product->quantityAvailable);?></span> </span> / <span> Sold <span id="quantitysold"><?php Utils::pageEcho($product->quantitySold);?></span> </span>
                    </div>
                <?php } ?>
                <div class="inline">
                    <div class="inline header">Quantity:</div>
                    <?php
                        $maxQuantity = (intval($product->quantityAvailable) > intval($product->maxItemsOrder) && intval($product->maxItemsOrder) > 0) ? intval($product->maxItemsOrder) : intval($product->quantityAvailable);
                    ?>
                    <div class="inline"><input id="orderquantity" type="number" max="<?php Utils::pageEcho($maxQuantity);?>" min="1" value="1" /></div>
                </div>
                <div class="inline">
                    <div class="inline header">Total price: </div>
                    <div id="finalPrice" class="inline" align="center"> <?php echo $itemPricing["priceSymbol".$exchangeExtension].$itemPricing["price".$exchangeExtension];?> </div>
                </div>
                <div class="inline addtocart">
                    <div id="buynowbuttondiv" align="center">
                        Add to cart
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
                        <div class="inline"><?php echo $value;?></div>
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
        Debug Output: [<a href="#" id="debugOutPutTogg">+</a>]
        <div id="DebugProductOutput">
            <?php Utils::preEcho($product);?>
        </div>
        <?php
        return ob_get_clean();
    }
}

?>