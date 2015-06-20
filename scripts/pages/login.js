/**
 * Created by SK on 6/19/2015.
 */

jQuery(document).ready( function() {
    $ns.Utils.getData(
        'get',
        "/wp-content/plugins/coffeeshopping-plugin/bower_components/intl-tel-input/lib/libphonenumber/build/utils.js",
        {},
        'script',
        true
    );
    var form = $('#loginform');
    var errorMessages = {
        log: {
            phoneIL: 'Please specify correct Israel phone number'
        },
        pwd: {
            number: 'Must be a number',
            maxlength: 'password must be {0} digit long',
            minlength: 'password must be {0} digit long'
        }
    };
    errorMessages = $.extend({}, errorMessages, $ns.errorMessages || {});



    form.validate({
        rules: {
            log: {
                required:true,
                phoneIL: 'il'
            },
            pwd: {
                required:true,
                number: true,
                maxlength: 4,
                minlength: 4
            }
        },
        messages: {
            log: {
                phoneIL: errorMessages.log.number
            },
            pwd: {
                number: errorMessages.pwd.number,
                maxlength: jQuery.validator.format(errorMessages.pwd.maxlength),
                minlength: jQuery.validator.format(errorMessages.pwd.minlength)
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

});