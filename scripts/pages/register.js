/**
 * Created by SK on 6/19/2015.
 */

jQuery(document).ready( function() {
    var form, formAlert;

    form = $('#registerForm');
    formAlert = $('#form-alert');
    $ns.errorMessages = $ns.errorMessages || {};

    form.validate({
        onkeyup : function(element){
            $(element).valid()
        },
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
                required : $ns.errorMessages.required || 'This field is required',
                phoneIL: $ns.errorMessages.phoneIL || 'Please specify correct Israel phone number'
            },
            pwd: {
                required :  $ns.errorMessages.required || 'This field is required',
                number: $ns.errorMessages.number || 'Must be a number',
                maxlength: jQuery.validator.format($ns.errorMessages.maxLength || 'password must be {0} digit long'),
                minlength: jQuery.validator.format($ns.errorMessages.minLength || 'password must be {0} digit long'),
                equalTo : $ns.errorMessages.equalTo || 'does not equal'
            },
            cpwd: {
                required :  $ns.errorMessages.required || 'This field is required',
                number: $ns.errorMessages.number || 'Must be a number',
                maxlength: jQuery.validator.format($ns.errorMessages.maxLength || 'password must be {0} digit long'),
                minlength: jQuery.validator.format($ns.errorMessages.minLength || 'password must be {0} digit long'),
                equalTo : $ns.errorMessages.equalTo || 'does not equal'
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

    /*
    // fetching address rules
    $ns.Utils.getData(
        'get',
        "/wp-content/plugins/coffeeshopping-plugin/scripts/partials/addressForm.js",
        {},
        'script',
        true
    );

    // adding address rules to registeration.
    for(var fieldName in $ns.addressRules){
        if(!$ns.addressRules.hasOwnProperty(fieldName)) continue;
        var input = form.find('[name="'+ fieldName +'"]');
        input.rules( "add", $ns.addressRules[fieldName]);
    }
    */

});