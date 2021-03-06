<?php
/*
 * list of methods and their protection
 *
 * - method - {String} - the method name
 *  each method should have the following properties:
 *  - protected - {boolean|optional - default=true} -  true or false for example the method /search/term isnt protected. however /cart/ is protected as it require user specific info.
 *  - req_capabilities - {Array|optional} - holds the required capabilities to access this method  e.g(['manage_options', 'edit_post']) will be used to limit access to certain requests
 *
 * if protected is true and req_capabilities is empty it will simply check if user is logged in
 *
 *
 * */

global $methods;
$methods = array(
    'registerNewUser' => array('protected' => false, 'req_capabilities' => []),
    'updateUserProfile' => array('protected' => true, 'req_capabilities' => ['manage_options', 'cs_member']),
    'addAddress' => array('protected' => true, 'req_capabilities' => ['manage_options', 'cs_member']),
    'removeAddress' => array('protected' => true, 'req_capabilities' => ['manage_options', 'cs_member']),
    'userLogin' => array('protected' => false, 'req_capabilities' => []),
    'addProduct' => array('protected' => false, 'req_capabilities' => []),
    'removeProduct' => array('protected' => false, 'req_capabilities' => []),
    'updateQuantity' => array('protected' => false, 'req_capabilities' => []),
    'getClosestStore' => array('protected' => false, 'req_capabilities' => []),
    'saveCart' => array('protected' => true, 'req_capabilities' => ['manage_options', 'cs_member']),
    'requestResetPassword' => array('protected' => false, 'req_capabilities' => []),
    'resetPassword' => array('protected' => false, 'req_capabilities' => []),
);