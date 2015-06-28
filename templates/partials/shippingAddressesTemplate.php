<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 6/27/2015
 * Time: 1:51 PM
 */

/**
 * A template to display a user's shipping addresses.
 * Requires:
 *  $addresses      array                   - An array of arrays containing addresses info.
 *  $header         string  default-''      - A header to display.
 *  $selectable     bool    default-true    - Should we include selection radios.
 *  $actions        bool    default-true    - Should we include managing actions.
 */

$header = (isset($header)) ? $header : "";
$selectable = (isset($selectable)) ? $selectable : true;
$actions = (isset($actions)) ? $actions : true;
?>

<div id="savedAddresses" class="inline">
<?php if(isset($addresses) && !empty($addresses)) { ?>
    <h4> <?php echo $header;?> </h4>
    <?php foreach($addresses as $address){ ?>
        <div class="single-address saved-address">
            <?php if ($selectable){ ?>
            <div class="inline addressradio">
                <input type="radio" name="address_id" value="<?php echo $address['ID'];?>" id="addressradio_<?php echo $address['ID'];?>" />
            </div>
            <?php } ?>

            <?php
                $scope = array(
                    'address' => $address
                );
                Utils::getTemplate('singleAddress', $scope);
            ?>

            <?php if ($actions){ ?>
            <div class="inline addressactions flleft">
                <div class="inline removeaddress"><a href="javascript:;">[X]</a></div>
            </div>
            <?php } ?>
        </div>
    <?php } ?>
<?php } ?>
</div>