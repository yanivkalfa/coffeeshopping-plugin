
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

    $ns.Utils = new Utils();
})(jQuery);

