<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/25/2015
 * Time: 5:47 AM
 */
?>

<div id="storeLocator">
    <div id="mapcontdiv" class="inline">
        <div class="storesTitle">
            Closest store location:
        </div>
        <div id="mapdiv">
            <div class="aStoreDiv">
                <div class="aStoreTitle">
                    <div class="aStoreTitleName"><?php echo $store["name"];?></div>
                    <i><div class="aStoreTitleAddress"><?php echo $store["address"];?></div></i>
                </div>
                <div class="aStoreImageDiv">
                    <iframe id="mapiframe" class="aStoreImageDiv" width="100%" height="100%" frameborder="0" style="border:0" src="<?php echo storeHelper::getStoreGoogleMapsEmbed($store["address"]);?>" allowfullscreen></iframe>

                </div>
            </div>
        </div>
    </div>
    <div id="storescontdiv" class="inline">
        <div class="storesTitle">
            Our stores:
        </div>
        <?php
        $allStores = storeHelper::getStores(array());
        if ($allStores){
            foreach($allStores as $aStore){
                ?>
                <div class="aStoreDiv" data-embed="<?php echo storeHelper::getStoreGoogleMapsEmbed($aStore["address"]);?>">
                    <div class="aStoreTitle">
                        <div class="aStoreTitleName"><?php echo $aStore["name"];?></div>
                        <i><div class="aStoreTitleAddress"><?php echo $aStore["address"];?></div></i>
                    </div>
                    <div class="aStoreImageDiv">
                        <img src="<?php echo storeHelper::getStoreMapImg($aStore);?>">
                    </div>
                </div>

            <?php
            }
        }
        ?>

    </div>
</div>