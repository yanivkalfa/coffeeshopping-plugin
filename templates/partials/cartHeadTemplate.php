<div class="row cart-head-wrap">
    <a href="/<?php echo $page; ?>">
        <span class="cart-head-currency"><?php echo get_option('selected_currency', '&#8362;'); ?></span>
        <span class="cart-head-total"><?php echo $total; ?></span>
        <span class="cart-head-item-count"><?php echo $productCount; ?></span>
    </a>
</div>