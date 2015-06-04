<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/4/2015
 * Time: 8:21 PM
 */

abstract class ebay_Utils{
  static public function getEbayPicture($link, $size){
      // s- square, w-wide.
      $sizes = array(
          "42s"           => "13",
          "64s"           => "14",
          "80s"             => "17",
          "125s"           => "15",
          "150s"           => "7",
          "200s"          => "2",
          "300s"            => "8",
          "400s"          => "1",
          "500s"           => "12",
          "600s"           => "20",
          "800s"          => "3",
          "960s"           => "59",
          "1200s"           => "45",
          "1600s"           => "10",

          "100w"           => "5",
          "200w"           => "9",
          "310w"           => "11",
          "400w"           => "16",
          "640w"          => "4",
      );
      $regexp = "/\\$\\_\\d+\\.JPG.+/i";
      $replace = "\$_".$sizes[$size].".JPG";
      return preg_replace($regexp, $replace, $link, 1);
  }
}

?>