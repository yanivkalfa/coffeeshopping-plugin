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
    public $delivery_min;
    public $delivery_max;

    public function __construct($product=NULL){

        if(is_array($product)) {
            $this->ID = isset($product['ID']) ? $product['ID'] : null;
            $this->cart_id = isset($product['cart_id']) ? $product['cart_id'] : null;
            $this->unique_store_id = $product['unique_store_id'];
            $this->store = isset($product['store']) ? $product['store'] : null;
            $this->store_link = isset($product['store_link']) ? $product['store_link'] : null;
            $this->selected_variant = isset($product['selected_variant']) ? $product['selected_variant'] : array();
            $this->selected_variant_sku = isset($product['selected_variant_sku']) ? $product['selected_variant_sku'] : null;
            $this->img = isset($product['img']) ? $product['img'] : null;
            $this->title = isset($product['title']) ? $product['title'] : null;
            $this->price = (float)isset($product['price']) ? $product['price'] : 0;
            $this->status = isset($product['status']) ? $product['status'] : null;
            $this->quantity = (int)isset($product['quantity']) ? $product['quantity'] : 1;
            $this->available_quantity = (int)isset($product['available_quantity']) ? $product['available_quantity'] : 1;
            $this->order_limit = (int)isset($product['order_limit']) ? $product['order_limit'] : 1;
            $this->delivery_min = isset($product['delivery_min']) ? $product['delivery_min'] : null;
            $this->delivery_max = isset($product['delivery_max']) ? $product['delivery_max'] : null;


            if($product['price_modifiers'] && count($product['price_modifiers'])){
                if(is_array($product['price_modifiers'][0])){
                    foreach($product['price_modifiers'] as $key => $PPM){
                        $this->price_modifiers[$key] = new ProductPriceModifier($PPM);
                    }
                }else if(is_object($product['price_modifiers'][0])){
                    $this->price_modifiers = $product['price_modifiers'];
                }
            }
        }
    }

    public function getPrice(){
        return $this->price;
    }

    public function getPriceAfterQuantity(){
        return $this->getPrice() * $this->quantity;
    }

    public function sumPriceModifiers (){
        $sum = 0;
        foreach($this->price_modifiers as $key => $PPM){
            $sum += $PPM->getModifierValue($this->getQuantity());
        }

        return $sum;
    }

    public function getCalculatedPrice(){
        return $this->getPriceAfterQuantity() + $this->sumPriceModifiers();
    }

    public function getPriceModifiers(){
        return $this->price_modifiers;
    }

    public function getQuantity(){
        return $this->quantity;
    }

    public function updateQuantity($quantity){
        if($quantity < 0 || !is_int($quantity)) return false;

        if(!$this->checkLimitation($quantity, $this->available_quantity, $this->order_limit)){
            return false;
        }

        $this->quantity = $quantity;
        return true;
    }

    public function getObjectAsArray(){
        $object = $this->getAsArray();
        $object['price_modifiers'] = $this->getChildrenAsArray($object['price_modifiers']);
        return $object;
    }

}