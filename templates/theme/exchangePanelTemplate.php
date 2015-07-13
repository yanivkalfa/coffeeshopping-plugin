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
            <div class="inline"> <i class="fa fa-university"></i> <?php _e("Exchange Rates:", "coffee-shipping");?></div>
        </h2>
    </div>

    <div class="exchangePanelCont">
        <div class="detail">
            <div class="inline"><?php _e("USD:", "coffee-shipping");?></div>
            <div class="inline"><span>$1</span> <i class="fa fa-exchange"></i> ₪<?php echo $exchanger->exchangeRate("USD", "ILS");?></div>
        </div>
        <div class="detail">
            <div class="inline"><?php _e("EUR:", "coffee-shipping");?></div>
            <div class="inline"><span>&euro;1</span> <i class="fa fa-exchange"></i> ₪<?php echo $exchanger->exchangeRate("EUR", "ILS");?></div>
        </div>
        <div class="detail">
            <div class="inline"><?php _e("GBP:", "coffee-shipping");?></div>
            <div class="inline"><span>&pound;1</span> <i class="fa fa-exchange"></i> ₪<?php echo $exchanger->exchangeRate("GBP", "ILS");?></div>
        </div>
    </div>

    <div class="exchangePanelCreds">
        <div><?php _e("*Rates provided by", "coffee-shipping");?></div>
        <div><a href="http://www.ecb.int"><?php _e("European Central Bank", "coffee-shipping");?></a></div>
    </div>

</div>