<?php
$value = array(

    'frontEnd' => array(
        array('handle' => 'jquery_zoomit_js',          'src' => 'bower_components/jquery.zoomIt/jquery.zoomIt',         'extension' => 'js',    'deps' => '', 'media' => '', 'page' => 'product'),
        array('handle' => 'jquery_zoomit.css',         'src' => 'bower_components/jquery.zoomIt/jquery.zoomIt',         'extension' => 'css',   'deps' => '', 'media' => 'screen', 'page' => 'product'),
        array('handle' => 'jquery_cbcarousel_js',      'src' => 'bower_components/jquery.cbCarousel/jquery.cbCarousel', 'extension' => 'js',    'deps' => '', 'media' => '', 'page' => 'product'),
        array('handle' => 'jquery_cbcarousel_css',     'src' => 'bower_components/jquery.cbCarousel/jquery.cbCarousel', 'extension' => 'css',   'deps' => '', 'media' => 'screen', 'page' => 'product'),
        array('handle' => 'jquery_masonry_js',         'src' => 'bower_components/masonry/dist/masonry.pkgd',           'extension' => 'js',    'deps' => '', 'media' => ''),
        array('handle' => 'imagesloaded_js',           'src' => 'bower_components/imagesloaded/imagesloaded',           'extension' => 'js',    'deps' => '', 'media' => ''),

        array('handle' => 'search_css',                'src' => '/css/search',                                          'extension' => 'css',   'deps' => '', 'media' => 'screen', 'page' => 'search'),
        array('handle' => 'search_js',                 'src' => '/scripts/pages/search',                                'extension' => 'js',    'deps' => '', 'media' => ''),
        array('handle' => 'product_css',               'src' => '/css/product',                                         'extension' => 'css',   'deps' => '', 'media' => 'screen', 'page' => 'product'),
        array('handle' => 'product_js',                'src' => '/scripts/pages/product',                               'extension' => 'js',    'deps' => '', 'media' => '', 'page' => 'product'),

        array('handle' => 'ProductsListWidget',        'src' => '/scripts/partials/ProductsListWidget',             'extension' => 'js',    'deps' => '', 'media' => ''),
        array('handle' => 'header_css',                'src' => '/css/header',                      'extension' => 'css',   'deps' => '', 'media' => 'screen'),
        array('handle' => 'searchWidget_css',          'src' => '/css/searchWidget',                'extension' => 'css',   'deps' => '', 'media' => 'screen'),
        array('handle' => 'myCartWidget_css',          'src' => '/css/myCartWidget',                'extension' => 'css',   'deps' => '', 'media' => 'screen'),
        array('handle' => 'myCartWidget_js',           'src' => '/scripts/partials/myCartWidget',   'extension' => 'js',    'deps' => '', 'media' => ''),
        array('handle' => 'cart_css',                  'src' => '/css/cart',                        'extension' => 'css',   'deps' => '', 'media' => 'screen'),
        array('handle' => 'cart_js',                   'src' => '/scripts/pages/cart',              'extension' => 'js',    'deps' => '', 'media' => ''),

        array('handle' => 'login_css',                 'src' => '/css/login',                       'extension' => 'css',   'deps' => '', 'media' => 'screen', 'page' => 'login'),
        array('handle' => 'login_js',                  'src' => '/scripts/pages/login',             'extension' => 'js',    'deps' => '', 'media' => '', 'page' => 'login'),

        array('handle' => 'register_css',              'src' => '/css/register',                    'extension' => 'css',   'deps' => '', 'media' => 'screen' , 'page' => array('register','resetpassword')),
        array('handle' => 'register_js',               'src' => '/scripts/pages/register',          'extension' => 'js',    'deps' => '', 'media' => '', 'page' => 'register'),

        array('handle' => 'checkout_css',              'src' => '/css/checkout',                    'extension' => 'css',   'deps' => '', 'media' => 'screen' , 'page' => 'checkout'),
        array('handle' => 'checkout_js',               'src' => '/scripts/pages/checkout',          'extension' => 'js',    'deps' => '', 'media' => '', 'page' => 'checkout'),

        array('handle' => 'storeLocator_js',           'src' => '/scripts/partials/storeLocator',   'extension' => 'js',    'deps' => '', 'media' => '', 'page' => array('checkout', 'stores')),
        array('handle' => 'storeLocator_css',          'src' => '/css/storeLocator',                'extension' => 'css',   'deps' => '', 'media' => 'screen', 'page' => array('checkout', 'stores')),

        array('handle' => 'myAccount_js',              'src' => '/scripts/pages/myAccount',   'extension' => 'js',    'deps' => '', 'media' => '', 'page' => 'myaccount'),
        array('handle' => 'myAccount_css',             'src' => '/css/myAccount',             'extension' => 'css',   'deps' => '', 'media' => 'screen', 'page' => 'myaccount'),

        array('handle' => 'theme_css',                 'src' => '/templates/theme/css/theme',       'extension' => 'css',   'deps' => '', 'media' => 'screen'),

        //array('handle' => 'address_form_js',           'src' => '/scripts/partials/addressForm',    'extension' => 'js',    'deps' => '', 'media' => '', 'page' => array('checkout' )),

        array('handle' => 'reset_password_js',          'src' => '/scripts/pages/resetPassword',   'extension' => 'js',    'deps' => '', 'media' => '', 'page' => 'resetpassword'),
    ),

    'backEnd' => array(

    ),

    'shared' => array(

        /* jquery */
        array('handle' => 'jquery', 'src' =>  'bower_components/jquery/dist/jquery.min', 'extension' => 'js', 'deps' => '', 'media' => ''),

        /* jquery.ui */
        array('handle' => 'jquery_ui_js', 'src' =>  'bower_components/jquery.ui/dist/jquery-ui', 'extension' => 'js', 'deps' => array('jquery'), 'media' => ''),
        array('handle' => 'jquery_ui_css', 'src' => 'bower_components/jquery.ui/dist/jquery-ui', 'extension' => 'css', 'deps' => '', 'media' => 'screen'),

        /* jquery tiny pubsub*/
        array('handle' => 'jquery_tiny_pubsub', 'src' =>  'bower_components/jquery.tinyPubSub/jquery.tinyPubSub', 'extension' => 'js', 'deps' => array('jquery'), 'media' => ''),

        /* jquery serializeJson*/
        array('handle' => 'jquery_serialize_json', 'src' =>  'bower_components/jquery.serializeJSON/jquery.serializejson', 'extension' => 'js', 'deps' => array('jquery'), 'media' => ''),

        /* jquery.validate */
        array('handle' => 'jquery_validate', 'src' => 'bower_components/jquery-validation/dist/jquery.validate', 'extension' => 'js', 'deps' => array('jquery'), 'media' => ''),
        array('handle' => 'jquery_validate_methods', 'src' => 'bower_components/jquery-validation/dist/additional-methods', 'extension' => 'js', 'deps' => array('jquery_validate'), 'media' => ''),

        /* google phone validation */
        array('handle' => 'google_phone_validation', 'src' => 'bower_components/google.phoneValidation/google.phoneValidation', 'extension' => 'js', 'deps' => array('jquery'), 'media' => ''),

        /* dragula */
        array('handle' => 'dragula_js', 'src' =>  'bower_components/dragula.js/dist/dragula.min', 'extension' => 'js', 'deps' => array('jquery'), 'media' => ''),
        array('handle' => 'dragula_css', 'src' => 'bower_components/dragula.js/dist/dragula', 'extension' => 'css', 'deps' => '', 'media' => 'screen'),

        /* bootstrap */
        array('handle' => 'bootstrap_js', 'src' =>  'bower_components/bootstrap/dist/js/bootstrap.min', 'extension' => 'js', 'deps' => array('jquery'), 'media' => ''),
        array('handle' => 'bootstrap_css', 'src' => 'bower_components/bootstrap/dist/css/bootstrap.min', 'extension' => 'css', 'deps' => '', 'media' => 'screen'),

        /* es5-shim */
        array('handle' => 'es5-shim', 'src' =>  'bower_components/es5-shim/es5-shim.min', 'extension' => 'js', 'deps' => array('jquery'), 'media' => ''),

        /* lodash */
        array('handle' => 'lodash', 'src' =>  'bower_components/lodash/lodash.min', 'extension' => 'js', 'deps' => array('jquery'), 'media' => ''),

        /* moment */
        array('handle' => 'moment', 'src' =>  'bower_components/moment/min/moment.min', 'extension' => 'js', 'deps' => array('jquery'), 'media' => ''),

        /* font-awesome */
        array('handle' => 'font-awesome', 'src' => 'bower_components/font-awesome/css/font-awesome.min', 'extension' => 'css', 'deps' => '', 'media' => 'screen'),

        /* extras - js*/
        array('handle' => 'app_js', 'src' => 'scripts/app', 'extension' => 'js', 'deps' => array('jquery'), 'media' => ''),

        /* extras - css */
        array('handle' => 'main_css', 'src' => 'css/main', 'extension' => 'css', 'deps' => '', 'media' => 'screen'),

        /* Util functions */
        array('handle' => 'util_js', 'src' => '/scripts/Utils', 'extension' => 'js', 'deps' => '', 'media' => '',),
    )
);