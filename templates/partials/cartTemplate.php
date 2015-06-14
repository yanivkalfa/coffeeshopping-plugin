<!-- all variables comes from Utils::getTemplate -->

<script language="javascript" type="text/javascript">
    // Set some vars.
    $ns.cart = <?php echo json_encode($_SESSION['cart']->getObjectAsArray());?>;
</script>

<div class="cart row col-lg-12 col-el-12">
    <div class="controls col-lg-12 col-el-12">
        <span class="col-lg-2 col-el-2">Update Cart</span>
        <span class="col-lg-2 col-el-2">Empty Cart</span>
        <span class="col-lg-2 col-el-2">button tree</span>
        <span class="col-lg-2 col-el-2">button four</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Image</th>
                <th>Store</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total Price</th>
                <th>Action</th>
            </tr>

        </thead>

        <tbody>
            <?php foreach($_SESSION['cart']->get() as $key => $product) : ?>
            <tr>
                <td><?php echo $product->title; ?></td>
                <td><img src="<?php echo $product->img; ?>">?</td>
                <td><?php echo $product->store; ?></td>
                <td><?php echo $product->quantity; ?></td>
                <td><?php echo $product->getPrice(); ?></td>
                <td><?php echo $product->getPriceAfterQuantity(); ?></td>
                <td>
                    <span class="col-lg-2 col-el-2" data-product-key="{unique_store_id: '<?php echo $product->unique_store_id; ?>'}> ">X</span>
                </td>
            </tr>
            <? endforeach; ?>
        </tbody>
    </table>

</div>