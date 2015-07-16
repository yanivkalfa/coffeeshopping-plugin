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
    $ns.exchExtension = "Exch";
    $ns.exchSymbol = "<?php echo Utils::getCurrencySymbol(EXCH_CURRENCY);?>";
    $ns.exchCurrency = "<?php echo EXCH_CURRENCY;?>";
    $ns.productID = "<?php echo $product->ID;?>";
    $ns.productPic = "<?php echo (isset($product->pics[0]["picURL"])) ? $product->pics[0]["picURL"] : "" ;?>";
    $ns.productTitle = "<?php echo $product->title;?>";
    $ns.storeLink = "<?php echo $product->storeLink;?>";
    $ns.orderLimit = "<?php echo (isset($product->orderLimit)) ? $product->orderLimit : 1;?>";
    $ns.store = "<?php echo $store;?>";
    $ns.paypalcomm = parseFloat(<?php echo 2.5/100; //TODO:: get from admin panel. ?>);

    $ns.jqueryUrl = '<?php echo plugins_url( '../../bower_components/jquery/dist/jquery.min.js', __FILE__ ); ?>';
</script>

<div class="productpagecontent">
    <div id="topcontainer">
        <div id="topdetailspanel">
            <div class="productDetailsTitle">
                <h1><?php Utils::pageEcho($product->title); ?></h1>
                <h2><?php Utils::pageEcho($product->subtitle); ?></h2>
            </div>
            <div class="productCategoryText">
                <?php echo (isset($product->categoryText) && !empty($product->categoryText))? "<h4> ".__("Category:", 'coffee-shopping' )." ".$product->categoryText."</h4>": "";?>
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
            <a href="<?php echo $product->storeLink;?>" title="<?php _e("View on", 'coffee-shopping' ); ?> <?php echo $store;?>" target="_blank"><div class="<?php echo Utils::getAPILogoClass($store);?> flleft"></div></a>
            <h3>
                <?php _e("Make your order", 'coffee-shopping' ); ?>
                <?php echo $product->topRatedItem ? "<div title=\"".__("Top rated seller", 'coffee-shopping' )."\" class=\"topratedproductimg flleft\"></div>" : "";?>
                :
            </h3>
            <div id="itemcondition">
                <div class="inline header"><?php _e("Item condition:", 'coffee-shopping' ); ?></div>
                <div class="inline"><?php Utils::pageEcho($product->conditionText);?></div>
            </div>

            <div id="itemvariations" data-hasvars="<?php echo (count($product->variationSets)>0)?"1":"0";?>">
                <?php
                $varSetCnt = 0;
                foreach ($product->variationSets as $setName => $setVars){
                    ?>
                    <div id="vardiv_<?php echo $varSetCnt;?>">
                        <div class="inline header"><?php Utils::pageEcho($setName);?>:</div>
                        <div class="inline">
                            <select id="varset_<?php echo $varSetCnt;?>" name="varset_<?php echo $varSetCnt;?>" class="varset" data-name="<?php echo $setName;?>">
                                <option value=""><?php _e("Select", 'coffee-shopping' ); ?> <?php echo $setName;?></option>
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
                <div id="stockavailability" align="center">
                    <span> <?php _e("Available", 'coffee-shopping' ); ?> <span id="quantityavail"><?php Utils::pageEcho($product->quantityAvailable);?></span> </span> / <span> <?php _e("Sold", 'coffee-shopping' ); ?> <span id="quantitysold"><?php Utils::pageEcho($product->quantitySold);?></span> </span>
                </div>
            <?php } ?>

            <div id="itemshippingdiv">
                <div><?php _e("Shipping options:", 'coffee-shopping' ); ?> </div>
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
                                <label for="<?php echo "shipradio".$i;?>"> <?php echo $shippingOpts["priceExch"]." ".Utils::getCurrencySymbol(EXCH_CURRENCY);?> </label>
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

        <div><?php _e("Order details", 'coffee-shopping' ); ?> [<a href="#" id="orderdetailstogg">+</a>]</div>
        <div id="quicksummary" class="inline">
            <div id="sellerinfo">
                <div class="inline header"> <?php _e("Seller:", 'coffee-shopping' ); ?> </div>
                <div class="inline">
                    <span> <?php echo $product->sellerInfo["userID"];?></span>,
                    <span> <?php _e("Score:", 'coffee-shopping' ); ?> <?php echo $product->sellerInfo["feedbackScore"];?></span>,
                    <span> <?php _e("Positive:", 'coffee-shopping' ); ?> <?php echo $product->sellerInfo["feedbackPercent"];?>%</span>
                    <?php echo $product->sellerInfo["topRated"]? "<div class=\"topratedsellerimg\"></div>" : "";?>
                </div>
            </div>

            <div id="shippinginfo">
                <div>
                    <div class="inline header"> <?php _e("Shipping from:", 'coffee-shopping' ); ?> </div>
                    <div class="inline"><?php Utils::pageEcho($product->location.", ".Utils::getCountryFromCode($product->country));?></div>
                </div>
            </div>

            <div id="pricinginfo" class="inline">
                <div><?php _e("Pricing summary:", 'coffee-shopping' ); ?></div>
                <div>
                    <div class="inline header"><?php _e("Item price:", 'coffee-shopping' ); ?></div>
                    <div id="itemprice" class="inline"> <?php echo $itemPricing["priceSymbolExch"].$itemPricing["priceExch"];?> </div>
                </div>
                <div>
                    <div class="inline header"><?php _e("Shipping price:", 'coffee-shopping' ); ?></div>
                    <div id="shippingprice" class="inline"></div>
                </div>
                <?php
                /*
                <div>
                    <div class="inline header"><?php _e("Store comission:", 'coffee-shopping' ); ?></div>
                    <div id="storeprice" class="inline popupinfo"
                         data-popup-title="<?php _e("Store commission info", 'coffee-shopping' ); ?>"
                         data-popup-content="<?php _e("Store commission will be calculated on checkout.", 'coffee-shopping' ); ?>"
                         data-popup-footer="<?php _e('*CoffeeShopping club members enjoy <br> store commission discount!','coffee-shopping' ); ?>">
                        (?)</div>
                </div>
                */
                ?>
                <div>
                    <div class="inline header"><?php _e("Paypal comission:", 'coffee-shopping' ); ?></div>
                    <div id="paypalprice" class="inline"></div>
                </div>
            </div>

            <div id="pricingsumm" class="inline" align="center">
                <div><?php _e("Price per item:", 'coffee-shopping' ); ?></div>
                <div id="totalprice"> <?php echo $itemPricing["priceSymbolExch"].$itemPricing["priceExch"];?> </div>
            </div>

            <div id="exchangeDisplayDiv">
                <div id="exchangeDivCont">
                    <div id="pop_origprice">
                        <div class="popinline header"> <?php _e("Original price:", 'coffee-shopping' ); ?> </div>
                        <div class="popinline">
                            <span id="originalCurrSymbol"></span><span id="originalPrice"></span>
                        </div>
                    </div>
                    <div id="pop_rate">
                        <div class="popinline header"> <?php _e("Exchange rate:", 'coffee-shopping' ); ?> </div>
                        <div class="popinline">
                            <?php echo Utils::getCurrencySymbol(EXCH_CURRENCY);?><span id="exchangeRate"></span>
                            =
                            <span id="originalCurrSymbol2"></span>1
                        </div>
                    </div>
                    <div id="pop_provider" align="center">
                        <?php _e("*Exchange rates supplied by", 'coffee-shopping' ); ?><br/>
                        <?php _e("'European Central Bank'", 'coffee-shopping' ); ?>
                    </div>
                </div>
                <div class="pop_bottom"></div>
            </div>

            <div id="infoPopUpDisplayDiv">
                <div id="infoPopUpDisplayCont">
                    <div id="PopUpInfotitle">

                    </div>
                    <div id="PopUpInfocontent">

                    </div>
                    <div id="PopUpInfofooter" align="center">

                    </div>
                </div>
                <div id="pop_bottom"></div>
            </div>


        </div>



        <div id="ordercontainer" class="inline">
            <?php if ($varSetCnt==0){ ?>
                <div id="stockavailability" align="center">
                    <span> <?php _e("Available", 'coffee-shopping' ); ?> <span id="quantityavail"><?php Utils::pageEcho($product->quantityAvailable);?></span> </span> / <span> <?php _e("Sold", 'coffee-shopping' ); ?> <span id="quantitysold"><?php Utils::pageEcho($product->quantitySold);?></span> </span>
                </div>
            <?php } ?>
            <div class="inline">
                <div class="inline header"><?php _e("Quantity:", 'coffee-shopping' ); ?></div>
                <?php
                $maxQuantity = (intval($product->quantityAvailable) > intval($product->orderLimit) && intval($product->orderLimit) > 0) ? intval($product->orderLimit) : intval($product->quantityAvailable);
                ?>
                <div class="inline"><input id="orderquantity" type="number" max="<?php Utils::pageEcho($maxQuantity);?>" min="1" value="1" /></div>
            </div>
            <div class="inline">
                <div class="inline header"><?php _e("Total price:", 'coffee-shopping' ); ?> </div>
                <div id="finalPrice" class="inline" align="center"> <?php echo $itemPricing["priceSymbolExch"].$itemPricing["priceExch"];?> </div>
            </div>
            <div class="inline addtocart">
                <div id="buynowbuttondiv" align="center"><?php _e("Add to cart", 'coffee-shopping' ); ?></div>
                <div id="addtocartresultOK"></div>
                <div id="addtocartresultERR"></div>
            </div>
        </div>
    </div>
    <div id="detailscontainer">
        <div id="itemIDspec" align="left"><?php echo $_GET["store"];?> <?php _e("item number:", 'coffee-shopping' ); ?> <?php echo $product->ID;?></div>
        <?php if (isset($product->itemSpecifics) && !empty($product->itemSpecifics)) { ?>
            <div id="itemspecs">
                <h3><?php _e("Item Specifics:", 'coffee-shopping' ); ?></h3>
                <?php foreach ($product->itemSpecifics as $spec => $value) { ?>
                    <div class="inline block">
                        <div class="inline header"><?php echo $spec;?>:</div>
                        <div class="inline"><?php echo $value;?></div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>

        <hr>
        <div id="detailspanel">
            <iframe id="productDescriptionIframe" src="<?php echo plugins_url( '../../templates/partials/productDescriptionTemplate.php?view-product='.$_GET['view-product'].'&store='.$_GET['store'] , __FILE__ );?>"></iframe>
        </div>
    </div>
</div>