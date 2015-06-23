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
        $sql = "SELECT *, ( 3959 * acos( cos( radians($lat) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( lat ) ) ) ) AS distance FROM $table_name ORDER BY distance LIMIT 1;";

        return $wpdb->get_row($sql, ARRAY_A);
    }

}