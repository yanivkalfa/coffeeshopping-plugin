<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/25/2015
 * Time: 5:10 PM
 */
?>
<div id="checkoutDoneDiv">
    <div id="checkoutheader">
        <div><?php _e("You've successfully save your cart, order ID:", 'coffee-shopping' ); ?> <?php echo $orderId; ?> </div>
        <div><?php _e("You can visit your account to review your card, order status and general information", 'coffee-shopping' ); ?> <a href="<?php echo $myAccountPage;?>"><?php _e("My Account", 'coffee-shopping' ); ?></a></div>
        <div><?php _e("To complete the order, please, refer to one of our stores.", 'coffee-shopping' ); ?></div>
    </div>

    <div id="checkoutstorelocator">
        <?php
        // loading checkout template
        Utils::getTemplate('storeLocator');
        ?>
    </div>


</div>