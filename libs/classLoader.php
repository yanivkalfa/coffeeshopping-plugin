<?php
class classLoader{
	
	public function __construct() {
        $this->requireFiles();
	}

    public function requireFiles(){
        require_once 'ajax/handler.php';
        require_once 'ajax/method.php';
    }
}

$classLoader = new classLoader();

