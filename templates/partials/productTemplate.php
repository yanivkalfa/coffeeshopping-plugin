<?php
/**
 * TODO: details not in use: $product->>> handlingTime, timeLeft, availableTill.
 */

?>
<!-- all variables comes from Utils::getTemplate -->

<script language="javascript" type="text/javascript">

    // Set some vars.
    $ns.itemPricing = <?php echo json_encode($itemPricing);?>;
    $ns.shippingOpts = <?php echo json_encode($product->shippingDetails->shippingOptions);?>;
    $ns.selectedShipping = 0; // proper indexes start from 0.
    $ns.variationSets = <?php echo json_encode($product->variationSets);?>;
    $ns.variations = <?php echo json_encode($product->variations);?>;
    $ns.selectedVariant = -1; // default search result.
    $ns.exchExtension = "<?php echo $exchangeExtension; ?>";
    $ns.exchSymbol = "<?php echo Utils::getCurrencySymbol($exchangeCurrency);?>";
    $ns.exchCurrency = "<?php echo $exchangeCurrency;?>";
    $ns.productID = "<?php echo $product->ID;?>";
    $ns.productPic = "<?php echo (isset($product->pics[0]["picURL"])) ? $product->pics[0]["picURL"] : "" ;?>";
    $ns.productTitle = "<?php echo $product->title;?>";
    $ns.storeLink = "<?php echo $product->storeLink;?>";
    $ns.orderLimit = "<?php echo (isset($product->orderLimit)) ? $product->orderLimit : 1;?>";
    $ns.store = "<?php echo $store;?>";
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
                        $imgGallery = Utils::getPictureBySize($store, $pic["picURL"], "500wh");
                        $imgBig = Utils::getPictureBySize($store, $pic["picURL"], "original");
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
                            $imgThumb = Utils::getPictureBySize($store, $pic["picURL"], "64wh");
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
            <a href="<?php echo $product->storeLink;?>" title="View on <?php echo $store;?>" target="_blank"><div class="<?php echo Utils::getAPILogoClass($store);?> flleft"></div></a>
            <h3>
                <?php Utils::pageEcho("Make your order"); ?>
                <?php echo $product->topRatedItem ? "<div title=\"Top rated product!\" class=\"topratedproductimg flleft\"></div>" : "";?>
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
                    <div class="inline"><?php Utils::pageEcho($product->location.", ".Utils::getCountryFromCode($product->country));?></div>
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
                $maxQuantity = (intval($product->quantityAvailable) > intval($product->orderLimit) && intval($product->orderLimit) > 0) ? intval($product->orderLimit) : intval($product->quantityAvailable);
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