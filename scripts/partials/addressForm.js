$ns.addressRules = {
    'address[city]' : {
        required: true,
        messages: {
            required: "Required input"
        }
    },
    'address[street]' : {
        required: true,
        messages: {
            required: "Required input"
        }
    },
    'address[house]' : {
        required: true,
        messages: {
            required: "Required input"
        }
    },
    'address[apt]' : {
        required: true,
        messages: {
            required: "Required input"
        }
    },
    'address[postcode]' : {
        required: true,
        messages: {
            required: "Required input"
        }
    },
    'address[phone_number]' : {
        required: true,
        maxlength: 11,
        messages: {
            "required": "Required input",
            "maxlength": jQuery.validator.format($ns.errorMessages.maxlength || 'Field cannot be longer then {0} digits')
        }
    },
    'address[full_name]' : {
        required: true,
        messages: {
            required: "Required input"
        }
    }
};