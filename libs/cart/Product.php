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
    public $quantity;

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
            $this->quantity = (int)$product['quantity'];

            if($product['price_modifiers'] && count($product['price_modifiers'])){
                if(is_array($product['price_modifiers'][0])){
                    foreach($product['price_modifiers'] as $key => $PPD){
                        $this->price_modifiers[$key] = new ProductPriceModifier($PPD);
                    }
                }else if(is_object($product['price_modifiers'][0])){
                    $this->price_modifiers = $product['price_modifiers'];
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

    public function getPriceAfterQuantity(){
        return $this->getPrice() * $this->quantity;
    }

    public function getPriceModifiers(){
        return $this->price_modifiers;
    }

}