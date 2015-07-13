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
        $uriPart = array();
        foreach($params as $key => $value){
            if(!empty($value) && $value != ''){
                $uriPart[] = $key.'='.$value;
            }
        }
        return $this->endPoint.'?'.implode('&',$uriPart);
    }

    public function translate($expression, $toLanguages, $fromLanguages = false){
        if($fromLanguages){
            $this->fromLanguages = $fromLanguages;
        }
        $this->toLanguages = $toLanguages;

        $toReturn = array(
            "success" => false,
            "msg" => ""
        );
        $params = array(
            "key" => $this->secretKey,
            "q" => $expression,//urlencode($expression),
            "source" =>$this->fromLanguages,
            "target" => $this->toLanguages,
            "prettyprint" => true
        );

        $url = $this->composeURL($params);

        $optsOverride = array(
            //CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            //CURLOPT_SSL_VERIFYPEER => false,
            //CURLOPT_SSL_VERIFYHOST => false,
            //CURLOPT_HTTPHEADER => array('content_type: application/json')
        );
        $response = Utils::get_url($url, 'get',array(), NULL, $optsOverride);

        //$translation = json_decode(urldecode($response['output']), true);

        echo '<textarea>'.htmlentities($response['output']).'</textarea>';
        echo json_last_error();
        //Utils::preEcho($translation);

        if($response['result'] == 'ERROR'){
            return array(
                "success" => false,
                "msg" => array('name' =>'cUrlError', 'errorMsg' => __( "We've encounter and error accessing the api", 'coffee-shopping' ))
            );
        }
        $translation = json_decode($response['output']);

        Utils::preEcho($translation);
        /*

        if(empty($translation->error) && !empty($translation->data->translations)){
            $toReturn['msg'] = $translation->data->translations[0]->translatedText;
        }else{
            $toReturn['msg'] = $translation->error->message;
        }

        return $toReturn;
        */
    }
}
