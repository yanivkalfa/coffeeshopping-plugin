<?php
/*
  Plugin Name: Coffee Shopping
  Description: Plugin designed for coffeeshopping.co.il!!
  Version: 0.0.1
  Text Domain: coffee-shopping
*/

/*
 * IMPORTANT NOTE:
 * on gantry framework plugin - \wp-content\plugins\gantry\core\renderers\gantrymainbodyrenderer.class.php there is
 * an error/bug on line 157, the function should be static, so change to: static public function invertPositionOrder($sidebar_widgets).
 * */
define("BASE_URL", plugin_dir_url(__FILE__));
define("BASE_ADDRESS", dirname(__FILE__));
define("IMAGES_DIR", dirname(plugin_dir_url(__FILE__))."/images/");
define("IMAGES_DIR_PATH", dirname(plugin_dir_path(__FILE__))."images/");
define("LIBS", BASE_ADDRESS.'/libs');
define("CONFIGS", BASE_ADDRESS.'/configs');
define("SERVICES",BASE_ADDRESS.'/services');
define("TEMPLATE_DIR",BASE_ADDRESS.'/templates');
define("THEME_DIR", get_template_directory());
define("EXCH_CURRENCY", "ILS"); // Our exchange currency - TODO:: load from admin.

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
            /**
             * Loading localization files
             */
            add_action('plugins_loaded', array($this, 'loadTextDomain'));

            /*
            * @ remove admin bar for user
            */
            add_action( 'init', array($this, 'removeAdminBarForUsers') );

            /*
             * @ Admin in english (cause hebrew stinks there).
             */
            add_filter('locale', array($this, 'set_admin_language'));

            /*
            * @ include classes
            */
            add_action( 'plugins_loaded', array($this, 'includeClasses') );

            /*
            * @ Instantiate cart
            */
            add_action( 'plugins_loaded', array($this, 'instantiateCart') );

            /*
            * @ Instantiate shortcodes
            */
            add_action( 'plugins_loaded', array($this, 'instantiateShortcodes') );

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
            register_activation_hook(__FILE__, array($this, 'pluginActivate'));
            register_deactivation_hook(__FILE__, array($this, 'pluginDeactivate'));

            /*
             * @ changing how login works and making it so it wont redirect to(wp_login) if login fail
             * */
            add_action( 'wp_login_failed', array($this, 'custom_login_fail') );
            add_action( 'authenticate', array($this, 'custom_login_empty'));
            add_filter( 'login_redirect', array($this, 'custom_login_redirect'), 10, 3);

            /*
             * add action to register our widgets
             */
            add_action( 'widgets_init', array($this, 'register_coffeeshoppingwidgets') );

            /*
             * After logging re-instantiating cart
             * */
            add_filter( 'authenticate', array($this, 'afterLogin'),30, 3 );
        }

        public function loadTextDomain() {
            load_plugin_textdomain( 'coffee-shopping', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        }


        public function afterLogin( $user, $username, $password ) {
            $savedCart = CartDatabaseHelper::getCart($user->ID);
            if(isset($_SESSION['cart']) && $savedCart) {
                $_SESSION['cart']->clear();
                CartHelper::instantiateCart($savedCart);
            }
            return $user;
        }

        /**
         * Redirects admins to the admin panel and users to where-ever.
         */
        public function custom_login_redirect($redirect_to, $requested_redirect_to, $user) {
            global $user;
            if ( isset( $user->roles ) && is_array( $user->roles ) ) {
                //check for admins
                if ( in_array( 'administrator', $user->roles ) ) {
                    // redirect them to the default place
                    return admin_url();
                } else {
                    return $requested_redirect_to ? $requested_redirect_to : home_url();
                }
            } else {
                return home_url();//(!empty($request)) ? $request : home_url();
            }
        }

        /**
         * Removing top bar for users who aren't admins
         */
        public function removeAdminBarForUsers() {

            if (!current_user_can('manage_options')) {
                // for the front-end
                remove_action('wp_footer', 'wp_admin_bar_render', 1000);

                // css override for the frontend
                function remove_admin_bar_style_frontend() {
                    echo '<style type="text/css" media="screen">
				html { margin-top: 0 !important; }
				* html body { margin-top: 0 !important; }
				</style>';
                }
                add_filter('wp_head','remove_admin_bar_style_frontend', 99);
            }
        }

        /*
        * @ Including libs
        */
        public function includeClasses() {
            $allClassFolders = array(
                glob(CONFIGS.'/*.php'),
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
                include(BASE_ADDRESS .$classes[$cName] . $cName. ".php");
                return true;
            }
            spl_autoload_register("autoLoader");

            if(!session_id()){ session_start(); }
        }

        /**
         * Instantiate shopping car.
         */
        public function instantiateCart() {
            CartHelper::instantiateCart();

            //$results = GoogleTranslatorHelper::translate('something', 'en');
            //Utils::preEcho($results);
            /*
            $a = TwiloHelper::sendMessage('this is a new message', "4237074169");
            Utils::pageEcho($a);*/
        }

        public function instantiateShortcodes() {
            new Shortcode_management();
        }


        /*
         * $ Add some roles/capabilities\
         * */
        public function dashboard_roles() {
            //remove_cap( 'subscriber', 'read' );

            global $wp_roles; // global class wp-includes/capabilities.php
            $wp_roles->remove_cap( 'subscriber', 'read' );
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
         * @ Changing how login works and making it so it wont redirect to (wp_login) if login fail
         * */
        public function custom_login_fail( $username ) {
            /*
            $referrer = (isset($_GET['referrer'])) ? $_GET['referrer'] : "";
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

            wp_redirect( site_url().'/login?status=failed' );
        }

        /*
         * @ Changing how login works and making it so it wont redirect to (wp_login) if login fail
         * */
        public function custom_login_empty() {
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
            }*/
            wp_redirect( site_url().'/login?status=empty' );
        }

        // Register our widgets.
        public function register_coffeeshoppingwidgets() {
            require (dirname(__FILE__).'/services/templateLoaderWidget.php');
            require (dirname(__FILE__).'/services/searchWidget.php');
            require(dirname(__FILE__) . '/services/productsListWidget.php');
            require (dirname(__FILE__).'/services/myCartWidget.php');
            require (dirname(__FILE__).'/services/displayPostWidget.php');
            register_widget( 'templateLoaderWidget' );
            register_widget( 'searchWidget' );
            register_widget( 'productsListWidget' );
            register_widget( 'myCartWidget' );
            register_widget( 'displayPostWidget' );

        }

        /**
         * Dequeue and deregister old jquery
         */
        public function removeJquery() {
            wp_dequeue_script( 'jquery');
            wp_deregister_script( 'jquery');
        }

        /**
         * Removing jquery - adding shared scripts and then fonrend scripts lastly adding constants. to constants.js
         */
        public function frontRegisterScripts() {
            $this->removeJquery();
            $this->registerScripts(CSCons::get('reqScripts')['shared']);
            $this->registerScripts(CSCons::get('reqScripts')['frontEnd']);

            $main_js_namespace = array(
                'ajaxURL' => admin_url('admin-ajax.php'),
                'data' => array(
                    'action' => '',
                    'method' => '',
                    'post' => ''
                ),
                'events' => array(
                    'CART_UPDATE' => 'CART_UPDATE'
                )
            );
            wp_localize_script('util_js','$ns',$main_js_namespace);
        }

        /**
         * Removing jquery - adding shared scripts and then backend scripts lastly adding constants to app.js.
         */
        public function backRegisterScripts() {
            $this->removeJquery();
            $this->registerScripts(CSCons::get('reqScripts')['shared']);
            $this->registerScripts(CSCons::get('reqScripts')['backEnd']);

            $main_js_namespace = array(
                'ajaxURL' => admin_url('admin-ajax.php'),
                'data' => array(
                    'action' => '',
                    'method' => '',
                    'post' => ''
                )
            );
            wp_localize_script('util_js','$ns',$main_js_namespace);
        }

        /**
         * Taking an array of scripts handles iterating over it and
         * register/queuing scripts and styles.
         *
         * @param {array} $scripts
         */
        public function registerScripts($scripts) {
            global $pagename;
            foreach($scripts as $singleHeader)
            {
                $handle = $singleHeader['handle'];
                $src = plugins_url($singleHeader['src'].'.'.$singleHeader['extension'], __FILE__);
                $deps = isset($singleHeader['deps']) ? $singleHeader['deps'] : '';
                $ver = false;

                if (isset($singleHeader['page'])){
                    $page = (is_array($singleHeader['page'])) ? $singleHeader['page'] : array($singleHeader['page']);
                    if (!in_array($pagename, $page)){
                        continue;
                    }
                }

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
         * @ Create admin pages
         *
        */
        public function set_up_admin_menu() {
            add_menu_page('Coffee Shopping', 'Coffee Shopping', 'manage_options', 'coffeeShopping', array($this, 'settings'));
            add_submenu_page('coffeeShopping', 'Settings', 'Settings', 'manage_options', 'settings', array($this, 'settings'));
        }

        public function settings() {
            if ( !current_user_can( 'manage_options' ))
            {
                wp_die( __( "You do not have sufficient permissions to access this page.", 'coffee-shopping' ) );
            }
            require (dirname(__FILE__).'/services/settings.php');
        }

        /**
         * Creating new templates
         */
        public function addTemplatesToTheme() {
            require_once(LIBS . '/Utils.php');
            require_once(CONFIGS . '/constant.php');
            $pages = CSCons::get('pages') ?: array();

            @mkdir(THEME_DIR.'/cs_templates');
            foreach($pages as $page){
                Utils::file_str_replace(TEMPLATE_DIR.'/template-gantry.php', array('%%templateTitle%%','%%templateName%%'),array($page['title'], $page['name']), THEME_DIR.'/cs_templates' . '/template-'.$page['name'].'.php');

                $fileName = THEME_DIR.'/html/layouts' . '/body_'.$page['name'].'.php';
                Utils::deleteLocation($fileName);
                Utils::file_str_replace(TEMPLATE_DIR.'/body_gantry.php', array('GantryLayoutBody_csClassName', '%%className%%'),array('GantryLayoutBody_'.$page['name'], $page['name']),  $fileName);
            }
        }

        /**
         * Remove cs_templates from theme.
         */
        public function removeTemplatesFromTheme() {
            $dst = THEME_DIR.'/cs_templates';
            // deleting cs_templates
            Utils::deleteLocation($dst);
        }

        /**
         * Create pages.
         */
        public function createPages() {
            $pages = CSCons::get('pages') ?: array();
            foreach($pages as $page){
                $optionName = 'cs_'.$page['name'].'_p_id';
                $post = get_post( get_option($optionName));

                if(!isset($post->ID) || empty($post->ID))
                {
                    $my_post = array(
                        'post_title'    => $page['title'],
                        'post_status'   => 'publish',
                        'post_author'   => 1,
                        'post_name'     => $page['name'],
                        'post_type'     => 'page',
                        'page_template'     => 'cs_templates/template-'.$page['name'].'.php'
                    );
                    update_option($optionName,wp_insert_post( $my_post ) );
                }
            }
        }

        /**
         * Remove pages.
         */
        public function removePages() {
            $pages = CSCons::get('pages') ?: array();
            foreach($pages as $page){
                $optionName = 'cs_'.$page['name'].'_p_id';
                wp_delete_post( get_option($optionName), true);
                update_option($optionName, false);
            }
        }

        /**
         *  Set admin language to english.
         * @param string $lang
         * @return string
         */
        public function set_admin_language($lang) {

            if (is_admin()) {
                return 'en_US';
            } else {
                return $lang;
            }
        }

        /**
         * Adds csMember role
         * @return mixed
         */
        public function addCsMemberRole() {
            return add_role(
                'csMember',
                __( 'CoffeeShopping Member' ),
                array(
                    'cs_member' => true,  // true allows this capability
                )
            );
        }

        /**
         * Removes csMember role
         * @return mixed
         */
        public function removeCsMemberRole() {
            return remove_role( 'csMember' );
        }

        public function setHomePage() {

            /*
            $loginPage = get_permalink();
            if (!$loginPage){Utils::adminPreECHO(__("Can't get login page id", 'coffee-shopping' ), __("topTemplate.php ERROR:: ", 'coffee-shopping' ));
            $homepage = get_page_by_title( 'Front Page' );
                */

            $homePage = get_option("cs_home_p_id");
            if ( $homePage)
            {
                update_option( 'page_on_front', $homePage );
                update_option( 'show_on_front', 'page' );
            }
        }

        /*
         * @ On activation create Db and default page
         * https://docs.google.com/spreadsheets/d/1i_vk_ftcvHzHuoeu5_vh08uMAqY9zkCbRBNsYzgbTZI/edit#gid=0
         *
        */
        public function pluginActivate() {
            $this->addTemplatesToTheme();
            $this->createPages();
            $this->addCsMemberRole();
            $this->setHomePage();

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            global $wpdb;

            $table_name = $wpdb->prefix . "cs_carts";
            $table = "CREATE TABLE IF NOT EXISTS $table_name (
                ID bigint(20) NOT NULL AUTO_INCREMENT,
                user_id bigint(20) NOT NULL,
                deliver_to varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                address_id bigint(20) NOT NULL,
                payment_method varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'cash',
                payment_amount float(20) NOT NULL,
                purchase_location varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                status varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'saved',
                price_modifiers text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                note varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                delivered_date TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY cuunique (`ID`)
                );";
            dbDelta($table);

            $table_name = $wpdb->prefix . "cs_cart_products";
            $table = "CREATE TABLE IF NOT EXISTS $table_name (
                ID bigint(20) NOT NULL AUTO_INCREMENT,
                cart_id bigint(20) NOT NULL,
                unique_store_id varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                store varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                store_link varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                selected_variant text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                selected_variant_sku varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                img varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                title varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                price_modifiers text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                price float(20) NOT NULL,
                status varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                quantity int(10) NOT NULL,
                available_quantity int(10) NOT NULL,
                order_limit int(10) NOT NULL,
                delivery_min varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                delivery_max varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                delivered_date TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY cuunique (`ID`)
                );";
            dbDelta($table);

            $table_name = $wpdb->prefix . "cs_stats";
            $table = "CREATE TABLE IF NOT EXISTS $table_name (
                ID bigint(20) NOT NULL AUTO_INCREMENT,
                type varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                value varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                reference varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                UNIQUE KEY cuunique (`ID`)
                );";
            dbDelta($table);

            $table_name = $wpdb->prefix . "cs_items_override";
            $table = "CREATE TABLE IF NOT EXISTS $table_name (
                ID bigint(20) NOT NULL AUTO_INCREMENT,
                item_id bigint(20) NOT NULL,
                attribute varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                value varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                UNIQUE KEY cuunique (`ID`)
                );";
            dbDelta($table);

            $table_name = $wpdb->prefix . "cs_search_override";
            $table = "CREATE TABLE IF NOT EXISTS $table_name (
                ID bigint(20) NOT NULL AUTO_INCREMENT,
                item_id bigint(20) NOT NULL,
                search_keyword varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                attribute varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                at_position int(10) NOT NULL,
                UNIQUE KEY cuunique (`ID`)
                );";
            dbDelta($table);

            $table_name = $wpdb->prefix . "cs_addresses";
            $table = "CREATE TABLE IF NOT EXISTS $table_name (
                ID bigint(20) NOT NULL AUTO_INCREMENT,
                city varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                street varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                house varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                apt varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                postcode varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                phone_number varchar(11) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                full_name varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                UNIQUE KEY cuunique (`ID`)
                );";
            dbDelta($table);

            $table_name = $wpdb->prefix . "cs_categories";
            $table = "CREATE TABLE IF NOT EXISTS $table_name (
                ID bigint(20) NOT NULL AUTO_INCREMENT,
                parent_id bigint(20) NOT NULL,
                title varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                store varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                UNIQUE KEY cuunique (`ID`)
                );";
            dbDelta($table);

            $table_name = $wpdb->prefix . "cs_stores";
            $table = "CREATE TABLE IF NOT EXISTS $table_name (
              ID bigint(20) NOT NULL AUTO_INCREMENT,
              name varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
              address varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
              lat float(10, 6) NOT NULL,
              lng float(10, 6) NOT NULL,
              description varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
              imgurl varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
              UNIQUE KEY cuunique (`ID`)
              );";
            dbDelta($table);

            $table_name = $wpdb->prefix . "cs_saved_products";
            $table = "CREATE TABLE IF NOT EXISTS $table_name (
              ID bigint(20) NOT NULL AUTO_INCREMENT,
              productID bigint(20) NOT NULL,
              store varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
              title varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
              image varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
              price float(10, 2) NOT NULL,
              priceCurrency varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
              shipping float(10, 2) NOT NULL,
              shippingCurrency varchar(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
              listname varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci,
              lastupdate TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
              UNIQUE KEY cuunique (`ID`)
              );";
            dbDelta($table);
        }


        /*
        * @ on deactivation remove DB and pages
        */
        public function pluginDeactivate() {
            $this->removePages();
            $this->removeTemplatesFromTheme();
            $this->removeCsMemberRole();
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
