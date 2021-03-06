jQuery(document).ready( function(){
    var form, formAlert, hasSavedAddress, formRulesLoaded;
    var submitCheckout, reselect, shippingSelection, shippingContents, shipToHomeTab, shipToStoreTab,
        savedAddressTab, newAddressTab, shipToHome, newAddressField, backButton,
        toDoorStep, cartToDoorStepCost, toDoorStepInput, toDoorStepCost, totalCost,
        shipToStore, shipToStoreInput;

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
    backButton = $('#backButton');
    toDoorStep = $('.toDoorStep');
    toDoorStepInput = $('#toDoorStepInput');
    toDoorStepCost = $('.toDoorStepCost');
    cartToDoorStepCost = $('.cart-toDoorStep .detail .cost');
    totalCost = $('#cart-calculated-total .totalCost');
    shipToStore = $('.shipToStore');
    shipToStoreInput = $('#shipToStoreInput');

    formRulesLoaded = false;
    hasSavedAddress = Boolean($('.saved-address').length);
    $ns.errorMessages = $ns.errorMessages || {};

    $ns.Utils.getAsyncData($ns.addressUrl,'script', true).then(function(){
        formRulesLoaded = true;
    });

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
                $('.form-group label.error').hide();
            }

        });

        if(!hasSavedAddress){
            if(!formRulesLoaded){
                $ns.Utils.getAsyncData($ns.addressUrl,'script', true).then(function(){
                    $ns.Utils.addOrRemoveRules('add', $ns.addressRules, form);
                    formRulesLoaded = true;
                });
            }else{
                $ns.Utils.addOrRemoveRules('add', $ns.addressRules, form);
            }
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

        if(!formRulesLoaded){
            $ns.Utils.getAsyncData($ns.addressUrl,'script', true).then(function(){
                $ns.Utils.addOrRemoveRules(method, $ns.addressRules, form);
                formRulesLoaded = true;
            });
        }else{
            $ns.Utils.addOrRemoveRules(method, $ns.addressRules, form);
        }
    });

    backButton.on("click", function(){
        shipToHome.click();
    });

    toDoorStep.on("click", function(){
        var cost = parseFloat(toDoorStepCost.html());
        var totalCostVal = parseFloat(totalCost.data("origcost"));
        if (toDoorStepInput.is(":checked")){
            cartToDoorStepCost.html(cost);
            totalCost.html(totalCostVal+cost);
        }else{
            cartToDoorStepCost.html("0");
            totalCost.html(totalCostVal);
        }
    });
});