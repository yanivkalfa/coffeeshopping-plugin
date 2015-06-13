<?php

abstract class Utils{

    /**
     * @param String $templateName - template name
     * @param Mixed $scope - $scope variable that will be available in the template.
     * @param String $folder - in-case its in an inner folder.
     * @param String $prefix - prefix
     * @param String $suffix - suffix
     * @return bool
     */
    static public function getTemplate($templateName, $scope = null, $folder = 'partials/', $prefix = '', $suffix = 'Template'){
        $fileName = TEMPLATE_DIR . '/'.$folder.$prefix . $templateName . $suffix.'.php';

        if(!file_exists($fileName)) {
            return false;
        }

        // flatten scope.
        if(is_array($scope)) extract($scope);
        include($fileName);
        return true;
    }

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
    static public function uploadFile($file, $location){
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
    static public function deleteFile($fileName, $location){
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
     * Deletes a file  or a folder recursively.
     * @param String $path the path of the file or directory to delete.
     * @return bool
     */
    static public function deleteLocation($path) {
        if (is_dir($path) === true) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($files as $file) {
                if (in_array($file->getBasename(), array('.', '..')) !== true) {
                    if ($file->isDir() === true) {
                        rmdir($file->getPathName());
                    }

                    else if (($file->isFile() === true) || ($file->isLink() === true)) {
                        unlink($file->getPathname());
                    }
                }
            }

            return rmdir($path);
        } else if ((is_file($path) === true) || (is_link($path) === true)) {
            return unlink($path);
        }

        return false;
    }

    /**
     * Copy a file  or a folder recursively.
     * @param String $src the source to copy from.
     * @param String $dst the destination to copy to.
     * @return bool
     */
    static public function copyLocation($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    self::copyLocation($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    static public function file_str_replace($pathToFile, $lookFor, $changeTo, $saveTo = false){
        if(!isset($pathToFile) || !isset($lookFor) ||  !isset($changeTo)) return false;
        $fileRead = file_get_contents($pathToFile);
        if(is_array($lookFor)){
            foreach($lookFor as $key => $pattern){
                $fileRead = str_replace($lookFor[$key],$changeTo[$key],$fileRead);
            }
        }else{
            $fileRead = str_replace($lookFor,$changeTo,$fileRead);
        }
        $saveTo = $saveTo ? $saveTo : $pathToFile;
        return file_put_contents($saveTo,$fileRead);
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

    // Get a county name for a given country code $countryCode.
    static public function getCountryFromCode($countryCode){
        $countries = CSCons::get('countries') ?: NULL;
        return isset($countries[$countryCode]) ? $countries[$countryCode] : "Unknown (".$countryCode.")";
    }

    // Get a full currency name for a given $currency.
    static public function getCurrencyName($currency){
        $currencyNames = CSCons::get('currencyNames') ?: NULL;
        return isset($currencyNames[$currency]) ? $currencyNames[$currency] : "Unknown (".$currency.")";
    }

    // Get an html valid symbol for a given $currency.
    static public function getCurrencySymbol($currency){
        $currencySymbols = CSCons::get('currencySymbols') ?: NULL;
        return isset($currencySymbols[$currency]) ? $currencySymbols[$currency] : $currency;
    }

    // Remove any "restricted extensions" links from given $html.
    static public function cleanDescriptionHTML($html){
        // Clean regex, no string escaping - (?i)\b((?:https?:(?:\/{1,3}|[a-z0-9%])|[a-z0-9.\-]+[.](?:com|net|org|edu|gov|mil|aero|asia|biz|cat|coop|info|int|jobs|mobi|museum|name|post|pro|tel|travel|xxx|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cs|cu|cv|cx|cy|cz|dd|de|dj|dk|dm|do|dz|ec|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|Ja|sk|sl|sm|sn|so|sr|ss|st|su|sv|sx|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)\/)(?:[^\s()<>{}\[\]]+|\([^\s()]*?\([^\s()]+\)[^\s()]*?\)|\([^\s]+?\))+(?:\([^\s()]*?\([^\s()]+\)[^\s()]*?\)|\([^\s]+?\)|[^\s`!()\[\]{};:'".,<>?Â«Â»â€œâ€â€˜â€™])|(?:(?<!@)[a-z0-9]+(?:[.\-][a-z0-9]+)*[.](?:com|net|org|edu|gov|mil|aero|asia|biz|cat|coop|info|int|jobs|mobi|museum|name|post|pro|tel|travel|xxx|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cs|cu|cv|cx|cy|cz|dd|de|dj|dk|dm|do|dz|ec|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|Ja|sk|sl|sm|sn|so|sr|ss|st|su|sv|sx|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)\b\/?(?!@)))
        $reg_exUrl = "/(?i)\\b((?:h?t?t?ps?:(?:\\/{1,2}|[a-z0-9%])|[a-z0-9.\\-]+[.](?:com|net|org|edu|gov|mil|aero|asia|biz|cat|coop|info|int|jobs|mobi|museum|name|post|pro|tel|travel|xxx|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cs|cu|cv|cx|cy|cz|dd|de|dj|dk|dm|do|dz|ec|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|Ja|sk|sl|sm|sn|so|sr|ss|st|su|sv|sx|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)\\/)(?:[^\\s()<>{}\\[\\]]+|\\([^\\s()]*?\\([^\\s()]+\\)[^\\s()]*?\\)|\\([^\\s]+?\\))+(?:\\([^\\s()]*?\\([^\\s()]+\\)[^\\s()]*?\\)|\\([^\\s]+?\\)|[^\\s`!()\\[\\]{};:'\".,<>?Â«Â»â€œâ€â€˜â€™])|(?:(?<!@)[a-z0-9]+(?:[.\\-][a-z0-9]+)*[.](?:com|net|org|edu|gov|mil|aero|asia|biz|cat|coop|info|int|jobs|mobi|museum|name|post|pro|tel|travel|xxx|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cs|cu|cv|cx|cy|cz|dd|de|dj|dk|dm|do|dz|ec|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|Ja|sk|sl|sm|sn|so|sr|ss|st|su|sv|sx|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)\\b\\/?(?!@)))/";
        // Check if we have any urls.
        $urls = Array();
        if ((int)preg_match_all($reg_exUrl, $html, $urls)>0) {
            foreach($urls[0] as $url){
                // Remove only
                $restrictedExt = CSCons::get('descRestrictedExt') ?: NULL;
                // Search our array for the extension if exists.
                foreach ($restrictedExt as $extension){
                    if ($extension == substr($url, strlen($extension)*-1)){
                        $html = str_ireplace($url, "", $html);
                    }
                }
            }
        }

        return $html;
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