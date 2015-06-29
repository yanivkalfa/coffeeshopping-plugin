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

    public static function addAddress($address){
        if(!is_array($address)) return false;
        return self::insertItem($address, 'cs_addresses');
    }

    public static function deleteAddress($address_id){
        if(!$address_id) return false;
        return self::deleteItem(array('ID' => $address_id), 'cs_addresses');
    }
}