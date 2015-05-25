<?php
class classLoader{
	
	public function __construct() {
        $this->requireFiles();
	}

    public function requireFiles(){
        require_once 'core/method/ajax_method.php';
        require_once 'core/method/method.php';
    }
}

$classLoader = new classLoader();

