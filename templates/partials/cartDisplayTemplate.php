<div class="cartcontentblock">
    <div class="cartdetails inline">
        <h4>Cart Order: #<?php echo $cart->ID;?></h4>
        <div class="cartdate">
            <?php echo $cart->create_date;?>
        </div>

        <div class="cartdelivery">
            <div>Deliver to:</div>
            <div>
            <?php
            if ($cart->deliver_to=="home"){
                $scope = array(
                    'address' => CartDatabaseHelper::getCartAddress($cart->address_id)
                );
                Utils::getTemplate('singleAddress', $scope);
            }else{
                $store = storeHelper::getStore($cart->address_id);
                esc_attr_e($aStore["name"]).' - '.esc_attr_e($aStore["address"]);
            }
            ?>
            </div>
        </div>

        <div class="cartpayment">
            <div>Payment details:</div>
            <div><?php echo $cart->payment_method.' - '.$cart->payment_amount;?></div>
        </div>

        <div class="cartstatus">
            <div>
                <div class="inline">
                    <i class="fa fa-check-square-o fa-2x"></i> &nbsp;
                    <i class="fa fa-ils fa-2x"></i> &nbsp;
                    <i class="fa fa-truck fa-2x"></i> &nbsp;
                    <i class="fa fa-cubes fa-2x"></i> &nbsp;
                    <i class="fa fa-thumbs-up fa-2x"></i> &nbsp;
                </div>
            </div>
        </div>


    </div>
    <div class="cartproducts inline">
        <?php
        $products = $cart->get();
        foreach($products as $index => $product){
            $scope = array(
                'product' => $product,
                'itemClass' => ($index==count($products)-1) ? "lastitem": "",
                'actions' => false,
                'status' => true
            );
            Utils::getTemplate('cartItems',$scope);
        }
        ?>
    </div>
</div>




