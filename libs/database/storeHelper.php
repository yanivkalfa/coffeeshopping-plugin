<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/23/2015
 * Time: 8:49 PM
 */

abstract class storeHelper{

    /**
     * @func getStoreMapImg($store)
     *  - Returns a link to the store's map image.
     * @param   $store  array   - store array.
     * @return          string  - URL to the image file.
     */
    public static function getStoreMapImg($store, $width = 200, $height = 200){
        $path = dirname(__FILE__)."/../../css/images/";
        $filename = 'storemapimage-'.$store["ID"].'-'.$width.'x'.$height.'.png';
        $storeMapImage = plugins_url( '/../../css/images/'.$filename, __FILE__ );
        // Check if we have an image for that store
        if (file_exists($path.$filename)){return $storeMapImage;}
        // If we don't have the file - get it from google! :)
        $iconURL = "http://oi57.tinypic.com/20h2e4y.jpg"; // TODO:: Change to an image hosted on our server.
        $storeLocation = $store["lat"].",".$store["lng"];
        $marker = 'icon:'.urlencode($iconURL."|".$storeLocation);
        $googleStaticMapsAPIURL = "https://maps.googleapis.com/maps/api/staticmap?language=he&center=$storeLocation&zoom=15&size=".$width."x".$height."&scale=1&format=png32&markers=$marker";
        file_put_contents($path.$filename, file_get_contents($googleStaticMapsAPIURL));
        return $storeMapImage;
    }

    /**
     * @func getStoreGoogleMapsEmbed($store)
     *  - Returns the 'src' to use when embedding 'Google maps embed API' iframe.
     * @param   $storeAddress   array   - store address.
     * @return                  string  - 'src' attribute for the iframe.
     */
    public static function getStoreGoogleMapsEmbed($storeAddress){
        $mapsAPIKey = "AIzaSyDJ-x2RfRCj_wjm0gPO-VW4ZEIheV1EWhE"; // TODO:: Get this thing loaded from the admin crap.
        $embedSrc = "https://www.google.com/maps/embed/v1/place?key=$mapsAPIKey";
        $embedSrc .= "&zoom=15";
        $embedSrc .= "&language=he";
        $embedSrc .= "&region=il";
        $embedSrc .= "&attribution_source=CoffeeShopping Google Maps";
        $embedSrc .= "&q=".urlencode($storeAddress);

        return $embedSrc;
    }

    /**
     * @func getClosestStore($lat, $lng)
     *  - Gets the store ID of the closest store to the given coordinates.
     * @param $lat      float   - latitude coordinates.
     * @param $lng      float   - longitude coordinates
     * @return          mixed   - false / ID.
     */
    public static function getClosestStore($lat, $lng){
        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_stores';
        $sql = "SELECT ID, ( 3959 * acos( cos( radians($lat) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( lat ) ) ) ) AS distance FROM $table_name ORDER BY distance LIMIT 1;";
        $result = $wpdb->get_row($sql, ARRAY_A);
        return $result["ID"];
    }

    /**
     * @func getStores($ids)
     *  - Get an array of arrays of all stores.
     * @param   $ids    array   - The requested stores IDs in a flat array, if empty - gets all.
     * @return          mixed
     */
    public static function getStores($ids){
        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_stores';
        if (empty($ids)){
            return $wpdb->get_results("SELECT * FROM $table_name WHERE 1", ARRAY_A);
        }else {
            return $wpdb->get_results("SELECT * FROM $table_name WHERE `ID` IN (" . implode(",", $ids) . ")", ARRAY_A);
        }
    }

    /**
     * @func getStore($id)
     *  - Gets a specific store details by a given ID.
     * @param   $id     int     - store ID.
     * @return          mixed   - store array / false.
     */
    public static function getStore($id){
        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_stores';
        return $wpdb->get_row("SELECT * FROM $table_name WHERE `ID` = '$id'", ARRAY_A);
    }

    /**
     * @func insertStore($store)
     *  - Inserts a new store to the stores database.
     * @param   $store  array   - store array.
     * @return          bool
     */
    public static function insertStore($store){
        if(!isset($store)) return false;

        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_stores';
        return $wpdb->insert( $table_name, $store) ? $wpdb->insert_id : false;
    }

    /**
     * @func updateStore($store)
     *  - Updates a store in the stores db.
     * @param   $store  array   - store array.
     * @return          bool
     */
    public static function updateStore($store){
        if(!isset($store)) return false;

        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_stores';
        return $wpdb->update($table_name, $store, array( 'ID' => $store['ID'] )) !== false ? $store['ID'] : false;
    }

    /**
     * @func deleteStore($storeWhere)
     *  - Updates a store in the stores db.
     * @param   $storeWhere     array   - an array of column-value pairs to that indicates which store to delete, eg. Array('ID'=>1);
     * @return                  bool
     */
    public static function deleteStore($storeWhere){
        if(!isset($item)) return false;

        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_stores';
        return $wpdb->delete($table_name, $storeWhere);
    }

    /**
     * @func getStoreStructure()
     *  - Get an example of the store array format.
     * @return  array|false
     */
    public static function getStoreStructure(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_stores';
        $result = $wpdb->get_results("Describe '$table_name';", ARRAY_A);
        if (!$result){return false;}

        // Build our store Array example.
        $storeArray = array();
        foreach($result as $row){
            $storeArray[$row["Field"]] = $row["Type"];
        }
        return $storeArray;
    }
}