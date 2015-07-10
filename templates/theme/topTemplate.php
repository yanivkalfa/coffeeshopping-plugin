<?php
$loginPage = get_permalink(get_option("cs_login_p_id"));
if (!$loginPage){Utils::adminPreECHO(__("Can't get login page id", 'coffee-shopping' ), __("topTemplate.php ERROR:: ", 'coffee-shopping' ));}
$registerPage = get_permalink(get_option("cs_register_p_id"));
if (!$registerPage){Utils::adminPreECHO(__("Can't get register page id", 'coffee-shopping' ), __("topTemplate.php ERROR:: ", 'coffee-shopping' ));}
$logoutPage = get_permalink(get_option("cs_logout_p_id"));
if (!$logoutPage){Utils::adminPreECHO(__("Can't get logout page id", 'coffee-shopping' ), __("topTemplate.php ERROR:: ", 'coffee-shopping' ));}
$storesPage = get_permalink(get_option("cs_stores_p_id"));
if (!$storesPage){Utils::adminPreECHO(__("Can't get stores page id", 'coffee-shopping' ), __("topTemplate.php ERROR:: ", 'coffee-shopping' ));}

?>

<div id="toptemplatecont" class="center">

    <div class="inline"><?php _e("Stores:", 'coffee-shopping' ); ?></div>
    <?php
    $storesArr = array();
    foreach(StoreDatabaseHelper::getStores() as $store){
        $storesArr[] = '<a href="'.$storesPage.'"><div class="inline">'.$store["name"].'</div></a>';
    }
    echo implode(" | ", $storesArr);
    ?>
    |
    <?php if(!is_user_logged_in()) { ?>
        <span>
        <a href="<?php echo $loginPage;?>"><?php _e("Login", 'coffee-shopping' ); ?></a> |
        <a href="<?php echo $registerPage;?>"><?php _e("Register", 'coffee-shopping' ); ?></a>
    </span>
    <?php } else {
        $user = wp_get_current_user();
        ?>
        <span> Hey <?php echo $user->user_nicename ?> <a href="<?php echo $logoutPage;?>"><?php _e("Logout", 'coffee-shopping' ); ?></a></span>

    <?php }?>
</div>