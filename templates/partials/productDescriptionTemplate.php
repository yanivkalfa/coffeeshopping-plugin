<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
</head>
<body>
<div id="productDescriptionWrap">
</div>

<script>
    (function(){
        var jqueryUrl = window.parent.getJqueryUrl();
        var jqueryScript = document.createElement('script');
        jqueryScript.setAttribute('src',jqueryUrl);
        document.head.appendChild(jqueryScript);
        jqueryScript.onload = jqueryScript.onreadystatechange = function() {
            if (!done && (!this.readyState || this.readyState == "loaded" || this.readyState == "complete")) {
                $(document).ready(function(){
                    var content = window.parent.getContent();
                    $('#productDescriptionWrap').html(content);
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