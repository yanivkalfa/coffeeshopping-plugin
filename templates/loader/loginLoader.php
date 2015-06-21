<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/18/2015
 * Time: 11:01 PM
 */

$scope = array(
    'errorMessages' => CSCons::get('errorMessages') ?: array(),
    'referrer' => '/'.(isset($_GET['referrer']) ? $_GET['referrer'] : 'myaccount').'/'
);

Utils::getTemplate('login', $scope);
?>