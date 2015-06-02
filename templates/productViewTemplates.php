<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 5/26/2015
 * Time: 2:49 PM
 */

abstract class productViewTemplates {

    static public function getProductView($product){
        ob_start();
        ?>

        <div id="topcontainer">
            <div id="toppicturepanel">

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