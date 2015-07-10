$(document).ready(function(){
    var searchForm = jQuery("#searchwidgetcont");
    var searchBox = jQuery("#searchwidgetadvanceddiv");
    var searchToggler = jQuery("#searchadvbutton");
    var advs = jQuery("#advsearcher");

    searchToggler.on("click", function(){
        if (searchToggler.hasClass("active")){
            searchToggler.removeClass("active");
            advs.val("0");
            searchBox.addClass("display-none");
        }else {
            searchToggler.addClass("active");
            advs.val("1");
            searchBox.removeClass("display-none");
        }
    });

    searchForm.on("submit", function(){
        if (advs.val()==0){
            resetoptions();
        }
    });



    function resetoptions(){
        var stores = jQuery(".storecheckbox"),
            conditions = jQuery(".conditionscheckbox"),
            sortOrder = jQuery("#sortOrder"),
            advs = jQuery("#advsearcher");

        advs.val("0");
        sortOrder.val("BestMatch");
        conditions.removeAttr("checked");
        stores.removeAttr("checked");
    }

});
