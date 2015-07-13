<div>
    <div id="featured-products-widget" class="masonrygrid grid">
        <?php foreach($products as $product){
            if (!isset($product["productID"]) || empty($product["productID"])){break;}
            $imgURL = (isset($product["image"])) ? '<img src="'.Utils::getPictureBySize($product["store"], $product["image"], "500wh").'" />' : __("Image Not Found!", 'coffee-shopping' );
            ?>
            <div class="grid-sizer"></div>
            <a href="<?php echo Utils::getProductPageLink($product["productID"], $product["store"]);?>">
                <div class="masonryitem grid-item">
                    <div class="mosonryimage"><?php echo $imgURL;?></div>
                    <div class="mosonrytitle"><?php echo Utils::truncateStringToLength($product["title"], 40);?></div>
                    <div class="mosonryprice"><?php _e("Price:", 'coffee-shopping' ); ?><?php echo $product["priceSymbolExch"].$product["priceExch"]?></div>
                    <div class="mosonryshipping"><?php _e("Shipping:", 'coffee-shopping' ); ?><?php echo $product["shippingSymbolExch"].$product["shippingExch"]?></div>
                </div>
            </a>
        <?php }?>
    </div>
</div>
