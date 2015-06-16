<?php

class ProductPriceModifier extends BasicCartObject {
    public $name;
    public $nameAs;
    public $value;
    public $additional;

    public function __construct($ProductPriceModifier=NULL){

        if(is_array($ProductPriceModifier)) {
            $this->name = isset($ProductPriceModifier['name']) ? $ProductPriceModifier['name'] : '';
            $this->nameAs = isset($ProductPriceModifier['name']) ? $ProductPriceModifier['name'] : '';
            $this->value = isset($ProductPriceModifier['value']) ? $ProductPriceModifier['value'] : 0;
            $this->additional = isset($ProductPriceModifier['additional']) ? $ProductPriceModifier['additional'] : 0;
        }
    }

    public function getObjectAsArray(){
        return $this->getAsArray();
    }

    public function getModifierValue($quantity){
        if(!$quantity) return 0;
        if($this->additional){
            if($quantity > 1) {
                return $this->value + $this->additional*($quantity-1);
            }else{
                return $this->value;
            }

        }else{
            return $this->value * $quantity;
        }

    }
}