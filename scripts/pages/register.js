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
            $(element).valid();
        },
        rules: {
            log: {
                required:true,
                phoneIL: 'il'
            }
        },
        messages: {
            log: {
                required : $ns.errorMessages.required || 'This field is required',
                phoneIL: $ns.errorMessages.phoneIL || 'Please specify correct Israel phone number'
            }
        },
        submitHandler: function(form) {
            var data, errorMsg, errorType;
            $ns.data.action = 'ajax_handler';
            $ns.data.method = 'registerNewUser';
            $ns.data.post = 'user=' + encodeURIComponent(JSON.stringify($(form).serializeObject()));
            data = $ns.Utils.getData();
            if(data.success){
                errorMsg = '<span>You\'v registered successfully, please </span><a href="/login/">Login</a>';
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