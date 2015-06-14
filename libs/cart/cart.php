<?php

class Cart extends Collection{
    public $user_id;
    public $deliver_to;
    public $address_id;
    public $payment_method;
    public $purchase_location;
    public $status;
    public $create_date;
    public $address;

    public function __construct($cart = NULL, Address $address = NULL, $products = NULL) {
        $prdc = $products;
        if($products && count($products) && is_array($products[0])){
            $prdc = [];
            foreach($products as $key => $prd){
                $prdc[$key] = new Product($prd);
            }
        }

        $colOpts = array( 'collection' => $prdc, 'colName' => 'products', 'prop'=> 'unique_store_id'  );
        parent::__construct($colOpts);

        if(is_array($cart)){
            $this->ID = $cart['ID'];
            $this->user_id = $cart['user_id'];
            $this->deliver_to = $cart['deliver_to'];
            $this->address_id = $cart['address_id'];
            $this->payment_method = $cart['payment_method'];
            $this->purchase_location = $cart['purchase_location'];
            $this->status = $cart['status'];
            $this->create_date = $cart['create_date'];
        }

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

    public function add ($item, $prop = false){
        $index = $this->indexOf($item, $prop? : $this->prop);
        if($index > -1) {
            $product = $this->get()[$index];
            $product->setQuantity($product->getQuantity()+1);
            return true;
        }

        return $this->{$this->colName}[] = $item;
    }


    public function setAddress($address){
        $this->address = $address;
    }

    public function getTotal(){
        $total = 0;
        $products = $this->get();
        foreach($products as $product){
            $total += $product->getPriceAfterQuantity();
        }
        return $total;
    }

    public function getStats(){
        return array(
            'total' => $this->getTotal(),
            'productCount' => count($this->get())
        );
    }
}

/*
Utils::preEcho($_SESSION['cart']->products);

$_SESSION['cart']->add(new Product(15, 1, 153, 'ebay','','bekini', 230));
$_SESSION['cart']->add(new Product(5, 1, 153, 'ebay', '', 'bycles', 123));

Utils::preEcho($_SESSION['cart'], '<br> total:'.$_SESSION['cart']->getTotal());

unset($_SESSION['cart']);


$products = [
    [
        'ID' => 5,
        'cart_id' => 1,
        'unique_store_id' => 250,
        'store' => 'ebay',
        'img' => '',
        'title' => 'bekini',
        'price' => 432,
        'status' => ''
    ],
    [
        'ID' => 5,
        'cart_id' => 1,
        'unique_store_id' => 153,
        'store' => 'ebay',
        'img' => '',
        'title' => 'bycles',
        'price' => 123,
        'status' => ''
    ]
];

$_SESSION['cart'] = new Cart(0, new Address(0), $products);

Utils::preEcho($_SESSION['cart'], '<br> total:'.$_SESSION['cart']->getTotal());
*/