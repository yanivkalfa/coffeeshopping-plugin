<?php
class ClassLoader{

    public function __construct() {
        $this->loadClasses();

    }

    public function loadClasses(){
        require_once 'cart/Collection.php';
        require_once 'cart/Address.php';
        require_once 'cart/ProductPriceModifier.php';
        require_once 'cart/Product.php';
        require_once 'cart/Cart.php';

    }
}

$ClassLoader = new ClassLoader();

