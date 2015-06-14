<?php

class ProductPriceModifier extends BasicCartObject {
    public $name;
    public $nameAs;
    public $value;

    public function __construct($ProductPriceModifier=NULL){

        if(is_array($ProductPriceModifier)) {
            $this->name = $ProductPriceModifier['name'];
            $this->nameAs = $ProductPriceModifier['nameAs'];
            $this->value = $ProductPriceModifier['value'];
        }
    }

    public function getObjectAsArray(){
        return $this->getAsArray();
    }
}