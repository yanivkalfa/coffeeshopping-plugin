<?php

class ProductPriceModifier extends BasicCartObject {
    public $name;
    public $nameAs;
    public $value;
    public $additional;

    public function __construct($ProductPriceModifier=NULL){

        if(is_array($ProductPriceModifier)) {
            $this->name = $ProductPriceModifier['name'];
            $this->nameAs = $ProductPriceModifier['nameAs'];
            $this->value = $ProductPriceModifier['value'];
            $this->additional = $ProductPriceModifier['additional'];
        }
    }

    public function getObjectAsArray(){
        return $this->getAsArray();
    }
}