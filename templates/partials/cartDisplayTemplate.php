<?php
$shiptodoorstepimage = plugins_url( '../../css/images/CarrierShipment.png', __FILE__ );
$cartPage = get_permalink(get_option("cs_cart_p_id"));
if (!$cartPage){Utils::adminPreECHO(__("Can't get cart page id", 'coffee-shopping' ), __("cartDisplayTemplate.php ERROR:: ", 'coffee-shopping' ));}
?>

<div class="cartcontentblock">
    <div class="cartdetails inline">
        <div class="cartordertitle">
        <h4>
            <div class="inline header"><?php _e("Order ID: #", 'coffee-shopping' ); ?></div><div class="inline"><?php echo $cart->ID;?></div>
            <?php echo ($cart->status=="saved") ? '<div class="inline titleincart"> <a href="'.$cartPage.'">('.__("In cart", 'coffee-shopping' ).')</a> </div>' : '';?>
        </h4>
        </div>

        <div class="cartorderdate">
            <div class="inline header">
                <?php _e("Created at:", 'coffee-shopping' ); ?>
            </div>
            <div class="inline">
                <?php echo $cart->create_date;?>
            </div>
        </div>

        <div class="cartorderstatus">
            <div>
                <div class="inline header">
                    <?php _e("Order status:", 'coffee-shopping' ); ?>
                </div>
                <div class="inline">
                    <?php echo CSCons::get('cartStatus')[$cart->status]['nameAs'];?>
                </div>
            </div>
            <?php if ($status){?>
            <div class="cartorderstatusicons inline">
                <?php
                $scope = array(
                    'statusArr' => CSCons::get('cartStatus'),
                    //'statusArr' => array_keys(CSCons::get('cartStatus')),
                    'status' => $cart->status,
                    'size' => 2
                );
                Utils::getTemplate('statusIcons', $scope);
                ?>
            </div>
            <?php } ?>
        </div>

        <div class="cartorderdelivery">
            <div class="header"><?php _e("Deliver to:", 'coffee-shopping' ); ?></div>
            <div>
            <?php
            if ($cart->deliver_to=="home" || $cart->deliver_to=="doorStep"){
                $scope = array(
                    'address' => AddressDatabaseHelper::getAddress($cart->address_id)
                );
                Utils::getTemplate('singleAddress', $scope);
            }else{
                $store = StoreDatabaseHelper::getStore($cart->address_id);
                esc_attr_e($store["name"]).' - '.esc_attr_e($store["address"]);
            }
            ?>
            </div>
            <div>
                <?php
                if ($cart->deliver_to=="doorStep"){
                ?>
                    <img src="<?php echo $shiptodoorstepimage;?>" class="inline" width="32px" height="32px;"/><div class="inline"> <?php _e("Shipping to your door step", 'coffee-shopping' ); ?></div>
                <?php
                }
                ?>
            </div>
        </div>

        <div class="cartorderpayment">
            <div class="header"><?php _e("Payment details:", 'coffee-shopping' ); ?></div>
            <?php
            $paymentMethods = CSCons::get("paymentMethods");
            $methodTxt = $paymentMethods[$cart->payment_method]["normal"]["nameAs"];
            ?>
            <div><?php echo $methodTxt.' - '.Utils::getCurrencySymbol("ILS").round($cart->payment_amount, 2);?></div>
        </div>

    </div>
    <div class="cartorderproducts inline">
        <?php
        $products = $cart->get();
        foreach($products as $index => $product){
            $scope = array(
                'product' => $product,
                'itemClass' => ($index==count($products)-1) ? "lastitem": "",
                'actions' => false,
                'status' => $status,
            );
            Utils::getTemplate('cartItems',$scope);
        }
        ?>
    </div>
</div>




