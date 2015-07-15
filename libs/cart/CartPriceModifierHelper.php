<?php
/*
* @ Cart helper
*/

abstract class CartPriceModifierHelper {
    public static $collection;

    public static function initCollection($modifiers){
        $collectionInit = array(
            'colName' => 'modifiers',
            'unique' => true,
            'prop' => 'name',
            'collection' => $modifiers
        );
        self::$collection = new Collection($collectionInit);
    }

    public static function get($modifiers, $item = null){
        self::initCollection($modifiers);
        return self::$collection->get($item);
    }

    public static function add(&$modifiers, $item){
        self::initCollection($modifiers);
        $res = self::$collection->add($item);
        $modifiers = self::$collection->get();
        return $res;
    }

    public static function update(&$modifiers, $item){
        self::initCollection($modifiers);
        $res = self::$collection->update($item);
        $modifiers = self::$collection->get();
        return $res;
    }

    public static function remove(&$modifiers, $item){
        self::initCollection($modifiers);
        $res = self::$collection->remove($item);
        $modifiers = self::$collection->get();
        return $res;
    }
}