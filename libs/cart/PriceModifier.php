<?php

class PriceModifier extends BasicCartObject {
    public $name;
    public $nameAs;
    public $value;
    public $additional;

    public function __construct($PriceModifier=NULL){

        if(is_array($PriceModifier)) {
            $this->name = isset($PriceModifier['name']) ? $PriceModifier['name'] : '';
            $this->nameAs = isset($PriceModifier['nameAs']) ? $PriceModifier['nameAs'] : '';
            $this->value = isset($PriceModifier['value']) ? $PriceModifier['value'] : 0;
            $this->additional = isset($PriceModifier['additional']) ? $PriceModifier['additional'] : 0;
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