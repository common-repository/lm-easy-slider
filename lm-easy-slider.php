<?php
/*
Plugin Name: LM Easy Slider
Description: Slider di immagini basato su Bootstrap 4.4
Author: leonardoboss, marsy79
Text Domain: lm-easy-slider
Domain Path: /languages
Version: 1.0
*/
define ("LMESLIDER_BASENAME_DIR", basename( dirname( __FILE__ ) ));
define ("LMESLIDER_BASENAME_FILE", basename(__FILE__));
define ('LMESLIDER_INCLUDE_PATH', plugin_dir_path(__FILE__) . 'includes/');
define ('LMESLIDER_FRONT_END_PATH', plugin_dir_path(__FILE__) . 'front-end/');

$lmes_plugin='';

$nslider = 0;
require_once LMESLIDER_INCLUDE_PATH.'/lm-easy-slider-functions.php';

add_action( 'wp_enqueue_scripts', 'lmEasySlider_bootstrap_slider_js', 10 );

function lmEasySlider_bootstrap_slider_js() {
		
    wp_enqueue_script( 'jquery' ); 
    
	wp_deregister_script( 'bootstrap' );
	wp_register_script( 'bootstrap', plugin_dir_url( __FILE__ ).'dist/js/bootstrap.min.js', '', '', true );
    wp_enqueue_script( 'bootstrap' );

	
	wp_register_script( 'slider', plugin_dir_url( __FILE__ ).'front-end/slider.js', '', '', true );
    wp_enqueue_script( 'slider' );
    
}

add_action( 'wp_enqueue_scripts', 'lmEasySlider_bootstrap_slider_css' );

function  lmEasySlider_bootstrap_slider_css() {
	wp_enqueue_style( 'bootstrap', plugin_dir_url( __FILE__ ).'dist/css/bootstrap.min.css' );
	wp_enqueue_style( 'slider', plugin_dir_url( __FILE__ ).'/front-end/slider.css' );
}