/**
 * Created by SK on 6/19/2015.
 */

jQuery(document).ready( function() {
    var form = $('#loginform');
    $ns.errorMessages = $ns.errorMessages || {};
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
                required : $ns.errorMessages.required || 'This field is required',
                phoneIL: $ns.errorMessages.phoneIL || 'Please specify correct Israel phone number'
            },
            pwd: {
                required :  $ns.errorMessages.required || 'This field is required',
                number: $ns.errorMessages.number || 'Must be a number',
                maxlength: jQuery.validator.format($ns.errorMessages.maxLength || 'password must be {0} digit long'),
                minlength: jQuery.validator.format($ns.errorMessages.minLength || 'password must be {0} digit long')
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });

});