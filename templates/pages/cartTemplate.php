<!-- all variables comes from Utils::getTemplate -->

<script language="javascript" type="text/javascript">
    // Set some vars.
    $ns.cart = <?php echo json_encode($_SESSION['cart']->getObjectAsArray());?>;
</script>

<div id="cartcontainer" class="<?php echo (count($_SESSION['cart']->get())) ? '' : 'display-none'; ?> cart has-products">

    <?php
        $cart = $_SESSION['cart']->get();
        foreach($cart as $index => $product){

            $scope = array(
                'itemClass' => ($index==count($cart)-1) ? "lastitem": "",
                'product'   => $product,
                'actions'   => true

            );
            Utils::getTemplate('cartItems', $scope);
        }
    ?>


    <div id="carttotalsdiv">
        <div class="cart-totals">
            <div class="cart-subtotal">
                <div class="inline header">Subtotal:</div>
                <div id="cart-total" class="inline detail"><?php echo $_SESSION['cart']->getTotal(); ?></div>
            </div>

            <div id="aggregated-price-modifier">
                <?php foreach ( $_SESSION['cart']->getAggregatedPriceModifiers() as $key => $aggregatedPriceModifier ){ ?>
                    <div class="cart-<?php echo $aggregatedPriceModifier->name; ?>">
                        <div class="inline header"><?php echo $aggregatedPriceModifier->nameAs; ?></div>
                        <div class="inline detail cart-total"><?php echo $aggregatedPriceModifier->value; ?></div>
                    </div>
                <?php } ?>
            </div>

            <div class="cart-subtotal">
                <div class="inline header">Total:</div>
                <div id="cart-calculated-total" class="inline detail"><?php echo $_SESSION['cart']->getCalculatedTotal(); ?></div>
            </div>

            <div class="carttotals-actions">
                <span class="cart-continue-shopping btn btn-primary">Continue shopping</span>
                <a href="<?php echo $checkoutPage;?>" class="cart-save btn btn-primary">Checkout</a>
                <!-- <span class=" cart-product-procced-checkout btn btn-primary">Proceed to checkout</span>  -->
            </div>
        </div>
    </div>

</div>

<div class="<?php echo (count($_SESSION['cart']->get())) ? 'display-none' : ''; ?> no-products cart-border cart-padding">
    <?php Utils::getTemplate('emptyCartError'); ?>
</div>

<div class="clearFix"></div>



