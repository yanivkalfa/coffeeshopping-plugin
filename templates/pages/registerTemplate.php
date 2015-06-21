<script language="javascript" type="text/javascript">
    // Set some vars.
    $ns.errorMessages = <?php echo json_encode($errorMessages);?>;
</script>

<form id="registerForm">
    <div class="inline">
        <div id="form-alert" class="display-none">Register successfully</div>

        <div>
            <h4>Register to CoffeeShopping:</h4>
        </div>

        <div class="form-group form-ltr">
            <label>Phone Number *</label>
            <input type="text" class="form-control" name="log"/>
        </div>

        <div class="form-group form-ltr">
            <label>Password *</label>
            <input type="password" class="form-control" name="pwd" />
        </div>

        <div class="form-group form-ltr">
            <label>Confirm Password *</label>
            <input type="password" class="form-control" name="cpwd" />
        </div>

        <div class="form-group form-ltr">
            <input id="userloginbutton" type="submit" class="btn btn-primary form-control full-width-button" value="Register" />
        </div>
    </div>

</form>