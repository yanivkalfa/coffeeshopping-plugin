jQuery(document).ready( function(){

    function getProduct(selector){
        var parent, product;
        parent = $(selector).parents('.cart-product');
        if(!parent.length) return false;

        product = parent.data('product-key') || false;
        if(!product || _.isEmpty(product)) return false;

        return product;
    }

    function renderCartSummery(data) {
        if (!data.productCount) {
            $('.no-products').removeClass('display-none');
            $('.has-products').addClass('display-none');
            return;
        }
        $('#cart-total').html(data.total);
        $('#cart-calculated-total').html(data.calculatedTotal);
        $('#aggregated-price-modifier').html('');

        _.forEach(data.aggregatedPriceModifiers, function (aggregatedPriceModifier) {
            $('#aggregated-price-modifier').append(
                $('<div>').attr('class', 'cart-' + aggregatedPriceModifier.name).append(
                    $('<div>').attr('class', 'inline header').html(aggregatedPriceModifier.nameAs),
                    $('<div>').attr('class', 'inline detail').html(aggregatedPriceModifier.value)
                )
            );
        });
    }

    $ns.data.action = 'ajax_handler';

    $('.product-quantity').on('change',  function(){
        var product;
        product = getProduct(this);
        var changed = $ns.Utils.onProductQuantityChange(jQuery(this), product.available_quantity, product.order_limit );

        if(product.quantity !== changed){
            $(this).parents('.cartitemdetails').find('.cart-product-update').removeClass('display-none');
        }

    });

    $('.cart-product-update').on('click',  function(){
        var product, quantity;
        product = getProduct(this);
        quantity =  $(this).parents('.cartitemdetails').find('.product-quantity').val();
        if(!product || _.isNaN(parseInt(quantity))) return false;

        $ns.data.method = 'updateQuantity';
        $ns.data.post = 'product=' + encodeURIComponent(JSON.stringify(product)) + '&quantity=' + quantity + '&extendCartUpdate=true';

        var data = $ns.Utils.getData();
        if(data.success){
            console.log(data);
            $.publish($ns.events.CART_UPDATE, data.msg);
            renderCartSummery(data.msg);
        }
        $(this).addClass('display-none');
    });

    $('.cart-product-remove').on('click',  function(){
        var product = getProduct(this);
        if(!product) return false;

        $ns.data.method = 'removeProduct';
        $ns.data.post = 'product=' + encodeURIComponent(JSON.stringify(product)) + '&extendCartUpdate=true';

        var data = $ns.Utils.getData();
        if(data.success){
            console.log(data);
            $.publish($ns.events.CART_UPDATE, data.msg);
            $(this).parents('.cartitemsdiv').remove();
            $('#cartcontainer .cartitemsdiv:last').addClass("lastitem");
            console.log($('#cartcontainer .cartitemsdiv:last'));
            renderCartSummery(data.msg);
        }
    });

    $('.cart-save').on('click',  function(){
        var id = getProduct(this);

        $ns.data.method = 'addProduct';
        $ns.data.post = 'product=' + encodeURIComponent(JSON.stringify(_.pick(product,['unique_store_id'])));

        var data = $ns.Utils.getData();
        if(data.success){
            console.log(data);
            $.publish($ns.events.CART_UPDATE, data.msg);
            jQuery("#buynowbuttondiv").data("addedtocart", "1");
        }
    });


    /*
    * 'removeProduct' => array('protected' => true, 'req_capabilities' => ['manage_options', 'registered_member']),
     'updateProductQuantity' => array('protected' => true, 'req_capabilities' => ['manage_options', 'registered_member']),
    * */
});

/*
var exchDetails = getProductPricesDetails($ns.exchExtension);
var product = {
    unique_store_id : $ns.productID,
    store : $ns.store,
    img : $ns.productPic,
    title : $ns.productTitle,
    price : exchDetails["itemprice"],
    quantity : jQuery("#orderquantity").val(),
    price_modifiers : [
        {name:'storeCommission', nameAs : 'Store Commission', value : exchDetails["storeprice"]},
        {name:'PayPalFees', nameAs : 'PayPal Fees', value : exchDetails["paypalprice"]},
        {name:'shippingCosts', nameAs : 'Shipping Costs', value : exchDetails["shippingprice"]}
    ],
    selected_var : getCurrentVarSel(),
    selected_var_SKU: $ns.variations[$ns.selectedVariant]["SKU"],
    storelink: $ns.storeLink
};

$ns.data.action = 'ajax_handler';
$ns.data.method = 'addProduct';
$ns.data.post = 'product=' + encodeURIComponent(JSON.stringify(product));

var data = $ns.Utils.getData();
if(data.success){
    console.log(data);
    $.publish($ns.events.CART_UPDATE, data.msg);
    jQuery("#buynowbuttondiv").data("addedtocart", "1");
}
*/