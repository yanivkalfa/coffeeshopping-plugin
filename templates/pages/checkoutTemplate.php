
<?php if(isset($orderId)) { ?>
    <div>You've successfully save your cart, order ID: <?php echo $orderId; ?> </div>
    <div>You can visit your account to review your card, order status and general information <a href="/myaccount/">My Account</a></div>
<?php }else { ?>
<div class="inline">

    <h4>We need some more info:</h4>
<?php Utils::getTemplate('addressForm'); ?>
</div>
<?php } ?>
