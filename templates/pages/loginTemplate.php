<script language="javascript" type="text/javascript">
    // Set some vars.
    $ns.errorMessages = <?php echo json_encode($errorMessages);?>;
</script>

<form id="loginform" action="/wp-login.php" method="post">
    <div class="inline">
        <?php if(isset($_GET['status']) && $_GET['status'] == 'failed') { ?>
            <div class="alert-warning">Login Failed</div>
        <?php } ?>

        <div id="userlogintitle">
            <h4>Login details:</h4>
        </div>
        <div class="form-group form-ltr">
            <label>Phone Number *</label>
            <input type="text" class="form-control" name="log" />
        </div>
        <div class="form-group form-ltr">
            <label>Password *</label>
            <input type="password" class="form-control" name="pwd" />
        </div>

        <div class="form-group form-ltr">
            <input name="redirect_to" value="<?php echo site_url(). ($referrer ? $referrer : '' ) ?>" type="hidden">
            <input type="submit" class="btn btn-primary form-control full-width-button" value="Login" />
        </div>
    </div>

</form>