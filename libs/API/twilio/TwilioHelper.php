<?php

abstract class TwiloHelper {

    public static function sendMessage($msg, $to) {
        if(!$msg || !$to){
            return array(
                'success' => false,
                'msg' => __("Message or recipient is missing !", 'coffee-shopping' )
            );
        }

        $sid = get_option('twilo_sid', "AC050355ed5951224742da5af7140ad899");
        $token = get_option('twilo_token', "b9a95270e1c12d79c4664d3930cbc417");
        $from = get_option('twilo_number', "+1(865) 375-5049");

        $client = new Services_Twilio($sid, $token);
        try {
            $message = $client->account->messages->create(array(
                "From" => $from,
                "To" => $to,
                "Body" => $msg,
            ));

            return array(
                'success' => true,
                'msg' => $message->sid
            );
        } catch (Services_Twilio_RestException $e) {
            return array(
                'success' => false,
                'msg' => $e->getMessage()
            );
        }
    }

}
