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
<div class="inline">

    <h4>We need some more info:</h4>
    <form id="loginform" action="/checkout/" method="post">
        <div class="form-group form-ltr">
            <?php if(isset($address)) { ?>
                <label>Use different address
                    <input type="checkbox" name="useDifferentAddress" id="useDifferentAddress" />
                </label>
            <?php } ?>
        </div>

        <div class="form-group form-ltr">
            <label>Ship to Store
                <input type="checkbox" name="shipToStore" id="shipToStore" />
            </label>
        </div>


        <div id="addressWrapper" class="<?php echo isset($address) ? 'display-none' : ''; ?>">
            <?php Utils::getTemplate('addressForm'); ?>
        </div>


        <div class="form-group form-ltr">
            <input name="saveAddress" value="true" type="hidden">
            <input type="submit" class="btn btn-primary form-control full-width-button" value="Save Details" />
        </div>
    </form>
</div>
<?php } ?>
