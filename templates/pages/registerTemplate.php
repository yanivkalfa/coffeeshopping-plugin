<script language="javascript" type="text/javascript">
    // Set some vars.
    $ns.errorMessages = <?php echo json_encode($errorMessages);?>;
</script>

<?php
if(is_super_admin() || !is_user_logged_in()){
    // If the user is not logged in Or an Admin.
    ?>
    <div id="registrationformcont">
        <form id="registerForm" class="registrationblock">
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

                <div class="form-group form-ltr">
                    <label><?php _e("Password *", 'coffee-shopping' ); ?></label>
                    <div class="phonearea">
                        <input id="logininput" type="text" class="form-control" name="log"/>
                        <div id="inputvalidatorOK"></div>
                        <div id="inputvalidatorERR"></div>
                    </div>
                </div>

                <div class="form-group form-ltr">
                    <label><?php _e("Confirm Password *", 'coffee-shopping' ); ?></label>
                    <div class="phonearea">
                        <input id="logininput" type="text" class="form-control" name="log"/>
                        <div id="inputvalidatorOK"></div>
                        <div id="inputvalidatorERR"></div>
                    </div>
                </div>

                <div id="form-alert" class="display-none"><?php _e("Register successfully", 'coffee-shopping' ); ?></div>

                <div class="form-group form-ltr">
                    <input id="userloginbutton" type="submit" class="btn btn-primary form-control full-width-button" value="<?php _e("Register", 'coffee-shopping' ); ?>" />
                </div>
            </div>

        </form>
    </div>


    <div id="registrationdone">

        <div class="registrationblock">
            <div><h2><?php _e("Thank you for registering!", 'coffee-shopping' ); ?></h2></div>
            <div><?php _e("An automated login password was generated for you, ", 'coffee-shopping' ); ?><b><u><?php _e("please memorize it!", 'coffee-shopping' ); ?></u></b></div>
            <div class="inline"><?php _e("Password:", 'coffee-shopping' ); ?> </div><div id="passwordfield" class="inline">1234</div>
            <div class="registrationnote"><?php _e("* You can change your password in your", 'coffee-shopping' ); ?> <a href="<?php echo $myAccountPage;?>"><?php _e("account page.", 'coffee-shopping' ); ?></a></div>
            <?php if ($referrer){?>
                <div><a href="<?php echo $referrer;?>"><?php _e("Go back to where you were.", 'coffee-shopping' ); ?></a> </div>
            <?php } ?>
        </div>

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