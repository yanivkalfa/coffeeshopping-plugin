<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/25/2015
 * Time: 6:32 PM
 */

abstract class UserDatabaseHelper extends SuperDatabaseHelper{

    public static function registerNewUser($post){
        $user = json_decode($post['user'], true);

        $error = FormValidators::validateFormInput($user['log'], 'phoneIL');
        if(is_array($error)){
            return array( 'success' => false, 'msg' => $error );
        }

        $userData = array(
            'user_login'  =>  $user['log'],
            'user_pass'   =>  self::generateUserPass(),
            'role' => 'csMember'
        );

        $user = wp_insert_user( $userData ) ;
        if(is_wp_error($user)){
            return array( 'success' => false, 'msg' => $user->get_error_message() );
        }

        $userData["ID"] = $user;

        return array( 'success' => true, 'msg' => $userData );
    }

    public static function generateUserPass(){
        return rand(0,9).rand(0,9).rand(0,9).rand(0,9);
    }


}