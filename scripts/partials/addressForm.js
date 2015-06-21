
$ns.addressRules = {
    city : {
        required: true,
        minlength: 2,
        messages: {
            required: "Required input",
            minlength: jQuery.validator.format($ns.errorMessages.maxLength || 'password must be {0} digit long')
        }
    },
    house : {
        required: true,
        minlength: 2,
        messages: {
            required: "Required input",
            minlength: jQuery.validator.format($ns.errorMessages.maxLength || 'password must be {0} digit long')
        }
    },
    apt : {
        required: true,
        minlength: 2,
        messages: {
            required: "Required input",
            minlength: jQuery.validator.format($ns.errorMessages.maxLength || 'password must be {0} digit long')
        }
    },
    postcode : {
        required: true,
        minlength: 2,
        messages: {
            required: "Required input",
            minlength: jQuery.validator.format($ns.errorMessages.maxLength || 'password must be {0} digit long')
        }
    },
    phone_number : {
        required: true,
        minlength: 2,
        messages: {
            required: "Required input",
            minlength: jQuery.validator.format($ns.errorMessages.maxLength || 'password must be {0} digit long')
        }
    }
};

