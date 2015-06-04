<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 5/26/2015
 * Time: 2:49 PM
 */

abstract class productViewTemplates {
    /**
     * @func getProductErrorContent($msg)
     *  - Returns a HTML ready formatted search result error to display.
     * @param   string  $msg          - The message to put inside the template.
     * @return  string  HTML error page.
     */
    static public function getProductErrorContent($msg){
        ob_start();
        ?>

        <div class="searchresulterror">
            <?php echo $msg?>
        </div>

        <?php
        return ob_get_clean();
    }

    /**
     * @func getProductView($product)
     *  - Returns a HTML ready formatted product page view content.
     * @param   object  $product         - $ObjProduct object.
     * @return  string  HTML product page.
     */
    static public function getProductView($product){
        ob_start();
        ?>
        <script language="javascript" type="text/javascript">
            // use load event on window to start the script
            jQuery(window).load(function(){
                var previews 	= jQuery('.full-image a'),          // image previews
                    thumbnails 	= jQuery('.gallery-thumbnails a');  // small thumbnails for changing previews

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
                })

            });
        </script>
        <div id="topcontainer">
            <div id="toppicturepanel">
                <div class="container">
                    <div class="full-image">
                        <a href="pictures/1.jpg" class="zoomIt visible"><img src="<?php echo $product->img;?>" alt="" /></a>
                        <a href="pictures/2.jpg" class="zoomIt hidden"><img src="pictures/tn_2.jpg" alt="" /></a>
                        <a href="pictures/3.jpg" class="zoomIt hidden"><img src="pictures/tn_3.jpg" alt="" /></a>
                        <a href="pictures/4.jpg" class="zoomIt hidden"><img src="pictures/tn_4.jpg" alt="" /></a>
                    </div>
                    <ul class="gallery-thumbnails">
                        <li><a href="#"><img src="pictures/tn_1.jpg" width="50" alt="" /></a></li>
                        <li><a href="#"><img src="pictures/tn_2.jpg" width="50" alt="" /></a></li>
                        <li><a href="#"><img src="pictures/tn_3.jpg" width="50" alt="" /></a></li>
                        <li><a href="#"><img src="pictures/tn_4.jpg" width="50" alt="" /></a></li>
                    </ul>
                </div>
            </div>
            <div id="topdetailspanel">

            </div>

        </div>

        <div id="detailscontainer">
            <div id="detailspaenlbutton">
                Product details
            </div>
            <div id="shippingpanelbutton">
                Shipping Info
            </div>

            <div id="detailspaenl">

            </div>
            <div id="shippingpanel">

            </div>


        </div>


        <?php
        return ob_get_clean();
    }
}

?>