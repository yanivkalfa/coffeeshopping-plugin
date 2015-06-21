<?php

abstract class FormValidators{

    static public function validateFormInput(){
        $validators = func_get_args();
        $value = (string)$validators[0];
        array_splice($validators, 0,1);
        $fnArgs = array($value);
        $errorMessages = CSCons::get('errorMessages');

        foreach($validators as $validator){

            if(is_array($validator)){
                reset ($validator);
                $fnName = key ( $validator );
                $fnArgs = array_merge (
                    $fnArgs,
                    Utils::toArray($validator[$fnName])
                );

            }else{
                $fnName = $validator;
            }

            if(!method_exists ( 'FormValidators' , $fnName )){
                return array('errorName' => 'methodDoesNotExists', 'errorMsg' => $errorMessages['methodDoesNotExists']);
            }


            $error = self::required($value);
            if(is_array($error)){
                return $error;
            }

            $error = call_user_func_array(array('FormValidators', $fnName), $fnArgs);
            if(is_array($error)){
                return $error;
            }
        }

        return call_user_func_array(array('FormValidators', 'required'), $fnArgs);
    }

    static public function format($value, $replacements = array()){

        $i=0;
        foreach($replacements as $replace){
            $search = '{'.$i.'}';
            $value = str_replace ( $search , $replace , $value);
            $i++;
        }

        return $value;
    }

    static public function required($value){
        $errorMessages = CSCons::get('errorMessages');

        if(!isset($value) || empty($value)){
            return array('errorName' => 'isRequiredButEmpty', 'errorMsg' => $errorMessages['required']);
        }

        return true;
    }

    static public function length($value, $length){
        $errorMessages = CSCons::get('errorMessages');

        if(isset($length) && is_numeric($length)){
            if( strlen($value) !== $length){
                return array('errorName' => 'valueLengthIsWrong', 'errorMsg' => self::format($errorMessages['length'], Utils::toArray($length)));
            }
        }

        return true;
    }

    static public function minLength($value, $length){
        $errorMessages = CSCons::get('errorMessages');

        if(isset($length) && is_numeric($length)){
            if( strlen($value) < $length){
                return array('errorName' => 'valueTooShort', 'errorMsg' => self::format($errorMessages['minLength'], Utils::toArray($length)));
            }

        }

        return true;
    }

    static public function maxLength($value, $length){
        $errorMessages = CSCons::get('errorMessages');

        if(isset($length) && is_numeric($length)){
            if( strlen($value) > $length){
                return array('errorName' => 'valueTooLong', 'errorMsg' => self::format($errorMessages['maxLength'], Utils::toArray($length)));
            }

        }

        return true;
    }

    static public function number($value){
        $errorMessages = CSCons::get('errorMessages');

        if(!is_numeric($value)){
            return array('errorName' => 'valueNoneNumber', 'errorMsg' => $errorMessages['number']);
        }

        return true;
    }

    static public function equalTo($value, $equalTo){
        $errorMessages = CSCons::get('errorMessages');

        if($value !== $equalTo){
            return array('errorName' => 'notEqualTo', 'errorMsg' => $errorMessages['equalTo']);
        }

        return true;
    }

    // todo: change this to actual IL phone validation
    static public function phoneIL($value){
        $errorMessages = CSCons::get('errorMessages');

        $error  = array('errorName' => 'nonePhoneIL', 'errorMsg' => $errorMessages['phoneIL']);
        if(is_array(self::number($value))){
            return $error;
        }

        if(
            is_array(self::length($value, 10)) &&
            is_array(self::length($value, 9)) &&
            is_array(self::length($value, 4))
        ){
            return $error;
        }

        return true;
    }

}

?>
