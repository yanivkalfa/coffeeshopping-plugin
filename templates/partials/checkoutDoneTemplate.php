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
        <div align="center">
            <div class="inCartImage"></div>
        </div>
        <div><?php _e("You've successfully saved your cart, your order ID:", 'coffee-shopping' ); ?> <?php echo $orderId; ?> </div>
        <div><?php _e("You can visit your account to review your profile, orders status and general information", 'coffee-shopping' ); ?></div>
        <div><a class="linktomyaccount" href="<?php echo $myAccountPage;?>"> <i class="fa fa-user"></i> <?php _e("My Account", 'coffee-shopping' ); ?></a></div>
        <div><?php _e("To complete the order, please, refer to one of our stores.", 'coffee-shopping' ); ?></div>
        <div id="storelocatorhand"><a href="#storelocatorhand"><i class="fa fa-hand-o-down fa-4x"></i></a></div>
    </div>

    <div id="checkoutstorelocator">
        <?php
        // loading checkout template
        Utils::getTemplate('storeLocator');
        ?>
    </div>


</div>