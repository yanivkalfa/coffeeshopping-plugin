<?php
/*
* @ CoffeeShopping Constants
*/

abstract class CSCons {

    public static function get($consName){
        if(!$consName) {
            return false;
        }

        $fileName = BASE_ADDRESS . '/configs/available_constants/' . $consName .'.php';

        if(!file_exists($fileName)) {
            return false;
        }

        include($fileName);
        return isset($value) ? $value : false;
    }
}