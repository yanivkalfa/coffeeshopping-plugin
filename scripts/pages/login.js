/**
 * Created by SK on 6/19/2015.
 */

jQuery(document).ready( function() {
    var telInput = $("#loginphone"),
        passInput = $("#loginpassword"),
        submitbutton = $("#userloginbutton");

    telInput.intlTelInput({
        onlyCountries: ["il"],
        utilsScript: "/wp-content/plugins/coffeeshopping-plugin/bower_components/intl-tel-input/lib/libphonenumber/build/utils.js"
    });

    // on blur: validate
    telInput.blur(function() {
        if ($.trim(telInput.val())) {
            if (telInput.intlTelInput("isValidNumber")) {
                submitbutton.html("Login");
                submitbutton.removeClass("disabled");
            } else {
                telInput.addClass("error");
                submitbutton.html("Invalid Phone#");
                submitbutton.addClass("disabled");
            }
        }
    });

    // on keydown: reset
    telInput.keydown(function() {
        telInput.removeClass("error");
    });

    // Password validator.
    passInput.numericInput({allowFloat: false, allowNegative: false, limitInput: 4});

});