<?php
if(isset($_GET['refresh'])){
    Utils::getTemplate('logout', NULL ,'pages');
    return;
}
wp_logout();
$_SESSION['cart']->clear();

if(isset($_GET['referrer'])){
    wp_redirect( $_GET['referrer'] );
}else{
    wp_redirect( site_url().'/logout?refresh=1'  );
}
