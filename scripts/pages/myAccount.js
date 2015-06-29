/**
 * Created by SK on 6/27/2015.
 */
jQuery(document).ready( function(){
    var alertSelector,tabselector,form, savedAddresses;
    tabselector = jQuery(".tabselector");
    savedAddresses = $('#savedAddresses');

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
        '</div>',
        ].join(''));
    }

    toggleDisplayDiv();

    tabselector.on("click", function(e){
        jQuery(".tabselector.active").removeClass("active");
        jQuery(this).addClass("active");
        toggleDisplayDiv();
    });


    savedAddresses.on("click", '.removeaddress', function(e){
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
                alertSelector.html(data.msg.errorMsg)
                    .removeClass('display-none alert-error alert-warning alert-success').addClass('alert-error');
            }else{
                alertSelector.html('We were unable to communicate with the server')
                    .removeClass('display-none alert-error alert-warning alert-success').addClass('alert-error');
            }
        }
        return false;
    });


    function toggleDisplayDiv(){
        var selected = jQuery(".tabselector.active").data("assoc");
        jQuery(".tabdiv").hide();
        jQuery("#" + selected + "-tab-div").show();
    }

    form = $('#profileForm');
    alertSelector = $('#profileForm-alert');


    form.on('submit', function(){
        var data;
        $ns.data.action = 'ajax_handler';
        $ns.data.method = 'updateUserProfile';
        $ns.data.post = 'user=' + encodeURIComponent(JSON.stringify($(form).serializeObject()));
        data = $ns.Utils.getData();
        if(data.success){
            alertSelector.html('')
                .removeClass('display-none alert-error alert-warning alert-success').addClass('display-none');
        }else {
            if(data.msg){
                alertSelector.html(data.msg.errorMsg)
                    .removeClass('display-none alert-error alert-warning alert-success').addClass('alert-error');
            }else{
                alertSelector.html('We were unable to communicate with the server')
                    .removeClass('display-none alert-error alert-warning alert-success').addClass('alert-error');
            }
        }
        return false;
    });



    form = $('#addressForm');
    alertSelector = $('#addressForm-alert');
    $ns.errorMessages = $ns.errorMessages || {};

    if(form.length) {
        form[0].reset();

        form.validate({
            onkeyup : function(element){
                $(element).valid()
            },
            rules: {},
            messages: {},
            submitHandler: function (form) {

                var data;
                $ns.data.action = 'ajax_handler';
                $ns.data.method = 'addAddress';
                $ns.data.post = 'address=' + encodeURIComponent(JSON.stringify($(form).serializeJSON().address));
                data = $ns.Utils.getData();
                if(data.success){

                    savedAddresses.append(createAddressHtml(data.msg));
                    alertSelector.html('')
                        .removeClass('display-none alert-error alert-warning alert-success').addClass('display-none');
                }else {
                    if(data.msg){
                        alertSelector.html(data.msg.errorMsg)
                            .removeClass('display-none alert-error alert-warning alert-success').addClass('alert-error');
                    }else{
                        alertSelector.html('We were unable to communicate with the server')
                            .removeClass('display-none alert-error alert-warning alert-success').addClass('alert-error');
                    }
                }
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
            $ns.Utils.addOrRemoveRules('add', $ns.addressRules, form);
        });
    }
});