<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/18/2015
 * Time: 11:01 PM
 */

$myAccountPage = get_permalink(get_option("cs_myAccount_p_id"));
if (!$myAccountPage){Utils::adminPreECHO(__("Can't get register page id", 'coffee-shopping' ), __("loginLoader.php ERROR:: ", 'coffee-shopping' ));}
$logoutPage = get_permalink(get_option("cs_logout_p_id"));
if (!$logoutPage){Utils::adminPreECHO(__("Can't get logout page id", 'coffee-shopping' ), __("topTemplate.php ERROR:: ", 'coffee-shopping' ));}
$loginPage = get_permalink(get_option("cs_login_p_id"));
if (!$loginPage){Utils::adminPreECHO(__("Can't get login page id", 'coffee-shopping' ), __("topTemplate.php ERROR:: ", 'coffee-shopping' ));}


$error = false;
if(isset($_POST['submitUser']) && isset($_POST['log']) && wp_verify_nonce($_POST['coffee-shopping'],'registerUser')){
    if(is_super_admin() || !is_user_logged_in()){


        // Register the user to the DB.
        $user = UserDatabaseHelper::registerNewUser($_POST['log']);
        if(!$user["success"]){
            // failed to register.
            $error = __("We couldn't register the user", 'coffee-shopping' );
        }else{
            wp_redirect( $loginPage.'?register=success' );
            exit();
        }
    }
}

$scope = array(
    'errorMessages' => CSCons::get('errorMessages') ?: array(),
    'myAccountPage' => $myAccountPage,
    'logoutPage'    => $logoutPage,
    'referrer' => (isset($_GET['referrer']) ? $_GET['referrer'] : false)
);

if($error){
    $scope['registerError'] = $error;
}
Utils::getTemplate('register', $scope, 'pages');
