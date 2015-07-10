<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/25/2015
 * Time: 5:47 AM
 * TODO:: Add store working hours to stores. [maybe varchar desc]
 */
?>

<div id="storeLocator">
    <div id="storeLocatorAddress">
        <div class="inline textbottom">Search for a store close to:</div>
        <div class="inline">
            <input id="storeLocateInput" type="text"/>
            <div class="inline va-top"><input id="storeLocateButton" type="button" value="Go!"/></div>
        </div>

        <div id="storeLocatorAddressErr" class="display-none">We couldn't find the provided address!</div>
    </div>

    <div id="mapcontdiv" class="inline">
        <div class="storesTitle">
            Closest store location:
        </div>
        <div id="mapdiv">
            <div class="aStoreDiv">
                <div class="aStoreTitle">
                    <div class="aStoreTitleName"></div>
                    <i><div class="aStoreTitleAddress"></div></i>
                </div>
                <div class="aStoreImageDiv">
                    <iframe id="mapiframe" class="aStoreImageDiv" width="100%" height="100%" frameborder="0" style="border:0" src="" allowfullscreen></iframe>
                </div>
                &nbsp;
                <div class="aStoreDescriptionDiv">
                    <div class="aStoreDescImageDiv">
                        <img class="aStoreImage" src="" alt="Store image"/>
                    </div>
                    <div class="aStoreDescription"></div>
                </div>
            </div>
        </div>
    </div>
    <div id="storescontdiv" class="inline">
        <div class="storesTitle">
            Our stores:
        </div>
        <?php
        $allStores = StoreDatabaseHelper::getStores(array());
        if ($allStores){
            foreach($allStores as $aStore){
                ?>
                <div class="aStoreDiv"
                     data-store-id="<?php echo $aStore["ID"];?>"
                     data-embed="<?php echo StoreDatabaseHelper::getStoreGoogleMapsEmbed($aStore["address"]);?>"
                     data-title-name="<?php esc_attr_e($aStore["name"]);?>"
                     data-title-address="<?php esc_attr_e($aStore["address"]);?>"
                     data-description="<?php esc_attr_e($aStore["description"]);?>"
                     data-imgurl="<?php esc_attr_e($aStore["imgurl"]);?>"
                    >
                    <div class="aStoreTitle">
                        <div class="aStoreTitleName"><?php echo $aStore["name"];?></div>
                        <i><div class="aStoreTitleAddress"><?php echo $aStore["address"];?></div></i>
                    </div>
                    <div class="aStoreImageDiv">
                        <img src="<?php echo StoreDatabaseHelper::getStoreMapImg($aStore);?>">
                    </div>
                </div>

            <?php
            }
        }
        ?>

    </div>
</div>