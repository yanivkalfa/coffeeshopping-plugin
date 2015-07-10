<?php

class Cart extends Collection{
    public $user_id;
    public $deliver_to;
    public $address_id;
    public $payment_method;
    public $payment_amount;
    public $purchase_location;
    public $status;
    public $note;
    public $create_date;
    public $delivered_date;

    public function __construct($cart = NULL, $products = NULL) {
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
            $cartStatus = CSCons::get('cartStatus') ?: array();
            $this->ID = isset($cart['ID']) ? $cart['ID'] : null;
            $this->user_id = isset($cart['user_id']) ? $cart['user_id'] : null;
            $this->deliver_to = isset($cart['deliver_to']) ? $cart['deliver_to'] : null;
            $this->address_id = isset($cart['address_id']) ? $cart['address_id'] : null;
            $this->payment_method = isset($cart['payment_method']) ? $cart['payment_method'] : null;
            $this->payment_amount = isset($cart['payment_amount']) ? $cart['payment_amount'] : null;
            $this->purchase_location = isset($cart['purchase_location']) ? $cart['purchase_location'] : null;
            $this->status = isset($cart['status']) ?  $cart['status'] : $cartStatus['saved']['name'] ;
            $this->note = isset($cart['note']) ? $cart['note'] : null;
            $this->create_date = isset($cart['create_date']) ? $cart['create_date'] : null;
            $this->delivered_date = isset($cart['create_date']) ? $cart['create_date'] : null;
        }

    }

    public function setUserId($user_id){
        $this->user_id = $user_id;
    }

    public function setDeliverTo($deliver_to){
        $this->deliver_to = $deliver_to;
    }

    public function setAddressId($address_id){
        $this->address_id = $address_id;
    }

    public function setPaymentMethod($payment_method){
        $this->payment_method = $payment_method;
    }

    public function setPaymentAmount(){
        $this->payment_amount = $this->getCalculatedTotal();
    }

    public function setPurchaseLocation($purchase_location){
        $this->purchase_location = $purchase_location;
    }

    public function add ($item, $prop = false){
        if(!$this->checkLimitation($item->quantity, $item->available_quantity, $item->order_limit)){
            return false;
        }
        $index = $this->indexOf($item, $prop? : $this->prop);

        if($index > -1) {
            $product = $this->get()[$index];
            return $product->updateQuantity($product->getQuantity()+1);
        }

        return $this->{$this->colName}[] = $item;
    }

    public function getTotal(){
        $total = 0;
        $products = $this->get();
        foreach($products as $product){
            $total += $product->getPriceAfterQuantity();
        }
        return $total;
    }

    public function getTotalAfterProductModifiers(){
        $total = 0;
        $products = $this->get();
        foreach($products as $product){
            $total += $product->getCalculatedPrice();
        }

        return $total;
    }

    public function getCalculatedTotal(){
        $total = $this->getTotalAfterProductModifiers();
        $total += $this->getPayPalFees($total);
        $total += $this->getStoreCommission($total);
        return $total;
    }


    public function getStats($extended = false){
        $productCount = 0;
        foreach($this->get() as $key => $product){
            $productCount += $product->getQuantity();
        }

        if($extended){
            return array(
                'total' => CartHelper::formatNumber($this->getTotal()).Utils::getCurrencySymbol("ILS"),
                'calculatedTotal' => CartHelper::formatNumber($this->getCalculatedTotal(),2).Utils::getCurrencySymbol("ILS"),
                'productCount' => $productCount,
                'aggregatedPriceModifiers' => CartHelper::formatAggregatedPriceModifiers($this->getAggregatedPriceModifiers()),
            );
        }else{
            return array(
                'total' => CartHelper::formatNumber($this->getTotal()),
                'productCount' => $productCount
            );
        }

    }

    public function getAggregatedPriceModifiers(){
        $modifiers = array();
        foreach($this->get() as $key => $product){
            foreach($product->getPriceModifiers() as $mKey => $modifier){
                if(!isset($modifiers[$modifier->name])) {
                    $modifiers[$modifier->name] = new ProductPriceModifier((array)$modifier);
                    $modifiers[$modifier->name]->value = $modifier->getModifierValue($product->getQuantity());
                }else{
                    $modifiers[$modifier->name]->value += $modifier->getModifierValue($product->getQuantity());
                }
            }
        }
        $total = $this->getTotalAfterProductModifiers();
        $PayPalFees = $this->getPayPalFees($total);
        $PayPalFeesArr = array(
            'name' => 'PayPalFees',
            'nameAs' => 'PayPal Fees',
            'value' => $PayPalFees,
        );
        $modifiers['PayPalFees'] = new ProductPriceModifier($PayPalFeesArr);

        $total += $PayPalFees;
        $storeCommissionArr = array(
            'name' => 'storeCommission',
            'nameAs' => 'Store Commission',
            'value' => $this->getStoreCommission($total),
        );
        $modifiers['storeCommission'] = new ProductPriceModifier($storeCommissionArr);

        return array_values($modifiers);
    }

    public function getPayPalFees($total){
        if(!$total){
            return false;
        }
        $PayPalFees = get_option('PayPalFees', array( 'percentage' => 2.5));

        return $PayPalFees['percentage'] ?  $total * $PayPalFees['percentage']/100 : 0;
    }

    public function getStoreCommission($total){
        if(!$total){
            return false;
        }
        $storeCommission = get_option('storeCommission', array('store' => 5, 'percentage' => 10, 'bigger' => true));

        $fees = $storeCommission['store'];
        $percentageFees = 0;
        if($storeCommission['percentage'] || $storeCommission['bigger']){
            $percentageFees = $total * $storeCommission['percentage']/100;
        }

        if($storeCommission['bigger']){
            $fees = max($fees, $percentageFees);
        }

        return $fees;
    }

    public function productExist($newItem, $savedItem){
        $newItem =(array)$newItem;
        return $newItem['selected_variant'] === $savedItem->selected_variant;
    }

    public function indexOf ($item = false, $prop = false){
        if(!$item) return -1;

        // created $filtered array with items with same unique store id.
        $filtered = array_filter($this->{$this->colName}, function($saved)use($item) {
            $saved = (array)$saved;
            $item =(array)$item;
            return $saved[$this->prop] === $item[$this->prop];
        });

        // if $filtered array is empty meaning we did not find any item with same id.
        if(!count($filtered)) return -1;

        // running over all the items with same if adn checking if the
        // selected_variant arrays match if not meaning it is a new item
        foreach($filtered as $index => $savedItem){
            $exist = $this->productExist($item, $savedItem);
            if($exist){
                return $index;
            }
        }

        return -1;
    }

    public function clear(){
        unset($_SESSION['cart']);
        return true;
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
