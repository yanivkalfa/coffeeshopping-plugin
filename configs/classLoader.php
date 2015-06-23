<?php
class ClassLoader{

    public function __construct() {
        $this->loadClasses();

    }

    public function loadClasses(){
        $pluginFolder = dirname(dirname(__FILE__));
        require_once $pluginFolder.'/libs/ajax/Ajax_handler.php';
        require_once $pluginFolder.'/libs/cart/CartHelper.php';
        require_once $pluginFolder.'/libs/cart/BasicCartObject.php';
        require_once $pluginFolder.'/libs/cart/Collection.php';
        require_once $pluginFolder.'/libs/cart/Address.php';
        require_once $pluginFolder.'/libs/cart/ProductPriceModifier.php';
        require_once $pluginFolder.'/libs/cart/Product.php';
        require_once $pluginFolder.'/libs/cart/cart.php';

    }
}

$ClassLoader = new ClassLoader();

