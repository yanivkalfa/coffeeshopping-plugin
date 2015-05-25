<?php
/*
 * Method
*/

class Method {
    public $method;
    public $protected;
    public $req_capabilities;

	public function __construct($method, $protected = true, $req_capabilities = array()) {
        if(!isset($method)) return false;
        $this->method = $method;
        $this->protected = $protected;
        $this->req_capabilities = $req_capabilities;
	}

}