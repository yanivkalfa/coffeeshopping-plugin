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
        updateQuantity();
        updateProductPrices();
    });

    // Handle our onHover popUps.
    jQuery("#itemprice, #shippingprice").mouseover(function(){
        displayPopExchangeRates(jQuery(this));
    }).mouseout(function(){
        jQuery("#exchangeDisplayDiv").hide();
    });

    // Handle togglers.
    jQuery("#exchangeDisplayDiv").hide();
    jQuery("#quicksummary").hide();
    jQuery("#orderdetailstogg").click(function(e){toggleID(this, "#quicksummary", e)});
    jQuery("#DebugProductOutput").hide();
    jQuery("#debugOutPutTogg").click(function(e){toggleID(this, "#DebugProductOutput", e)});
    // Scroll our details panel to the left to display contents in case of need.
    jQuery("#detailspaenl").scrollLeft(0);

    // Handle 'Add to cart'
    jQuery("#buynowbuttondiv").click(function(){
        if (jQuery(this).data("addedtocart")==1){
            jQuery(this).data("addedtocart", "0");
            jQuery(this).html("Add to cart").removeClass("cartremove");
            removeFromCart();
        }else{
            jQuery(this).data("addedtocart", "1");
            addToCart();
            jQuery(this).html("Remove from cart").addClass("cartremove");
        }

    });

    // ON-LOAD - get the defaults.
    updateShippingOpt();
    updateProductPrices();

    function addToCart(){
        var exchDetails = getProductPricesDetails($ns.exchExtension);
        var product = {
            unique_store_id : $ns.productID,
            store : $ns.store,
            img : $ns.productPic,
            title : $ns.productTitle,
            price : exchDetails["itemprice"],
            quantity : jQuery("#orderquantity").val(),
            price_modifiers : [
                {name:'storeCommission', nameAs : 'Store Commission', value : exchDetails["storeprice"]},
                {name:'PayPalFees', nameAs : 'PayPal Fees', value : exchDetails["paypalprice"]},
                {name:'shippingCosts', nameAs : 'Shipping Costs', value : exchDetails["shippingprice"]}
            ],
            selected_var : getCurrentVarSel(),
            selected_var_SKU: $ns.selectedVariant!=-1 ? $ns.variations[$ns.selectedVariant]["SKU"] : "",
            store_link: $ns.storeLink
        };

        $ns.data.action = 'ajax_handler';
        $ns.data.method = 'addProduct';
        $ns.data.post = 'product=' + encodeURIComponent(JSON.stringify(product));

        var data = $ns.Utils.getData();
        if(data.success){
            console.log(data);
            $.publish($ns.events.CART_UPDATE, data.msg);
            jQuery("#buynowbuttondiv").data("addedtocart", "1");
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

    // Searches for a specific variation set options. Returns 0 or Variation KEY
    // @param array Array(variation set name => value);
    function searchVariation(search){
        //console.log(search);
        var itemfound = 0;
        Object.keys($ns.variations).forEach(function(key){
            if (itemfound!=0){return -1;}
            var available = true;
            Object.keys(search).forEach(function(searchkey){
                if ($ns.variations[key]["setInfo"][searchkey] != search[searchkey]) {
                    available = false;
                    return -1;
                }
            });
            if (available==true){
                itemfound = key;
                return -1;
            }
        });

        return itemfound;
    }

    function getCurrentVarSel(){
        var varArr = [];
        jQuery(".varset").each(function(){
            varArr[jQuery(this).data("name")] = jQuery(this).val();
        });
        return varArr;
    }

    function setSelectedVariant(varArr){
        // Set default value.
        varArr = (typeof varArr !== 'undefined') ? varArr : getCurrentVarSel();
        $ns.selectedVariant       = searchVariation(varArr);
    }

    function updateSelectedVariant(jqRef){
        var assocName       = jqRef.data("name");
        var assocVal        = jqRef.val();

        // Get our current variant.
        var varArr = []; varArr[assocName] = assocVal;
        $ns.selectedVariant       = searchVariation(varArr);

        // Set variation details.
        jQuery("#quantityavail").html(parseFloat( $ns.variations[$ns.selectedVariant]["quantity"]).toFixed(0));
        jQuery("#quantitysold").html(parseFloat( $ns.variations[$ns.selectedVariant]["quantitySold"]).toFixed(0));

        // Set our variation pricing.
        updateProductPrices();

        // Sets the picture for our variation (if available).
        jQuery(".gallery-thumbnails .item > a[data-assoc=\"" + assocName + "\"][data-assocval=\"" + assocVal + "\"]").click();

        // Set the variation available sets.
        updateVariationSets(assocName);
    }

    function updateVariationSets(currentSet){
        var variationsArr   = Object.keys($ns.variationSets);                   // Get our keys.
        var nextIndex       = variationsArr.indexOf(currentSet)+1;          // Start from next set on...
        // Test all sets and hide non existing $ns.variations.
        for (i = nextIndex; i < Object.keys($ns.variationSets).length; i++){
            // Test all options of this set.
            jQuery("#varset_" + i + " > option").show().each(function(){
                var varArr = [];
                varArr[variationsArr[i]] = jQuery(this).html();
                // If we don't have an item with this specific option - hide it.
                if (searchVariation(varArr) == 0) {
                    jQuery(this).hide();
                }
            });
        }
    }

    function updateQuantity(){
        var jqRef = jQuery("#orderquantity");
        var quantitylimit = jQuery("#quantityavail").html();
        if ($ns.selectedVariant != -1){
            quantitylimit = parseInt($ns.variations[$ns.selectedVariant]["quantity"]);
        }
        if (quantitylimit > $ns.maxItemsOrder){quantitylimit = $ns.maxItemsOrder;}
        if (jqRef.val() < 1){
            jqRef.effect("highlight", 1500);
            jqRef.val(1);
            return;
        }
        if(parseInt(jqRef.val()) > parseInt(quantitylimit)){
            jqRef.effect("highlight", 1500);
            jqRef.val(quantitylimit);
        }
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

    function getProductPricesDetails(pricetype){
        // Make sure the user have chosen his shipping option before we go on.
        if ($ns.selectedShipping==-1){
            jQuery("#shippmentdiv").effect("highlight", 1500);
            return [];
        }

        // Set default value.
        pricetype = (typeof pricetype !== 'undefined') ? pricetype : "";

        // Load these from admin panel.
        var paypalcomm = parseFloat(3.5/100);
        var storecomm = 10/100;
        var minstorecomm = 5;
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
        var orderquantity       = parseFloat( jQuery("#orderquantity").val() );

        // Make some calcs.
        // Sum our shipping costs.
        shippingprice += (orderquantity>1) ? (orderquantity-1)*shippingadditional : 0;
        // Add our duty costs if applicable.
        shippingprice += (shippingduty>0) ? shippingduty : 0;
        // Add our insurance costs if applicable.
        shippingprice += (shippinginsurance>0) ? shippinginsurance : 0;

        // Calc item cost by order quantity.
        var allitemsprice = itemprice*orderquantity;
        var outputArr = [];
        // Single item price, before any modifiers.
        outputArr["itemprice"] = itemprice;
        // Total shipping costs - shipping+(additional*quantity)+duty+insurance.
        outputArr["shippingprice"] = shippingprice;
        // Store comminsion * item price + shipping costs. [if lower then minimum, set to minimum].
        outputArr["storeprice"] = (storecomm*(allitemsprice+shippingprice)>minstorecomm) ? storecomm*(allitemsprice+shippingprice) : minstorecomm;
        // Paypal comminsion * item price + shipping costs + store commision.
        outputArr["paypalprice"] = paypalcomm*(allitemsprice+shippingprice+outputArr["storeprice"]);
        // Final price = item(s) price + shipping + paypal + store.
        outputArr["finalPrice"] = allitemsprice+shippingprice+outputArr["paypalprice"]+outputArr["storeprice"];
        // Total price per item = final price/quantity.
        outputArr["totalprice"] = outputArr["finalPrice"]/orderquantity;

        return outputArr;
    }

});