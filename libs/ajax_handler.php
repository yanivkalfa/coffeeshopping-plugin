<?php
/*
* @ WP ajax management class
* @ constract just add action.
*/

class ajax_handler {
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
	
	/*
	* @ function checks if $_post method is set and then call the method while passing the $_POST post variables. 
	* @ Accept: nothing.
	* @ Returns: nothing - output a json encode value returned from the method that was called..
	*/
	public function handle_ajax() {

		if(isset($_POST['method']) && ajax_method::isAuthenticated($_POST['method'])) {
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

    public function doSomeSome($post){
        return $post;
    }
}
$ajax_handler = new ajax_handler();