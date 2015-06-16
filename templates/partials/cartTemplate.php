<!-- all variables comes from Utils::getTemplate -->

<script language="javascript" type="text/javascript">
    // Set some vars.
    $ns.cart = <?php echo json_encode($_SESSION['cart']->getObjectAsArray());?>;
</script>

<div class="<?php echo (count($_SESSION['cart']->get())) ? '' : 'display-none'; ?> cart has-products row col-lg-12 col-el-12">
    <?php foreach($_SESSION['cart']->get() as $key => $product) : ?>
        <div class="cart-product col-lg-12 col-el-12 cart-border pb-10 mb-15" data-product-key="<?php echo htmlentities(json_encode($product)); ?>">
            <div class="col-lg-12 col-el-12 cart-padding ">
                <div class="col-lg-3 col-el-3 cart-product-part">
                    <span class="cart-product-image"><img src="<?php echo $product->img; ?>"></span>
                </div>
                <div class="col-lg-4 col-el-4 cart-product-part">
                    <div class="col-lg-12 col-el-12">
                        <span class="cart-product-title"><a href="<?php echo Utils::getProductPageLink($product->unique_store_id, $product->store) ; ?>"><?php echo $product->title; ?></a></span>
                    </div>
                    <div class="col-lg-12 col-el-12">
                        <span class="cart-product-details">Store: </span>
                        <span class="cart-product-store"><a href="<?php echo $product->store_link; ?>"><?php echo $product->store; ?></a></span>
                    </div>

                    <?php foreach($product->selected_variant as $variantName => $variantValue) : ?>
                    <div class="col-lg-12 col-el-12">
                        <span class="cart-product-details"><?php echo ucfirst($variantName); ?> : </span>
                        <span class="cart-product-variant"><?php echo $variantValue; ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="col-lg-3 col-el-3 cart-product-part">
                    <span class="cart-product-quantity">
                        <span class="col-lg-5 col-el-5 cart-product-quantity">Quantity:</span>
                        <span class="col-lg-6 col-el-6"><input type="number" min="1" max="<?php echo $product->order_limit; ?>" id="quantity" class="product-quantity form-control" value="<?php echo $product->quantity; ?>"/></span>
                    </span>
                    <span class="display-none cart-product-update btn btn-primary">Update</span>
                </div>
                <div class="col-lg-2 col-el-2 cart-product-part">
                    <span class="cart-product-price"><?php echo $product->getPriceAfterQuantity(); ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-el-12 controls">
                <div class="col-lg-2 col-el-2 pull-right">
                    <span class="cart-product-remove btn btn-primary">Remove item</span>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="col-lg-12 col-el-12 mt-15">
        <div class="cart-totals row col-lg-7 col-el-7 pull-right cart-border cart-padding">

            <div class="col-lg-3 col-el-3"></div>
            <div class="col-lg-9 col-el-9">
                <div class="row">
                    <div class="col-lg-8 col-el-8 text-align-right">Subtotal</div>
                    <div class="col-lg-4 col-el-4 cart-total"><?php echo $_SESSION['cart']->getTotal(); ?></div>
                </div>
                <div class="aggregated-price-modifier">
                    <?php foreach($_SESSION['cart']->getAggregatedPriceModifiers() as $key => $aggregatedPriceModifier) : ?>
                        <div class="row">
                            <div class="col-lg-8 col-el-8 text-align-right"><?php echo $aggregatedPriceModifier->nameAs; ?></div>
                            <div class="col-lg-4 col-el-4"><?php echo $aggregatedPriceModifier->value; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="row">
                    <div class="col-lg-8 col-el-8 text-align-right">Total</div>
                    <div class="col-lg-4 col-el-4 cart-calculated-total"><?php echo $_SESSION['cart']->getCalculatedTotal(); ?></div>
                </div>

                <div class="row pull-right">
                    <span class=" cart-continue-shopping btn btn-primary">Continue shopping</span>
                    <span class=" cart-save btn btn-primary">Save Cart</span>
                    <!-- <span class=" cart-product-procced-checkout btn btn-primary">Proceed to checkout</span>  -->
                </div>
            </div>

        </div>
    </div>
</div>

<div class="<?php echo (count($_SESSION['cart']->get())) ? 'display-none' : ''; ?> no-products row col-lg-12 col-el-12 cart-border cart-padding">
    Your shopping cart is empty, but it doesn't have to be.<br>
    There are lots of great deals and one-of-a-kind items just waiting for you.<br>
    Start shopping, and look for the "Add to cart" button. You can add several items to your cart from different sellers and pay for them all at once.
</div>



