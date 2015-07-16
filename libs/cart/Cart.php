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
    public $price_modifiers;

    public function __construct($cart = array(), $products = NULL) {
        $prdc = $products;
        if($products && count($products) && is_array($products[0])){
            $prdc = [];
            foreach($products as $key => $prd){
                $prdc[$key] = new Product($prd);
            }
        }

        $colOpts = array( 'collection' => $prdc, 'colName' => 'products', 'prop'=> 'unique_store_id'  );
        parent::__construct($colOpts);

        $cartStatus = CSCons::get('cartStatus') ?: array();
        $this->ID = isset($cart['ID']) ? $cart['ID'] : null;
        $this->user_id = isset($cart['user_id']) ? $cart['user_id'] : null;
        $this->deliver_to = isset($cart['deliver_to']) ? $cart['deliver_to'] : null;
        $this->address_id = isset($cart['address_id']) ? $cart['address_id'] : null;
        $this->payment_method = isset($cart['payment_method']) ? $cart['payment_method'] : 'cash';
        $this->payment_amount = isset($cart['payment_amount']) ? $cart['payment_amount'] : null;
        $this->purchase_location = isset($cart['purchase_location']) ? $cart['purchase_location'] : null;
        $this->status = isset($cart['status']) ?  $cart['status'] : $cartStatus['saved']['name'] ;
        $this->note = isset($cart['note']) ? $cart['note'] : null;
        $this->create_date = isset($cart['create_date']) ? $cart['create_date'] : null;
        $this->delivered_date = isset($cart['create_date']) ? $cart['create_date'] : null;

        if($cart['price_modifiers'] && count($cart['price_modifiers'])){
            if(is_array($cart['price_modifiers'][0])){
                foreach($cart['price_modifiers'] as $key => $PPM){
                    $this->price_modifiers[$key] = new PriceModifier($PPM);
                }
            }else if(is_object($cart['price_modifiers'][0])){
                $this->price_modifiers = $cart['price_modifiers'];
            }else{
                $this->price_modifiers = [];
            }
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

    public function getPayPalFees($total){
        if(!$total){
            return false;
        }
        $payPalFees = get_option('payPalFees', array( 'percentage' => 2.5));

        return $payPalFees['percentage'] ?  $total * $payPalFees['percentage']/100 : 0;
    }

    public function getStoreCommission($total){
        if(!$total){
            return false;
        }

        $paymentMethods = CSCons::get('paymentMethods') ?: array();
        $memberType = UserDatabaseHelper::isClubMember() ? 'clubMember' : 'normal';
        $optionName = $paymentMethods[$this->payment_method][$memberType]['optionName'];
        $defaultValue = $paymentMethods[$this->payment_method][$memberType]['default'];
        $storeCommission = get_option($optionName, $defaultValue);

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

    public function updateCartPriceModifiers($total){
        $priceModifiersNames = CSCons::get('priceModifiers') ?: array();

        $modifier = CartPriceModifierHelper::get($this->price_modifiers,'payPalFees');
        $payPalFees = $this->getPayPalFees($total);
        if(!$modifier){
            $modifier = array(
                'name' => 'payPalFees',
                'nameAs' => $priceModifiersNames['payPalFees'],
                'value' => $payPalFees,
            );
            $modifier = new PriceModifier($modifier);
            $modifier->order = 0;
            CartPriceModifierHelper::add($this->price_modifiers,$modifier);
        }else{
            $modifier->value = $payPalFees;
        }

        $modifier = CartPriceModifierHelper::get($this->price_modifiers,'storeCommission');
        $total += $payPalFees;
        $storeCommission = $this->getStoreCommission($total);
        if(!$modifier){
            $modifier = array(
                'name' => 'storeCommission',
                'nameAs' => $priceModifiersNames['storeCommission'],
                'value' => $storeCommission,
            );
            $modifier = new PriceModifier($modifier);
            $modifier->order = 1;
            CartPriceModifierHelper::add($this->price_modifiers,$modifier);
        }else{
            $modifier->value = $storeCommission;
        }

        usort($this->price_modifiers, function($a, $b) {
            if ($a->order == $b->order) {
                return 0;
            }
            return ($a->order < $b->order) ? -1 : 1;
        });

    }

    public function getCalculatedTotal(){
        $total = $this->getTotalAfterProductModifiers();
        $this->updateCartPriceModifiers($total);

        foreach($this->price_modifiers as $modifier){
            $total += $modifier->value;
        }
        return $total;
    }

    public function getToDoorStepPrice(){
        return 60;
    }

    public function totalIncludingToDoorStep(){
        $toDoorStep = CartPriceModifierHelper::get($_SESSION['cart']->price_modifiers,'toDoorStep');
        return $this->getCalculatedTotal() + (!$toDoorStep?$this->getToDoorStepPrice():0);
    }

    public function getTotalMinusModifier($modifierName){
        $modifier = CartPriceModifierHelper::get($_SESSION['cart']->price_modifiers, $modifierName);
        return $this->getCalculatedTotal() - $modifier->value;
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
                    $modifiers[$modifier->name] = new PriceModifier((array)$modifier);
                    $modifiers[$modifier->name]->value = $modifier->getModifierValue($product->getQuantity());
                }else{
                    $modifiers[$modifier->name]->value += $modifier->getModifierValue($product->getQuantity());
                }
            }
        }

        $total = $this->getTotalAfterProductModifiers();
        $this->updateCartPriceModifiers($total);
        foreach($this->price_modifiers as $modifier){
            $modifiers[$modifier->name] = $modifier;
        }

        return array_values($modifiers);
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