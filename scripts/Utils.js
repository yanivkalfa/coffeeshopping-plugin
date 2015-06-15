
(function($){

    function Utils (){}

    Utils.prototype.getData = function(){
        var data = false;
        $.ajax({
            url: $ns.ajaxURL,
            type: "post",
            async: false,
            dataType:'json',
            data:$ns.data,
            success: function (resp, status) {
                if(status === 'success'){
                    return data =  resp;
                }

                console.log('Error happened with the request: ', resp);
            },
            error : function (resp) {
                console.log('Error happened with the request: ', resp);
            }
        });
        return data;
    };

    Utils.prototype.onProductQuantityChange = function(selector, availableQuantity, orderLimit){

        if (selector.val() < 1){
            selector.effect("highlight", 1500);
            selector.val(1);
            return false;
        }

        var limitsArr = [], productLimit = 0;
        availableQuantity = _.isNaN(parseInt(availableQuantity));
        if(!availableQuantity) {
            limitsArr.push(availableQuantity);
        }

        orderLimit = _.isNaN(parseInt(orderLimit));
        if(!orderLimit) {
            limitsArr.push(orderLimit);
        }

        if(!limitsArr.length) {
            productLimit = Math.min.apply(this,limitsArr);
        }

        if(productLimit > 0 && parseInt(selector.val()) > productLimit){
            selector.effect("highlight", 1500);
            selector.val(productLimit);
        }
        return true;
    };

    $ns.Utils = new Utils();
})(jQuery);
