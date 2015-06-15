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
    'addProduct' => array('protected' => true, 'req_capabilities' => ['manage_options', 'registered_member']),
    'removeProduct' => array('protected' => true, 'req_capabilities' => ['manage_options', 'registered_member']),
    'updateQuantity' => array('protected' => true, 'req_capabilities' => ['manage_options', 'registered_member']),
    'saveCart' => array('protected' => true, 'req_capabilities' => ['manage_options', 'registered_member']),
);