<?php
global $current_user;
get_currentuserinfo();
?>
<div id="profileform">
    <h4><?php _e("Profile details:", 'coffee-shopping' ); ?></h4>
    <div>
        <div class="inline header">
            <?php _e("Account Phone #:", 'coffee-shopping' ); ?>
        </div>
        <div class="inline detail form-group">
            <?php echo $user->user_login; ?>
        </div>
    </div>
    <div>
        <div class="inline header">
            <?php _e("First Name:", 'coffee-shopping' ); ?>
        </div>
        <div class="inline detail form-group">
            <input type="text" name="first_name" id="first_name" value="<?php echo $current_user->user_firstname; ?>">
        </div>
    </div>

    <div>
        <div class="inline header">
            <?php _e("Last Name:", 'coffee-shopping' ); ?>
        </div>
        <div class="inline detail form-group">
            <input type="text" name="last_name" id="last_name" value="<?php echo $current_user->user_lastname; ?>">
        </div>
    </div>

    <div>
        <div class="inline header">
            <?php _e("E-mail:", 'coffee-shopping' ); ?>
        </div>
        <div class="inline detail form-group">
            <input type="email" name="user_email" id="user_email" value="<?php echo $user->user_email; ?>">
        </div>
    </div>

    <div>
        <div class="inline header">
            <?php _e("New Password:", 'coffee-shopping' ); ?>
        </div>
        <div class="inline detail form-group">
            <input type="password" name="pass1" id="pass1" size="16" value="" autocomplete="off">
        </div>
    </div>
    <div>
        <div class="inline header">
            <?php _e("Repeat New Password:", 'coffee-shopping' ); ?>
        </div>
        <div class="inline detail form-group">
            <input type="password" name="pass2" id="pass2" size="16" value="" autocomplete="off">
        </div>
    </div>
    <div>
        <?php _e("* Fill in only if you wish to update you're password.", 'coffee-shopping' ); ?>
    </div>
    <input type="hidden" name="ID" value="<?php echo $user->ID; ?>">
</div>