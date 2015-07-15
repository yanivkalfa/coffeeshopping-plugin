<?php

abstract class Utils{

    static public function escapeJavaScriptText($string){
        return str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$string), "\0..\37'\\")));
    }

    static public function truncateStringToLength($string, $length){
        return substr( $string, 0, strrpos( substr( $string, 0, $length), ' ' ) );
    }

    static public function getAPILogoClass($API){
        return $API."-storeimg";
    }

    static public function getPictureBySize($API, $link, $size){
        if (!Utils::API_Exists($API)){return false;}
        $apiClass = $API."Adapter";
        $API_Adapter = new $apiClass();
        return $API_Adapter->getPictureBySize($link, $size);
    }

    /**
     * @param String $templateName - template name
     * @param Mixed $scope - $scope variable that will be available in the template.
     * @param String $folder - in-case its in an inner folder.
     * @param String $prefix - prefix
     * @param String $suffix - suffix
     * @return bool
     */
    static public function getTemplate($templateName, $scope = null, $folder = '', $prefix = '', $suffix = 'Template'){
        $folder = isset($folder) && !empty($folder) ? $folder.'/' : 'partials/';
        $fileName = TEMPLATE_DIR . '/'.$folder.$prefix . $templateName . $suffix.'.php';

        if(!file_exists($fileName)) {
            return false;
        }

        // flatten scope.
        if(is_array($scope)) extract($scope, EXTR_OVERWRITE);
        include($fileName);
        return true;
    }

    static public function pageEcho($echo){
        echo $echo;
    }

    static public function isMobile(){
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
            return true;
        }
        return false;
    }

    static public function isProductID($api, $test){
        if (!self::API_Exists($api)){return false;}
        $apiUtils = $api."_Utils";
        return $apiUtils::isProductID($test);
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
            Utils::preEcho($item[$prop], '<br>$item[$prop]: ');
            */


            if($prop && $item[$prop] && ((isset($srch[$prop]) && $item[$prop] === $srch[$prop]) || $item[$prop] === $srch)) return $i;
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
     * @param mixed $optsOverride -  options override - if supplied will override existing options
     * @return mixed - Whatever is returned by the server.
     */
    static function get_url($url, $method = "get", $headers = array(), $data = "", $optsOverride = array()){

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
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, 20);

        // overriding opts
        foreach($optsOverride as $opt => $value){
            curl_setopt($crl, $opt, $value);
        }

        $ret = curl_exec($crl);
        // Check if any error occured
        if(curl_errno($crl)){
            curl_close($crl);
            return array(
                "result" => "ERROR",
                "output" => $ret
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
        if (empty($currency)){return "";}
        $currencyNames = CSCons::get('currencyNames') ?: NULL;
        return isset($currencyNames[$currency]) ? $currencyNames[$currency] : "Unknown (".$currency.")";
    }

    // Get an html valid symbol for a given $currency.
    static public function getCurrencySymbol($currency){
        if (strlen($currency)<3){return "";}
        $currencySymbols = CSCons::get('currencySymbols') ?: NULL;
        return isset($currencySymbols[$currency]) ? $currencySymbols[$currency] : $currency;
    }

    /**
     * @func addExchangeKeys(&$mixed, $keys)
     *  - Add exchange keys to a given array/object.
     * @param   mixed     $mixed        - The array/object to temper with.
     * @param   array     $keys         - The key we are exchanging (must have a $key.'Currency' key to hold it's currencyID.
     */
    static public function addExchangeKeys(&$mixed, $keys){
        $exchSuff = "Exch";
        $exchCurrency = EXCH_CURRENCY;
        $exchanger = new currencyExchange();
        foreach($keys as $key){
            if (is_object($mixed)){
                $mixed->{$key."Symbol"}             =  Utils::getCurrencySymbol($mixed->{$key."Currency"});
                $mixed->{$key.$exchSuff}            =  $exchanger->exchangeRateConvert($mixed->{$key."Currency"}, $mixed->{$key}, $exchCurrency);
                $mixed->{$key."Currency".$exchSuff} =  $exchCurrency;
                $mixed->{$key."Symbol".$exchSuff}   =  Utils::getCurrencySymbol($exchCurrency);
            }else{
                $mixed[$key."Symbol"]               =  Utils::getCurrencySymbol($mixed[$key."Currency"]);
                $mixed[$key.$exchSuff]              =  $exchanger->exchangeRateConvert($mixed[$key."Currency"], $mixed[$key], $exchCurrency);
                $mixed[$key."Currency".$exchSuff]   =  $exchCurrency;
                $mixed[$key."Symbol".$exchSuff]     =  Utils::getCurrencySymbol($exchCurrency);
            }
        }
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

    static public function toArray($value){
        if(!isset($value) || empty($value)) return array();
        return is_array($value) ? $value : array($value);
    }

    static public function getProductPageLink($ID, $store){
        $productPage = get_permalink(get_option("cs_product_p_id"));
        return $productPage."?view-product=" . $ID . "&store=" . $store;
    }


}

?>
