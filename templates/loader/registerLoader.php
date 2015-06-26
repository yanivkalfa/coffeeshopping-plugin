<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/18/2015
 * Time: 11:01 PM
 */

$myAccountPage = get_permalink(get_option("cs_myAccount_p_id"));
if (!$myAccountPage){Utils::adminPreECHO("Can't get register page id", "loginLoader.php ERROR:: ");}
$logoutPage = get_permalink(get_option("cs_logout_p_id"));
if (!$logoutPage){Utils::adminPreECHO("Can't get logout page id", "topTemplate.php ERROR:: ");}

$scope = array(
    'errorMessages' => CSCons::get('errorMessages') ?: array(),
    'myAccountPage' => $myAccountPage,
    'logoutPage'    => $logoutPage,
    'referrer' => (isset($_GET['referrer']) ? $_GET['referrer'] : false)
);
Utils::getTemplate('register', $scope, 'pages');
