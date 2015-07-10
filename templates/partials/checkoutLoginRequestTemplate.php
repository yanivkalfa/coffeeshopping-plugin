<?php
$loginPage = get_permalink(get_option("cs_login_p_id"));
if (!$loginPage){Utils::adminPreECHO(__("Can't get login page id", 'coffee-shopping' ), __("checkoutLoginRequestTemplate.php ERROR:: ", 'coffee-shopping' ));}
$registerPage = get_permalink(get_option("cs_register_p_id"));
if (!$registerPage){Utils::adminPreECHO(__("Can't get register page id", 'coffee-shopping' ), __("checkoutLoginRequestTemplate.php ERROR:: ", 'coffee-shopping' ));}
?>

<div><?php _e("You have to login or register to save your cart and make an order", 'coffee-shopping' ); ?></div>
<div><?php _e("Please", 'coffee-shopping' ); ?> <a href="<?php echo $loginPage;?>?referrer=<?php echo $referrer?>"><?php _e("Login", 'coffee-shopping' ); ?></a> | <a href="<?php echo $registerPage;?>?referrer=<?php echo $referrer?>"><?php _e("Register", 'coffee-shopping' ); ?></a></div>