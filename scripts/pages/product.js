/*
 if(!window.$ns) window.$ns = {};
 $ns.itemPricing = <?php echo json_encode($itemPricing);?>;
 $ns.shippingOpts = <?php echo json_encode($product->shippingDetails->shippingOptions);?>;
 $ns.selectedShipping = 0; // proper indexes start from 0.
 $ns.variationSets = <?php echo json_encode($product->variationSets);?>;
 $ns.variations = <?php echo json_encode($product->variations);?>;
 $ns.selectedVariant = 0; // default search result.
 $ns.exchExtension = "<?php echo $exchangeExtension; ?>";
 $ns.exchSymbol = "<?php echo Utils::getCurrencySymbol($exchangeCurrency);?>";
 $ns.exchCurrency = "<?php echo $exchangeCurrency;?>";
* */

/*
* This section are function that are made available for the ifram.
* */
(function(){
  window.navigateTo = function(href){
    window.location.href = href;
  };

  window.getContent = function(){
    return $ns.productContent;
  };

  window.getJqueryUrl = function(){
    return $ns.jqueryUrl;
  }
})();

jQuery(document).ready( function(){
    /*
     ZoomIt BEGIN/
     */
    var previews 	= jQuery('.zoomItcontainer .full-image a'), // image previews
        thumbnails 	= jQuery('.zoomItcontainer .gallery-thumbnails a'); // small thumbnails for changing previews
    // start zoom only on visible element
    jQuery('.zoomIt.visible').jqZoomIt({
        init : function(){ // on zoom init, add class to element
            jQuery( this ).addClass('zoomIt_loaded');
        }
    });
    // small navigation thumnails functionality
    jQuery(thumbnails).click(function(e){
        e.preventDefault();
        // hide all previews
        jQuery(previews).removeClass('visible').addClass('hidden');
        // get key of thumbnail
        var key = jQuery.inArray( this, thumbnails );
        // show preview having the same key as small thumbnail
        jQuery(previews[key]).removeClass('hidden').addClass('visible');
        // check if preview has loaded class and if not, start zoom and add class
        if( !jQuery(previews[key]).hasClass('zoomIt_loaded') ){
            // start zoom
            jQuery(previews[key]).jqZoomIt();
            // add zoom loaded class
            jQuery(previews[key]).addClass('zoomIt_loaded');
        }
    });
    // large image function
    jQuery(previews).click(function(e){
        e.preventDefault();
    });
    // Load our carousel.
    var m = jQuery('.galleryContainer').CB_CarouseljQ({
        options: {
            visibleItems: 10,
            threshold: -15
        },
        change: function(s, obj){
            jQuery(obj).children("a").click();
        }
    });
    /*
     /END ZoomIt
     */

    // handle our shipping options.
    jQuery(".shippingopt").click(function(e){
        // Get the chosen index.
        $ns.selectedShipping = jQuery(this).data("index");
        updateShippingOpt();
        updateProductPrices();
    });

    // handle our $ns.variations.
    jQuery(".varset").change(function(e){
        updateSelectedVariant(jQuery(this));
    });


    // Handle our quantity changes.
    jQuery("#orderquantity").change(function(e){
        $ns.Utils.onProductQuantityChange(jQuery("#orderquantity"), getAvailableQuantity(), $ns.orderLimit );
        updateProductPrices();
    });

    // Handle our onHover popUps.
    jQuery("#itemprice, #shippingprice").mouseover(function(){
        displayPopExchangeRates(jQuery(this));
    }).mouseout(function(){
        jQuery("#exchangeDisplayDiv").hide();
    });
    // Non specifics info popups.
    jQuery(".popupinfo").mouseover(function() {
        displayCustomInfoPopUp(jQuery(this));
    }).mouseout(function(){
        jQuery("#infoPopUpDisplayDiv").hide();
    });


    // Handle togglers.
    jQuery("#infoPopUpDisplayDiv").hide();
    jQuery("#exchangeDisplayDiv").hide();
    jQuery("#quicksummary").hide();
    jQuery("#orderdetailstogg").click(function(e){toggleID(this, "#quicksummary", e)});
    jQuery("#DebugProductOutput").hide();
    jQuery("#debugOutPutTogg").click(function(e){toggleID(this, "#DebugProductOutput", e)});
    // Scroll our details panel to the left to display contents in case of need.
    jQuery("#detailspaenl").scrollLeft(0);

    // Handle 'Add to cart'
    jQuery("#buynowbuttondiv").click(addToCart);

    // ON-LOAD - get the defaults.
    updateShippingOpt();
    updateProductPrices();

    function addToCart(){
        if (jQuery("#itemvariations").data("hasvars")==1 && $ns.selectedVariant==-1){
            jQuery(".varset").effect("highlight", 1500);
            return false;
        }

        var exchDetails = getProductPricesDetails($ns.exchExtension, 1);
        var varArr = ($ns.selectedVariant!=-1) ? getCurrentVarSel() : {};

        var product = {
            unique_store_id : $ns.productID,
            store : $ns.store,
            img : ($ns.selectedVariant!=-1) ? getSelectedVariantImage(varArr) : $ns.productPic,
            title : $ns.productTitle,
            price : exchDetails["itemprice"],
            quantity : jQuery("#orderquantity").val(),
            price_modifiers : [
                //{name:'storeCommission', nameAs : 'Store Commission', value : exchDetails["storeprice"]},
                {name:'PayPalFees', nameAs: 'PayPal Fees', value: exchDetails["paypalprice"],
                    additional: parseFloat($ns.paypalcomm*$ns.shippingOpts[$ns.selectedShipping]["additional" + $ns.exchExtension]) || 0
                },
                {name:'shippingCosts', nameA : 'Shipping Costs', valu : exchDetails["shippingprice"],
                    additional: parseFloat($ns.shippingOpts[$ns.selectedShipping]["additional" + $ns.exchExtension]) || 0
                }
            ],
            selected_variant : varArr,
            selected_var_SKU: $ns.selectedVariant!=-1 ? $ns.variations[$ns.selectedVariant]["SKU"] : "",
            store_link: $ns.storeLink,
            available_quantity: getAvailableQuantity(),
            order_limit: $ns.orderLimit,
            delivery_min: $ns.shippingOpts[$ns.selectedShipping]["deliveryMin"]["date"],
            delivery_max: $ns.shippingOpts[$ns.selectedShipping]["deliveryMax"]["date"]
        };

        $ns.data.action = 'ajax_handler';
        $ns.data.method = 'addProduct';
        $ns.data.post = 'product=' + encodeURIComponent(JSON.stringify(product));

        var data = $ns.Utils.getData();
        if(data.success){
            $.publish($ns.events.CART_UPDATE, data.msg);
        }
    }
    function removeFromCart(){

    }

    function toggleID(togg, id, e){
        e.preventDefault();
        if (jQuery(togg).html()=="-") {
            jQuery(id).hide();
            jQuery(togg).html("+");
        }else{
            jQuery(id).show();
            jQuery(togg).html("-");
        }
    }

    function displayPopExchangeRates(jqRef){
        if (jQuery("#exchangeDisplayDiv").is(":visible")){return;}
        var originalPrice = 0;
        var exchangePrice = 0;
        var originalCurrSymbol = 0;

        switch (jqRef.attr("id")) {
            case "itemprice":
                originalPrice = parseFloat($ns.itemPricing["price"]).toFixed(2);
                exchangePrice = parseFloat($ns.itemPricing["price" + $ns.exchExtension]).toFixed(2);
                originalCurrSymbol = $ns.itemPricing["priceSymbol"];
                if ($ns.selectedVariant != -1){
                    originalPrice = parseFloat($ns.variations[$ns.selectedVariant]["price"]).toFixed(2);
                    exchangePrice = parseFloat($ns.variations[$ns.selectedVariant]["price" + $ns.exchExtension]).toFixed(2);
                    originalCurrSymbol = $ns.variations[$ns.selectedVariant]["priceSymbol"];
                }
                break;

            case "shippingprice":
                originalPrice = parseFloat($ns.shippingOpts[$ns.selectedShipping]["price"]).toFixed(2);
                exchangePrice = parseFloat($ns.shippingOpts[$ns.selectedShipping]["price" + $ns.exchExtension]).toFixed(2);
                originalCurrSymbol = $ns.shippingOpts[$ns.selectedShipping]["priceSymbol"];
                break;
        }
        var exchangeRate = parseFloat(exchangePrice/originalPrice).toFixed(2);

        // No need to display anything if price is 0;
        if (originalPrice==0){return;}

        // Update dom.
        jQuery("#originalPrice").html(originalPrice);
        jQuery("#originalCurrSymbol, #originalCurrSymbol2").html(originalCurrSymbol);
        jQuery("#exchangeRate").html(exchangeRate);

        // Show popup and place it above our elems.
        var position = jqRef.position();
        jQuery("#exchangeDisplayDiv").css({
            top:    position.top   -    jQuery("#exchangeDisplayDiv").height(),
            left:   position.left  -    jQuery("#exchangeDisplayDiv").width()+jqRef.width()+40
        }).show();
    }

    function displayCustomInfoPopUp(owner){
        var attachTo = owner,
            InfoControl = jQuery("#infoPopUpDisplayDiv");

        if (InfoControl.is(":visible")){return;}

        jQuery("#PopUpInfotitle").html(attachTo.data("popup-title"));
        jQuery("#PopUpInfocontent").html(attachTo.data("popup-content"));
        jQuery("#PopUpInfofooter").html(attachTo.data("popup-footer"));

        // Show popup and place it above our elems.
        var position = attachTo.position();
        InfoControl.css({
            top:    position.top   -    InfoControl.height(),
            left:   position.left  -    InfoControl.width()+attachTo.width()+40
        }).show();
    }

    // Searches for a specific variation set options. Returns -1 or Variation KEY
    // @param array Array(variation set name => value);
    function searchVariation(search){
        if (Object.keys(search).length<jQuery(".varset").length){return -1}
        var itemfound = -1;
        _.forEach(Object.keys($ns.variations), function(key){
            var matched = -1;
            _.forEach(Object.keys(search), function(searchkey){
                matched = key;
                if ($ns.variations[key]["setInfo"][searchkey] != search[searchkey]) {
                    matched = -1;
                    return false;
                }
            });
            if (matched!=-1){
                itemfound = matched;
                return false;
            }
        });
        return itemfound;
    }

    function getCurrentVarSel(){
        var varArr = {};
        jQuery(".varset").each(function(){
            if (jQuery(this).val()==""){return false;}
            varArr[jQuery(this).data("name")] = jQuery(this).val();
        });
        return varArr;
    }

    function setSelectedVariant(varArr){
        // Set default value.
        varArr = (typeof varArr !== 'undefined') ? varArr : getCurrentVarSel();
        $ns.selectedVariant       = searchVariation(varArr);
    }

    function getSelectedVariantImage(varArr){
        var image = "";
        _.forEach(Object.keys(varArr), function(key){
            var value = varArr[key];

            if (typeof $ns.variationSets[key][value] == 'string'){
                image = $ns.variationSets[key][value];
                return false;
            }
        });
        return image;
    }

    function updateSelectedVariant(jqRef){
        var assocName       = jqRef.data("name");
        var assocVal        = jqRef.val();
        var imgElem         = jQuery(".gallery-thumbnails .item > a[data-assoc=\"" + assocName + "\"][data-assocval=\"" + assocVal + "\"]");
        // Sets the picture for our variation (if available).
        imgElem.click();

        // Set the variation available sets.
        updateVariationSets(assocName);

        // Get our current variant.
        var varArr = getCurrentVarSel();
        $ns.selectedVariant       = searchVariation(varArr);

        // If we have a proper selected variant.
        if ($ns.selectedVariant!=-1) {
            // Set variation details.
            jQuery("#quantityavail").html(parseFloat($ns.variations[$ns.selectedVariant]["quantity"]).toFixed(0));
            jQuery("#quantitysold").html(parseFloat($ns.variations[$ns.selectedVariant]["quantitySold"]).toFixed(0));

            // Set our variation pricing.
            updateProductPrices();
        }
    }

    function updateVariationSets(currentSet){
        var variationsArr   = Object.keys($ns.variationSets);                   // Get our keys.
        var nextIndex       = variationsArr.indexOf(currentSet)+1;          // Start from next set on...
        // Test all sets and hide non existing $ns.variations.
        for (i = nextIndex; i < Object.keys($ns.variationSets).length; i++){
            // Test all options of this set.
            jQuery("#varset_" + i + " > option").show().each(function(){
                var varArr = {};
                varArr[variationsArr[i]] = jQuery(this).html();
                // If we don't have an item with this specific option - hide it.
                if (searchVariation(varArr) == 0) {
                    jQuery(this).hide();
                }
            });
        }
    }

    function getAvailableQuantity(){
        var quantitylimit = parseInt(jQuery("#quantityavail").html());
        if ($ns.selectedVariant != -1){
            quantitylimit = parseInt($ns.variations[$ns.selectedVariant]["quantity"]);
        }
        return quantitylimit;
    }

    function updateShippingOpt(){
        // Set the description text:
        var shippingDet = [];
        if ($ns.shippingOpts[$ns.selectedShipping]["deliveryMin"]["date"]){
            shippingDet.push("Estimated delivery " +
                "<b>" +
                $ns.shippingOpts[$ns.selectedShipping]["deliveryMin"]["date"] +
                "</b> and <b>" +
                $ns.shippingOpts[$ns.selectedShipping]["deliveryMax"]["date"] +
                "</b>" +
                " <span id=\"shippingdays\">(" +
                $ns.shippingOpts[$ns.selectedShipping]["deliveryMin"]["days"] +
                "-" +
                $ns.shippingOpts[$ns.selectedShipping]["deliveryMax"]["days"] +
                ") </span>"
            );
        }else{
            shippingDet.push("Estimated delivery varies for items shipped from an international location");
        }
        if ($ns.shippingOpts[$ns.selectedShipping]["additional"]){
            if ($ns.shippingOpts[$ns.selectedShipping]["additional"]=="0.0"){
                shippingDet.push("FREE Shipping for additional items!");
            }else {
                shippingDet.push("Additional item cost: " + $ns.exchSymbol + parseFloat($ns.shippingOpts[$ns.selectedShipping]["additional" + $ns.exchExtension]).toFixed(2));
            }
        }
        if ($ns.shippingOpts[$ns.selectedShipping]["insurance"]){
            shippingDet.push("Shipping insurance cost: " + $ns.exchSymbol + parseFloat($ns.shippingOpts[$ns.selectedShipping]["insurance" + $ns.exchExtension]).toFixed(2));
        }
        if ($ns.shippingOpts[$ns.selectedShipping]["duty"]){
            shippingDet.push("Import duty cost: " + $ns.exchSymbol + parseFloat($ns.shippingOpts[$ns.selectedShipping]["duty" + $ns.exchExtension]).toFixed(2));
        }
        // Display the shipping details.
        jQuery("#shippingcostsdets").html(shippingDet.join("<br />"));
        // Set the price value.
        jQuery("#shippingprice").html($ns.exchSymbol + parseFloat($ns.shippingOpts[$ns.selectedShipping]["price" + $ns.exchExtension]).toFixed(2));
    }

    function updateProductPrices(){
        var exchDetails = getProductPricesDetails($ns.exchExtension);

        // Check for errors.
        if (Object.keys(exchDetails).length==0){return;}

        // Display the prices in their container.
        Object.keys(exchDetails).forEach(function(key){
            // On page output.
            var priceOutput = (isNaN(parseFloat(exchDetails[key]))) ? "-" : $ns.exchSymbol + parseFloat(exchDetails[key]).toFixed(2);
            jQuery("#" + key).html(priceOutput);
        });
    }

    function getProductPricesDetails(pricetype, orderquantity){
        // Make sure the user have chosen his shipping option before we go on.
        if ($ns.selectedShipping==-1){
            jQuery("#shippmentdiv").effect("highlight", 1500);
            return [];
        }

        // Set default values.
        pricetype = (typeof pricetype !== 'undefined') ? pricetype : "";
        orderquantity = (typeof orderquantity !== 'undefined') ? orderquantity : parseInt( jQuery("#orderquantity").val() );

        // Load these from admin panel;
        //var storecomm = 10/100;
        //var minstorecomm = 5
;
        // Get shipping details.
        var shippingprice       = parseFloat( $ns.shippingOpts[$ns.selectedShipping]["price" + pricetype]);
        var shippingadditional  = parseFloat( $ns.shippingOpts[$ns.selectedShipping]["additional" + pricetype]);
        var shippingduty        = parseFloat( $ns.shippingOpts[$ns.selectedShipping]["duty" + pricetype]);
        var shippinginsurance   = parseFloat( $ns.shippingOpts[$ns.selectedShipping]["insurance" + pricetype]);

        // Get item pricing details.
        var itemprice           = parseFloat( $ns.itemPricing["price" + pricetype] );
        // If we have a specific variant use it's details.
        if ($ns.selectedVariant!=-1){
            // set variation details.
            itemprice           = parseFloat( $ns.variations[$ns.selectedVariant]["price" + pricetype] );
        }

        // Make some calcs.
        // Sum our shipping costs.
        shippingprice += (orderquantity>1) ? (orderquantity-1)*shippingadditional : 0;
        // Add our duty costs if applicable.
        shippingprice += (shippingduty>0) ? shippingduty : 0;
        // Add our insurance costs if applicable.
        shippingprice += (shippinginsurance>0) ? shippinginsurance : 0;

        // Calc item cost by order quantity.
        var allitemsprice = itemprice*orderquantity;
        var outputArr = {};
        // Single item price, before any modifiers.
        outputArr["itemprice"] = itemprice;
        // Total shipping costs - shipping+(additional*quantity)+duty+insurance.
        outputArr["shippingprice"] = shippingprice;
        // Paypal comminsion * item price + shipping costs + store commision.
        outputArr["paypalprice"] =$ns. paypalcomm*(allitemsprice+shippingprice);//+outputArr["storeprice"]);
        // Final price = item(s) price + shipping + paypal + store.
        outputArr["finalPrice"] = allitemsprice+shippingprice+outputArr["paypalprice"];//+outputArr["storeprice"];
        // Store comminsion * finalPrice. [if lower then minimum, set to minimum].
        // outputArr["storeprice"] = (storecomm*(outputArr["finalPrice"])>minstorecomm) ? storecomm*(outputArr["finalPrice"]) : minstorecomm;
        // Total price per item = final price/quantity.
        outputArr["totalprice"] = outputArr["finalPrice"]/orderquantity;

        return outputArr;
    }

});