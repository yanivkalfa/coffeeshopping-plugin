<div>
    <div id="featured-products-widget" class="masonrygrid grid">
        <?php foreach($products as $product){
            $imgURL = (isset($product->pics[0]["picURL"])) ? '<img src="'.Utils::getPictureBySize($store, $product->pics[0]["picURL"], "500wh").'" />' : "Image Not Found!";
            ?>
            <div class="grid-sizer"></div>
            <a href="<?php echo Utils::getProductPageLink($product->ID, $store);?>">
                <div class="masonryitem grid-item">
                    <div class="mosonryimage"><?php echo $imgURL;?></div>
                    <div class="mosonrytitle"><?php echo Utils::truncateStringToLength($product->title, 40);?></div>
                    <div class="mosonryprice">Price:<?php echo $product->{"priceSymbol".$exchangeExtension}.$product->{"price".$exchangeExtension};?></div>
                </div>
            </a>
        <?php }?>
    </div>
</div>