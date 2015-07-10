/**
 * Created by SK on 6/25/2015.
 */

jQuery(document).ready( function(){
    // declare vars.
    var mapiframe, mapNameDiv, mapAddressDiv, mapDescription, mapImage, mapWazeLink, aStoreLocation,
        storeLocatorAddress, storeLocatorAddressErr, storeLocateInput, storeLocateButton;

    mapiframe = $("#mapiframe");
    mapNameDiv = $("#mapdiv .aStoreTitleName");
    mapAddressDiv = $("#mapdiv .aStoreTitleAddress");
    mapDescription = $("#mapdiv .aStoreDescription");
    mapImage = $("#mapdiv .aStoreImage");
    mapWazeLink = $("#mapdiv .aStoreWazeLink");
    aStoreLocation = $("#storescontdiv .aStoreDiv");

    storeLocatorAddress = $("#storeLocatorAddress");
    storeLocatorAddressErr = $("#storeLocatorAddressErr");
    storeLocateInput = $("#storeLocateInput");
    storeLocateButton = $("#storeLocateButton");


    // Register events.
    aStoreLocation.click(function(e){
        displayStoreId($(this).data("store-id"));
    });
    storeLocateButton.click(function(e){
        storeLocatorAddressErr.hide();
        getGeocodeByAddress(storeLocateInput.val());
    });

    // Get HTML5 Lat-Lng details.
    if (navigator && navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(navigatorGetStorePosition, showError, {enableHighAccuracy:true, timeout:60000, maximumAge:600000});
    }
    function navigatorGetStorePosition(position) {
        updateClosestStore(position.coords.latitude, position.coords.longitude);
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
    }

    function getGeocodeByAddress(address){
        var googleMapsGeo = "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyDJ-x2RfRCj_wjm0gPO-VW4ZEIheV1EWhE&region=IL&components=country:IL&address=" + encodeURIComponent(address);
        var geocode = $ns.Utils.getExternalData(googleMapsGeo);
        if ( typeof geocode == "object" && geocode.status=="OK"){
            updateClosestStore(geocode.results[0].geometry.location.lat, geocode.results[0].geometry.location.lng);
        }else{
            storeLocatorAddressErr.show();
        }
    }

    function updateClosestStore(lat, lng){
        $ns.data.action = 'ajax_handler';
        $ns.data.method = 'getClosestStore';
        var coords = {
            lat: lat,
            lng: lng
        };
        $ns.data.post = 'coords=' + encodeURIComponent(JSON.stringify(coords));

        var data = $ns.Utils.getData();
        if(data.success){
            console.log(data);
            displayStoreId(data.msg.ID);
        }
    }

    function displayStoreId(id){
        var storeDiv = $('.aStoreDiv[data-store-id="' + id + '"]');
        mapiframe.attr("src", $(storeDiv).data("embed"));
        mapNameDiv.html($(storeDiv).data("title-name"));
        mapAddressDiv.html($(storeDiv).data("title-address"));
        mapDescription.html($(storeDiv).data("description"));
        mapImage.attr("src", $(storeDiv).data("imgurl"));
        mapWazeLink.attr("href", $(storeDiv).data("wazeurl"));
    }
});
