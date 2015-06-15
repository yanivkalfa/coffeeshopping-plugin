<?php

class Product extends BasicCartObject {
    public $cart_id;
    public $unique_store_id;
    public $store;
    public $store_link;
    public $selected_variant;
    public $selected_variant_sku;
    public $img;
    public $title;
    public $price_modifiers = array();
    public $price;
    public $status;
    public $quantity;

    public function __construct($product=NULL){

        if(is_array($product)) {
            $this->ID = isset($product['ID']) ? $product['ID'] : null;
            $this->cart_id = isset($product['cart_id']) ? $product['cart_id'] : null;
            $this->unique_store_id = $product['unique_store_id'];
            $this->store = isset($product['store']) ? $product['store'] : null;
            $this->store_link = isset($product['store_link']) ? $product['store_link'] : null;
            $this->selected_variant = isset($product['selected_variant']) ? $product['selected_variant'] : null;
            $this->selected_variant_sku = isset($product['selected_variant_sku']) ? $product['selected_variant_sku'] : null;
            $this->img = isset($product['img']) ? $product['img'] : null;
            $this->title = isset($product['title']) ? $product['title'] : null;
            $this->price = (float)isset($product['price']) ? $product['price'] : 0;
            $this->status = isset($product['status']) ? $product['status'] : null;
            $this->quantity = (int)isset($product['quantity']) ? $product['quantity'] : 1;

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

    public function sumPriceModifiers (){
        $sum = 0;
        foreach($this->price_modifiers as $key => $PPD){
            $sum += $PPD->value;
        }

        return $sum;
    }

    public function getPrice(){
        return $this->price;
    }

    public function getPriceAfterQuantity(){
        return $this->getPrice() * $this->quantity;
    }

    public function getCalculatedPrice(){
        return $this->price + $this->sumPriceModifiers();
    }

    public function getCalculatedPriceAfterQuantity(){
        return $this->getCalculatedPrice() * $this->quantity;
    }

    public function getPriceModifiers(){
        return $this->price_modifiers;
    }

    public function getQuantity(){
        return $this->quantity;
    }

    public function updateQuantity($quantity){
        if($quantity < 0 || !is_int($quantity)) return false;
        return $this->quantity = $quantity;
    }

    public function getObjectAsArray(){
        $object = $this->getAsArray();
        $object['price_modifiers'] = $this->getChildrenAsArray($object['price_modifiers']);
        return $object;
    }

}