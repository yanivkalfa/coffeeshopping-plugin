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

                // Load our carousel.
                var m = jQuery('.galleryContainer').CB_CarouseljQ({
                    change: function(s, obj){
                        jQuery(obj).children("a").click();
                    }
                });

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
                                $imgGallery = ebay_Utils::getEbayPicture($pic, "400s");
                                $imgBig = ebay_Utils::getEbayPicture($pic, "1600s");
                                ?>
                        <a href="<?php echo $imgBig;?>" class="zoomIt <?php echo $class;?>"><img src="<?php echo $imgGallery;?>" alt="" /></a>
                                <?php
                                $class = "hidden";
                            }
                        ?>
                    </div>
                    <div class="galleryContainer" align="center">
                        <ul class="gallery-thumbnails">
                            <?php
                            foreach ($product->pics as $pic){
                                $imgThumb = ebay_Utils::getEbayPicture($pic, "64s");
                                ?>
                            <li class="item"><a href="#"><img src="<?php echo $imgThumb;?>" width="54" alt="" /></a></li>
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
                    Make your order
                    <?php echo $product->topRatedItem ? " top-rated-item " : "";?>
                    :
                </h3>
                <div id="itemcondition">
                    <div class="inline size25">Item condition:</div>
                    <div class="inline size25"><?php Utils::pageEcho($product->conditionText);?></div>
                </div>

                <div id="itemvariations">
                    <?php
                    foreach ($product->variationSets as $setName => $setVars){
                        ?>
                        <div id="vardiv_<?php echo $setName;?>">
                            <div class="inline size25"><?php Utils::pageEcho($setName);?>:</div>
                            <div class="inline size25">
                                <select name="varset_<?php echo $setName;?>">
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
                    }
                    ?>
                </div>

                <div id="itemshippingdiv">
                    <div id="shippmentdiv">
                        <div class="inline size25">Shipping options:</div>
                        <div class="inline size25">
                            <select id="shippingOptions" name="shippingopts">
                                <?php
                                    foreach ($product->shippingDetails->shippingOptions as $shippingOpts){
                                        ?>
                                    <option value="<?php echo $shippingOpts["shippingServiceCost"];?>" data-currency="<?php echo $shippingOpts["shippingServiceCostCurrency"];?>" data-additional="<?php echo $shippingOpts["shippingServiceAdditionalCost"];?>" data-additionalCurrency="<?php echo $shippingOpts["shippingServiceAdditionalCostCurrency"];?>" data-delMin="<?php echo $shippingOpts["estimatedDeliveryMinTime"];?>" data-delMax="<?php echo $shippingOpts["estimatedDeliveryMaxTime"];?>" data-duty="<?php echo $shippingOpts["importCharge"];?>" data-dutyCurrency="<?php echo $shippingOpts["importChargeCurrency"];?>">
                                        <?php Utils::pageEcho($shippingOpts["shippingServiceName"]);?>
                                    </option>
                                <?php
                                    }
                                ?>
                            </select>
                        </div>
                        <div id="shippingcostsdets" class="inline size50">

                        </div>
                    </div>
                </div>

                <hr/>

                <div id="quicksummary">
                    <div id="sellerinfo">
                        <span> <?php echo $product->sellerInfo["userID"];?></span>
                        <span> Score: <?php echo $product->sellerInfo["feedbackScore"];?></span>
                        <span> Positive: <?php echo $product->sellerInfo["feedbackPercent"];?>%</span>
                        <?php echo $product->sellerInfo["topRated"]? "<div class=\"topratedsellerimg\"></div>" : "";?>
                    </div>
                    <div id="shippinginfo">
                        <div class="inline size25">
                            Shipping from:
                        </div>
                        <div class="inline size25">
                            <?php Utils::pageEcho($product->location.", ".$product->country);?>
                        </div>
                        <div class="inline size25">
                            Delivery time:
                        </div>
                        <div class="inline size25">
                            Between <span id="deliverytimefrom"> X </span> and <span id="deliverytimeto"> Y </span>
                        </div>
                        <div>

                        </div>
                    </div>
                    <div id="pricinginfoheaders" class="inline size25">
                        <div>Item price:</div>
                        <div>Shipping price:</div>
                        <div>Store comission:</div>
                        <div>Paypal comission:</div>
                    </div>
                    <div id="pricinginfodets" class="inline size12">
                        <div id="itemprice"><?php echo $product->price;?> <?php echo $product->priceCurrency;?></div>
                        <div id="shippingprice">123</div>
                        <div id="storeprice">456</div>
                        <div id="paypalprice">789</div>
                    </div>
                    <div id="totalprice" class="inline size12">
                        <div>Price per item:</div>
                        <div id="totalprice">1233</div>
                        <div>Total price:</div>
                        <div id="finalPrice">123123</div>
                    </div>
                </div>

                <div id="orderquantity">
                    <div class="inline size25">
                        <div>
                            <span> Available <?php Utils::pageEcho($product->quantityAvailable);?> </span> / <span> Sold <?php Utils::pageEcho($product->quantitySold);?> </span>
                        </div>
                        <div>
                            Quantity: <input id="orderquantity" type="number" max="<?php Utils::pageEcho($product->maxItemsOrder);?>" min="1" value="1" />
                        </div>
                    </div>
                    <div class="inline size25">
                        <div id="buynowbuttondiv">
                            BUY NOW
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <div id="detailscontainer">
            <div id="itemIDspec" align="left"><?php echo $_GET["store"];?> item number: <?php echo $product->ID;?></div>
            <div id="itemspecs">
                <h3>Item Specifics:</h3>
                <table id="itemspecstable" width="100%">
                    <tr>
                        <?php
                        $cnt = 0;
                        foreach($product->itemSpecifics as $spec => $value){
                            $cnt ++;
                            if ($cnt>2){
                                $cnt = 0;
                                echo "</tr><tr>";
                            }
                        ?>

                        <td width="20%"><?php echo $spec;?>:</td>
                        <td width="30%"><?php echo $value;?></td>
                        <?php
                        }
                        ?>
                    </tr>
                </table>
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