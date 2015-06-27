<div id="myaccountpagecontainer">


    <div id="tabs-selector">
        <div id="profile-selector" class="tabselector active" data-assoc="profile">Profile</div>
        <div id="addresses-selector" class="tabselector" data-assoc="addresses">Addresses</div>
        <div id="orders-selector" class="tabselector" data-assoc="orders">Orders</div>
        <div id="history-selector" class="tabselector" data-assoc="history">History</div>
    </div>



    <div id="profile-tab-div" class="tabdiv">
        <?php Utils::getTemplate('profileForm', $scope); ?>

        <input type="hidden" name="action" value="update">
        <input type="hidden" name="user_id" id="user_id" value="1">
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Update Profile">
    </div>



    <div id="addresses-tab-div" class="tabdiv">

        <div id="newAddressTab">
            <?php Utils::getTemplate('addressForm'); ?>
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Add address!">
        </div>

        <div id="savedAddressTab">
            <?php
            $scope = array(
                'addresses' => $addresses,
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