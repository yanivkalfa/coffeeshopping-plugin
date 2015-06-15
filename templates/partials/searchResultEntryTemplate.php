<li id="<?php echo $item["ID"];?>" class="searchresultentry">

    <div id="imgdivcont">
        <div class="imgdiv">
            <a href="<?php echo esc_url($productPageLink);?>" class="imglink">
                <img src="<?php echo $item["image"];?>" alt="<?php echo $item["title"];?>">
            </a>
        </div>
    </div>

    <div id="detailscont">
        <div id="detailstopcont">
            <h3 class="titlediv">
                <a href="<?php echo esc_url($productPageLink);?>" class="" title="<?php echo $item["title"];?>">
                    <strong><?php echo $item["title"];?></strong>
                </a>
                <span class="catspan">Category: <?php echo $item["categoryText"];?></span>
            </h3>

            <div class="subtitlediv">
                <?php echo $item["subtitle"];?>
            </div>
        </div>

        <div id="detailsdivcont">
            <ul class="pricediv nolistbull minidetails">
                <li class="itemcondition">
                    <span class="">Condition: <?php echo $item["conditionText"];?></span>
                </li>
                <li class="buyprice">
                <span class="bold">
                        Price: <b><?php echo $item["priceSymbol".$exchangeExtension];?></b><?php echo $item["price".$exchangeExtension];?>
                </span>
                </li>
                <li class="shippinginfo">
                <span class="shipp">
                    <span>
                        Shipping: <span class="bfsp"><?php echo $item["shippingType"];?></span>
                    </span>
                </span>
                </li>
            </ul>
            <ul class="locationdiv nolistbull minidetails">
                <li>
                    From <?php echo $item["locationInfo"];?>
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

    <div id="entryfooterdiv">
        <a href="<?php echo esc_url($productPageLink);?>">View details</a>
    </div>
</li>