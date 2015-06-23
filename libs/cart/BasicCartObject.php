<?php

class BasicCartObject{
    public $ID;

    public function getAsArray(){
        return (array)$this;
    }

    public function getChildrenAsArray($children){
        foreach($children as $key => $child){
            $children[$key] = $child->getObjectAsArray();
        }
        return $children;
    }

    public function getObjectAsArray(){
        $object = $this->getAsArray();
        $object['products'] = $this->getChildrenAsArray($object['products']);
        return $object;
    }

    public function checkLimitation($quantity, $available_quantity, $order_limit){

        $productLimit = 0;
        $limitsArr = [];

        if($available_quantity > 0){
            $limitsArr[] = $available_quantity;
        }

        if($order_limit > 0){
            $limitsArr[] = $order_limit;
        }

        if(count($limitsArr)){
            $productLimit = min($limitsArr);
        }

        if($productLimit > 0 && $quantity > $productLimit){
            return false;
        }

        return true;
    }
}