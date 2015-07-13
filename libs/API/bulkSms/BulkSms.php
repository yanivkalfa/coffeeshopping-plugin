<?php

class BulkSms {

    private $userName;
    private $password;
    public $endpoint;
    public $port;

    public function __construct($username,$password,$endpoint, $port = 443) {
        if(!$username || !$password || !$endpoint) {
            return false;
        }

        $this->userName = $username;
        $this->password = $password;
        $this->endpoint = $endpoint;
        $this->port = $port;
    }

    public function send_sms($sms, $recipient){
        $url = $this->endpoint . '/submission/send_sms/2/2.0';

        $post_body = BulkSmsHelper::unicode_sms(  $this->userName, $this->password, $sms, $recipient );
        $result = BulkSmsHelper::send_message($url, $post_body, $this->port );

        return $result;

        if( $result['success'] ) {
            BulkSmsHelper::print_ln( BulkSmsHelper::formatted_server_response( $result ) );
        }
        else {
            BulkSmsHelper::print_ln( BulkSmsHelper::formatted_server_response( $result ) );
        }
    }

}