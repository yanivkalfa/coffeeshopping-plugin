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
        <div class="inline textbottom"><?php _e("Search for a store close to:", 'coffee-shopping' ); ?></div>
        <div class="inline">
            <input id="storeLocateInput" type="text"/>
            <div class="inline va-top"><input id="storeLocateButton" type="button" value="<?php _e("Go!", 'coffee-shopping' ); ?>"/></div>
        </div>

        <div id="storeLocatorAddressErr" class="display-none"><?php _e("We couldn't find the provided address!", 'coffee-shopping' ); ?></div>
    </div>

    <div id="mapcontdiv" class="inline">
        <div class="storesTitle">
            <?php _e("Closest store location:", 'coffee-shopping' ); ?>
        </div>
        <div id="mapdiv">
            <div class="aStoreDiv">
                <div class="aStoreTitle">
                    <div class="aStoreTitleName"></div>
                    <i><div class="aStoreTitleAddress"></div></i>
                </div>
                <div class="aStoreImageDiv">
                    <iframe id="mapiframe" class="aStoreImageDiv" width="100%" height="100%" frameborder="0" style="border:0" src="" allowfullscreen></iframe>
                    <?php if(Utils::isMobile()){?>
                        <a class="aStoreWazeLink aStoreMapWazeLink" href=""><div class="wazenavimg"></div></a>
                    <?php } ?>
                </div>
                <div class="aStoreDescriptionDiv">
                    <div class="aStoreDescImageDiv">
                        <img class="aStoreImage" src="" alt="Store image"/>
                    </div>
                    <div class="aStoreDescription"></div>
                    <?php if(Utils::isMobile()){?>
                        <div class="aStoreDescriptionNav">
                            <?php _e("Click to navigate using waze: ", "coffee-shopping");?>
                            <a class="aStoreWazeLink aStoreDescriptionWazeLink" href=""><div class="wazenavimg"></div></a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div id="storescontdiv" class="inline">
        <div class="storesTitle">
            <?php _e("Our stores:", 'coffee-shopping' ); ?>
        </div>
        <?php
        $allStores = StoreDatabaseHelper::getStores(array());
        if ($allStores){
            foreach($allStores as $aStore){
                ?>
                <div class="aStoreDiv"
                     data-store-id="<?php echo $aStore["ID"];?>"
                     data-embed="<?php if(isset($aStore["address"])){echo StoreDatabaseHelper::getStoreGoogleMapsEmbed($aStore["address"]);} ?>"
                     data-title-name="<?php if(isset($aStore["name"])){esc_attr_e($aStore["name"]);} ?>"
                     data-title-address="<?php if(isset($aStore["address"])){esc_attr_e($aStore["address"]);} ?>"
                     data-description="<?php if(isset($aStore["description"])){esc_attr_e($aStore["description"]);} ?>"
                     data-imgurl="<?php if(isset($aStore["imgurl"])){esc_attr_e($aStore["imgurl"]);} ?>"
                     data-wazeurl="<?php esc_attr_e("waze://?ll=".$aStore["lat"].",".$aStore["lng"]."&navigate=yes");?>"
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