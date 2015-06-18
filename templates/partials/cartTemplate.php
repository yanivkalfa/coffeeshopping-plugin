<!-- all variables comes from Utils::getTemplate -->

<script language="javascript" type="text/javascript">
	// Set some vars.
	$ns.cart = <?php echo json_encode($_SESSION['cart']->getObjectAsArray());?>;
</script>

<div id="cartcontainer" class="<?php echo (count($_SESSION['cart']->get())) ? '' : 'display-none'; ?> cart has-products">

	<?php foreach($_SESSION['cart']->get() as $key => $product){ ?>
		<div class="cartitemsdiv">

			<div class="cart-product" data-product-key="<?php echo htmlentities(json_encode($product)); ?>">

				<div class="cartitemimg inline">
					<a href="<?php echo Utils::getProductPageLink($product->unique_store_id, $product->store) ; ?>"><img src="<?php echo Utils::getPictureBySize($product->store, $product->img, "150wh"); ?>"></a>
				</div>

				<div class="cartitemdetailsdiv inline">
					<div class="cartitemtitle">
						<h4><a href="<?php echo Utils::getProductPageLink($product->unique_store_id, $product->store) ; ?>"><?php echo $product->title; ?></a></h4>
					</div>

					<div class="cartitemvariants">
						<?php foreach($product->selected_variant as $variantName => $variantValue) : ?>
							<div class="col-lg-12 col-el-12">
								<span class="cart-product-details"><?php echo ucfirst($variantName); ?> : </span>
								<span class="cart-product-variant"><?php echo $variantValue; ?></span>
							</div>
						<?php endforeach; ?>
					</div>

					<div class="cartitemdetails inline">

						<div class="cartitemdetailstore">
							<a href="<?php echo $product->store_link;?>" title="View on <?php echo $product->store;?>" target="_blank"><div class="<?php echo Utils::getAPILogoClass($product->store);?>"></div></a>
						</div>


						<div class="cartitemdetailquantity">
							<div class="inline header">
								Quantity:
							</div>
							<div class="inline detail">
								<input type="number" min="1" max="<?php echo $product->order_limit; ?>" id="quantity" class="product-quantity form-control" value="<?php echo $product->quantity; ?>"/>
							</div>
						</div>

						<div class="cartitemdetailquantity">
							<div class="inline detail"></div>
							<div class="inline detail">
								<a class="display-none cart-product-update">[Update]</a>
							</div>

						</div>

						<div class="cartitemdetailquantity">
							<div class="inline header">
								Total price:
							</div>
							<div class="inline detail">
								<span class="cart-product-price"><?php echo $product->getPriceAfterQuantity(); ?></span>
							</div>
							<span class="cart-product-remove btn btn-primary">Remove item</span>
						</div>

					</div>

				</div>


			</div>

		</div>

	<?php } ?>


		<div id="carttotalsdiv">
			<div class="cart-totals">
				<div class="cart-subtotal">
					<div class="inline header">Subtotal</div>
					<div class="inline detail cart-total"><?php echo $_SESSION['cart']->getTotal(); ?></div>
				</div>

				<?php foreach ( $_SESSION['cart']->getAggregatedPriceModifiers() as $key => $aggregatedPriceModifier ) : ?>
					<div class="cart-<?php echo $aggregatedPriceModifier->name; ?>">
						<div class="inline header"><?php echo $aggregatedPriceModifier->nameAs; ?></div>
						<div class="inline detail cart-total"><?php echo $aggregatedPriceModifier->value; ?></div>
					</div>
				<?php endforeach; ?>

				<div class="cart-subtotal">
					<div class="inline header">Total</div>
					<div class="inline detail cart-total"><?php echo $_SESSION['cart']->getCalculatedTotal(); ?></div>
				</div>

				<div class="carttotals-actions">
					<span class="cart-continue-shopping btn btn-primary">Continue shopping</span>
					<span class="cart-save btn btn-primary">Save Cart</span>
					<!-- <span class=" cart-product-procced-checkout btn btn-primary">Proceed to checkout</span>  -->
				</div>
			</div>
		</div>

</div>



<div class="<?php echo (count($_SESSION['cart']->get())) ? 'display-none' : ''; ?> no-products row col-lg-12 col-el-12 cart-border cart-padding">
	Your shopping cart is empty, but it doesn't have to be.<br>
	There are lots of great deals and one-of-a-kind items just waiting for you.<br>
	Start shopping, and look for the "Add to cart" button. You can add several items to your cart from different sellers and pay for them all at once.
</div>



