<?php
/*
@package kalkulator
Plugin Name: kalkulator dostav
Plugin URI: https://github.com/Samz851/kalkulator-dostav.git
Description: Shipping Calculator. 
Version: 2.6
Author: Samer Alotaibi
Author URI:
*/



require_once 'class-kalkulator-widget.php';
// Remove WP Version From Styles	
add_filter( 'style_loader_src', 'kalk_remove_ver_css_js', 9999 );
// Remove WP Version From Scripts
add_filter( 'script_loader_src', 'kalk_remove_ver_css_js', 9999 );

// Function to remove version numbers
function kalk_remove_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
// Register assets
function custom_register_scripts() {
    wp_register_script( 'jquery-legacy', 'http://code.jquery.com/jquery-1.9.0.js' );
    wp_register_script( 'plugin-migrate', plugin_dir_url( __FILE__ ) . 'assets/js/jq-migrate.js');
    wp_register_style('kalkulator-stylesheet', plugin_dir_url( __FILE__ ) . 'assets/compressed.css');
    wp_register_script('jquery-3', plugin_dir_url( __FILE__ ) . 'assets/js/jquery-3.3.1.min.js');
    wp_register_script('compressed-scripts', plugin_dir_url( __FILE__ ) . 'assets/js/compressed.js');
    wp_register_script('main-script', plugin_dir_url( __FILE__ ) . 'assets/js/main.js?now="yes"');
}
// Use wp_enqueue_scripts hook
add_action('wp_enqueue_scripts', 'custom_register_scripts');

// Register and load the widget
function kalkulator_load_widget() {
    register_widget( 'kalkulator_widget' );
};
add_action( 'widgets_init', 'kalkulator_load_widget' );

