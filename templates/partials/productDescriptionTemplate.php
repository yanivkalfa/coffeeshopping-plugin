<?php

if(!isset($_GET['view-product']) || !isset($_GET['store'])){
    exit;
}
function getWordPressRoot($filename){

    if(file_exists($filename)){
        return $filename;
    }


}

$fullpath = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));
if (!function_exists('add_action')){
    require_once ($fullpath.'/wp-load.php' );
}

// Sanitize our product id and store name.
$productID = $_GET["view-product"];
$store = $_GET["store"];

// Our options array.
$itemOpts = array();
// Requested details.
$itemOpts["IncludeSelector"] = array("Description");
$sandbox = false;

// performs the actual request.
$result = productView::getProduct($store, $productID, $itemOpts, $sandbox);

?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
</head>
<body>
<div id="productDescriptionWrap">
    <?php echo $result['output']->descriptionHTML; ?>
</div>

<script>
    (function(){
        var jqueryUrl = window.parent.getJqueryUrl();
        var jqueryScript = document.createElement('script');
        jqueryScript.setAttribute('src',jqueryUrl);
        document.head.appendChild(jqueryScript);
        jqueryScript.onload = jqueryScript.onreadystatechange = function() {
            if (!this.readyState || this.readyState == "loaded" || this.readyState == "complete") {
                $(document).ready(function(){

                    window.parent.setIframeHeight($(document).outerHeight(true));

                    $('a').on('click', function(e){
                        var newLocation = $(this).attr('href');
                        window.parent.navigateTo(newLocation);
                        e.preventDefault();
                        return false;
                    });
                });
            }
        };
    })();
</script>
</body>
</html>