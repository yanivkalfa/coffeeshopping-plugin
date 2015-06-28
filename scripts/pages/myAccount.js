/**
 * Created by SK on 6/27/2015.
 */
jQuery(document).ready( function(){
    var tabselector = jQuery(".tabselector");

    toggleDisplayDiv();

    tabselector.on("click", function(e){
        jQuery(".tabselector.active").removeClass("active");
        jQuery(this).addClass("active");
        toggleDisplayDiv();
    });


    function toggleDisplayDiv(){
        var selected = jQuery(".tabselector.active").data("assoc");
        jQuery(".tabdiv").hide();
        jQuery("#" + selected + "-tab-div").show();
    }

  var form = $('#registerForm');
  var data, errorMsg, errorType;
  $ns.data.action = 'ajax_handler';
  $ns.data.method = 'registerNewUser';
  $ns.data.post = 'user=' + encodeURIComponent(JSON.stringify($(form).serializeObject()));
  data = $ns.Utils.getData();
  if(data.success){
    successfullRegistration(data.msg.generatedPass);
    if (data.msg.errorMsg!="prevent-login"){
      userLogin(data.msg.generatedPass);
    }
  }else {
    setInputError()
    if(data.msg){
      failedRegistration(data.msg.errorMsg, 'alert-warning');
    }else{
      failedRegistration('<span>There was a network error please try again</span>', 'alert-error');
    }
  }

  form.reset();
});