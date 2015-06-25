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
        <div>You've successfully save your cart, order ID: <?php echo $orderId; ?> </div>
        <div>You can visit your account to review your card, order status and general information <a href="<?php echo $myAccountPage;?>">My Account</a></div>
        <div>To complete the order, please, refer to one of our stores.</div>
    </div>

    <div id="checkoutstorelocator">
        <?php
        // loading checkout template
        Utils::getTemplate('storeLocator');
        ?>
    </div>


</div>