<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/18/2015
 * Time: 11:01 PM
 */

Utils::getTemplate('resetPassword', array('errorMessages' => CSCons::get('errorMessages') ?: array()), 'pages');
