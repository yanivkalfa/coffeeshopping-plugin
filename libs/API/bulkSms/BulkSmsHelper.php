<?php

abstract class BulkSmsHelper {

  public static function send_message ( $url, $post_body, $port ) {

    $result = Utils::get_url($url, $method = "post", array(), $post_body, $port);
    $response_string = $result['output'];

    $sms_result = array();
    $sms_result['success'] = 0;
    $sms_result['details'] = '';
    $sms_result['api_status_code'] = '';
    $sms_result['api_message'] = '';
    $sms_result['api_batch_id'] = '';

    if ( $result['result'] == 'ERROR' ) {
      $sms_result['details'] = $result['output'];
    } else {
      $sms_result['details'] .= "Response from server: $response_string\n";
      $api_result = explode( '|', $response_string );
      $status_code = $api_result[0];
      $sms_result['api_status_code'] = $status_code;
      $sms_result['api_message'] = $api_result[1];
      if ( count( $api_result ) != 3 ) {
        $sms_result['details'] .= "Error: could not parse valid return data from server.\n" . count( $api_result );
      } else {
        if ($status_code == '0') {
          $sms_result['success'] = 1;
          $sms_result['api_batch_id'] = $api_result[2];
          $sms_result['details'] .= "Message sent - batch ID $api_result[2]\n";
        }
        else if ($status_code == '1') {
          # Success: scheduled for later sending.
          $sms_result['success'] = 1;
          $sms_result['api_batch_id'] = $api_result[2];
        }
        else {
          $sms_result['details'] .= "Error sending: status code [$api_result[0]] description [$api_result[1]]\n";
        }
      }
    }

    return $sms_result;
  }

  public static function print_ln($content) {
    if (isset($_SERVER["SERVER_NAME"])) {
      print $content."<br />";
    }
    else {
      print $content."\n";
    }
  }

  public static function formatted_server_response( $result ) {
    $this_result = "";

    if ($result['success']) {
      $this_result .= "Success: batch ID " .$result['api_batch_id']. "API message: ".$result['api_message']. "\nFull details " .$result['details'];
    }
    else {
      $this_result .= "Fatal error: HTTP status " .$result['http_status_code']. ", API status " .$result['api_status_code']. " API message " .$result['api_message']. " full details " .$result['details'];

      if ($result['transient_error']) {
        $this_result .=  "This is a transient error - you should retry it in a production environment";
      }
    }
    return $this_result;
  }

  public static function unicode_sms ( $username, $password, $message, $msisdn ) {
    $post_fields = array (
        'username' => $username,
        'password' => $password,
        'message'  => self::string_to_utf16_hex( $message ),
        'msisdn'   => $msisdn,
        'dca'      => '16bit'
    );

    return self::make_post_body($post_fields);
  }

  public static function make_post_body($post_fields) {
    $stop_dup_id = self::make_stop_dup_id();
    if ($stop_dup_id > 0) {
      $post_fields['stop_dup_id'] = self::make_stop_dup_id();
    }
    $post_body = '';
    foreach( $post_fields as $key => $value ) {
      $post_body .= urlencode( $key ).'='.urlencode( $value ).'&';
    }
    $post_body = rtrim( $post_body,'&' );

    return $post_body;
  }

  public static function character_resolve($body) {
    $special_chrs = array(
        'Δ'=>'0xD0', 'Φ'=>'0xDE', 'Γ'=>'0xAC', 'Λ'=>'0xC2', 'Ω'=>'0xDB',
        'Π'=>'0xBA', 'Ψ'=>'0xDD', 'Σ'=>'0xCA', 'Θ'=>'0xD4', 'Ξ'=>'0xB1',
        '¡'=>'0xA1', '£'=>'0xA3', '¤'=>'0xA4', '¥'=>'0xA5', '§'=>'0xA7',
        '¿'=>'0xBF', 'Ä'=>'0xC4', 'Å'=>'0xC5', 'Æ'=>'0xC6', 'Ç'=>'0xC7',
        'É'=>'0xC9', 'Ñ'=>'0xD1', 'Ö'=>'0xD6', 'Ø'=>'0xD8', 'Ü'=>'0xDC',
        'ß'=>'0xDF', 'à'=>'0xE0', 'ä'=>'0xE4', 'å'=>'0xE5', 'æ'=>'0xE6',
        'è'=>'0xE8', 'é'=>'0xE9', 'ì'=>'0xEC', 'ñ'=>'0xF1', 'ò'=>'0xF2',
        'ö'=>'0xF6', 'ø'=>'0xF8', 'ù'=>'0xF9', 'ü'=>'0xFC',
    );

    $ret_msg = '';
    if( mb_detect_encoding($body, 'UTF-8') != 'UTF-8' ) {
      $body = utf8_encode($body);
    }
    for ( $i = 0; $i < mb_strlen( $body, 'UTF-8' ); $i++ ) {
      $c = mb_substr( $body, $i, 1, 'UTF-8' );
      if( isset( $special_chrs[ $c ] ) ) {
        $ret_msg .= chr( $special_chrs[ $c ] );
      }
      else {
        $ret_msg .= $c;
      }
    }
    return $ret_msg;
  }

  /*
  * Unique ID to eliminate duplicates in case of network timeouts - see
  * EAPI documentation for more. You may want to use a database primary
  * key. Warning: sending two different messages with the same
  * ID will result in the second being ignored!
  *
  * Don't use a timestamp - for instance, your application may be able
  * to generate multiple messages with the same ID within a second, or
  * part thereof.
  *
  * You can't simply use an incrementing counter, if there's a chance that
  * the counter will be reset.
  */
  public static function make_stop_dup_id() {
    return 0;
  }

  public static function string_to_utf16_hex( $string ) {
    return bin2hex(mb_convert_encoding($string, "UTF-16", "UTF-8"));
  }

  public static function xml_to_wbxml( $msg_body ) {

    $wbxmlfile = 'xml2wbxml_'.md5(uniqid(time())).'.wbxml';
    $xmlfile = 'xml2wbxml_'.md5(uniqid(time())).'.xml';

    //create temp file
    $fp = fopen($xmlfile, 'w+');

    fwrite($fp, $msg_body);
    fclose($fp);

    //convert temp file
    exec(xml2wbxml.' -v 1.2 -o '.$wbxmlfile.' '.$xmlfile.' 2>/dev/null');
    if(!file_exists($wbxmlfile)) {
      print_ln('Fatal error: xml2wbxml conversion failed');
      return false;
    }

    $wbxml = trim(file_get_contents($wbxmlfile));

    //remove temp files
    unlink($xmlfile);
    unlink($wbxmlfile);
    return $wbxml;
  }

}