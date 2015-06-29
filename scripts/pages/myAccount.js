/**
 * Created by SK on 6/27/2015.
 */
jQuery(document).ready( function(){
    var alertSelector,tabselector,profileForm, addressForm, savedAddresses,profileFormAlert,addressFormAlert;
    tabselector = jQuery(".tabselector");
    savedAddresses = $('#savedAddresses');
    $ns.errorMessages = $ns.errorMessages || {};
    $ns.warningMessages = $ns.warningMessages || {};

    function createAddressHtml(address){
        return $([
        '<div class="single-address saved-address">',
            '<div class="inline addressdets">',
                '<label>',
                    '<div class="addressName">',
                        '<div class="inline">',
                            '<b>' + address.full_name + '</b>',
                        '</div>',
                        '<div class="inline">&nbsp;-&nbsp;<i>(' + address.phone_number + ')</i> </div>',
                    '</div>',
                    '<div class="addressDetails">',
                    address.street + ' ' + address.house + '/' + address.apt + ', ' + address.city + ', ' + address.postcode,
                    '</div>',
                '</label>',
            '</div>',
            '<div class="inline addressactions flleft">',
                '<div class="inline removeaddress" data-address-id="' + address.ID + '">[X]</div>',
            '</div>',
        '</div>'
        ].join(''));
    }

    if (window.location.hash!=""){
        jQuery(".tabselector.active").removeClass("active");
        jQuery(".tabselector[data-assoc='" + window.location.hash + "']").addClass("active");
    }
    toggleDisplayDiv();

    tabselector.on("click", function(e){
        jQuery(".tabselector.active").removeClass("active");
        jQuery(this).addClass("active");
        toggleDisplayDiv();
    });

    profileFormAlert = $('#profileForm-alert');
    savedAddresses.on("click", '.removeaddress', function(e){
        if (!confirm($ns.warningMessages.delete || 'You are about to delete an address would you like to continue ?')) {
            return false;
        }

        var address_id, data;
        address_id = $(this).data('address-id');

        $ns.data.action = 'ajax_handler';
        $ns.data.method = 'removeAddress';
        $ns.data.post = 'address_id=' + address_id;
        data = $ns.Utils.getData();
        if(data.success){
            $(this).parents('.single-address').remove();
        }else {
            if(data.msg){
                profileFormAlert.html(data.msg.errorMsg)
                    .removeClass('display-none alert-error alert-warning alert-success').addClass('alert-error');
            }else{
                profileFormAlert.html('We were unable to communicate with the server')
                    .removeClass('display-none alert-error alert-warning alert-success').addClass('alert-error');
            }
        }
        return false;
    });

    function toggleDisplayDiv(){
        var showtab = jQuery(".tabselector.active").data("assoc");
        jQuery(".tabdiv").hide();
        jQuery(showtab + "-tab-div").show();
    }

    profileForm = $('#profileForm');
    profileForm.on('submit', function(){
        var data;
        $ns.data.action = 'ajax_handler';
        $ns.data.method = 'updateUserProfile';
        $ns.data.post = 'user=' + encodeURIComponent(JSON.stringify(profileForm.serializeJSON()));
        data = $ns.Utils.getData();
        if(data.success){
            profileFormAlert.html(data.msg)
                .removeClass('display-none alert-error alert-warning alert-success').addClass('alert-success');
        }else {
            if(data.msg){
                profileFormAlert.html(data.msg.errorMsg)
                    .removeClass('display-none alert-error alert-warning alert-success').addClass('alert-error');
            }else{
                profileFormAlert.html('We were unable to communicate with the server')
                    .removeClass('display-none alert-error alert-warning alert-success').addClass('alert-error');
            }
        }
        return false;
    });

    addressForm = $('#addressForm');
    addressFormAlert = $('#addressForm-alert');
    if(addressForm.length) {
        addressForm[0].reset();

        addressForm.validate({
            onkeyup : function(element){
                $(element).valid()
            },
            rules: {},
            messages: {},
            submitHandler: function (form) {

                var data;
                $ns.data.action = 'ajax_handler';
                $ns.data.method = 'addAddress';
                $ns.data.post = 'address=' + encodeURIComponent(JSON.stringify(addressForm.serializeJSON().address));
                data = $ns.Utils.getData();
                if(data.success){

                    savedAddresses.append(createAddressHtml(data.msg));
                    addressFormAlert.html('')
                        .removeClass('display-none alert-error alert-warning alert-success').addClass('display-none');
                }else {
                    if(data.msg){
                        addressFormAlert.html(data.msg.errorMsg)
                            .removeClass('display-none alert-error alert-warning alert-success').addClass('alert-error');
                    }else{
                        addressFormAlert.html('We were unable to communicate with the server')
                            .removeClass('display-none alert-error alert-warning alert-success').addClass('alert-error');
                    }
                }

                addressForm[0].reset();

                return false;
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

                    alertSelector.html(errorMsg)
                        .removeClass('display-none alert-error alert-warning alert-success').addClass('alert-error');
                } else {
                    alertSelector.html('')
                        .removeClass('display-none alert-error alert-warning alert-success').addClass('display-none');
                }
            },
            showErrors: function () {
                this.defaultShowErrors();
                $("#address_id-error").hide();
            }

        });

        $ns.Utils.getAsyncData($ns.addressUrl,'script', true).then(function(){
            $ns.Utils.addOrRemoveRules('add', $ns.addressRules, addressForm);
        });
    }
});