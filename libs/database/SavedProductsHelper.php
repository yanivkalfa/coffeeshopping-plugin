<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 7/10/2015
 * Time: 7:36 PM
 */

abstract class SavedProductsHelper extends SuperDatabaseHelper{

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
        return $wpdb->get_results("SELECT * FROM $table_name WHERE `$identifier` IN (" . implode(",", $values) . ")", ARRAY_A);
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

    /**
     * @func insertSavedProductsList($IDs, $store, $listname)
     *  - Gets and inserts a list of products to the saved products database list.
     * @param $IDs          -   array   -   an array of id's to get.
     * @param $store        -   string  -   an API identifier.
     * @param $listname     -   string  -   to which list should we insert these.
     * @return              -   bool    -   true/false;
     */
    function insertSavedProductsList($IDs, $store, $listname){
        if (isset($IDs) && !empty($IDs) && isset($store) && !empty($store)) {
            // Our options array. //TODO:: when we'll have more APIs, add a function to get these properly.
            $itemOpts = array(
                'IncludeSelector' => explode(",", "Details")
            );
            // performs the actual request.
            $result = productView::getProducts($store, $IDs, $itemOpts);

            // Output results if we have any proper ones, else display errors.
            if ($result["result"] == "ERROR") {
                // Failed to get the products.
                Utils::adminPreECHO(__( "featuredProductsWidget::productView::getProducts(...) failed!", 'coffee-shopping' ), __( "featuredProductsWidget ERROR:: ", 'coffee-shopping' ));
                Utils::adminPreECHO($result["output"]);

            } else {
                foreach($result["output"] as $product){
                    // Form a proper array
                    $productsArr = array(
                        "productID"         => $product->ID,
                        "store"             => $store,
                        "title"             => $product->title,
                        "image"             => $product->pics[0]["picURL"],
                        "price"             => $product->price,
                        "priceCurrency"     => $product->priceCurrency,
                        "shipping"          => $product->shipping,
                        "shippingCurrency"  => $product->shippingCurrency,
                        "listname"          => $listname,
                    );

                    // Get the current ID's for this list:

                    // Insert the new product to the db.
                    $result = SavedProductsHelper::insertSavedProduct($productsArr);
                    if (!$result){
                        Utils::adminPreECHO($productsArr, "Failed to insert the saved product to the DB, product:");
                    }

                }
            }
        }else{
            // No products or bad store.
            Utils::adminPreECHO(__( "No featured product IDs where set!", 'coffee-shopping' ), __( "featuredProductsWidget ERROR:: ", 'coffee-shopping' ));
            return false;
        }
        return true;
    }
}