<li id="<?php echo $item["ID"];?>" class="searchresultentry">

    <div class="imgdivcont">
        <div class="imgdiv">
            <a href="<?php echo esc_url($productPageLink);?>" class="imglink">
                <img src="<?php echo Utils::getPictureBySize($API, $item["image"], "300w"); ?>" alt="<?php echo $item["title"];?>">
            </a>
        </div>
    </div>

    <div class="detailscont">
        <div class="detailstopcont">
            <h3 class="titlediv">
                <a href="<?php echo esc_url($productPageLink);?>" class="" title="<?php echo $item["title"];?>">
                    <strong><?php echo $item["title"];?></strong>
                </a>
                <div class="catspan">
                    <div class="inline"><?php _e("Category:", 'coffee-shopping' ); ?></div>
                    <div class="inline"><?php echo $item["categoryText"];?></div>
                </div>

            </h3>

            <div class="subtitlediv">
                <?php echo $item["subtitle"];?>
            </div>
        </div>

        <div class="detailsdivcont">
            <ul class="pricediv nolistbull minidetails">
                <li class="itemcondition">
                    <div class="inline"><?php _e("Condition:", 'coffee-shopping' ); ?></div>
                    <div class="inline"><?php echo $item["conditionText"];?></div>
                </li>
                <li class="buyprice">
                    <div class="inline"><?php _e("Price:", 'coffee-shopping' ); ?></div>
                    <div class="inline"><b><?php echo $item["priceSymbolExch"];?></b><?php echo $item["priceExch"];?></b></div>
                </li>
                <li class="shippinginfo">
                        <?php
                            $shippingtext = ($item["shippingExch"]==0) ? __("Free", "coffee-shopping") : $item["shippingSymbolExch"].$item["shippingExch"];
                        ?>
                    <div class="inline"><?php _e("Shipping:", 'coffee-shopping' ); ?></div>
                    <div class="inline"><?php echo $shippingtext;?></div>
                </li>
            </ul>
            <ul class="locationdiv nolistbull minidetails">
                <li>
                    <div class="inline"><?php _e("From:", 'coffee-shopping' ); ?></div>
                    <div class="inline"><?php echo $item["locationInfo"];?></div>
                </li>
                <li>
                    <?php if ($item["isTopSeller"]) { ?>
                        <div class="topratedsellerimg"></div>
                    <?php }; ?>
                </li>
            </ul>
            <ul class="viewproductdiv nolistbull minidetails">
                <li>
                    <a href="<?php echo $item["storeLink"];?>" title="<?php _e("View on", 'coffee-shopping' ); ?> <?php echo $API;?>" target="_blank"><div class="<?php echo Utils::getAPILogoClass($API);?>"></div></a>
                </li>
            </ul>
        </div>
    </div>

    <div class="entryfooterdiv">
        <a class="btn btn-primary" href="<?php echo esc_url($productPageLink);?>">View details</a>
    </div>
</li>