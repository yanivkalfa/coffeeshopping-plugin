<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/18/2015
 * Time: 11:01 PM
 */

$scope = array('errorMessages' => CSCons::get('errorMessages') ?: array());
Utils::getTemplate('register', $scope, 'pages');
