<?php
$shiptohome = plugins_url( '../../css/images/shiptohome.png', __FILE__ );
$shiptostore = plugins_url( '../../css/images/shiptostore.png', __FILE__ );
?>
<?php if(isset($errorMessages)) {?>
<script language="javascript" type="text/javascript">
    // Set some vars.
    $ns.errorMessages = <?php echo json_encode($errorMessages);?>;
</script>
<?php } ?>


<?php if(isset($orderId)) { ?>
    <div>You've successfully save your cart, order ID: <?php echo $orderId; ?> </div>
    <div>You can visit your account to review your card, order status and general information <a href="<?php echo $myAccountPage;?>">My Account</a></div>
<?php }else { ?>
<div>

    <form id="addressForm" action="/checkout/" method="post">
        <h4>Please select where to ship to:</h4>
        <div id="reselect" class="display-none">Shipping options</div>
        <div id="shippingSelection" align="center">
            <div id="shipToHome" class="inline shipToHome"><img src="<?php echo $shiptohome;?>" alt="Ship to home"/></div>
            <div id="shipToStore" class="inline shipToStore"><img src="<?php echo $shiptostore;?>" alt="Ship to store"/></div>
        </div>


        <div id="shippingContents" class="display-none">

            <div id="shipToStoreTab" class="display-none" align="center">
                <div class="form-group">
                    <div id="shipToStore" class="inline shipToStore"><img src="<?php echo $shiptostore;?>" alt="Ship to store"/></div>
                    <input type="radio" name="address_id" id="shipToStore" value="shipToStore" class="display-none"/>
                </div>
            </div>

            <div id="shipToHomeTab" class="display-none">

                <div class="form-group">
                    <div id="shipToHome" class="inline shipToHome"><img src="<?php echo $shiptohome;?>" alt="Ship to home"/></div>
                    <?php if(isset($addresses) && !empty($addresses)) { ?>
                        <div id="savedAddressTab">
                            <h4> Shipping to:</h4>
                            <?php foreach($addresses as $address){ ?>
                                <div class="single-address">
                                    <div class="inline addressradio">
                                        <input type="radio" name="address_id" value="<?php echo $address['ID'];?>" id="addressradio_<?php echo $address['ID'];?>" />
                                    </div>
                                    <div class="inline addressdets">
                                        <label for="addressradio_<?php echo $address['ID'];?>">
                                            <div class="addressName">
                                                <div class="inline">
                                                    <b><?php echo $address['full_name'];?></b>
                                                </div>
                                                <div class="inline">
                                                    - <i>(<?php echo $address['phone_number'];?>)</i>
                                                </div>
                                            </div>
                                            <div class="addressDetails">
                                                <?php echo $address['street']." ".$address['house']."/".$address['apt'].", ".$address['city'].", ".$address['postcode'].".";?>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            <?php } ?>

                                <div id="newAddress" class="single-address">
                                    <div class="inline addressradio">
                                        <input type="radio" name="address_id" id="newAddressField" value="newAddress"/>
                                    </div>
                                    <label for="newAddressField">
                                        <div class="inline addressdets">
                                            <div id="newAddressTitle" class="inline">Create New Address</div>
                                        </div>
                                    </label>

                                </div>
                        </div>
                    <?php } ?>
                    <div id="newAddressTab" class="<?php echo isset($address) ? 'display-none' : ''; ?>">
                        <?php Utils::getTemplate('addressForm'); ?>
                    </div>

                </div>


            </div>
        </div>

        <div id="submitCheckout" class="form-group display-none">
            <input name="saveAddress" value="true" type="hidden">
            <input type="submit" class="btn btn-primary form-control full-width-button" value="Save Details" />
        </div>
    </form>
</div>
<?php } ?>
