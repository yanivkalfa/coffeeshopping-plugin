<?php
//$delivered
/*
foreach($unfinishedCarts as $cart){
    Utils::preEcho($cart, 'cart: ');
    Utils::preEcho($cart->get(), 'products: ');
}
*/
?>

<script language="javascript" type="text/javascript">
    // Set some vars.
    $ns.errorMessages = <?php echo json_encode($errorMessages);?>;
    $ns.addressUrl = <?php echo json_encode( BASE_URL.'scripts/partials/addressForm.js');?>;
</script>

<div id="myaccountpagecontainer">


    <div id="tabs-selector">
        <div id="profile-selector" class="tabselector active" data-assoc="profile"><i class="fa fa-user"></i> Profile</div>
        <div id="addresses-selector" class="tabselector" data-assoc="addresses"><i class="fa fa-building-o"></i> Addresses</div>
        <div id="orders-selector" class="tabselector" data-assoc="orders"><i class="fa fa-shopping-cart"></i> Orders</div>
        <div id="history-selector" class="tabselector" data-assoc="history"><i class="fa fa-history"></i> History</div>
    </div>



    <div id="profile-tab-div" class="tabdiv">
        <form id="profileForm">
            <?php Utils::getTemplate('profileForm', array('user' => $user)); ?>

            <input type="hidden" name="action" value="update">
            <input type="hidden" name="user_id" id="user_id" value="1">
            <div id="profileForm-alert" class="display-none"></div>
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Update Profile">
        </form>
    </div>



    <div id="addresses-tab-div" class="tabdiv">

        <div id="newAddressTab">
            <form id="addressForm">
                <?php Utils::getTemplate('addressForm'); ?>
                <div id="addressForm-alert" class="display-none"></div>
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Add address!">
            </form>
        </div>

        <div id="savedAddressTab">
            <?php
            $scope = array(
                'addresses' => isset($addresses) ? $addresses : array(),
                'header' => "Your addresses:",
                'selectable' => false
            );
            Utils::getTemplate('shippingAddresses', $scope);
            ?>
        </div>

    </div>



    <div id="orders-tab-div" class="tabdiv">

    </div>



    <div id="history-tab-div" class="tabdiv">

    </div>


</div>