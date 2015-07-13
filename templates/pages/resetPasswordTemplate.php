<script language="javascript" type="text/javascript">
    // Set some vars.
    $ns.errorMessages = <?php echo json_encode($errorMessages);?>;
    $ns.loginPage = <?php echo json_encode($loginPage);?>;
</script>

<div id="registrationformcont">
    <form id="resetPassword" class="registrationblock">
        <div class="inline">
            <div>
                <h2><?php _e("Reset your password:", 'coffee-shopping' ); ?></h2>
            </div>

            <div id="requestResetTab">
                <div class="form-group form-ltr">
                    <label><?php _e("Phone Number *", 'coffee-shopping' ); ?></label>
                    <div class="phonearea">
                        <input id="log" type="text" class="form-control" name="log"/>
                        <div id="inputvalidatorOK"></div>
                        <div id="inputvalidatorERR"></div>
                    </div>
                </div>

                <div  id="form-alert" class="display-none"></div>
                <div class="form-group form-ltr">
                    <input id="resetPassword" type="submit" name="submitUser" class="btn btn-primary form-control full-width-button" value="<?php _e("Reset", 'coffee-shopping' ); ?>" />
                </div>
            </div>

            <div id="verifyTokenTab" class="display-none">
                <div class="form-group form-ltr">
                    <label><?php _e("Verification Token", 'coffee-shopping' ); ?></label>
                    <div class="phonearea">
                        <input id="verifyTokenInput" type="text" class="form-control" name="verifyTokenInput"/>
                    </div>
                </div>

                <div  id="verify-token-alert" class="display-none"></div>
                <div class="form-group form-ltr">
                    <span id="verifyToken" class="btn btn-primary form-control full-width-button" ><?php _e("Verify Token", 'coffee-shopping' ); ?></span>
                    <a id="resentVerification" href="javascript:;"><?php _e("Send verification code again!", 'coffee-shopping' ); ?></a>
                </div>
            </div>
        </div>
    </form>
</div>