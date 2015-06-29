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

    /**
     * @param   $post
     * @return  array
     *          success -   bool    -   status.
     *          msg     -   array   -   generatedPass, errorMsg
     */
    public function registerNewUser($post){
        // Register the user to the DB.
        $user = UserDatabaseHelper::registerNewUser($post);
        if(!$user["success"]){
            // failed to register.
            return array(
                'success' => false,
                'msg' => array('generatedPass' => 0, "errorMsg" => $user["msg"])
            );
        }else{
            // Registration is OK, use our user array.
            $user = $user["msg"];
        }

        // Prevent login if it's an admin.
        if (is_super_admin()) {
            return array(
                'success'   => true,
                'msg'       => array('generatedPass' => $user['user_pass'], 'errorMsg' => "prevent-login")
            );
        }

        // All good - return pass.
        return array(
            'success'   => true,
            'msg'       => array('generatedPass' => $user['user_pass'], 'errorMsg' => '')
        );
    }

    /**
     * @param $post
     * @return array
     */
    public function updateUserProfile($post){
        $newUser = json_decode($post['user'], true);
        $newUser['ID'] = (int)$newUser['ID'];
        $user = wp_get_current_user();

        if(!$newUser['ID'] || $newUser['ID'] !==  $user->ID) {
            return array(
                'success'   => false,
                'msg'       => array('unAuthorized' => 'unAuthorized', 'errorMsg' => 'Cant change another user\' profile')
            );
        }

        $updated = wp_update_user( $newUser );
        if(isset($updated->errors)){
            return array(
                'success'   => false,
                'msg'       => array('updatingUserError' => 'updatingUserError', 'errorMsg' => 'We could not update user!')
            );
        }

        return array(
            'success'   => true,
            'msg'       => 'You\'ve successfully updated you\'re account'
        );
    }

    /**
     * @param $post
     * @return array
     */
    public function addAddress($post){
        $address = json_decode($post['address'], true);

        // instantiating new address
        $address = new Address($address);

        // validating the new address
        $error = $address->validateAddress();
        // if we have errors reporting them
        if(is_array($error)) {
            return array(
                'success' => false,
                'msg' => $error
            );
        }

        $address->ID = AddressDatabaseHelper::addAddress((array)$address);
        if(!$address->ID) {
            return array(
                'success' => false,
                'msg' => array('name' => 'unableToInsertAddress', 'errorMsg' => 'Unable to insert new address')
            );
        }

        $user = wp_get_current_user();
        // adding new address id to user meta.
        if(!add_user_meta($user->ID, 'address_id', $address->ID)) {
            return array(
                'success' => false,
                'msg' => array('name' => 'unableToAddAddressToUser', 'errorMsg' => 'Unable to add address to user')
            );
        }

        return array(
            'success' => true,
            'msg' => (array)$address
        );

    }

    /**
     * @param $post
     * @return array
     */
    public function removeAddress($post){
        $address_id = $post['address_id'];
        $user = wp_get_current_user();
        if(!$address_id) {
            return array(
                'success' => false,
                'msg' => array('name' => 'noAddressIdSupplied', 'errorMsg' => 'No address id was supplied')
            );
        }

        $hasCart = CartDatabaseHelper::getCartByAddressId($address_id);
        if(!$hasCart){
            AddressDatabaseHelper::deleteAddress($address_id);
        }

        $deleted = delete_user_meta($user->ID, 'address_id', $address_id);
        if(!$deleted) {
            return array(
                'success' => false,
                'msg' => array('name' => 'unableToRemoveAddress', 'errorMsg' => 'Unable to remove address')
            );
        }

        return array(
            'success' => true,
            'msg' => $address_id
        );
    }

    public function userLogin($details){
        $creds = array(
            'user_login' => $details["login"],
            'user_password' => $details["password"],
            'remember' => true
        );

        $user = wp_signon($creds, false);
        if (is_wp_error($user)){
            return array(
                'success' => false,
                'msg' => $user->get_error_message()
            );
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
        $result = StoreDatabaseHelper::getClosestStore($coords["lat"],$coords["lng"]);

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