<?php
$shiptohome = plugins_url( '../../css/images/shiptohome.png', __FILE__ );
$shiptostore = plugins_url( '../../css/images/shiptostore.png', __FILE__ );
?>

<?php /*if (isset($mapsAPIKey) && !empty($mapsAPIKey)){?>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?language=he&region=IL&key=<?php echo $mapsAPIKey;?>"></script>
    <script type="text/javascript">
        $ns.store = <?php echo json_encode($store);?>;
    </script>
<?php }*/?>

<?php if(isset($errorMessages)) {?>
    <script language="javascript" type="text/javascript">
        // Set some vars.
        $ns.errorMessages = <?php echo json_encode($errorMessages);?>;
    </script>
<?php } ?>


<?php if(isset($orderId)) { ?>
    <div>You've successfully save your cart, order ID: <?php echo $orderId; ?> </div>
    <div>You can visit your account to review your card, order status and general information <a href="<?php echo $myAccountPage;?>">My Account</a></div>
    <div>To complete the order, please, refer to one of our stores.</div>
    <br />
    <?php
    // create scope
    $scope = array(
        'store' => $store,
    );
    // loading checkout template
    Utils::getTemplate('storeLocator', $scope);
    ?>

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
                        <div id="shipToStore" class="inline shipToStore">
                            <img src="<?php echo $shiptostore;?>" alt="Ship to store"/>
                        </div>
                        <input type="radio" name="address_id" id="shipToStoreInput" value="shipToStore" class="display-none"/>
                    </div>
                </div>

                <div id="shipToHomeTab" class="display-none">

                    <div class="form-group">
                        <div id="shipToHome" class="inline shipToHome">
                            <img src="<?php echo $shiptohome;?>" alt="Ship to home"/>
                        </div>
                        <div id="savedAddressTab">
                            <?php if(isset($addresses) && !empty($addresses)) { ?>
                                <div id="form-alert" class="display-none">Register successfully</div>
                                <h4> Shipping to:</h4>
                                <?php foreach($addresses as $address){ ?>
                                    <div class="single-address saved-address">
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
                            <?php } ?>
                            <div id="newAddress" class="single-address <?php echo isset($address) ? '' : 'display-none'; ?>">
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
                        <div id="newAddressTab" class="<?php echo isset($address) ? 'display-none' : ''; ?>">
                            <?php Utils::getTemplate('addressForm'); ?>
                        </div>

                    </div>


                </div>
            </div>

            <div id="submitCheckout" class="form-group display-none">
                <input name="saveAddress" value="true" type="hidden">
                <input id="lat-location" name="lat" value="" type="hidden">
                <input id="lng-location" name="lng" value="" type="hidden">
                <input type="submit" class="btn btn-primary form-control full-width-button" value="Checkout" />
            </div>
        </form>
    </div>
<?php } ?>
