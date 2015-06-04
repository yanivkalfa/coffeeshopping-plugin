<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 5/19/2015
 * Time: 1:55 AM
 *
 * Functions list:
 * _______________
 * getSearch() - Searches eBay.
 * getProducts() - Gets a multiple item details. (malfunctions?!)
 * getProduct() - Gets a single item details.
 *
 */

class ebayAdapter {
    // API variables
    public $appid;                  // Our appID.

    // Basic construct function.
    public function __construct( ){
        $this->appid = "coffeesh-b71a-4145-bb26-3a4b57d7a787";          // Get the appid from the DB.
    }

    /**
     * @func _formCurlHeaders($headersArray)
     *  - Reforms our headers to fit CURL specs.
     * @param array $headersArray - Key:Val set of headers to combine.
     * @return array $headersProper - properly formatted headers for cURL.
     */
    public function _formCurlHeaders($headersArray){
        $headersProper = array();
        foreach($headersArray as $key => $val){
            $headersProper[] = $key . ": " . $val;
        }
        return $headersProper;
    }

    /*
     * @func getPrettyTimeFromEbayTime($eBayTimeString)
     *  - Transforms eBay's time string into readable time.
     * @param string $eBayTimeString
     * @return string $retnStr - Proper time string.
     */
    public function getPrettyTimeFromEbayTime($eBayTimeString){
        // Input is of form 'PT12M25S'
        $matchAry = array(); // initialize array which will be filled in preg_match
        $pattern = "#P([0-9]{0,3}D)?T([0-9]?[0-9]H)?([0-9]?[0-9]M)?([0-9]?[0-9]S)#msiU";
        preg_match($pattern, $eBayTimeString, $matchAry);

        $days  = (int) $matchAry[1];
        $hours = (int) $matchAry[2];
        $min   = (int) $matchAry[3];    // $matchAry[3] is of form 55M - cast to int
        $sec   = (int) $matchAry[4];

        $retnStr = '';
        if ($days)  { $retnStr .= " $days day"   . $this->pluralS($days);  }
        if ($hours) { $retnStr .= " $hours hour" . $this->pluralS($hours); }
        if ($min)   { $retnStr .= " $min minute" . $this->pluralS($min);   }
        if ($sec)   { $retnStr .= " $sec second" . $this->pluralS($sec);   }

        return $retnStr;
    } // function

    /*
     * @func pluralS($intIn)
     *  - Adds an 's' to make things plural in case of need.
     * @param integer a number to indicate if we need an 's'.
     * @return string - an additional 's' or empty.
     */
    public function pluralS($intIn) {
        // if $intIn > 1 return an 's', else return null string
        if ($intIn > 1) {
            return 's';
        } else {
            return '';
        }
    } // function

}
// Require the extensions.
require_once dirname(__FILE__)."/api_ebay_finding.php";
if (!class_exists("ebay_FindingAPI")){echo "Failed to load ebay_FindingAPI class!";}
require_once dirname(__FILE__) . "/api_ebay_product.php";
if (!class_exists("ebay_ShoppingAPI")){echo "Failed to load ebay_ShoppingAPI class!";}
require_once dirname(__FILE__) . "/api_ebay_utils.php";
if (!class_exists("ebay_Utils")){echo "Failed to load ebay_Utils class!";}



/* ------------------- DEAD OR UNUSED CODE ---------------- */

/* ------------------- DEAD OR UNUSED CODE ---------------- */
?>