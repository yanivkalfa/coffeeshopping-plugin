<?php

class Address {
    public $ID;
    public $city;
    public $house;
    public $apt;
    public $postcode;
    public $phone_number;

    public function __construct($ID,$city = '',$house = '',$apt = '',$postcode = '',$phone_number = ''){

        if(empty($ID)) return false;

        $this->ID = $ID;
        $this->city = $city;
        $this->house = $house;
        $this->apt = $apt;
        $this->postcode = $postcode;
        $this->phone_number = $phone_number;
    }
}