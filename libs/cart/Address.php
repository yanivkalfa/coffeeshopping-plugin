<?php

class Address extends BasicCartObject {
    public $full_name;
    public $phone_number;
    public $city;
    public $street;
    public $apt;
    public $house;
    public $postcode;

    public function __construct($address=NULL){

        if(is_array($address)) {
            $this->ID = isset($address['ID']) ? $address['ID'] : null;
            $this->city = isset($address['city']) ? $address['city'] : null;
            $this->street = isset($address['street']) ? $address['street'] : null;
            $this->house = isset($address['house']) ? $address['house'] : null;
            $this->apt = isset($address['apt']) ? $address['apt'] : null;
            $this->postcode = isset($address['postcode']) ? $address['postcode'] : null;
            $this->phone_number = isset($address['phone_number']) ? $address['phone_number'] : null;
            $this->full_name = isset($address['full_name']) ? $address['full_name'] : null;
        }
    }

    public function validateAddress(){

        foreach(get_object_vars($this) as $key => $value){
            if($key === 'ID') continue;
            $error = FormValidators::validateFormInput($this->{$key});
            if(is_array($error)){
                $error['field'] = $key;
                return $error;
            }
        }

        $error = FormValidators::validateFormInput($this->phone_number, array('maxLength' => 11));
        if(is_array($error)){
            $error['field'] = 'phone_number';
            return $error;
        }


        return true;
    }
}
