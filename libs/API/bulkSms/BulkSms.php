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

/*
             * //require_once $pluginFolder.'/libs/API/bulkSms/BulkSms.php';
            global $bulkSms;
            $bulkSms = new BulkSms(
                get_option('bulk_username', 'yanivkalfa'),
                get_option('bulk_password', 'shk123456'),
                get_option('bulk_endPoint', 'http://usa.bulksms.com/eapi'),
                5567
            );
            Utils::preEcho($bulkSms->send_sms('This is a message', 4237074169));
            */