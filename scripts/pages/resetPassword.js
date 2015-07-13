/**
 * Created by SK on 6/19/2015.
 */

jQuery(document).ready( function() {
  var form,formAlert, okMark, errorMark, requestResetTab, verifyTokenTab, resentVerification, verifyTokenInput, verifyToken;

  form = $('#resetPassword');
  okMark = $("#inputvalidatorOK");
  errorMark = $("#inputvalidatorERR");
  formAlert = $('#form-alert');

  requestResetTab = $('#requestResetTab');
  verifyTokenTab = $('#verifyTokenTab');


  verifyTokenInput = $('#verifyTokenInput');
  verifyToken = $('#verifyToken');
  resentVerification = $('#resentVerification');

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

  function failedRegistration(errorMsg, errorClass, selector){
    selector = selector || formAlert;
    selector.html(errorMsg)
      .removeClass('display-none alert-error alert-warning alert-success').addClass(errorClass);
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
        requestResetTab.addClass('display-none');
        verifyTokenTab.removeClass('display-none');
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



  resentVerification.click(function(){
    form.submit();
  });

  function verifyTokenHandler (){
    var token, selector, data;
    token = verifyTokenInput.val();
    selector = $('#verify-token-alert');

    if(!token){
      failedRegistration($ns.errorMessages.noToken || 'You must enter a token!', 'alert-error', selector);
      return false;
    }

    $ns.data.action = 'ajax_handler';
    $ns.data.method = 'resetPassword';
    $ns.data.post = 'log=' + encodeURIComponent($(form[0].log).val())  + '&token='+  encodeURIComponent(token);
    data = $ns.Utils.getData();
    if(data.success){
      // redirecting to login
      window.location.href = $ns.loginPage+'?resetPassword=success';
    }else {
      if(data.msg){
        failedRegistration(data.msg.errorMsg, 'alert-warning');
      }else{
        failedRegistration($ns.errorMessages.serverError || 'Something Went Wrong', 'alert-error');
      }
    }

  }

  verifyToken.click(verifyTokenHandler);

  verifyTokenInput.click(function(e){
    var key = e.which || e.keyCode;

    if(key === 13){
      verifyTokenHandler();
    }
  });


});