<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/25/2015
 * Time: 6:32 PM
 */

abstract class userHelper{

    public static function registerNewUser($post){
        $user = json_decode($post['user'], true);

        $error = FormValidators::validateFormInput($user['log'], 'phoneIL');
        if(is_array($error)){
            return array( 'success' => false, 'msg' => $error );
        }

        $userData = array(
            'user_login'  =>  $user['log'],
            'user_pass'   =>  self::generateUserPass(),
            'role' => 'subscriber'
        );

        $user = wp_insert_user( $userData ) ;

        return $user;
    }

    public static function generateUserPass(){
        return rand(0,9).rand(0,9).rand(0,9).rand(0,9);
    }


}