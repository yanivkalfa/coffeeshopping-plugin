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
                    <h2>Register to CoffeeShopping:</h2>
                </div>

                <div class="form-group form-ltr">
                    <label>Phone Number *</label>
                    <div class="phonearea">
                        <input id="logininput" type="text" class="form-control" name="log"/>
                        <div id="inputvalidatorOK"></div>
                        <div id="inputvalidatorERR"></div>
                    </div>
                </div>

                <div id="form-alert" class="display-none">Register successfully</div>

                <div class="form-group form-ltr">
                    <input id="userloginbutton" type="submit" class="btn btn-primary form-control full-width-button" value="Register" />
                </div>
            </div>

        </form>
    </div>


    <div id="registrationdone">

        <div class="registrationblock">
            <div><h2>Thank you for registering!</h2></div>
            <div>An automated login password was generated for you, <b><u>please memorize it!</u></b></div>
            <div class="inline">Password: </div><div id="passwordfield" class="inline">1234</div>
            <div class="registrationnote">* You can change your password in your <a href="<?php echo $myAccountPage;?>">account page.</a></div>
            <?php if ($referrer){?>
                <div>Click <a href="<?php echo $referrer;?>">HERE</a> to go back to where you were. </div>
            <?php } ?>
        </div>

    </div>

<?php

}else{
    // If user is already logged in and is not an admin.
    ?>

    <div id="registrationNone">

        <div class="registrationblock">
            <div><h2>Already registered</h2></div>
            <div>If you would like to register a new account please logout first. <a href="<?php echo $logoutPage;?>">Logout</a></div>
        </div>

    </div>

<?php }?>