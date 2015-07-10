<div id="header-first" class="headerblock">
    <?php
    /**
     * GET LOGO
     */
    Utils::getTemplate("logo", null, "theme/");
    ?>
</div>

<div id="header-middle" class="headerblock">
    <div id="header-search">
        <?php
        /**
         * GET Search
         */
        $searchPageLink = get_permalink(get_option("cs_search_p_id"));
        if (!$searchPageLink){
            Utils::adminPreECHO(__("Can't get search page link", 'coffee-shopping' ), __("searchWidget.php ERROR:: ", 'coffee-shopping' ));
            echo Utils::getErrorCode("frontEnd", "widget", "searchWidget", "7");
            return;
        }
        $scope = array(
            "searchPageLink" => $searchPageLink,
            "adv" => (isset($_GET["adv"]) && !empty($_GET["adv"])) ? $_GET["adv"] : 0
        );
        Utils::getTemplate('searchWidget', $scope);
        ?>
    </div>
    <div id="header-menu">
        <?php wp_nav_menu( array('menu' => 'Main' )); ?>
    </div>
</div>

<div id="header-last" class="headerblock">
    <?php
    /**
     * GET CART
     */
    $myCartWidgetPageLink = get_permalink(get_option("cs_cart_p_id"));
    if (!$myCartWidgetPageLink){
        Utils::adminPreECHO(__("Can't get search page link", 'coffee-shopping' ), __("myCartWidget.php ERROR:: ", 'coffee-shopping' ));
        echo Utils::getErrorCode("frontEnd", "widget", "myCartWidget", "7");
        return;
    }
    $cart = array(
        'productCount' => 0
    );
    if(isset($_SESSION['cart'])){
        $cart =  $_SESSION['cart']->getStats();
    }
    $cart['page'] = $myCartWidgetPageLink;
    Utils::getTemplate('myCartWidget', $cart);
    ?>
</div>

