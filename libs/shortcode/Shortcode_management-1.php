<?php
class Shortcode_management{
	
	public function __construct(){
        add_shortcode('coffeeshoppinglist', array($this, 'productsListShortCode'));
  	}

    public function productsListShortCode($atts){
        $result = productRecommendations::getProductListByType($atts);
        // Output results if we have any proper ones, else display errors.
        if ($result["result"]!="OK") {
            // Failed to get the products.
            Utils::adminPreECHO(__( "productsListShortCode::productRecommendations::getProductListByType(...) failed!", 'coffee-shopping' ), __( "productsListShortCode ERROR:: ", 'coffee-shopping' ));
            $scope = array(
                "errorsText" => "Failed to load the products, please check your details!"
            );
            ob_start();
            Utils::getTemplate('ProductsListError', $scope);
            return ob_get_clean();

        } else {

            if (count($result["output"])==0){
                Utils::adminPreECHO("Results 'OK' but nothing retrieved from server!");
                return false;
            }

            // Everything is OK - Load the featured products template.
            $scope = array(
                'products' => $result["output"],
            );
            ob_start();
            Utils::getTemplate('ProductsList', $scope);
            return ob_get_clean();
        }
    }


}
$coffeeshopping_shortcodes = new Shortcode_management();



/* ------------------- DEAD OR UNUSED CODE ---------------- */
/*

*/
/* ------------------- DEAD OR UNUSED CODE ---------------- */

