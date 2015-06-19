<form id="loginform" action="/wp-login.php" method="post">
    <div id="userlogincont" class="inline">
        <div id="userlogintitle">
            <h4>Login details:</h4>
        </div>
        <div id="userloginphone">
            <input id="loginphone" type="tel" name="log"/>
        </div>
        <div id="userloginpassword">
            <input id="loginpassword" type="password" name="pwd" />
        </div>

        <input name="redirect_to" value="<?php echo site_url() ?>/myaccount/" type="hidden">
        <button id="userloginbutton" type="submit" class="btn btn-primary disabled">Login</button>&nbsp;

    </div>

</form>