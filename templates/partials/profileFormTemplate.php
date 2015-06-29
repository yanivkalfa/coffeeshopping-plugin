<?php
global $current_user;
get_currentuserinfo();
?>
<div id="profileform">
    <h4>Profile details:</h4>
    <div>
        <div class="inline header">
            Account Phone #:
        </div>
        <div class="inline detail form-group">
            <?php echo $user->user_login; ?>
        </div>
    </div>
    <div>
        <div class="inline header">
            First Name:
        </div>
        <div class="inline detail form-group">
            <input type="text" name="first_name" id="first_name" value="<?php echo $current_user->user_firstname; ?>">
        </div>
    </div>

    <div>
        <div class="inline header">
            Last Name:
        </div>
        <div class="inline detail form-group">
            <input type="text" name="last_name" id="last_name" value="<?php echo $current_user->user_lastname; ?>">
        </div>
    </div>

    <div>
        <div class="inline header">
            E-mail:
        </div>
        <div class="inline detail form-group">
            <input type="email" name="user_email" id="user_email" value="<?php echo $user->user_email; ?>">
        </div>
    </div>

    <div>
        <div class="inline header">
            New Password:
        </div>
        <div class="inline detail form-group">
            <input type="password" name="pass1" id="pass1" size="16" value="" autocomplete="off">
        </div>
    </div>
    <div>
        <div class="inline header">
            Repeat New Password:
        </div>
        <div class="inline detail form-group">
            <input type="password" name="pass2" id="pass2" size="16" value="" autocomplete="off">
        </div>
    </div>
    <div>
        * Fill in only if you wish to update you're password.
    </div>
    <input type="hidden" name="ID" value="<?php echo $user->ID; ?>">
</div>