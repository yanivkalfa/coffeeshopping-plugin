jQuery(document).ready( function(){
    var form, formAlert, hasSavedAddress;
    var submitCheckout, reselect, shippingSelection, shippingContents, shipToHomeTab, shipToStoreTab,
        savedAddressTab, newAddressTab, shipToHome, newAddressField, shipToStore, shipToStoreInput;

    form = $('#addressForm');
    formAlert = $('#form-alert');
    submitCheckout = $('#submitCheckout');
    reselect = $('#reselect');
    shippingSelection = $('#shippingSelection');
    shippingContents = $('#shippingContents');
    shipToHomeTab = $('#shipToHomeTab');
    shipToStoreTab = $('#shipToStoreTab');
    savedAddressTab = $('#savedAddressTab');
    newAddressTab = $('#newAddressTab');
    shipToHome = $('.shipToHome');
    newAddressField = $('#newAddressField');
    shipToStore = $('.shipToStore');
    shipToStoreInput = $('#shipToStoreInput');

    hasSavedAddress = Boolean($('.saved-address').length);
    $ns.errorMessages = $ns.errorMessages || {};

    if(form.length) {
        form[0].reset();

        form.validate({
            onkeyup : function(element){
                $(element).valid()
            },
            rules: {
                address_id: {
                    required: true
                }
            },
            messages: {
                address_id: {
                    required: $ns.errorMessages.address_id || 'You must select a shipping method'
                }
            },
            submitHandler: function (form) {
                form.submit();
            },
            invalidHandler: function (event, validator) {
                var addressIdNeeded, errorMsg;

                _.forEach(validator.errorList, function (err) {
                    if ($(err.element).attr('name') === 'address_id') {
                        addressIdNeeded = true;
                    }
                });

                if (addressIdNeeded) {
                    errorMsg = '<span>' + validator.errorMap.address_id + '</span>';

                    formAlert.html(errorMsg)
                        .removeClass('display-none alert-error alert-warning alert-success').addClass('alert-error');
                } else {
                    formAlert.html('')
                        .removeClass('display-none alert-error alert-warning alert-success').addClass('display-none');
                }
            },
            showErrors: function () {
                this.defaultShowErrors();
                $("#address_id-error").hide();
            }

        });

        if(!hasSavedAddress){
            addOrRemoveRules('add');
        }
    }
    function addOrRemoveRules(method){
        //form.valid();
        for(var fieldName in $ns.addressRules){
            if(!$ns.addressRules.hasOwnProperty(fieldName)) continue;
            var input = form.find('[name="'+ fieldName +'"]');
            input.rules( method, $ns.addressRules[fieldName]);
        }
    }

    function reset(){
        submitCheckout.addClass('display-none');
        reselect.addClass('display-none');
        shippingSelection.removeClass('display-none');

        shippingContents.addClass('display-none');
        shipToHomeTab.addClass('display-none');
        shipToStoreTab.addClass('display-none');

        savedAddressTab.removeClass('display-none');
        newAddressTab.addClass('display-none');

        if(form.length){
            form[0].reset();
        }
    }
    reselect.click(reset);

    shipToHome.click(function(){

        reselect.removeClass('display-none');
        shippingSelection.addClass('display-none');
        shipToHomeTab.removeClass('display-none');
        shippingContents.removeClass('display-none');
        newAddressTab.addClass('display-none');
        savedAddressTab.addClass('display-none');
        newAddressField.prop('checked', false);
        submitCheckout.removeClass('display-none');

        if(hasSavedAddress){
            savedAddressTab.removeClass('display-none');
        }else{
            newAddressTab.removeClass('display-none');
            newAddressField.prop('checked', true);
        }

    });

    shipToStore.click(function(){
        reselect.removeClass('display-none');
        shippingSelection.addClass('display-none');
        shippingContents.removeClass('display-none');
        shipToStoreTab.removeClass('display-none');
        submitCheckout.removeClass('display-none');
        shipToStoreInput.prop('checked', true);
    });


    newAddressField.on("click", function(){
        var method = '';
        if(newAddressField.is(":checked")){
            savedAddressTab.addClass('display-none');
            newAddressTab.removeClass('display-none');
            method = 'add';
        }else{
            savedAddressTab.removeClass('display-none');
            newAddressTab.addClass('display-none');
            method = 'remove';
        }

        addOrRemoveRules(method);
    });

});