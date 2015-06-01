<?php
/*
  Plugin Name: Coffee Shopping
  Description: Plugin designed for coffeeshopping.co.il!!
  Version: 0.0.1
*/


define("BASE_ADDRESS", dirname(__FILE__));
define("IMAGES_DIR", dirname(plugin_dir_url(__FILE__))."/images/");
define("IMAGES_DIR_PATH", dirname(plugin_dir_path(__FILE__))."images/");
define("LIBS", BASE_ADDRESS.'/libs');
define("CONFIGS", BASE_ADDRESS.'/configs');
define("SERVICES",BASE_ADDRESS.'/services');


// run time configs
error_reporting( E_ALL /*-1*/);
/* E_ALL */
//ini_set("display_errors", 1);
ini_set('max_execution_time', 100000);
if(!class_exists('coffee_shopping'))
{
    class coffee_shopping
    {

        public function __construct()
        {

            /*
            * @ remove admin bar for user
            */
            add_action( 'init', array($this, 'removeAdminBarForUsers') );

            /*
            * @ include classes
            */
            add_action( 'plugins_loaded', array($this, 'includeClasses') );

            /*
            * @ include classes
            */
            add_action( 'plugins_loaded', array($this, 'instantiateCart') );

            /*
             * $ add some roles/capabilities
             * */
            add_action( 'admin_init', array($this, 'dashboard_roles'));

            /*
            * @ adding CSS / JS/ and name Scapes - change to load as per page basis
            */
            add_action( 'wp_enqueue_scripts', array($this,'frontRegisterScripts') );
            add_action( 'admin_enqueue_scripts', array($this,'backRegisterScripts') );

            /*
             * @ adding admin menus
             * */
            add_action('admin_menu', array($this, 'set_up_admin_menu'));


            /*
             * @ creating DBs on activation/deleting on deactivation
             * */
            register_activation_hook(__FILE__, array($this, 'create_db_when_activate'));
            register_deactivation_hook(__FILE__, array($this, 'delete_db_when_deactivate'));

            /*
             * @ changing how login works and making it so it wont redirect to(wp_login) if login fail
             * */
            add_action( 'wp_login_failed', array($this, 'custom_login_fail') );
            add_action( 'authenticate', array($this, 'custom_login_empty'));



        }

        public function removeAdminBarForUsers(){

            if (!current_user_can('manage_options')) {
                // for the front-end
                remove_action('wp_footer', 'wp_admin_bar_render', 1000);

                // css override for the frontend
                function remove_admin_bar_style_frontend() {
                    echo '<style type="text/css" media="screen">
				html { margin-top: 0px !important; }
				* html body { margin-top: 0px !important; }
				</style>';
                }
                add_filter('wp_head','remove_admin_bar_style_frontend', 99);
            }
        }

        /*
        * @ Including libs
        */
        public function includeClasses()
        {
            $allClassFolders = array(
                glob(CONFIGS.'/*.php'),
                glob(LIBS.'/*.php'),
            );

            foreach($allClassFolders as $classLib)
            {
                foreach($classLib as $className)
                {
                    if(!class_exists(str_replace('.php', '', basename($className))))
                    {
                        include($className);
                    }
                }
            }

            function autoLoader ($cName) {
                global $classes;
                if(!isset($classes[$cName])) return false;
                echo $cName.'<br>';
                echo BASE_ADDRESS .$classes[$cName] . $cName. ".php<br>";
                include(BASE_ADDRESS .$classes[$cName] . $cName. ".php");
                return true;
            }
            spl_autoload_register("autoLoader");

            if(!session_id()){ session_start(); }
        }

        public function instantiateCart(){
            if(is_user_logged_in()){

            }
            if(!isset($_SESSION['cart'])){
                $_SESSION['cart'] = new Cart(0);
            }

            echo '<pre>';
            print_r($_SESSION['cart']->products);
            echo '</pre>';

            $_SESSION['cart']->add(new Product(15, 1, 153, 'ebay','','bekini', 230));
            $_SESSION['cart']->add(new Product(5, 1, 153, 'ebay', '', 'bycles', 123));

            echo '<br> total:'.$_SESSION['cart']->getTotal();
            echo '<pre>';
            print_r($_SESSION['cart']);
            echo '</pre>';
            /*
            unset($_SESSION['cart']);


            $products = [
                [
                    'ID' => 5,
                    'cart_id' => 1,
                    'unique_store_id' => 250,
                    'store' => 'ebay',
                    'img' => '',
                    'title' => 'bekini',
                    'price' => 432,
                    'status' => ''
                ],
                [
                    'ID' => 5,
                    'cart_id' => 1,
                    'unique_store_id' => 153,
                    'store' => 'ebay',
                    'img' => '',
                    'title' => 'bycles',
                    'price' => 123,
                    'status' => ''
                ]
            ];

            $_SESSION['cart'] = new Cart(0, new Address(0), $products);


            echo '<br> total:'.$_SESSION['cart']->getTotal();
            echo '<pre>';
            print_r($_SESSION['cart']);
            echo '</pre>';
            */
        }


        /*
         * $ add some roles/capabilities\
         * */
        public function dashboard_roles()
        {

            /*
            add_role(
                'dashboard_user',
                __( 'Dashboard User' ),
                array(
                    'view_dashboards' => true,
                    'read' => true,
                    'upload_files' => true
                )
            );

            $role = get_role( 'dashboard_user' );
            $role->add_cap( 'upload_files' );

            if(!get_option('available_caps'))
            {
                update_option('available_caps',array('admin_all'));
            }
            */
        }

        /*
         * @ changing how login works and making it so it wont redirect to(wp_login) if login fail
         * */
        public function custom_login_fail( $username )
        {
            /*
            $referrer = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : "";
            if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') )
            {
                if ( !strstr($referrer,'?login=failed') )
                {
                    wp_redirect( $referrer . '?login=failed' );
                }
                else
                {
                    wp_redirect( $referrer );
                }
                exit;
            }
            */

            wp_redirect( site_url().'/user_login.php?status=failed' );
        }

        /*
         * @ changing how login works and making it so it wont redirect to(wp_login) if login fail
         * */
        public function custom_login_empty()
        {
            /*
            $referrer = get_home_url();
            if ( strstr($referrer,get_home_url()) && $user==null )
            {
                if ( !strstr($referrer,'?login=empty') )
                {
                    wp_redirect( $referrer . '?login=empty' );
                }
                else
                {
                    wp_redirect( $referrer );
                }
            }
            */
            wp_redirect( site_url().'/user_login.php?status=empty' );
        }

        public function frontRegisterScripts()
        {
            global $req_scripts;
            $this->registerScripts($req_scripts['shared']);
            $this->registerScripts($req_scripts['front_end']);

            $main_js_namespace = array(
                'ajaxURL' => admin_url('admin-ajax.php')
            );
            wp_localize_script('constants.js','$ns',$main_js_namespace);
        }

        public function backRegisterScripts()
        {
            global $req_scripts;
            $this->registerScripts($req_scripts['shared']);
            $this->registerScripts($req_scripts['back_end']);

            $main_js_namespace = array(
                'ajaxURL' => admin_url('admin-ajax.php')
            );
            wp_localize_script('app.js','$ns',$main_js_namespace);
        }


        public function registerScripts($scripts)
        {


            foreach($scripts as $singleHeader)
            {
                $handle = $singleHeader['handle'];
                $src = plugins_url($singleHeader['src'].'.'.$singleHeader['extension'],__FILE__);
                $deps = isset($singleHeader['deps']) ? $singleHeader['deps'] : '';
                $ver = false;
                if($singleHeader['extension'] == 'js')
                {
                    wp_register_script( $handle, $src, $deps, $ver );
                    wp_enqueue_script($handle);
                }
                else
                {
                    wp_register_style($handle,$src,$deps);
                    wp_enqueue_style( $handle, '', '', '', $singleHeader['media'] );
                }
            }
        }

        /*
         * @ create admin pages
         * */
        public function set_up_admin_menu()
        {
            add_menu_page('Coffee Shopping', 'Coffee Shopping', 'manage_options', 'coffeeShopping', array($this, 'settings'));
            add_submenu_page('coffeeShopping', 'Settings', 'Settings', 'manage_options', 'settings', array($this, 'settings'));
        }

        public function settings()
        {
            if ( !current_user_can( 'manage_options' ))
            {
                wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
            }
            require (dirname(__FILE__).'/services/settings.php');
        }

        /*
         * @ on activation create Db and default page
         * */
        public function create_db_when_activate()
        {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            global $wpdb;

            $table_name = $wpdb->prefix . "cs_carts";
            $table = "CREATE TABLE $table_name (
                ID bigint(20) NOT NULL AUTO_INCREMENT,
                user_id bigint(20) NOT NULL,
                deliver_to varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                address_id bigint(20) NOT NULL,
                payment_method varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                purchase_location varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                status bigint(20) varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                create_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY cuunique (`ID`)
                );";
            dbDelta($table);


            $table_name = $wpdb->prefix . "cs_cart_products";
            $table = "CREATE TABLE $table_name (
                ID bigint(20) NOT NULL AUTO_INCREMENT,
                cart_id bigint(20) NOT NULL,
                unique_store_id bigint(20) NOT NULL,
                store varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                img varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                title varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                price float(20) NOT NULL,
                status bigint(20) varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                UNIQUE KEY cuunique (`ID`)
                );";
            dbDelta($table);

            $table_name = $wpdb->prefix . "cs_stats";
            $table = "CREATE TABLE $table_name (
                ID bigint(20) NOT NULL AUTO_INCREMENT,
                type varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                value varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                reference varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                UNIQUE KEY cuunique (`ID`)
                );";
            dbDelta($table);

            $table_name = $wpdb->prefix . "cs_items_override";
            $table = "CREATE TABLE $table_name (
                ID bigint(20) NOT NULL AUTO_INCREMENT,
                item_id bigint(20) NOT NULL,
                attribute varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                value varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                UNIQUE KEY cuunique (`ID`)
                );";
            dbDelta($table);

            $table_name = $wpdb->prefix . "cs_search_override";
            $table = "CREATE TABLE $table_name (
                ID bigint(20) NOT NULL AUTO_INCREMENT,
                item_id bigint(20) NOT NULL,
                search_keyword varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                attribute varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                at_position int(10) NOT NULL,
                UNIQUE KEY cuunique (`ID`)
                );";
            dbDelta($table);

            $table_name = $wpdb->prefix . "cs_addresses";
            $table = "CREATE TABLE $table_name (
                ID bigint(20) NOT NULL AUTO_INCREMENT,
                city varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                house varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                apt varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                postcode varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                phone_number bigint(20) NOT NULL,
                UNIQUE KEY cuunique (`ID`)
                );";
            dbDelta($table);


            $table_name = $wpdb->prefix . "cs_categories";
            $table = "CREATE TABLE $table_name (
                ID bigint(20) NOT NULL AUTO_INCREMENT,
                parent_id bigint(20) NOT NULL,
                title varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                store varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                UNIQUE KEY cuunique (`ID`)
                );";
            dbDelta($table);
        }


        /*
        * @ on deactivation remove DB and pages
        */
        public function delete_db_when_deactivate()
        {
            /*
            global $wpdb;

            $table_name = $wpdb->prefix . "cs_carts";
            $wpdb->query("DROP TABLE IF EXISTS $table_name");

            $table_name = $wpdb->prefix . "cs_cart_items";
            $wpdb->query("DROP TABLE IF EXISTS $table_name");

            $table_name = $wpdb->prefix . "cs_stats";
            $wpdb->query("DROP TABLE IF EXISTS $table_name");

            $table_name = $wpdb->prefix . "cs_items_override";
            $wpdb->query("DROP TABLE IF EXISTS $table_name");

            $table_name = $wpdb->prefix . "cs_search_override";
            $wpdb->query("DROP TABLE IF EXISTS $table_name");

            $table_name = $wpdb->prefix . "cs_addresses";
            $wpdb->query("DROP TABLE IF EXISTS $table_name");

            $table_name = $wpdb->prefix . "cs_carts";
            $wpdb->query("DROP TABLE IF EXISTS $table_name");

            $table_name = $wpdb->prefix . "cs_categories";
            $wpdb->query("DROP TABLE IF EXISTS $table_name");
            */
        }
    }
}

$coffee_shopping = new coffee_shopping();




/* ------------------- DEAD OR UNUSED CODE ---------------- */
/*
*/
/* ------------------- DEAD OR UNUSED CODE ---------------- */


?>