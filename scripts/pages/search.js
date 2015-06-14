// shlomer this is all the product details we need.
// right now i added only 2 methods for manipulating cart:
// addProduct and saveCart

//To update parts of the sites we dont have direct access or several thing can be effected by one thing like:
// say you add an item or increase the quantity on an item, or remove an item (the cart total will change) and this should be
// reflected both in the top cart place and say view cart page.

// we are using pub/sub system where certain part of the site subscribes to a channel or event. and another part of the site publish to that channel.
// in out case the cart header subscribes to cart update event. and addProduct/saveCart publish to that channel.

$(document).ready(function(){
    $('#addProduct').click(function(){

        // shlomer this is all the product details we need.
        var product = {
            unique_store_id : "1",
            store : 'eBay',
            img : 'whatEverMotherFUcker.jpg',
            title : 'A Product',
            price : 31,
            quantity : 2,
            price_modifiers : [
                {name:'storeCommission', nameAs : 'Store Commission', value : 5},
                {name:'PayPalFees', nameAs : 'PayPal Fees', value : 17},
                {name:'shippingCosts', nameAs : 'Shipping Costs', value : 2}
            ]
        };

        $ns.data.action = 'ajax_handler';
        $ns.data.method = 'addProduct';
        $ns.data.post = 'product=' + encodeURIComponent(JSON.stringify(product));

        var data = $ns.Utils.getData();
        if(data.success){
            console.log(data);
            $.publish($ns.events.CART_UPDATE, data.msg);
        }
    });

    $('#saveCart').click(function(){

        $ns.data.action = 'ajax_handler';
        $ns.data.method = 'saveCart';

        var data = $ns.Utils.getData();
        if(data.success){
            console.log(data);
            $.publish($ns.events.CART_UPDATE, data.msg);
        }
    });

});
