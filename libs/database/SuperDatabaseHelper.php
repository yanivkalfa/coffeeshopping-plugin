<?php
/*
* @ Cart Database queiry
*/

abstract class SuperDatabaseHelper {

    /**
     * basic abstract function that insert item to db.
     * @param {array} $item
     * @param {string} $toTable
     * @return false|number
     */
    public static function insertItem($item, $toTable) {
        if(!isset($item)) return false;

        global $wpdb;
        $table_name = $wpdb->prefix . $toTable;
        return $wpdb->insert( $table_name, $item) ? $wpdb->insert_id : false ;
    }

    /**
     * basic abstract function that update an item on db.
     * @param {array} $item
     * @param {string} $toTable
     * @return false|number
     */
    public static function updateItem($item, $toTable) {
        if(!isset($item)) return false;

        global $wpdb;
        $table_name = $wpdb->prefix . $toTable;
        return $wpdb->update($table_name, $item, array( 'ID' => $item['ID'] )) !== false ? $item['ID'] : false;
    }

    /**
     * basic abstract function that delete an item from db.
     * @param {array} $item
     * @param {string} $toTable
     * @return false|number
     */
    public static function deleteItem($item, $toTable) {
        if(!isset($item)) return false;

        global $wpdb;
        $table_name = $wpdb->prefix . $toTable;
        return $wpdb->delete($table_name, $item);
    }
}