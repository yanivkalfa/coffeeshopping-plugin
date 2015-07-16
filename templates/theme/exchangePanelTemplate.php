<?php
/**
 * Created by PhpStorm.
 * User: SK
 * Date: 7/13/2015
 * Time: 7:05 PM
 */
$exchanger = new currencyExchange();
?>

<div class="exchangeRatesPanel">
    <div>
        <h2 class="title">
            <div class="inline"> <i class="fa fa-university"></i> <?php _e("Exchange Rates:", "coffee-shopping");?></div>
        </h2>
    </div>

    <div class="exchangePanelCont">
        <div class="detail">
            <div class="inline header"><?php _e("USD:", "coffee-shopping");?></div>
            <div class="inline detail"><span>$1</span> <i class="fa fa-exchange"></i> ₪<?php echo $exchanger->exchangeRate("USD", "ILS");?></div>
        </div>
        <div class="detail">
            <div class="inline header"><?php _e("EUR:", "coffee-shopping");?></div>
            <div class="inline detail"><span>&euro;1</span> <i class="fa fa-exchange"></i> ₪<?php echo $exchanger->exchangeRate("EUR", "ILS");?></div>
        </div>
        <div class="detail">
            <div class="inline header"><?php _e("GBP:", "coffee-shopping");?></div>
            <div class="inline detail"><span>&pound;1</span> <i class="fa fa-exchange"></i> ₪<?php echo $exchanger->exchangeRate("GBP", "ILS");?></div>
        </div>
    </div>

    <div class="exchangePanelCreds">
        <div><?php _e("*Rates provided by", "coffee-shopping");?></div>
        <div><a href="http://www.ecb.int"><?php _e("European Central Bank", "coffee-shopping");?></a></div>
    </div>

</div>