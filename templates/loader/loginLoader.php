<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/18/2015
 * Time: 11:01 PM
 */

$registerPage = get_permalink(get_option("cs_register_p_id"));
if (!$registerPage){Utils::adminPreECHO(__("Can't get register page id", 'coffee-shopping' ), __("loginLoader.php ERROR:: ", 'coffee-shopping' ));}
$myAccountPage = get_permalink(get_option("cs_myAccount_p_id"));
if (!$myAccountPage){Utils::adminPreECHO(__("Can't get register page id", 'coffee-shopping' ), __("loginLoader.php ERROR:: ", 'coffee-shopping' ));}

$scope = array(
    'registerPage' => $registerPage,
    'errorMessages' => CSCons::get('errorMessages') ?: array(),
    'referrer' => (isset($_GET['referrer']) ? $_GET['referrer'] : $myAccountPage)
);

Utils::getTemplate('login', $scope, 'pages');
