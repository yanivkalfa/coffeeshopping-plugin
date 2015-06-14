<?php

class Address extends BasicCartObject {
    public $city;
    public $house;
    public $apt;
    public $postcode;
    public $phone_number;

    public function __construct($address=NULL){

        if(is_array($address)) {
            $this->ID = $address['ID'];
            $this->city = $address['city'];
            $this->house = $address['house'];
            $this->apt = $address['apt'];
            $this->postcode = $address['postcode'];
            $this->phone_number = $address['phone_number'];
        }
    }
}