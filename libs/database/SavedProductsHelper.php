<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 7/10/2015
 * Time: 7:36 PM
 */

abstract class SavedProductsHelper extends SuperDatabaseHelper{

    public static function getSavedProductsLists(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_saved_products';
        return $wpdb->get_results("SELECT DISTINCT `listname` FROM $table_name WHERE 1", ARRAY_A);
    }

    /**
     * @func getSavedProducts($identifier, $values)
     *  - Gets an array of product arrays according to the criteria specified.
     * @param   $identifier    string   - The requested identifier field in the saved_products table.
     * @param   $values        array    - The requested value(s) for that identifier.
     * @return
     */
    public static function getSavedProducts($identifier, $values){
        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_saved_products';
        return $wpdb->get_results("SELECT * FROM $table_name WHERE `$identifier` IN ('" . implode("','", $values) . "')", ARRAY_A);
    }

    /**
     * @func getSavedProduct($id)
     *  - Gets a specific product by a given ID.
     * @param   $id     int     - product ID.
     * @return          mixed   - product array / false.
     */
    public static function getSavedProduct($id){
        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_saved_products';
        return $wpdb->get_row("SELECT * FROM $table_name WHERE `ID` = '$id'", ARRAY_A);
    }

    /**
     * @func insertSavedProduct($product)
     *  - Inserts a new product to the saved products database.
     * @param   $product    array   - product array.
     * @return              bool
     */
    public static function insertSavedProduct($product){
        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_saved_products';
        return $wpdb->insert( $table_name, $product) ? $wpdb->insert_id : false;
    }

    /**
     * @func updateSavedProduct($product)
     *  - Updates a product in the saved products db.
     * @param   $product    array   - product array.
     * @return              bool
     */
    public static function updateSavedProduct($product){
        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_saved_products';
        return $wpdb->update($table_name, $product, array( 'ID' => $product['ID'] )) !== false ? $product['ID'] : false;
    }

    /**
     * @func deleteProduct($productWhere)
     *  - Updates a product in the products db.
     * @param   $productWhere       array   - an array of column-value pairs to that indicates which product to delete, eg. Array('ID'=>1);
     * @return                      bool
     */
    public static function deleteProduct($productWhere){
        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_saved_products';
        return $wpdb->delete($table_name, $productWhere);
    }

    /**
     * @func getStoreStructure()
     *  - Get an example of the product array format.
     * @return  array|false
     */
    public static function getProductStructure(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_saved_products';
        $result = $wpdb->get_results("Describe '$table_name';", ARRAY_A);
        if (!$result){return false;}

        // Build our store Array example.
        $productArray = array();
        foreach($result as $row){
            $productArray[$row["Field"]] = $row["Type"];
        }
        return $productArray;
    }
}