
$(document).ready(function(){
    $.subscribe($ns.events.CART_UPDATE, function(e, msg){
        if(!msg) return false;
        $('.cart-head-total').html(msg.total);
        $('.cart-head-item-count').html(msg.productCount);
    });
});