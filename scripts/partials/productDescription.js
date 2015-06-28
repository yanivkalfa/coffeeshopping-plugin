$(document).ready(function(){
  var content = window.parent.getContent();
  $('#productDescriptionWrap').html(content);
  $('a').on('click', function(e){
    var newLocation = $(this).attr('href');
    window.parent.navigateTo(newLocation);
    e.preventDefault();
    return false;
  });
});