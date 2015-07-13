<?php
/**
 * Created by PhpStorm.
 * User: yanivkalfaaccount
 * Date: 7/12/2015
 * Time: 7:50 PM
 */
//googleTranslatorLanguages

class GoogleTranslator {
    public $secretKey;
    public $endPoint;
    public $fromLanguages;
    public $toLanguages;

    public function __construct($secretKey, $endPoint,$fromLanguages = 'iw', $toLanguages='en'){
        if(!$secretKey ||  !$endPoint) {
            return false;
        }

        $this->secretKey = $secretKey;
        $this->endPoint = $endPoint;
        $this->fromLanguages = $fromLanguages;
        $this->toLanguages = $toLanguages;
    }

    public function composeURL($params){
        return $this->endPoint.'?'.http_build_query($params);
    }

    public function translate($expression, $toLanguages, $fromLanguages = false){
        if($fromLanguages){
            $this->fromLanguages = $fromLanguages;
        }
        $this->toLanguages = $toLanguages;

        $params = array(
            "key" => $this->secretKey,
            "q" => $expression,
            "source" =>$this->fromLanguages,
            "target" => $this->toLanguages,
            "prettyprint" => true
        );

        $url = $this->composeURL($params);

        $optsOverride = array(
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => array('content_type: application/json')
        );
        $response = Utils::get_url($url, 'get',array(), NULL, $optsOverride);

        if($response['result'] == 'ERROR'){
            return array(
                "success" => false,
                "msg" => array('name' =>'cUrlError', 'errorMsg' => __( "We've encounter and error accessing the api", 'coffee-shopping' ))
            );
        }

        $translation = json_decode($response['output']);
        $decodingError = json_last_error ();
        if($decodingError){
            return array(
                "success" => false,
                "msg" => array('name' =>'cUrlError', 'errorMsg' => __( "We've encounter and error accessing the api", 'coffee-shopping' ))
            );
        }

        if(empty($translation->error) && !empty($translation->data->translations)){
            $toReturn['msg'] = $translation->data->translations[0]->translatedText;
        }else{
            $toReturn['msg'] = $translation->error->message;
        }

        return $toReturn;
    }
}
