<?php
$cartPage = get_permalink(get_option("cs_cart_p_id"));
if (!$cartPage){Utils::adminPreECHO("Can't get cart page id", "cartDisplayTemplate.php ERROR:: ");}
?>
<div class="cartcontentblock">
    <div class="cartdetails inline">
        <div class="cartordertitle">
        <h4>
            <div class="inline header">Order ID: #</div><div class="inline"><?php echo $cart->ID;?></div>
            <?php echo ($cart->status=="saved") ? '<div class="inline titleincart"> <a href="'.$cartPage.'">(In Cart)</a> </div>' : '';?>
        </h4>
        </div>

        <div class="cartorderdate">
            <div class="inline header">
                Created at:
            </div>
            <div class="inline">
                <?php echo $cart->create_date;?>
            </div>
        </div>

        <div class="cartorderstatus">
            <div>
                <div class="inline header">
                    Order status:
                </div>
                <div class="inline">
                    <?php echo CSCons::get('cartStatus')[$cart->status];?>
                </div>
            </div>
            <?php if ($status){?>
            <div class="cartorderstatusicons inline">
                <?php
                $scope = array(
                    'statusArr' => array_keys(CSCons::get('cartStatus')),
                    'status' => $cart->status,
                    'size' => 2
                );
                Utils::getTemplate('statusIcons', $scope);
                ?>
            </div>
            <?php } ?>
        </div>

        <div class="cartorderdelivery">
            <div class="header">Deliver to:</div>
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

        <div class="cartorderpayment">
            <div class="header">Payment details:</div>
            <div><?php echo $cart->payment_method.' - '.$cart->payment_amount;?></div>
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




