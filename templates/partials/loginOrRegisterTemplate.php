<?php
$loginPage = get_permalink(get_option("cs_login_p_id"));
if (!$loginPage){Utils::adminPreECHO("Can't get login page id", "loginOrRegisterTemplate.php ERROR:: ");}
$registerPage = get_permalink(get_option("cs_register_p_id"));
if (!$registerPage){Utils::adminPreECHO("Can't get register page id", "loginOrRegisterTemplate.php ERROR:: ");}
?>

<div>You have to login or register to save your cart and make an order</div>
<div>Please <a href="<?php echo $loginPage;?>?referrer=<?php echo $referrer?>">Login</a> | <a href="<?php echo $registerPage;?>">Register</a></div>