<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/28/2015
 * Time: 7:21 PM
 */
?>

<div class="inline addressdets">
    <label for="addressradio_<?php echo $address['ID'];?>">
        <div class="addressName">
            <div class="inline">
                <b><?php echo $address['full_name'];?></b>
            </div>
            <div class="inline">
                - <i>(<?php echo $address['phone_number'];?>)</i>
            </div>
        </div>
        <div class="addressDetails">
            <?php echo $address['street']." ".$address['house']."/".$address['apt'].", ".$address['city'].", ".$address['postcode'].".";?>
        </div>
    </label>
</div>