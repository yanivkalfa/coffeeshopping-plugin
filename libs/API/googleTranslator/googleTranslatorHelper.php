<?php
/**
 * Created by PhpStorm.
 * User: yanivkalfaaccount
 * Date: 7/12/2015
 * Time: 7:52 PM
 */

abstract class GoogleTranslatorHelper{

    public static function translate($expression, $toLanguages, $fromLanguages = false){
        global $googleTranslator;
        if (!$googleTranslator instanceof GoogleTranslator) {
            $googleTranslator = new GoogleTranslator(
                get_option('google_secret_key', 'AIzaSyAbh66PiI2faK4XzYQFpWfotBq-_zCV8UY'),
                get_option('google_endPoint', 'https://www.googleapis.com/language/translate/v2')
            );
        }
        Utils::preEcho($googleTranslator);

        return $googleTranslator->translate($expression, $toLanguages, $fromLanguages);
    }
}

/*
define('secret_key','AIzaSyAbh66PiI2faK4XzYQFpWfotBq-_zCV8UY');
define('API_ENDPOINT','https://www.googleapis.com/language/translate/v2');

public $secretKey;
public $endPoint;
public $fromLanguages;
public $toLanguages;
*/