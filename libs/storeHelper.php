<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/23/2015
 * Time: 8:49 PM
 */

abstract class storeHelper{

    public static function getClosestStore($lat, $lng){
        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_stores';
        $sql = "SELECT ID, ( 3959 * acos( cos( radians($lat) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( lat ) ) ) ) AS distance FROM $table_name ORDER BY distance LIMIT 1;";
        $result = $wpdb->get_row($sql, ARRAY_A);
        return $result["ID"];
    }

    public static function getStores($ids){
        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_stores';
        return $wpdb->get_results("SELECT * FROM $table_name WHERE `ID` IN (".implode(",", $ids).")", ARRAY_A);
    }

    public static function getStore($id){
        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_stores';
        return $wpdb->get_row("SELECT * FROM $table_name WHERE `ID` = '$id'", ARRAY_A);
    }

    public static function insertStore($store){
        if(!isset($store)) return false;

        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_stores';
        return $wpdb->insert( $table_name, $store) ? $wpdb->insert_id : false;
    }


    public static function updateStore($store){
        if(!isset($store)) return false;

        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_stores';
        return $wpdb->update($table_name, $store, array( 'ID' => $store['ID'] )) !== false ? $store['ID'] : false;
    }

    public static function deleteStore($store){
        if(!isset($item)) return false;

        global $wpdb;
        $table_name = $wpdb->prefix . 'cs_stores';
        return $wpdb->delete($table_name, $store);
    }
}