<?php
/*
* @ Cart Database queiry
*/

abstract class AddressDatabaseHelper extends SuperDatabaseHelper {

    /**
     * getAddress by addressId
     *
     * @param {number} $userId
     * @return bool|array
     */
    public static function getAddress($addressId){
        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_addresses';
        return $wpdb->get_row("SELECT * FROM $table_name WHERE `ID` = '$addressId'", ARRAY_A);
    }

    public static function addAddress(){
        if(!$hasCart){
            global $wpdb;
            $table_name = $wpdb->prefix . 'cs_addresses';
            CartDatabaseHelper::deleteItem(array('ID' => $address_id), $table_name);
        }
    }

    public static function deleteAddress(){
        if(!$hasCart){
            global $wpdb;
            $table_name = $wpdb->prefix . 'cs_addresses';
            CartDatabaseHelper::deleteItem(array('ID' => $address_id), $table_name);
        }
    }
}