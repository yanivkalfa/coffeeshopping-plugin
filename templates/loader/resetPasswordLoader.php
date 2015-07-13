<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/18/2015
 * Time: 11:01 PM
 */
$loginPage = get_permalink(get_option("cs_login_p_id"));
if (!$loginPage){Utils::adminPreECHO(__("Can't get login page id", 'coffee-shopping' ), __("topTemplate.php ERROR:: ", 'coffee-shopping' ));}

$scope = array(
    'loginPage' => $loginPage,
    'errorMessages' => CSCons::get('errorMessages') ?: array()
);

Utils::getTemplate('resetPassword', $scope , 'pages');
