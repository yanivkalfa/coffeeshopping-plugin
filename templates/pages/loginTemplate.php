<script language="javascript" type="text/javascript">
    // Set some vars.
    $ns.errorMessages = <?php echo json_encode($errorMessages);?>;
</script>

<form id="loginform" action="/wp-login.php" method="post">
    <div class="inline">
        <div id="userlogintitle">
            <h2><?php _e("Login details:", 'coffee-shopping' ); ?></h2>
        </div>
        <div class="form-group">
            <div><?php _e("Phone Number *", 'coffee-shopping' ); ?></div>
            <input type="text" class="form-control" name="log" />
        </div>
        <div class="form-group">
            <div><?php _e("Password *", 'coffee-shopping' ); ?></div>
            <input type="password" class="form-control" name="pwd" />
        </div>

        <div class="form-group">
            <div><?php _e("Don't have an account?", 'coffee-shopping' ); ?><a href="<?php echo $registerPage;?>"><?php _e("Register!", 'coffee-shopping' ); ?></a></div>
        </div>

        <?php if(isset($_GET['status']) && $_GET['status'] == 'failed') { ?>
            <div class="alert-error loginerror"><?php _e("Login Failed", 'coffee-shopping' ); ?></div>
        <?php } ?>

        <div class="form-group">
            <input name="redirect_to" value="<?php echo ($referrer ? $referrer : '' ) ?>" type="hidden">
            <input type="submit" class="btn btn-primary form-control full-width-button" value="<?php _e("Login", 'coffee-shopping' ); ?>" />
        </div>
    </div>

</form>