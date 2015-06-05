<?php
class ClassLoader{

    public function __construct() {
        $this->loadClasses();

    }

    public function loadClasses(){
        $pluginFolder = dirname(dirname(__FILE__));
        require_once $pluginFolder.'/libs/cart/Collection.php';
        require_once $pluginFolder.'/libs/cart/address.php';
        require_once $pluginFolder.'/libs/cart/ProductPriceModifier.php';
        require_once $pluginFolder.'/libs/cart/Product.php';
        require_once $pluginFolder.'/libs/cart/cart.php';

    }
}

$ClassLoader = new ClassLoader();

