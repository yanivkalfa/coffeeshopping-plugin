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
    var form, errorMessages, formAlert;

    form = $('#registerForm');
    formAlert = $('#form-alert');
    errorMessages = {
        log: {
            phoneIL: 'Please specify correct Israel phone number'
        },
        pwd: {
            number: 'Must be a number',
            maxlength: 'password must be {0} digit long',
            minlength: 'password must be {0} digit long',
            equalTo : 'Passwords does not match'
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
                minlength: 4,
                equalTo: '[name="cpwd"]'
            },
            cpwd : {
                required:true,
                number: true,
                maxlength: 4,
                minlength: 4,
                equalTo: '[name="pwd"]'
            }
        },
        messages: {
            log: {
                phoneIL: errorMessages.log.number
            },
            pwd: {
                number: errorMessages.pwd.number,
                maxlength: jQuery.validator.format(errorMessages.pwd.maxlength),
                minlength: jQuery.validator.format(errorMessages.pwd.minlength),
                equalTo : 'does not equal'
            },
            cpwd: {
                number: errorMessages.pwd.number,
                maxlength: jQuery.validator.format(errorMessages.pwd.maxlength),
                minlength: jQuery.validator.format(errorMessages.pwd.minlength),
                equalTo : 'does not equal'
            }
        },
        submitHandler: function(form) {
            var data, errorMsg, errorType;
            $ns.data.action = 'ajax_handler';
            $ns.data.method = 'registerNewUser';
            $ns.data.post = 'user=' + encodeURIComponent(JSON.stringify($(form).serializeObject()));
            data = $ns.Utils.getData();
            if(data.success){
                errorMsg = '<span>You\'v registered successfully please </span><a href="/login/">Login</a>';
                errorType = 'alert-success';
            }else {
                if(data.msg){
                    errorMsg = '<span>' + data.msg.errorMsg + '</span>' ;
                    errorType = 'alert-warning';
                }else{
                    errorMsg = '<span>There was a network error please try again</span>';
                    errorType = 'alert-error';
                }
            }

            formAlert.html(errorMsg)
                .removeClass('display-none alert-error alert-warning alert-success').addClass(errorType);

            form.reset();
        }
    });

});