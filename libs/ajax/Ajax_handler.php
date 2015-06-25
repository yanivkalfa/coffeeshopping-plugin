<?php
/*
* @ WP ajax management class
* @ constract just add action.
*/

class Ajax_handler {
	/*
	* @ premissions/capabilities responses.
	*/
	private $cap_msgs = array(
		'add' => "Sorry, it looks like you don't have permission to add",
		'edit' => "Sorry, it looks like you don't have permission to edit",
		'delete' => "Sorry, it looks like you don't have permission to delete"
	);

	public function __construct() {
		add_action('wp_ajax_nopriv_ajax_handler', array($this, 'handle_ajax'));
        add_action('wp_ajax_ajax_handler', array($this, 'handle_ajax'));
	}

    private function isAuthenticated($methodName) {
        global $methods;
        if(!isset($methods[$methodName])) return false;

        $protected = isset($methods[$methodName]['protected']) ? $methods[$methodName]['protected'] : null;
        $req_capabilities = isset($methods[$methodName]['req_capabilities']) ? $methods[$methodName]['req_capabilities'] : null;

        $methodInit =  new Method($methodName, $protected, $req_capabilities);
        return $this->authenticate($methodInit);
    }

    private function authenticate($method){
        if($method->protected){
            if(count($method->req_capabilities)){
                foreach($method->req_capabilities as $cap){
                    if(current_user_can($cap)) return true;
                }
                return false;
            }else {
                return is_user_logged_in();
            }
        }

        return true;
    }
	
	/*
	* @ function checks if $_post method is set and then call the method while passing the $_POST post variables. 
	* @ Accept: nothing.
	* @ Returns: nothing - output a json encode value returned from the method that was called..
	*/
	public function handle_ajax() {

		if(isset($_POST['method']) && $this->isAuthenticated($_POST['method'])) {
			$method = $_POST['method'];
			if(method_exists($this, $method)) {
				if(isset($_POST['post']) && $_POST['post'] != 'false') {
					parse_str(stripslashes($_POST['post']), $post);
					$request = call_user_func(array($this, $method), $post);
					echo (is_array($request)) ? json_encode($request) : $request;
				} else {
					$request = call_user_func(array($this, $method));
					echo (is_array($request)) ? json_encode($request) : $request;
				}
			} else {
				$json['success'] = false;
				$json['msg'] = __CLASS__.'::'.$method.' not found, define first! por favor..';
				echo json_encode($json);
			}
		}else{
            $json['success'] = false;
            $json['msg'] = __CLASS__.':: method was not defined or authenticate request.';
            echo json_encode($json);
        }
		exit;
	}


    public function registerNewUser($post){
        $user = json_decode($post['user'], true);

        $error = FormValidators::validateFormInput($user['log'], 'phoneIL');
        if(is_array($error)){
            return array( 'success' => false, 'msg' => $error );
        }

        $error = FormValidators::validateFormInput($user['pwd'], 'number', array('minLength' => 4), array('maxLength' => 4));
        if(is_array($error)){
            return array( 'success' => false, 'msg' => $error );
        }

        $userData = array(
            'user_login'  =>  $user['log'],
            'user_pass'   =>  $user['pwd'],
            'user_nicename'   =>  $user['firstName'] . ' ' . $user['lastName'],
            'first_name'   =>  $user['firstName'],
            'last_name'   =>  $user['lastName'],
            'role' => 'subscriber'
        );

        $user = wp_insert_user( $userData ) ;

        if(isset($user->errors)){
            foreach($user->errors as $key => $errors){
                return array(
                    'success' => false,
                    'msg' => array('name' => $key, 'errorMsg' => $errors[0])
                );
            }
        }

        return array(
            'success' => true,
            'msg' => ''
        );
    }

    public function addProduct($post){
        $product = json_decode($post['product'], true);
        $extendCartUpdate = isset($post['extendCartUpdate']) ? json_decode($post['extendCartUpdate'], true) : false;
        if(!$_SESSION['cart']->add(new Product($product))){
            return array(
                'success' => false,
                'msg' => 'Couldn\'t add the product to cart'
            );
        }

        return array(
            'success' => true,
            'msg' => $_SESSION['cart']->getStats($extendCartUpdate)
        );
    }

    public function removeProduct($post){
        $product = json_decode($post['product'], true);
        $extendCartUpdate = isset($post['extendCartUpdate']) ? json_decode($post['extendCartUpdate'], true) : false;
        $_SESSION['cart']->remove($product);

        return array(
            'success' => true,
            'msg' => $_SESSION['cart']->getStats($extendCartUpdate)
        );
    }

    public function updateQuantity($post){
        $product = json_decode($post['product'], true);
        $quantity = json_decode($post['quantity'], true);
        $extendCartUpdate = isset($post['extendCartUpdate']) ? json_decode($post['extendCartUpdate'], true) : false;
        $product = $_SESSION['cart']->get($product);
        if(!$product){
            return array(
                'success' => false,
                'msg' => 'Error: Product was not found'
            );
        }
        if(!$product->updateQuantity($quantity)){
            return array(
                'success' => false,
                'msg' => 'Error: Could not update product\'s quantity'
            );
        }

        return array(
            'success' => true,
            'msg' => $_SESSION['cart']->getStats($extendCartUpdate)
        );
    }

    public function saveCart(){
        CartDatabaseHelper::saveCart();

        return array(
            'success' => true,
            'msg' => 'Cart saved successfully'
        );
    }

    public function clearCart(){
        unset($_SESSION['cart']);

        return array(
            'success' => true,
            'msg' => $_SESSION['cart']->getStats()
        );
    }

    public function getClosestStore($post){
        $coords = json_decode($post["coords"], true);
        $result = storeHelper::getClosestStore($coords["lat"],$coords["lng"]);

        if ($result!==false){
            return array(
                'success' => true,
                'msg' => array("ID" => $result)
            );
        }else{
            return array(
                'success' => false,
                'msg' => ''
            );
        }
    }
}
$ajax_handler = new ajax_handler();