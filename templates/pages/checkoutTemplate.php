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

    <?php
    $scope = array(
        "orderId" => $orderId,
        "myAccountPage" => $myAccountPage
    );
    // loading checkout template
    Utils::getTemplate('checkoutDone', $scope);
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
                            <?php
                                $scope = array(
                                    'addresses' => $addresses,
                                    'header' => "Shipping to:",
                                    'actions' => false
                                );
                                Utils::getTemplate('shippingAddresses', $scope);
                            ?>
                            <div id="newAddress" class="single-address <?php echo isset($addresses) ? '' : 'display-none'; ?>">
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
                        <div id="newAddressTab" class="<?php echo isset($addresses) ? 'display-none' : ''; ?>">
                            <?php Utils::getTemplate('addressForm'); ?>
                        </div>

                    </div>


                </div>
            </div>

            <div id="submitCheckout" class="form-group display-none">
                <input name="saveAddress" value="true" type="hidden">
                <input type="submit" class="btn btn-primary form-control full-width-button" value="Checkout" />
            </div>
        </form>
    </div>
<?php } ?>
