<?php
/*
* @ CoffeeShopping Constants
*/

abstract class CSCons {

    public static function get($consName){

        $constants = array(
            'descRestrictedExt' => array(
                "js",
                "json",
                "css",
            ),
            'errorCategories' => array(
                "API",
                "frontEnd",
                "backEnd",
                "templateLoader",
            ),
            'errorSubCategories' => array(
                "ebay",
                "aliexp",
                "productView",
                "productSearch",
                "widget",
            ),
            'errorSubCategoryTypes' => array(
                "getSearch",
                "getProduct",
                "getProducts",
                "missingArgs",
                "getShippingCosts",
                "searchAPI",
                "searchALL",
                "searchWidget",
	            "myCartWidget"
            ),
            'errorCodesHandler' => array(
                "0" => "Improper search string",
                "1" => "cURL Communication error",
                "2" => "Failed to get the requested item details",
                "3" => "Failed to get page contents, some arguments are missing",
                "4" => "It seems like this item's listing is inactive",
                "5" => "Failed to get the requested item(s) details",
                "6" => "Failed to get the search results",
                "7"  => "Can't get search page link",
                "8"  => "Can't get product page link",
	            "9"  => "Widget failed to load."
            ),

            'pages' => array(
                array('name' => 'cart', 'title'=> 'Cart'),
                array('name' => 'product', 'title'=> 'Product'),
                array('name' => 'search', 'title'=> 'Search'),
                array('name' => 'checkout', 'title'=> 'Checkout'),
                array('name' => 'home', 'title'=> 'Home'),
                array('name' => 'myAccount', 'title'=> 'My Account'),
                array('name' => 'register', 'title'=> 'Register'),
                array('name' => 'login', 'title'=> 'Login'),
                array('name' => 'logout', 'title'=> 'Logout'),
            ),

            'req_scripts' => array(

                'front_end' => array(
                    array('handle' => 'jquery_zoomit_js',          'src' => 'bower_components/jquery.zoomIt/jquery.zoomIt',         'extension' => 'js',    'deps' => '', 'media' => '', 'page' => 'product'),
                    array('handle' => 'jquery_zoomit.css',         'src' => 'bower_components/jquery.zoomIt/jquery.zoomIt',         'extension' => 'css',   'deps' => '', 'media' => 'screen', 'page' => 'product'),
                    array('handle' => 'jquery_cbcarousel_js',      'src' => 'bower_components/jquery.cbCarousel/jquery.cbCarousel', 'extension' => 'js',    'deps' => '', 'media' => '', 'page' => 'product'),
                    array('handle' => 'jquery_cbcarousel_css',     'src' => 'bower_components/jquery.cbCarousel/jquery.cbCarousel', 'extension' => 'css',   'deps' => '', 'media' => 'screen', 'page' => 'product'),
                    array('handle' => 'search_css',                'src' => '/css/search',                                          'extension' => 'css',   'deps' => '', 'media' => 'screen', 'page' => 'search'),
                    array('handle' => 'search_js',                 'src' => '/scripts/pages/search',                                'extension' => 'js',    'deps' => '', 'media' => '', 'page' => 'search'),
                    array('handle' => 'product_css',               'src' => '/css/product',                                         'extension' => 'css',   'deps' => '', 'media' => 'screen', 'page' => 'product'),
                    array('handle' => 'product_js',                'src' => '/scripts/pages/product',                               'extension' => 'js',    'deps' => '', 'media' => '', 'page' => 'product'),


	                array('handle' => 'searchWidget_css',          'src' => '/css/searchWidget',                'extension' => 'css',   'deps' => '', 'media' => 'screen'),
                    array('handle' => 'myCartWidget_css',          'src' => '/css/myCartWidget',                'extension' => 'css',   'deps' => '', 'media' => 'screen'),
                    array('handle' => 'myCartWidget_js',           'src' => '/scripts/partials/myCartWidget',   'extension' => 'js',    'deps' => '', 'media' => ''),
                    array('handle' => 'cart_css',                  'src' => '/css/cart',                        'extension' => 'css',   'deps' => '', 'media' => 'screen'),
                    array('handle' => 'cart_js',                   'src' => '/scripts/pages/cart',              'extension' => 'js',    'deps' => '', 'media' => ''),

                    array('handle' => 'login_css',                 'src' => '/css/login',                       'extension' => 'css',   'deps' => '', 'media' => 'screen', 'page' => 'login'),
                    array('handle' => 'login_js',                  'src' => '/scripts/pages/login',             'extension' => 'js',    'deps' => '', 'media' => '', 'page' => 'login'),

                    array('handle' => 'register_css',              'src' => '/css/register',                     'extension' => 'css',   'deps' => '', 'media' => 'screen' , 'page' => 'register'),
                    array('handle' => 'register_js',               'src' => '/scripts/pages/register',           'extension' => 'js',    'deps' => '', 'media' => '', 'page' => 'register'),

                    array('handle' => 'checkout_css',              'src' => '/css/checkout',                      'extension' => 'css',   'deps' => '', 'media' => 'screen' , 'page' => 'checkout'),
                    array('handle' => 'checkout_js',               'src' => '/scripts/pages/checkout',            'extension' => 'js',    'deps' => '', 'media' => '', 'page' => 'checkout'),
                    array('handle' => 'address_form_js',           'src' => '/scripts/partials/addressForm',      'extension' => 'js',    'deps' => '', 'media' => '', 'page' => 'checkout'),
                    array('handle' => 'storeLocator_js',           'src' => '/scripts/partials/storeLocator',     'extension' => 'js',    'deps' => '', 'media' => '', 'page' => 'checkout'),

	                array('handle' => 'theme_css',                 'src' => '/templates/theme/css/theme',         'extension' => 'css',   'deps' => '', 'media' => 'screen'),
                ),

                'back_end' => array(

                ),

                'shared' => array(

                    /* jquery */
                    array('handle' => 'jquery', 'src' =>  'bower_components/jquery/dist/jquery.min', 'extension' => 'js', 'deps' => '', 'media' => ''),

                    /* jquery.ui */
                    array('handle' => 'jquery_ui_js', 'src' =>  'bower_components/jquery.ui/dist/jquery-ui', 'extension' => 'js', 'deps' => array('jquery'), 'media' => ''),
                    array('handle' => 'jquery_ui_css', 'src' => 'bower_components/jquery.ui/dist/jquery-ui', 'extension' => 'css', 'deps' => '', 'media' => 'screen'),

                    /* jquery tiny pubsub*/
                    array('handle' => 'jquery_tiny_pubsub', 'src' =>  'bower_components/jquery.tinyPubSub/jquery.tinyPubSub', 'extension' => 'js', 'deps' => array('jquery'), 'media' => ''),

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
            ),
            'countries' => array (
                'AF' => 'Afghanistan',
                'AX' => 'Aland Islands',
                'AL' => 'Albania',
                'DZ' => 'Algeria',
                'AS' => 'American Samoa',
                'AD' => 'Andorra',
                'AO' => 'Angola',
                'AI' => 'Anguilla',
                'AQ' => 'Antarctica',
                'AG' => 'Antigua And Barbuda',
                'AR' => 'Argentina',
                'AM' => 'Armenia',
                'AW' => 'Aruba',
                'AU' => 'Australia',
                'AT' => 'Austria',
                'AZ' => 'Azerbaijan',
                'BS' => 'Bahamas',
                'BH' => 'Bahrain',
                'BD' => 'Bangladesh',
                'BB' => 'Barbados',
                'BY' => 'Belarus',
                'BE' => 'Belgium',
                'BZ' => 'Belize',
                'BJ' => 'Benin',
                'BM' => 'Bermuda',
                'BT' => 'Bhutan',
                'BO' => 'Bolivia',
                'BA' => 'Bosnia And Herzegovina',
                'BW' => 'Botswana',
                'BV' => 'Bouvet Island',
                'BR' => 'Brazil',
                'IO' => 'British Indian Ocean Territory',
                'BN' => 'Brunei Darussalam',
                'BG' => 'Bulgaria',
                'BF' => 'Burkina Faso',
                'BI' => 'Burundi',
                'KH' => 'Cambodia',
                'CM' => 'Cameroon',
                'CA' => 'Canada',
                'CV' => 'Cape Verde',
                'KY' => 'Cayman Islands',
                'CF' => 'Central African Republic',
                'TD' => 'Chad',
                'CL' => 'Chile',
                'CN' => 'China',
                'CX' => 'Christmas Island',
                'CC' => 'Cocos (Keeling) Islands',
                'CO' => 'Colombia',
                'KM' => 'Comoros',
                'CG' => 'Congo',
                'CD' => 'Congo, Democratic Republic',
                'CK' => 'Cook Islands',
                'CR' => 'Costa Rica',
                'CI' => 'Cote D\'Ivoire',
                'HR' => 'Croatia',
                'CU' => 'Cuba',
                'CY' => 'Cyprus',
                'CZ' => 'Czech Republic',
                'DK' => 'Denmark',
                'DJ' => 'Djibouti',
                'DM' => 'Dominica',
                'DO' => 'Dominican Republic',
                'EC' => 'Ecuador',
                'EG' => 'Egypt',
                'SV' => 'El Salvador',
                'GQ' => 'Equatorial Guinea',
                'ER' => 'Eritrea',
                'EE' => 'Estonia',
                'ET' => 'Ethiopia',
                'FK' => 'Falkland Islands (Malvinas)',
                'FO' => 'Faroe Islands',
                'FJ' => 'Fiji',
                'FI' => 'Finland',
                'FR' => 'France',
                'GF' => 'French Guiana',
                'PF' => 'French Polynesia',
                'TF' => 'French Southern Territories',
                'GA' => 'Gabon',
                'GM' => 'Gambia',
                'GE' => 'Georgia',
                'DE' => 'Germany',
                'GH' => 'Ghana',
                'GI' => 'Gibraltar',
                'GR' => 'Greece',
                'GL' => 'Greenland',
                'GD' => 'Grenada',
                'GP' => 'Guadeloupe',
                'GU' => 'Guam',
                'GT' => 'Guatemala',
                'GG' => 'Guernsey',
                'GN' => 'Guinea',
                'GW' => 'Guinea-Bissau',
                'GY' => 'Guyana',
                'HT' => 'Haiti',
                'HM' => 'Heard Island & Mcdonald Islands',
                'VA' => 'Holy See (Vatican City State)',
                'HN' => 'Honduras',
                'HK' => 'Hong Kong',
                'HU' => 'Hungary',
                'IS' => 'Iceland',
                'IN' => 'India',
                'ID' => 'Indonesia',
                'IR' => 'Iran, Islamic Republic Of',
                'IQ' => 'Iraq',
                'IE' => 'Ireland',
                'IM' => 'Isle Of Man',
                'IL' => 'Israel',
                'IT' => 'Italy',
                'JM' => 'Jamaica',
                'JP' => 'Japan',
                'JE' => 'Jersey',
                'JO' => 'Jordan',
                'KZ' => 'Kazakhstan',
                'KE' => 'Kenya',
                'KI' => 'Kiribati',
                'KR' => 'Korea',
                'KW' => 'Kuwait',
                'KG' => 'Kyrgyzstan',
                'LA' => 'Lao People\'s Democratic Republic',
                'LV' => 'Latvia',
                'LB' => 'Lebanon',
                'LS' => 'Lesotho',
                'LR' => 'Liberia',
                'LY' => 'Libyan Arab Jamahiriya',
                'LI' => 'Liechtenstein',
                'LT' => 'Lithuania',
                'LU' => 'Luxembourg',
                'MO' => 'Macao',
                'MK' => 'Macedonia',
                'MG' => 'Madagascar',
                'MW' => 'Malawi',
                'MY' => 'Malaysia',
                'MV' => 'Maldives',
                'ML' => 'Mali',
                'MT' => 'Malta',
                'MH' => 'Marshall Islands',
                'MQ' => 'Martinique',
                'MR' => 'Mauritania',
                'MU' => 'Mauritius',
                'YT' => 'Mayotte',
                'MX' => 'Mexico',
                'FM' => 'Micronesia, Federated States Of',
                'MD' => 'Moldova',
                'MC' => 'Monaco',
                'MN' => 'Mongolia',
                'ME' => 'Montenegro',
                'MS' => 'Montserrat',
                'MA' => 'Morocco',
                'MZ' => 'Mozambique',
                'MM' => 'Myanmar',
                'NA' => 'Namibia',
                'NR' => 'Nauru',
                'NP' => 'Nepal',
                'NL' => 'Netherlands',
                'AN' => 'Netherlands Antilles',
                'NC' => 'New Caledonia',
                'NZ' => 'New Zealand',
                'NI' => 'Nicaragua',
                'NE' => 'Niger',
                'NG' => 'Nigeria',
                'NU' => 'Niue',
                'NF' => 'Norfolk Island',
                'MP' => 'Northern Mariana Islands',
                'NO' => 'Norway',
                'OM' => 'Oman',
                'PK' => 'Pakistan',
                'PW' => 'Palau',
                'PS' => 'Palestinian Territory, Occupied',
                'PA' => 'Panama',
                'PG' => 'Papua New Guinea',
                'PY' => 'Paraguay',
                'PE' => 'Peru',
                'PH' => 'Philippines',
                'PN' => 'Pitcairn',
                'PL' => 'Poland',
                'PT' => 'Portugal',
                'PR' => 'Puerto Rico',
                'QA' => 'Qatar',
                'RE' => 'Reunion',
                'RO' => 'Romania',
                'RU' => 'Russian Federation',
                'RW' => 'Rwanda',
                'BL' => 'Saint Barthelemy',
                'SH' => 'Saint Helena',
                'KN' => 'Saint Kitts And Nevis',
                'LC' => 'Saint Lucia',
                'MF' => 'Saint Martin',
                'PM' => 'Saint Pierre And Miquelon',
                'VC' => 'Saint Vincent And Grenadines',
                'WS' => 'Samoa',
                'SM' => 'San Marino',
                'ST' => 'Sao Tome And Principe',
                'SA' => 'Saudi Arabia',
                'SN' => 'Senegal',
                'RS' => 'Serbia',
                'SC' => 'Seychelles',
                'SL' => 'Sierra Leone',
                'SG' => 'Singapore',
                'SK' => 'Slovakia',
                'SI' => 'Slovenia',
                'SB' => 'Solomon Islands',
                'SO' => 'Somalia',
                'ZA' => 'South Africa',
                'GS' => 'South Georgia And Sandwich Isl.',
                'ES' => 'Spain',
                'LK' => 'Sri Lanka',
                'SD' => 'Sudan',
                'SR' => 'Suriname',
                'SJ' => 'Svalbard And Jan Mayen',
                'SZ' => 'Swaziland',
                'SE' => 'Sweden',
                'CH' => 'Switzerland',
                'SY' => 'Syrian Arab Republic',
                'TW' => 'Taiwan',
                'TJ' => 'Tajikistan',
                'TZ' => 'Tanzania',
                'TH' => 'Thailand',
                'TL' => 'Timor-Leste',
                'TG' => 'Togo',
                'TK' => 'Tokelau',
                'TO' => 'Tonga',
                'TT' => 'Trinidad And Tobago',
                'TN' => 'Tunisia',
                'TR' => 'Turkey',
                'TM' => 'Turkmenistan',
                'TC' => 'Turks And Caicos Islands',
                'TV' => 'Tuvalu',
                'UG' => 'Uganda',
                'UA' => 'Ukraine',
                'AE' => 'United Arab Emirates',
                'GB' => 'United Kingdom',
                'US' => 'United States',
                'UM' => 'United States Outlying Islands',
                'UY' => 'Uruguay',
                'UZ' => 'Uzbekistan',
                'VU' => 'Vanuatu',
                'VE' => 'Venezuela',
                'VN' => 'Viet Nam',
                'VG' => 'Virgin Islands, British',
                'VI' => 'Virgin Islands, U.S.',
                'WF' => 'Wallis And Futuna',
                'EH' => 'Western Sahara',
                'YE' => 'Yemen',
                'ZM' => 'Zambia',
                'ZW' => 'Zimbabwe',
            ),
            "currencyNames" => array(
                'USD' => "US Dollar",
                'GBP' => "Pound Sterling",
                'JPY' => "Japanese Yen",
                'EUR' => "European Euro",
                'AUD' => "Australian Dollar",
                'CAD' => "Canadian Dollar",
                'DKK' => "Denmark Krone",
                'NOK' => "Norway Krone",
                'ZAR' => "South Africa Rand",
                'SEK' => "Sweden Krona",
                'CHF' => "Switzerland Franc",
                'JOD' => "Jordan Dinar",
                'LBP' => "Lebanon Pound",
                'EGP' => "Egypt Pound",
                'HKD' => "Hong Kong Dollar",
                'CNY' => "Chinese Yuan Renminbi",
                'INR' => "Indian Rupee",
                'SGD' => "Singapore Dollar",
                'ILS' => "Israeli Shekel",
                'THB' => "Thai Baht",
                'CZK' => "Czech Koruna",
                'BGN' => "Bulgarian Lev",
                'HUF' => "Hungarian Forint",
                'PLN' => "Polish Zloty",
                'ROM' => "Romanian Leu",
                'HRK' => "Croatian Kuna",
                'RUN' => "Russian Rouble",
                'TRY' => "Turkish Lira",
                'BRL' => "Brasilian Real",
                'IDR' => "Indonesian Rupiah",
                'KRW' => "South Korean Won",
                'MXN' => "Mexican Peso",
                'MYR' => "Malaysian Ringgit",
                'NZD' => "New Zealand Dollar",
                'PHP' => "Philippine Peso",
            ),
            "currencySymbols" => array(
                'USD' => "$",
                'GBP' => "&pound;",
                'JPY' => "&yen;",
                'EUR' => "&euro;",
                'AUD' => "A$",
                'CAD' => "C$",
                'HKD' => "HK$",
                'ILS' => "&#8362;",
                'RUN' => "руб"
            ),
            'errorMessages' => array(
                'methodDoesNotExists' => 'Method does not exist',
                'required' => 'This field is required',
                'phoneIL' => 'Please specify correct Israel phone number',
                'number' => 'Must be a number',
                'maxLength' => 'password must be {0} digit long',
                'minLength' => 'password must be {0} digit long',
                'length' => 'Value length must be {0} digit long',
                'equalTo' => 'Passwords does not match',
                'address_id' => 'You must select a shipping method'
            ),
        );
        return isset($constants[$consName]) ? $constants[$consName] : false ;
    }
}