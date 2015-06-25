/**
 * Created by SK on 6/25/2015.
 */

jQuery(document).ready( function(){
    // declare vars.
    var mapiframe, mapNameDiv, mapAddressDiv, aStoreLocation;

    mapiframe = $("#mapiframe");
    mapNameDiv = $("#mapdiv .aStoreTitleName");
    mapAddressDiv = $("#mapdiv .aStoreTitleAddress");
    aStoreLocation = $("#storescontdiv .aStoreDiv");

    // Register events.
    aStoreLocation.click(function(e){
        displayStoreId($(this).data("store-id"));
        getStorePosition(position)
    });

    // Get HTML5 Lat-Lng details.

    navigator.geolocation.getCurrentPosition(getStorePosition, showError);
    function getStorePosition(position) {
        $ns.data.action = 'ajax_handler';
        $ns.data.method = 'getClosestStore';
        var coords = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
        };
        $ns.data.post = 'coords=' + encodeURIComponent(JSON.stringify(coords));

        var data = $ns.Utils.getData();
        if(data.success){
            console.log(data);
            displayStoreId(data.msg.ID);
        }
    }
    function showError(error) {
        switch(error.code) {
            case error.PERMISSION_DENIED:
                console.log("User denied the request for Geolocation.");
                break;
            case error.POSITION_UNAVAILABLE:
                console.log("Location information is unavailable.");
                break;
            case error.TIMEOUT:
                console.log("The request to get user location timed out.");
                break;
            case error.UNKNOWN_ERROR:
                console.log("An unknown error occurred.");
                break;
        }
        // TODO:: Use google API to get the coords. [https://developers.google.com/maps/documentation/geocoding/]
    }

    function displayStoreId(id){
        var storeDiv = $('.aStoreDiv[data-store-id="' + id + '"]');
        mapiframe.attr("src", $(storeDiv).data("embed"));
        mapNameDiv.html($(storeDiv).data("title-name"));
        mapAddressDiv.html($(storeDiv).data("title-address"));
    }
});
