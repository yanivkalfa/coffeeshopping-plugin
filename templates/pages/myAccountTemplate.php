<div id="myaccountpagecontainer">
    <div id="tabs-selector">
        <a href="#profile"><div id="profile-selector" class="tabselector active" data-assoc="#profile"><i class="fa fa-user"></i> Profile</div></a>
        <a href="#addresses"><div id="addresses-selector" class="tabselector" data-assoc="#addresses"><i class="fa fa-building-o"></i> Addresses</div></a>
        <a href="#orders"><div id="orders-selector" class="tabselector" data-assoc="#orders"><i class="fa fa-shopping-cart"></i> Orders</div></a>
        <a href="#history"><div id="history-selector" class="tabselector" data-assoc="#history"><i class="fa fa-history"></i> History</div></a>
    </div>



    <div id="profile-tab-div" class="tabdiv">
        <?php Utils::getTemplate('profileForm', array('user' => $user)); ?>

        <input type="hidden" name="action" value="update">
        <input type="hidden" name="user_id" id="user_id" value="1">
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Update Profile">
    </div>



    <div id="addresses-tab-div" class="tabdiv">

        <div id="newAddressTab">
            <?php Utils::getTemplate('addressForm'); ?>
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Add address">
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
        <?php
        if (count($unfinishedCarts)>0) {
            foreach ($unfinishedCarts as $cart) {
                $scope = array(
                    'cart' => $cart,
                    'status' => true,
                );
                Utils::getTemplate('cartDisplay', $scope);
            }
        }else{
            ?>

            <div>
                <div> You don't have any orders in your account yet! </div>
                <div> Stop wasting time, browse our site and make some orders!</div>
            </div>

            <?php
        }
        ?>
    </div>



    <div id="history-tab-div" class="tabdiv">
        <?php
        if (count($delivered)>0) {
            foreach($delivered as $cart){
                $scope = array(
                    'cart' => $cart,
                    'status' => false,
                );
                Utils::getTemplate('cartDisplay', $scope);
            }
        }else{
            ?>

            <div>
                <div> You don't have any order history yet! </div>
                <div> Once an order have been delivered it will be moved here.</div>
            </div>

        <?php
        }
        ?>
    </div>


</div>