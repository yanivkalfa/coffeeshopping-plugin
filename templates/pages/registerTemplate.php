<script language="javascript" type="text/javascript">
    // Set some vars.
    $ns.errorMessages = <?php echo json_encode($errorMessages);?>;
</script>

<?php
if(is_super_admin() || !is_user_logged_in()){
    // If the user is not logged in Or an Admin.
    ?>
    <div id="registrationformcont">
        <form id="registerForm" method="post" class="registrationblock">
            <div class="inline">
                <div>
                    <h2><?php _e("Register to CoffeeShopping:", 'coffee-shopping' ); ?></h2>
                </div>

                <div class="form-group form-ltr">
                    <label><?php _e("Phone Number *", 'coffee-shopping' ); ?></label>
                    <div class="phonearea">
                        <input id="logininput" type="text" class="form-control" name="log"/>
                        <div id="inputvalidatorOK"></div>
                        <div id="inputvalidatorERR"></div>
                    </div>
                </div>

                <?php if(isset($registerError)) { ?>
                    <div  class="alert-error"><?php echo $registerError ?></div>
                <?php } ?>

                <div class="form-group form-ltr">
                    <input id="userloginbutton" type="submit" name="submitUser" class="btn btn-primary form-control full-width-button" value="<?php _e("Register", 'coffee-shopping' ); ?>" />
                </div>
            </div>
            <?php wp_nonce_field('registerUser','coffee-shopping'); ?>
        </form>
    </div>

<?php

}else{
    // If user is already logged in and is not an admin.
    ?>

    <div id="registrationNone">
        <div class="registrationblock">
            <div><h2><?php _e("Already registered", 'coffee-shopping' ); ?></h2></div>
            <div><?php _e("If you would like to register a new account please logout first.", 'coffee-shopping' ); ?> <a href="<?php echo $logoutPage;?>"><?php _e("Logout", 'coffee-shopping' ); ?></a></div>
        </div>

    </div>

<?php }?>