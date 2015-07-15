<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/25/2015
 * Time: 6:32 PM
 */

abstract class UserDatabaseHelper extends SuperDatabaseHelper{

    public static function registerNewUser($log){
        // validating phone number
        $error = FormValidators::validateFormInput($log, 'phoneIL');
        if(is_array($error)){
            return array( 'success' => false, 'msg' => $error );
        }

        // generating password
        $password = self::generateUserPass();
        $userData = array(
            'user_login'  =>  $log,
            'user_pass'   =>  $password,
            'role' => 'csMember'
        );

        $smsMessages = CSCons::get('smsMessages') ?: array();
        // sending twilio message to phone. if fail - we are returning and not reating user.
        $twilioResults = TwiloHelper::sendMessage(str_replace('{0}', $password,$smsMessages['registered']), $log);
        if(!$twilioResults['success']){
            return $twilioResults;
        }

        // inserting user
        $userId = wp_insert_user( $userData ) ;
        if(is_wp_error($userId)){
            return array( 'success' => false, 'msg' => $userId->get_error_message() );
        }


        $userData["ID"] = $userId;

        return array( 'success' => true, 'msg' => $userData );
    }

    public static function generateUserPass(){
        return rand(0,9).rand(0,9).rand(0,9).rand(0,9);
    }

    public static function clearOldResetPasswordRequests($requests = array()){
        $now = time();
        $oneHourAgo = $now-3600;
        return array_filter($requests, function($v)use($oneHourAgo) {
            return $v > $oneHourAgo;
        });
    }

    public static function isResetPasswordRequestsTooFrequent($requests){
        return count($requests) >= 2;
    }

    public static function isClubMember(){
        $user = wp_get_current_user();
        return (bool)get_user_meta($user->ID, 'clubMember', true);
    }


}