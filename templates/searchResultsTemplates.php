<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 5/26/2015
 * Time: 2:49 PM
 */

abstract class searchResultsTemplates {

    static public function getSearchResultItem($item){
        ob_start();
        ?>
        <li id="<?php echo $item["ID"];?>" class="searchresultentry">

            <div class="imgdiv">
                <a href="#MAKELINK" class="imglink">
                    <img height="196" width="225" src="<?php echo $item["image"];?>" alt="<?php echo $item["title"];?>">
                </a>
            </div>

            <h3 class="titlediv">
                <a href="#MAKELINK" class="" title="<?php echo $item["title"];?>">
                    <strong><?php echo $item["title"];?></strong>
                </a>
                <span class="catspan">Category: <?php echo $item["categoryText"];?></span>
            </h3>

            <div class="subtitlediv">
                <?php echo $item["subtitle"];?>
            </div>

            <ul class="pricediv nolistbull minidetails">
                <li class="itemcondition">
                    <span class="">Condition: <?php echo $item["conditionText"];?></span>
                </li>
                <li class="buyprice">
                <span class="bold">
                        Price: <b><?php echo $item["priceCurrency"];?></b> <?php echo $item["price"];?>
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
                        <img class="" src="" height="19px" width="122px" alt="Top Seller">
                    <?php }; ?>
                </li>
            </ul>
            <ul class="viewproductdiv nolistbull">
                <li>
                    <img class="" src="" height="19px" width="122px" alt="@$API">
                </li>
                <li>
                    <a href="#MAKELINK"><input type="button" value="View details"></a>
                </li>
            </ul>
        </li>
        <?php
        return ob_get_clean();
    }
}

?>