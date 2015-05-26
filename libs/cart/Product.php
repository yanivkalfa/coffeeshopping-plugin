<?php

class Product {
    public $ID;
    public $cart_id;
    public $unique_store_id;
    public $store;
    public $img;
    public $title;
    public $price;
    public $status;

    public function __construct($ID,$cart_id,$unique_store_id,$store='',$img='',$title='',$price='',$status=''){

        if(empty($ID) || empty($cart_id) || empty($unique_store_id) ) return false;

        $this->ID = $ID;
        $this->cart_id = $cart_id;
        $this->unique_store_id = $unique_store_id;
        $this->store = $store;
        $this->img = $img;
        $this->title = $title;
        $this->price = (float)$price;
        $this->status = $status;
    }


}