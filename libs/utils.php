<?php

abstract class Utils{

    static public function pageEcho($echo){
        echo $echo;
    }

    /**
     * @func API_Exists($API)
     * @param string $API - API name to check.
     * @return bool.
     */
    static public function API_Exists($API){
        return class_exists($API."Adapter");
    }

    static public function getErrorCode($cat, $sub, $type, $num){
        $errorCategories = CSCons::get('errorCategories') ?: array();
        $errorSubCategories = CSCons::get('errorSubCategories') ?: array();
        $errorSubCategoryTypes = CSCons::get('errorSubCategoryTypes') ?: array();

        return array_search("$cat",$errorCategories).array_search("$sub", $errorSubCategories).array_search("$type", $errorSubCategoryTypes)."$num";
    }
    static public function getErrorCodeText($errorCode){
        $errorCodesHandler = CSCons::get('errorCodesHandler') ?: NULL;
        $errorNum = substr($errorCode, -1);
        return isset($errorCodesHandler[$errorNum]) ? $errorCodesHandler[$errorNum] : false ;
    }


    static public function preEcho($print_r, $preString = "", $postString = ""){
        echo "<pre>";
        echo $preString;
        print_r($print_r);
        echo $postString;
        echo "</pre>";
    }
    static public function adminPreECHO($print_r, $preString = "", $postString = ""){
        if (!is_super_admin()){return;}
        echo "<pre>";
        echo $preString;
        print_r($print_r);
        echo $postString;
        echo "</pre>";
    }

    static function indexOf($arr, $srch, $prop){
        $srch = is_object($srch) ? (array)$srch : $srch;
        for($i = 0; $i < count($arr); $i++){
            $item = is_object($arr[$i]) ? (array)$arr[$i] : $arr[$i];

            /*
            Utils::preEcho($item, '<br>property: '.$prop.'<br>$item: ');
            Utils::preEcho($srch, '<br>$srch: ');
            Utils::preEcho($srch[$prop], '<br>$srch[$prop]: ');
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
            curl_close($crl);
            return array(
                "result" => "ERROR",
                "output" => '<br />cURLing::'.$url.'- Curl error: (#'.curl_errno($crl).') ' . curl_error($crl).'<br />'
            );
        }
        curl_close($crl);
        return array(
            "result" => "OK",
            "output" => $ret
        );
    }

    static public function getCountryFromCode($countryCode){
        // Get our $country array.
        $countries = CSCons::get('countries') ?: NULL;
        return isset($countries[$countryCode]) ? $countries[$countryCode] : "Unknown (".$countryCode.")";
    }

    static public function arrayPluck($arr,$keep){
        $return = [];
        foreach($keep as $value){
            if(!isset($arr[$value])) continue;
            $return[$value] = $arr[$value];
        }
        return $return;
    }
}

?>