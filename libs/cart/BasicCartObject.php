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
        $object['address'] = $this->address->getAsArray();
        return $object;
    }
}