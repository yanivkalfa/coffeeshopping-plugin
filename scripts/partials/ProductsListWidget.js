/**
 * Created by SK on 6/30/2015.
 */
jQuery(document).ready( function(){
    var curWidgetID = "";

    jQuery(".api, .listname, .catid").on("change", function(e){
        shortcodeUpdate("#" + jQuery(this).parents(".widget").attr("id"));
    });
    jQuery(".limit, .itemid, .specificids").on("blur", function(e){
        shortcodeUpdate("#" + jQuery(this).parents(".widget").attr("id"));
    });
    jQuery(".typeselect").on("change", function(e){
        toggleWidget("#" + jQuery(this).parents(".widget").attr("id"));
        shortcodeUpdate("#" + jQuery(this).parents(".widget").attr("id"));
    });
    //jQuery(".typeselect").each(function(){jQuery(this).change();});

    function shortcodeUpdate(widgetID){
        console.log("Updating " + widgetID);
        curWidgetID = widgetID;
        var type = jQuery(widgetID + " select.typeselect option:selected").val();
        var shortcodediv = jQuery(widgetID + " #shortcodediv");
        var listname = jQuery(widgetID + " .listname"),
            limit = jQuery(widgetID + " .limit"),
            api = jQuery(widgetID + " .api"),
            catid = jQuery(widgetID + " .catid"),
            itemid = jQuery(widgetID + " .itemid"),
            specificids = jQuery(widgetID + " .specificids");

        switch (type){
            case "savedlist":
                shortcodediv.html('[coffeeshoppinglist type="savedlist" listname="' + listname.val() + '"]');
                break;

            case "mostwatched":
                shortcodediv.html('[coffeeshoppinglist type="mostwatched" api="' + api.val() + '" catid="'+ catid.val() +'" limit="'+ limit.val() +'"]');
                break;

            case "relateditems":
                shortcodediv.html('[coffeeshoppinglist type="relateditems" api="' + api.val() + '" catid="'+ catid.val() +'" limit="'+ limit.val() +'" itemid="'+ itemid.val() +'"]');
                break;

            case "similaritems":
                shortcodediv.html('[coffeeshoppinglist type="similaritems" api="' + api.val() + '" catid="'+ catid.val() +'" limit="'+ limit.val() +'" itemid="'+ itemid.val() +'"]');
                break;

            case "specificids":
                shortcodediv.html('[coffeeshoppinglist type="specificids" api="' + api.val() + '" specificids="'+ specificids.html() +'"]');
                break;
        }
    }

    function toggleWidget(widgetID){
        var options = jQuery(widgetID + " #commonopts");
        var type = jQuery(widgetID + " select.typeselect option:selected").val();
        var all_opts = jQuery(widgetID + " .widgetopt");
        var api_option = jQuery(widgetID + " #api_option"),
            limit_option = jQuery(widgetID + " #limit_option"),
            category_option = jQuery(widgetID + " #category_option"),
            item_option = jQuery(widgetID + " #item_option"),
            ids_option = jQuery(widgetID + " #ids_option"),
            listname_option = jQuery(widgetID + " #listname_option");



        options.removeClass("display-none");
        all_opts.css("display", "block");
        all_opts.hide();

        switch (type){
            case "savedlist":
                listname_option.show();
                break;

            case "mostwatched":
                api_option.show();
                category_option.show();
                limit_option.show();
                break;

            case "relateditems":
                api_option.show();
                category_option.show();
                limit_option.show();
                item_option.show();
                break;

            case "similaritems":
                api_option.show();
                category_option.show();
                limit_option.show();
                item_option.show();
                break;

            case "specificids":
                api_option.show();
                ids_option.show();
                break;
        }
    }
});

