<?php
/*
*/

abstract class ajax_method {

	public static function isAuthenticated($methodName) {
        global $methods;
        if(!isset($methods[$methodName])) return false;

        $method = isset($methods[$methodName]['method']) ? $methods[$methodName]['method'] : null;
        $protected = isset($methods[$methodName]['protected']) ? $methods[$methodName]['protected'] : null;
        $req_capabilities = isset($methods[$methodName]['req_capabilities']) ? $methods[$methodName]['req_capabilities'] : null;

        $methodInit =  new Method($methodName, $protected, $req_capabilities);
        return self::authenticate($methodInit);
	}

    private static function authenticate($method){
        if($method->protected){
            if(count($method->req_capabilities)){
                foreach($method->req_capabilities as $cap){
                    if(current_user_can($cap)) return true;
                }
                return false;
            }else {
                return is_user_logged_in();
            }
        }

        return true;
    }

}