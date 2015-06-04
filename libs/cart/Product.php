<?php

class Product {
    public $ID;
    public $cart_id;
    public $unique_store_id;
    public $store;
    public $img;
    public $title;
    public $price_modifiers = array();
    public $price;
    public $status;

    public function __construct($product=NULL){

        if(is_array($product)) {
            $this->ID = $product['ID'];
            $this->cart_id = $product['cart_id'];
            $this->unique_store_id = $product['unique_store_id'];
            $this->store = $product['store'];
            $this->img = $product['img'];
            $this->title = $product['title'];
            $this->price = (float)$product['price'];
            $this->status = $product['status'];

            if($product['price_modifiers'] && count($product['price_modifiers']) && is_array($product['price_modifiers'][0])){
                foreach($product['price_modifiers'] as $key => $PPD){
                    $this->price_modifiers[$key] = new ProductPriceModifier($PPD);
                }
            }
        }
    }

    public function getPrice(){
        return $this->price + $this->sumPriceModifiers();
    }

    public function sumPriceModifiers (){
        $sum = 0;

        foreach($this->price_modifiers as $key => $PPD){
            $sum += $PPD->value;
        }

        return $sum;
    }

    public function getPriceModifiers(){
        return $this->price_modifiers;
    }

}