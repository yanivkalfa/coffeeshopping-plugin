<?php if(isset($errorMessages)) {?>
<script language="javascript" type="text/javascript">
    // Set some vars.
    $ns.errorMessages = <?php echo json_encode($errorMessages);?>;
</script>
<?php } ?>


<?php if(isset($orderId)) { ?>
    <div>You've successfully save your cart, order ID: <?php echo $orderId; ?> </div>
    <div>You can visit your account to review your card, order status and general information <a href="/myaccount/">My Account</a></div>
<?php }else { ?>
<div>

    <form id="addressForm" action="/checkout/" method="post">
        <h4>Please select where to ship to:</h4><div id="reselect">Reselect shipping</div>
        <div id="shippingSelection">
            <div id="shipToHome" class="inline">Ship to Home</div>
            <div id="shipToStore" class="inline">Ship to Store</div>
        </div>


        <div id="shippingContents" class="display-none">

            <div id="shipToStoreTab" class="display-none">
                <div class="form-group form-ltr">
                    <label>Ship to Store
                        <input type="radio" name="address_id" id="shipToStore" value="shipToStore"/>
                    </label>
                </div>
            </div>

            <div id="shipToHomeTab" class="display-none">

                <div class="form-group form-ltr">
                    <h4> Shipping to:</h4>

                    <div class="form-group form-ltr">
                        <div id="newAddress">
                            <div id="newAddressTitle" class="inline">Create New Address</div>
                            <input type="radio" class="display-none" name="address_id" id="newAddressField" value="newAddress"/>
                        </div>
                    </div>
                    <?php if(isset($addresses) && !empty($addresses)) { ?>
                        <div id="savedAddressTab">
                        <?php foreach($addresses as $address){ ?>
                            <input type="radio" name="address_id" value="<?php echo $address['ID'];?>" />
                            <div class="single-address">
                                <?php foreach($address as $property => $value){ ?>
                                    <div>
                                        <div class="inline"><?php echo $property; ?></div>
                                        <div class="inline"><?php echo $value; ?></div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        </div>
                        <br>
                    <?php } ?>
                </div>

                <div id="newAddressTab" class="<?php echo isset($address) ? 'display-none' : ''; ?>">
                    <?php Utils::getTemplate('addressForm'); ?>
                </div>
            </div>
        </div>

        <div class="form-group form-ltr">
            <input name="saveAddress" value="true" type="hidden">
            <input type="submit" class="btn btn-primary form-control full-width-button" value="Save Details" />
        </div>
    </form>
</div>
<?php } ?>
