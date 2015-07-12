/**
 * Created by SK on 6/19/2015.
 */

jQuery(document).ready( function() {
  var form,formAlert, okMark, errorMark, requestReset, verifyToken;

  form = $('#resetPassword');
  okMark = $("#inputvalidatorOK");
  errorMark = $("#inputvalidatorERR");
  formAlert = $('#form-alert');

  requestReset = $('#requestReset');
  verifyToken = $('#verifyToken');


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
        //phoneIL: 'il'
      }
    },
    messages: {
      log: {
        required : $ns.errorMessages.required || 'This field is required',
        //phoneIL: $ns.errorMessages.phoneIL || 'Please specify correct Israel phone number'
      }
    },
    submitHandler: function(form) {
      var data;
      $ns.data.action = 'ajax_handler';
      $ns.data.method = 'requestResetPassword';
      $ns.data.post = 'log=' + encodeURIComponent($(form.log).val());
      data = $ns.Utils.getData();
      if(data.success){
        requestReset.addClass('display-none');
        verifyToken.removeClass('display-none');
      }else {
        if(data.msg){
          failedRegistration(data.msg.errorMsg, 'alert-warning');
        }else{
          failedRegistration($ns.errorMessages.serverError || 'Something Went Wrong', 'alert-error');
        }
      }
    },
    showErrors: function (errObj, errArr) {}
  });

  function failedRegistration(errorMsg, errorClass){
    formAlert.html(errorMsg)
      .removeClass('display-none alert-error alert-warning alert-success').addClass(errorClass);
  }


});