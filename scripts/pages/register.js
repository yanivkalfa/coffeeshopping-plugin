/**
 * Created by SK on 6/19/2015.
 */

jQuery(document).ready( function() {
    var form, okMark, errorMark;

    form = $('#registerForm');
    okMark = $("#inputvalidatorOK");
    errorMark = $("#inputvalidatorERR");

    errorMark.hide();
    okMark.hide();

    $ns.errorMessages = $ns.errorMessages || {};

    function setInputError(){
        okMark.hide();
        errorMark.show();
    }
    function setInputOK(){
        okMark.show();
        errorMark.hide();
    }

    form.validate({
        onkeyup : function(element){
            if($(element).valid()){
                setInputOK()
            }else{
                setInputError()
            }
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
            form.submit();
        },
        showErrors: function (errObj, errArr) {}


    });




});

/*

 var registrationFormContainer, form, formAlert, okMark, errorMark, registrationDone, passwordField;
 registrationFormContainer = $("#registrationformcont");
 form = $('#registerForm');
 formAlert = $('#form-alert');
 okMark = $("#inputvalidatorOK");
 errorMark = $("#inputvalidatorERR");
 passwordField = $("#passwordfield");




 var data;
 $ns.data.action = 'ajax_handler';
 $ns.data.method = 'registerNewUser';
 $ns.data.post = 'user=' + encodeURIComponent(JSON.stringify($(form).serializeJSON()));
 data = $ns.Utils.getData();
 if(data.success){
 successfullRegistration(data.msg.generatedPass);
 if (data.msg.errorMsg!="prevent-login"){
 userLogin(data.msg.generatedPass);
 }
 }else {
 setInputError();
 if(data.msg){
 failedRegistration(data.msg.errorMsg, 'alert-warning');
 }else{
 failedRegistration('<span>There was a network error please try again</span>', 'alert-error');
 }
 }

 form.reset();
 */

/*
 function userLogin(password){
 // TODO:: Rebuild this shit?!
 var login = $("#logininput").val();
 $ns.data.action = 'ajax_handler';
 $ns.data.method = 'userLogin';
 $ns.data.post = 'login=' + encodeURIComponent(login) + '&password=' + encodeURIComponent(password);
 data = $ns.Utils.getData();
 // Not doing anything in here cause wp_signon is redirecting us.
 }*/



/*
 function successfullRegistration(pass){
 passwordField.html(pass);
 registrationFormContainer.hide();
 registrationDone.show();
 }

 function failedRegistration(errorMsg, errorClass){
 formAlert.html(errorMsg)
 .removeClass('display-none alert-error alert-warning alert-success').addClass(errorClass);
 }
 */