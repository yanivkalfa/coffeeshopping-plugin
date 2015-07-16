<form id="searchwidgetcont"  class="form-search center" role="search" method="get" action="<?php echo esc_url( $searchPageLink ); ?>">
	<div id="searchinputdiv">
    <div id="searchwidgetdiv" class="input-prepend">
		<input id="searchwidgetinput" type="text" class="span2 search-query" name="search-product"
               placeholder="<?php _e("Search", 'coffee-shopping' ); ?>"
               value="<?php echo (isset($_GET["search-product"])) ? wp_kses( $_GET["search-product"], null ) : ""; ?>">
		<button id="searchwidgetbutton" type="submit" class="btn btn-primary" value="<?php _e( 'Search', 'coffee-shopping' ); ?>"><?php _e("Search", 'coffee-shopping' ); ?></button>
	</div>
        <i id="searchadvbutton" class="fa fa-bars fa-2x<?php echo ($adv) ? ' active' : "";?>" title="<?php _e("Advanced search", 'coffee-shopping' ); ?>"></i>
    </div>

    <div id="searchwidgetadvanceddiv" <?php echo (!$adv) ? 'class="display-none"' : "";?>>
        <div class="storesdiv inline">
            <div class="inline header"><?php _e("Stores:", "coffee-shopping");?></div>
            <div class="inline">
                <?php
                    $checked = ($adv && isset($_GET["storesrc"]) && in_array("ebay",$_GET["storesrc"])) ? ' checked="checked"' : "";
                ?>
                <input id="ebaybox" class="storecheckbox" type="checkbox" value="ebay" name="storesrc[]"<?php echo $checked;?>>
                <label for="ebaybox"><?php _e("eBay", "coffee-shopping");?></label>
            </div>
            <div class="inline">
                <?php
                    $checked = ($adv && isset($_GET["storesrc"]) && in_array("aliexp",$_GET["storesrc"])) ? ' checked="checked"' : "";
                ?>
                <input id="alibox" class="storecheckbox" type="checkbox" value="aliexp" name="storesrc[]"<?php echo $checked;?> disabled="disabled">
                <label for="alibox"><?php _e("aliexpress", "coffee-shopping");?></label>
            </div>
        </div>

        <div class="conditionsdiv inline">
            <div class="inline header"><?php _e("Condition:", "coffee-shopping");?></div>
            <div class="inline">
                <?php
                    $checked = ($adv && isset($_GET["conditions"]) && in_array("New",$_GET["conditions"])) ? ' checked="checked"' : "";
                ?>
                <input id="newcond" class="conditionscheckbox" type="checkbox" value="New" name="conditions[]"<?php echo $checked;?>>
                <label for="newcond"><?php _e("new", "coffee-shopping");?></label>
            </div>
            <div class="inline">
                <?php
                    $checked = ($adv && isset($_GET["conditions"]) && in_array("Used",$_GET["conditions"])) ? ' checked="checked"' : "";
                ?>
                <input id="usedcond" class="conditionscheckbox" type="checkbox" value="Used" name="conditions[]"<?php echo $checked;?>>
                <label for="usedcond"><?php _e("used", "coffee-shopping");?></label>
            </div>
        </div>

        <div class="sortingdiv inline">
            <div class="inline header"><?php _e("Sort by:", "coffee-shopping");?></div>
            <div class="inline">
                <select id="sortOrder" name="sortOrder">
                    <option value="BestMatch"<?php echo ($adv && isset($_GET["sortOrder"]) && $_GET["sortOrder"]=="BestMatch") ? ' selected="selected"' : "";?>><?php _e("Best match", "coffee-shopping");?></option>
                    <option value="PricePlusShippingLowest"<?php echo ($adv && isset($_GET["sortOrder"]) && $_GET["sortOrder"]=="PricePlusShippingLowest") ? ' selected="selected"' : "";?>><?php _e("Price + shipping lowest", "coffee-shopping");?></option>
                    <option value="PricePlusShippingHighest"<?php echo ($adv && isset($_GET["sortOrder"]) && $_GET["sortOrder"]=="PricePlusShippingHighest") ? ' selected="selected"' : "";?>><?php _e("Price + shipping highest", "coffee-shopping");?></option>
                </select>
            </div>
        </div>

    </div>
    <input id="advsearcher" type="hidden" name="adv" value="<?php echo $adv;?>">
</form>