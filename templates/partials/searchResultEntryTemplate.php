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
                <span class="catspan"><?php _e("Category:", 'coffee-shopping' ); ?> <?php echo $item["categoryText"];?></span>
            </h3>

            <div class="subtitlediv">
                <?php echo $item["subtitle"];?>
            </div>
        </div>

        <div class="detailsdivcont">
            <ul class="pricediv nolistbull minidetails">
                <li class="itemcondition">
                    <span class=""><?php _e("Condition:", 'coffee-shopping' ); ?> <?php echo $item["conditionText"];?></span>
                </li>
                <li class="buyprice">
                    <span class="bold">
                        <?php _e("Price:", 'coffee-shopping' ); ?> <b><?php echo $item["priceSymbol".$exchangeExtension];?></b><?php echo $item["price".$exchangeExtension];?>
                    </span>
                </li>
                <li class="shippinginfo">
                <span class="shipp">
                    <span>
                        <?php _e("Shipping:", 'coffee-shopping' ); ?> <span class="bfsp"><?php echo $item["shippingType"];?></span>
                    </span>
                </span>
                </li>
            </ul>
            <ul class="locationdiv nolistbull minidetails">
                <li>
                    <?php _e("From", 'coffee-shopping' ); ?> <?php echo $item["locationInfo"];?>
                </li>
                <li>
                    <?php if ($item["isTopSeller"]) { ?>
                        <div class="topratedsellerimg"></div>
                    <?php }; ?>
                </li>
            </ul>
            <ul class="viewproductdiv nolistbull minidetails">
                <li>
                    <a href="<?php echo $item["storeLink"];?>" title="View on <?php echo $API;?>" target="_blank"><div class="<?php echo Utils::getAPILogoClass($API);?>"></div></a>
                </li>
            </ul>
        </div>
    </div>

    <div class="entryfooterdiv">
        <a class="btn btn-primary" href="<?php echo esc_url($productPageLink);?>">View details</a>
    </div>
</li>