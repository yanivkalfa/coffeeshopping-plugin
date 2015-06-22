<?php
$loginPage = get_permalink(get_option("cs_login_p_id"));
if (!$loginPage){Utils::adminPreECHO("Can't get login page id", "topTemplate.php ERROR:: ");}
$registerPage = get_permalink(get_option("cs_register_p_id"));
if (!$registerPage){Utils::adminPreECHO("Can't get register page id", "topTemplate.php ERROR:: ");}
$logoutPage = get_permalink(get_option("cs_logout_p_id"));
if (!$logoutPage){Utils::adminPreECHO("Can't get logout page id", "topTemplate.php ERROR:: ");}

?>

<div id="toptemplatecont" class="center">
    <?php if(!is_user_logged_in()) { ?>
        <span>
        <a href="<?php echo $loginPage;?>">Login</a> |
        <a href="<?php echo $registerPage;?>">Register</a>
    </span>
    <?php } else {
        $user = wp_get_current_user();
        ?>
        <span> Hey <?php echo $user->user_nicename ?> <a href="<?php echo $logoutPage;?>">Logout</a></span>

    <?php }?>
     |
    <span> סניפים: אלנבי 83, תל אביב (סניף ראשי) | נורדאו 1, חיפה | שד' בן-גוריון 84, קרית מוצקין (רבמ"ד) | ז'בוטינסקי 1 (פינת הגעתון), נהריה | שפרעם</span>
</div>
