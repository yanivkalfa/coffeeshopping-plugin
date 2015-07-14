<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/4/2015
 * Time: 8:21 PM
 */

abstract class ebay_Utils{
    static public function isProductID($test){
        if (ctype_digit ($test) && strlen($test)>8){
            return true;
        }
    }

    static public function getEbayPicture($link, $size){

        // h - height, w - width, wh - either, fb - fit-box, exactsize, original.
        $sizes = array(
            "400h"          => "1",
            "200h"          => "2",
            "800h"          => "3",
            "480h"          => "4",
            "75h"           => "5",

            "42w"           => "13",
            "60w"           => "28",
            "300w"          => "29",

            "60wh"           => "22",
            "64wh"           => "14",
            "70wh"           => "6",
            "80wh"           => "17",
            "150wh"           => "7",
            "400wh"           => "19",
            "500wh"           => "12",

            "200x150"           => "9",
            "original"           => "10",
            "310x90"           => "11",
            "400x300"         => "16",

            "125x225fb"           => "15",
      );
        $regexp = "/\\$\\_\\d+\\.JPG.+/i";
        $replace = "\$_".$sizes[$size].".JPG";
        return preg_replace($regexp, $replace, $link, 1);
    }

    static public function getDeliveryTime($time){
        if (strtotime($time)==false){return $time;}
        $regexp = "/T.+/i";
        $replace = "";
        $date = new DateTime(preg_replace($regexp, $replace, $time, 1));
        return $date->format("M. jS");
    }

    static public function getDeliveryTimeDiff($time){
        $now = date_create();
        $datetime = date_create($time);
        return date_diff($now, $datetime)->format('%a days');
    }
}

?>