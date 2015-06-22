jQuery(document).ready( function(){



    function reset(){
        $('#shippingSelection').removeClass('display-none');

        $('#shippingContents').addClass('display-none');
        $('#shipToHomeTab').addClass('display-none');
        $('#shipToStoreTab').addClass('display-none');

        $('#newAddress').find('#newAddressTitle').html('Create New Address');
        $('#reselect').data('is-new', null);

        $('#savedAddressTab').removeClass('display-none');
        $('#newAddressTab').addClass('display-none');
        $('#addressForm')[0].reset();
    }

    reset();

    $('#reselect').click(reset);

    $('#shipToHome').click(function(){
        $('#shippingSelection').addClass('display-none');

        $('#shippingContents').removeClass('display-none');
        $('#shipToHomeTab').removeClass('display-none');
    });

    $('#shipToStore').click(function(){
        $('#shippingSelection').addClass('display-none');
        $('#shippingContents').removeClass('display-none');
        $('#shipToStoreTab').removeClass('display-none');
    });


    $('#newAddress').click(function(){
        if($(this).data('is-new')){
            $('#savedAddressTab').removeClass('display-none');
            $('#newAddressTab').addClass('display-none');
            $(this).find('#newAddressTitle').html('Create New Address');
            $(this).find('#newAddressField').prop("checked", false);
            $(this).data('is-new', false);
        }else{
            $('#savedAddressTab').addClass('display-none');
            $('#newAddressTab').removeClass('display-none');
            $(this).find('#newAddressTitle').html('Select From Saved Addresses');
            $(this).find('#newAddressField').prop("checked", true);
            $(this).data('is-new', true);
        }
    });

});