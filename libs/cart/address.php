<?php

class Address extends BasicCartObject {
    public $city;
    public $house;
    public $apt;
    public $postcode;
    public $phone_number;
    public $full_name;

    public function __construct($address=NULL){

        if(is_array($address)) {
            $this->ID = isset($address['ID']) ? $address['ID'] : null;
            $this->city = isset($address['city']) ? $address['city'] : null;
            $this->house = isset($address['house']) ? $address['house'] : null;
            $this->apt = isset($address['apt']) ? $address['apt'] : null;
            $this->postcode = isset($address['postcode']) ? $address['postcode'] : null;
            $this->phone_number = isset($address['phone_number']) ? $address['phone_number'] : null;
            $this->full_name = isset($address['full_name']) ? $address['full_name'] : null;
        }
    }

    public function validateAddress(){
        return true;
    }
}