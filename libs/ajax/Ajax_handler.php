<?php
/*
* @ WP ajax management class
* @ constract just add action.
*/

class Ajax_handler {
	/*
	* @ premissions/capabilities responses.
	*/

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
				$json['msg'] = __CLASS__.'::'.$method.__( " not found, define first! por favor..", 'coffee-shopping' );
				echo json_encode($json);
			}
		}else{
            $json['success'] = false;
            $json['msg'] = __CLASS__.':: '.__( "method was not defined or authenticate request.", 'coffee-shopping' );
            echo json_encode($json);
        }
		exit;
	}

    /**
     * @param $post
     * @return array
     */
    public function requestResetPassword($post){
        $log = json_decode($post['log'], true);
        if(!$log) {
            return array(
                'success'   => false,
                'msg'       => array('name' =>'noUserName', 'errorMsg' => __( "You have to supply your phone number", 'coffee-shopping' ))
            );
        }

        $user = get_user_by( 'login', $log );
        if(!$user){
            return array(
                'success'   => false,
                'msg'       => array('name' =>'noUser', 'errorMsg' => __( "User with this phone number was not found", 'coffee-shopping' ))
            );
        }

        $token = UserDatabaseHelper::generateUserPass().UserDatabaseHelper::generateUserPass();

        $resetPassword = get_user_meta($user->ID, 'resetPassword', true) ?: array();
        $resetPassword['requests'] = isset($resetPassword['requests']) ? $resetPassword['requests'] : array();
        $resetPassword['requests'] = UserDatabaseHelper::clearOldResetPasswordRequests($resetPassword['requests']);
        $tooFrequent = UserDatabaseHelper::isResetPasswordRequestsTooFrequent($resetPassword['requests']);
        if($tooFrequent){
            return array(
                'success'   => false,
                'msg'       => array('name' =>'tooFrequent', 'errorMsg' => __( "You can only reset your password twice every hour", 'coffee-shopping' ))
            );
        }

        $smsMessages = CSCons::get('smsMessages') ?: array();
        $twilioResults = TwiloHelper::sendMessage(str_replace('{0}', $token,$smsMessages['resetPassword']), $log);

        if(!$twilioResults['success']){
            return $twilioResults;
        }

        $resetPassword['token'] = $token;
        $resetPassword['requests'][] = time();
        rsort($resetPassword['requests']);

        update_user_meta( $user->ID, 'resetPassword', $resetPassword);

        return array( 'success' => true, 'msg' => '');
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
                'msg'       => array('generatedPass' => $user['user_pass'], 'errorMsg' => __( "prevent-login", 'coffee-shopping' ))
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
                'msg'       => array('unAuthorized' => 'unAuthorized', 'errorMsg' => __( "Cant change another user' profile", 'coffee-shopping' ))
            );
        }

        $updated = wp_update_user( $newUser );
        if(isset($updated->errors)){
            return array(
                'success'   => false,
                'msg'       => array('updatingUserError' => 'updatingUserError', 'errorMsg' =>  __( "We could not update user!", 'coffee-shopping' ))
            );
        }

        return array(
            'success'   => true,
            'msg'       => __( "You've successfully updated you're account", 'coffee-shopping' )
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
                'msg' => array('name' => 'unableToInsertAddress', 'errorMsg' => __( "Unable to insert new address", 'coffee-shopping' ))
            );
        }

        $user = wp_get_current_user();
        // adding new address id to user meta.
        if(!add_user_meta($user->ID, 'address_id', $address->ID)) {
            return array(
                'success' => false,
                'msg' => array('name' => 'unableToAddAddressToUser', 'errorMsg' => __( "Unable to add address to user", 'coffee-shopping' ))
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
                'msg' => array('name' => 'noAddressIdSupplied', 'errorMsg' => __( "No address id was supplied", 'coffee-shopping' ))
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
                'msg' => array('name' => 'unableToRemoveAddress', 'errorMsg' => __( "Unable to remove address", 'coffee-shopping' ))
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
                'msg' => __( "Couldn't add the product to cart", 'coffee-shopping' )
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
                'msg' =>  __( "Error: Product was not found", 'coffee-shopping' )
            );
        }
        if(!$product->updateQuantity($quantity)){
            return array(
                'success' => false,
                'msg' => __( "Error: Could not update product's quantity", 'coffee-shopping' )
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