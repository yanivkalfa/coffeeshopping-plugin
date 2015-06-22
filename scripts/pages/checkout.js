jQuery(document).ready( function(){



    function reset(){
        $('#submitCheckout').addClass('display-none');
        $('#reselect').addClass('display-none');
        $('#shippingSelection').removeClass('display-none');

        $('#shippingContents').addClass('display-none');
        $('#shipToHomeTab').addClass('display-none');
        $('#shipToStoreTab').addClass('display-none');

        $('#newAddress').find('#newAddressTitle').html('Create New Address');

        $('#savedAddressTab').removeClass('display-none');
        $('#newAddressTab').addClass('display-none');
        $('#addressForm')[0].reset();
    }

    reset();

    $('#reselect').click(reset);

    $('.shipToHome').click(function(){
        $('#reselect').removeClass('display-none');
        $('#shippingSelection').addClass('display-none');
        $('#shipToHomeTab').removeClass('display-none');
        // Internal toggler.
        if ($('#newAddressTab').is(":visible")){
            $('#newAddressTab').addClass('display-none');
            $('#savedAddressTab').removeClass('display-none');
        }else{
            $('#shippingContents').removeClass('display-none');
        }

        $('#submitCheckout').removeClass('display-none');
    });

    $('.shipToStore').click(function(){
        $('#reselect').removeClass('display-none');
        $('#shippingSelection').addClass('display-none');
        $('#shippingContents').removeClass('display-none');
        $('#shipToStoreTab').removeClass('display-none');
        $('#submitCheckout').removeClass('display-none');
    });


    $('#newAddressField').on("click", function(){
        if($('#newAddressField').is(":checked")){
            $('#savedAddressTab').addClass('display-none');
            $('#newAddressTab').removeClass('display-none');
        }else{
            $('#savedAddressTab').removeClass('display-none');
            $('#newAddressTab').addClass('display-none');
        }
    });

});