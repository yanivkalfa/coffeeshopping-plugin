<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/28/2015
 * Time: 1:54 AM
 */

?>

    <div class="cartitemsdiv <?php echo $itemClass;?>">

        <div class="cart-product" data-product-key="<?php echo htmlentities(json_encode($product)); ?>">

            <div class="cartitemimgdiv inline">
                <div class="cartitemimg inline">
                    <a href="<?php echo Utils::getProductPageLink($product->unique_store_id, $product->store) ; ?>">
                        <img src="<?php echo Utils::getPictureBySize($product->store, $product->img, "150wh"); ?>">
                    </a>
                </div>
            </div>

            <div class="cartitemdetailsdiv inline">
                <div class="cartitemtitle">
                    <h4>
                        <a href="<?php echo Utils::getProductPageLink($product->unique_store_id, $product->store) ; ?>"><?php echo $product->title; ?></a>
                    </h4>
                </div>

                <div class="cartitemdetailstore">
                    <a href="<?php echo $product->store_link;?>" title="View on <?php echo $product->store;?>" target="_blank">
                        <div class="<?php echo Utils::getAPILogoClass($product->store);?>"></div>
                    </a>
                </div>

                <div class="cartitemvariants">
                    <?php foreach($product->selected_variant as $variantName => $variantValue) : ?>
                        <div>
                            <div class="cart-product-variant inline header"><?php echo ucfirst($variantName); ?>: </div>
                            <div class="cart-product-details inline detail"><?php echo $variantValue; ?> </div>
                        </div>
                    <?php endforeach; ?>
                </div>


                <div class="cartitemdelivery inline">
                    <div class="inline header">
                        Estimated delivery:
                    </div>
                    <div class="inline detail">
                        <?php echo $product->delivery_min. "-" .$product->delivery_max ;?>
                    </div>
                </div>

                <?php if ($status){ ?>

                <div class="cartitemstatus inline">
                    <div class="inline">
                        <i class="fa fa-check-square-o fa-3x"></i> &nbsp;&nbsp;&nbsp;
                        <i class="fa fa-ils fa-3x"></i> &nbsp;&nbsp;&nbsp;
                        <i class="fa fa-truck fa-3x"></i> &nbsp;&nbsp;&nbsp;
                        <i class="fa fa-cubes fa-3x"></i> &nbsp;&nbsp;&nbsp;
                        <i class="fa fa-thumbs-up fa-3x"></i> &nbsp;&nbsp;&nbsp;
                    </div>
                </div>
                <?php } ?>

                <?php if ($actions){?>
                <div class="cartitemdetails inline">
                    <div class="cartitemdetailquantity">
                        <div class="inline header">
                            Quantity:
                        </div>
                        <div class="inline detail">
                            <input type="number" min="1" max="<?php echo $product->order_limit; ?>" id="quantity" class="product-quantity form-control" value="<?php echo $product->quantity; ?>"/>
                        </div>

                        <div class="display-none cart-product-update inline"> <a>[Update]</a></div>
                        <div class="cart-product-remove btn btn-primary inline">Remove item</div>
                    </div>

                </div>
                <?php } ?>

                <div class="cartitemdetailtotal">
                    <div class="inline header">
                        Total price:
                    </div>
                    <div class="inline detail">
                        <div class="cart-product-price"><?php echo $product->getPriceAfterQuantity(); ?></div>
                    </div>
                </div>

            </div>

        </div>

    </div>