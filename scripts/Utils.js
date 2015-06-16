
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
        var limitsArr = [], productLimit = 0, inputVal, prevVal;

        inputVal = parseInt(selector.val());
        inputVal = _.isNaN(inputVal) ? 1 : inputVal ;
        if (inputVal < 1){
            selector.stop( true, true).effect("highlight", 1500);
            selector.val(1);
            return false;
        }

        availableQuantity = parseInt(availableQuantity);
        if(!_.isNaN(availableQuantity) && availableQuantity !== 0) {
            limitsArr.push(availableQuantity);
        }

        orderLimit = parseInt(orderLimit);
        if(!_.isNaN(orderLimit) && orderLimit !== 0) {
            limitsArr.push(orderLimit);
        }

        if(limitsArr.length) {
            productLimit = Math.min.apply(Math,limitsArr);
        }

        if(productLimit > 0 && inputVal > productLimit){
            selector.stop( true, true).effect("highlight", 1500);
            selector.val(productLimit);
            return productLimit;// && productLimit > 1);
        }
        return inputVal;
    };

    $ns.Utils = new Utils();
})(jQuery);
