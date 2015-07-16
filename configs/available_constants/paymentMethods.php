<?php
/**
 * Created by PhpStorm.
 * User: yanivkalfaaccount
 * Date: 7/14/2015
 * Time: 5:58 PM
 */

$value = array(
    "cash" => array(
        "normal" => array("nameAs" => __("Cash", 'coffee-shopping' ), "optionName" => "store_comm_cash", "default" => array('store' => 5, 'percentage' => 10, 'bigger' => true)),
        "clubMember" => array("nameAs" => __("Cash (Club Member)", 'coffee-shopping' ), "optionName" => "store_comm_club_cash", "default" => array('store' => 3, 'percentage' => 7, 'bigger' => true))
    ),

    "credit" => array(
        "normal" => array("nameAs" => __("Credit", 'coffee-shopping' ), "optionName" => "store_comm_credit", "default" => array('store' => 7, 'percentage' => 12, 'bigger' => true)),
        "clubMember" => array("nameAs" => __("Credit (Club Member)", 'coffee-shopping' ), "optionName" => "store_comm_club_credit", "default" => array('store' => 5, 'percentage' => 10, 'bigger' => true))
    ),

    "payPal" => array(
        "normal" => array("nameAs" => __("PayPal", 'coffee-shopping' ), "optionName" => "store_comm_paypal", "default" => array()),
        "clubMember" => array("nameAs" => __("PayPal (Club Member)", 'coffee-shopping' ), "optionName" => "store_comm_club_paypal", "default" => array())
    ),
);