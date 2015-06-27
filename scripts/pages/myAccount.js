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
});