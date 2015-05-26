<?php

abstract class Utils{



    static function indexOf($arr, $srch, $prop){
        $srch = is_object($srch) ? (array)$srch : $srch;
        for($i = 0; $i < count($arr); $i++){
            $item = is_object($arr[$i]) ? (array)$arr[$i] : $arr[$i];

            /*
            echo '<br>property: '.$prop;
            echo '<br>$item: ';
            echo '<pre>';
            print_r($item);
            echo '</pre>';

            echo '<br>$srch: ';
            echo '<pre>';
            print_r($srch);
            echo '</pre>';

            echo '<br>$srch[$prop]: ';
            echo '<pre>';
            print_r($srch[$prop]);
            echo '</pre>';
            */

            if($prop && $item[$prop] && ($item[$prop] === $srch[$prop] || $item[$prop] === $srch)) return $i;
            if($item === $srch) return $i;
        }
        return -1;
    }

	/*
	* @ Uploading files to server.
	* @ Accept: global FILES, string Location to save the file.
	* @ Returns: fileName or false.
	*/
	static function uploadFile($file, $location){
		if($file["size"] < 10120000){
			if ($file["error"] > 0){
				return false;
			}else{
				if (file_exists($location . $file["name"])){	
					$tempfilename = $file["name"];
					for ($i = 0; file_exists($location . $tempfilename); $i++) {
	   					 $tempfilename = $i . $file["name"];
					}
					move_uploaded_file($file["tmp_name"], $location . $tempfilename);
					clearstatcache();
					return $tempfilename;
	
				}
                else
				{
					move_uploaded_file($file["tmp_name"], $location . $file["name"]);
					clearstatcache();
					return $file["name"];
				}
			}
		}else{
			return false;
		}
	}
	
	/*
	* @ Delete files to server.
	* @ Accept: string file name, string Location to save the file.
	* @ Returns: fileName or false.
	*/
    static function deleteFile($fileName, $location){
		$fullfilename = $location.$fileName;
		if (file_exists($fullfilename)){
			$fh = fopen($fullfilename, 'w') or die("can't open file");
			fclose($fh);
			unlink($fullfilename);
			return true;
		}else{
			return false;
		}
	}

    /**
     * @func get_url($url, $method = "get", $headers = array(), $data = "")
     *  - Uses curl to perform an HTTP1.1 call.
     * @param string $url - The url to use.
     * @param string $method - The method to use - get/post, defaults to get.
     * @param array $headers - The headers to send - eg.: $headers('Content-type: text/plain', 'Content-length: 100')
     * @param mixed $data -  The data to send - eg.: array["key"=>"value"], string, array("file" => "filename"), xml file content etc.
     * @return mixed - Whatever is returned by the server.
     */
    static function get_url($url, $method = "get", $headers = array(), $data = ""){
        $crl = curl_init();
        curl_setopt ($crl, CURLOPT_URL,$url);
        if (strtolower($method)=="get"){
            curl_setopt($crl, CURLOPT_HTTPGET, true);
        }elseif(strtolower($method)=="post"){
            curl_setopt($crl, CURLOPT_POST, 1);
            curl_setopt($crl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($crl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($crl, CURLOPT_HEADER, false);
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, 10);
        $ret = curl_exec($crl);
        // Check if any error occured
        if(curl_errno($crl)){
            echo '<br />cURLing::'.$url.'- Curl error: (#'.curl_errno($crl).') ' . curl_error($crl).'<br />';
            print_r($data);
        }
        curl_close($crl);
        return $ret;
    }
}

?>