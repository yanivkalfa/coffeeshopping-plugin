<?php
    $backButton = (isset($backButton)) ? $backButton : false;
?>
<div id="addressform">
    <h4>
        <?php if ($backButton){ ?>
            <div class="inline"><a href="javascript:" title="<?php _e("Back to saved addresses", 'coffee-shopping' ); ?>" id="backButton"><i class="fa fa-arrow-circle-right"></i></a> </div>
        <?php } ?>
        <div class="inline"><?php _e("New address:", 'coffee-shopping' ); ?></div>
    </h4>
    <div class="form-group">
        <input type="text" class="form-control" placeholder="<?php _e("Full Name *", 'coffee-shopping' ); ?>" name="address[full_name]" />
    </div>
    <div class="form-group">
        <input type="text" class="form-control" placeholder="<?php _e("Phone Number *", 'coffee-shopping' ); ?>" name="address[phone_number]" />
    </div>
    <div class="form-group">
        <input type="text" class="form-control" placeholder="<?php _e("City *", 'coffee-shopping' ); ?>" name="address[city]" />
    </div>
    <div class="form-group">
        <input type="text" class="form-control" placeholder="<?php _e("Street *", 'coffee-shopping' ); ?>" name="address[street]" />
    </div>
    <div id="addressHouseApt" class="form-group">
        <input id="addressHouse" type="text" class="form-control" placeholder=<?php _e("House #*", 'coffee-shopping' ); ?>" name="address[house]" />
        <input id="addressApt" type="text" class="form-control" placeholder="<?php _e("Apartment #*", 'coffee-shopping' ); ?>" name="address[apt]" />
    </div>
    <div class="form-group">
        <input type="text" class="form-control" placeholder="<?php _e("Postal Code *", 'coffee-shopping' ); ?>" name="address[postcode]" />
    </div>
</div>