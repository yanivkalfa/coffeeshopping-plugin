<div class="ui-widget cartwidgetcontainer">
	<div class="cartimage" title="<?php _e("View your cart!", 'coffee-shopping' ); ?>">
		<a href="<?php echo $page; ?>"><span class="cart-head-wrap"></span></a>
	</div>
	<div class="cartcount" title="<?php _e("View your cart!", 'coffee-shopping' ); ?>">
		<a href="<?php echo $page; ?>"><span class="cart-head-item-count"><?php echo $productCount; ?></span></a>
	</div>
</div>


<!--
        <span class="cart-head-currency"><?php echo get_option('selected_currency', '&#8362;'); ?></span>
        <span class="cart-head-total"><?php echo $total; ?></span>
        -->