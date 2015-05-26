<?php

class Cart extends Collection{
    public $ID;
    public $address;

    public function __construct($ID, $address = '', $products = array()) {
        $prdc = $products;
        if(count($products) && is_array($products[0])){
            $prdc = [];
            foreach($products as $key => $prd){
                $prdc[$key] = new Product($prd['ID'],$prd['cart_id'],$prd['unique_store_id'],$prd['store'],$prd['img'],$prd['title'],$prd['price'],$prd['status']);
            }
        }

        $colOpts = array( 'collection' => $prdc, 'colName' => 'products'  );
        parent::__construct($colOpts);

        $this->ID = $ID;
        $this->address = $address;
    }

    public function generateRandomId($randId = ''){

        if($this->isUnique($randId) && !empty($randId)){
            return $randId;
        }
        $randId = '';
        for($i = 0; $i<10; $i++){
            $randId .= rand(1,100);
        }
        return $this->generateRandomId($randId);
    }

    public function isUnique ($id){
        $products = $this->get();
        foreach($products as $product){
            if($product->id == $id) return false;
        }
        return true;
    }


    public function setAddress($address){
        $this->address = $address;
    }

    public function getTotal(){
        $total = 0;
        $products = $this->get();
        foreach($products as $product){
            $total += $product->price;
        }
        return $total;
    }
}