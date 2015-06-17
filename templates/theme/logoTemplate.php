<?php
$logoImg = plugins_url( '/css/images/website-logo.png', __FILE__ );
//$logoImg = plugins_url( '/css/images/website-logo-text.png', __FILE__ );
?>

<div id="websitelogocont">
	<div id="websitelogo">
		<a href="<?php echo home_url();?>"><img src="<?php echo $logoImg;?>"></a>
	</div>

</div>