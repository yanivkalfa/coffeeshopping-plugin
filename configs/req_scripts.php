<?php
/*
 * required scripts front/back/shared
 *
 * Minimal required properties:
 * handle, src, extension
 *
 * */

global $req_scripts;
$req_scripts = array(

    'front_end' => array(
        array('handle' => 'zoomit.jquery', 'src' => '/scripts/ZoomIt/zoomit.jquery', 'extension' => 'js', 'deps' => '', 'media' => 'screen'), // ZoomIt image gallery.
        array('handle' => 'zoomit', 'src' => '/scripts/ZoomIt/zoomit', 'extension' => 'css', 'deps' => '', 'media' => 'screen')        // ZoomIt image gallery.
    ),

    'back_end' => array(

    ),

    'shared' => array(

        /* jquery */
        array('handle' => 'jquery', 'src' =>  'bower_components/jquery/dist/jquery.min', 'extension' => 'js', 'deps' => '', 'media' => ''),

        /* jquery.ui */
        array('handle' => 'jquery_ui_js', 'src' =>  'bower_components/jquery.ui/dist/jquery-ui', 'extension' => 'js', 'deps' => array('jquery'), 'media' => ''),
        array('handle' => 'jquery_ui_css', 'src' => 'bower_components/jquery.ui/dist/jquery-ui', 'extension' => 'css', 'deps' => '', 'media' => 'screen'),

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
    )
);