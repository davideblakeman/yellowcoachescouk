<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
*   PHP v5.3+ required
*/

require_once __DIR__ . '/yellowcoachescouk_ajax.php';
require_once __DIR__ . '/Yellowcoachescouk_Wpdb.php';
require_once __DIR__ . '/Yellowcoachescouk_Quotes.php';

function understrap_remove_scripts() {
    wp_dequeue_style( 'understrap-styles' );
    wp_deregister_style( 'understrap-styles' );

    wp_dequeue_script( 'understrap-scripts' );
    wp_deregister_script( 'understrap-scripts' );

    // Removes the parent themes stylesheet and scripts from inc/enqueue.php
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {

	// Get the theme data
	$the_theme = wp_get_theme();
    wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . '/css/child-theme.min.css', array(), $the_theme->get( 'Version' ) );
    wp_enqueue_script( 'jquery');
	wp_enqueue_script( 'popper-scripts', get_template_directory_uri() . '/js/popper.min.js', array(), false);
    wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . '/js/child-theme.min.js', array(), $the_theme->get( 'Version' ), true );

    // Setup token security for ajax
    wp_localize_script(
        'child-understrap-scripts',
        'yellowcoachescouk_ajax_object',
        [
          'ajax_url'  => admin_url( 'admin-ajax.php' ),
          'security'  => wp_create_nonce( 'yellowcoachescouk-security-token' ),
        ]
    );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}

function add_child_theme_textdomain()
{
    load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
    
    // Yellowcoaches plugin
	// if (!class_exists('Social')) {
    // if ( is_plugin_active( 'yellowcoachescouk-plugin/yellowcoachescouk-plugin.php' ) )
    // {
		// load plugin if not already loaded
		include_once( __DIR__ . '/yellowcoachescouk-plugin/yellowcoachescouk-plugin.php' );
	// }
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );

// Custom Code Starts Here //

//Create shortcode for inserting the quote system's HTML into WordPress pages/posts
function quote_shortcode( $attr )
{
    $YCQ = new YellowcoachescoukQuotes;
    return $YCQ->getQuoteHTML();
}
add_shortcode( 'yellowcoachescouk_quotes', 'quote_shortcode' );

//Create database table on activation of this theme
function yellowcoachescouk_theme_setup()
{
    yellowcoachescouk_db_setup();
}
add_action( "after_switch_theme", "yellowcoachescouk_theme_setup" );

function yellowcoachescouk_db_setup()
{
	global $wpdb;
    $wpdb->yellowcoachescouk_quotes = $wpdb->prefix .'yellowcoachescouk_quotes';
    $wpdb->yellowcoachescouk_locations = $wpdb->prefix .'yellowcoachescouk_locations';
    
    $dbSchemaSetup = get_option( 'yellowcoaches-dbschema-setup' );

    if ( !$dbSchemaSetup )
    {
        $YCWPDB = new YellowcoachescoukWPDB;
        $YCWPDB->setupDBSchema();
    }
}

// function yellowcoachescouk_add_admin_script( $hook )
// {
// 	// if ( 'themes.php' != $hook )
//     //     return;

// 	wp_enqueue_script(
// 		'yellowcoachescouk-admin-script',
// 		plugins_url( '/js/admin.js', __FILE__ )
// 	);

// 	// Registers the CSS for the plugin's admin page
// 	wp_register_style( 
// 		'yellowcoachescouk-custom-style', 
// 		plugins_url( '/css/admin.css', __FILE__ ), 
// 		array(), 
// 		'20181120', 
// 		'all' 
// 	);
// 	 // Enqueue the style
// 	wp_enqueue_style( 'yellowcoachescouk-custom-style' );
// }
// add_action( 'admin_enqueue_scripts', 'yellowcoachescouk_add_admin_script' );

//Register callback functions for ajax invocations
add_action( 'wp_ajax_yellowcoachescouk_quote_get', 'yellowcoachescouk_ajax_quote_get' );
//For non logged in users
add_action( 'wp_ajax_nopriv_yellowcoachescouk_quote_get', 'yellowcoachescouk_ajax_quote_get' );

add_action( 'wp_ajax_yellowcoachescouk_quote_purchase', 'yellowcoachescouk_ajax_quote_purchase' );
add_action( 'wp_ajax_nopriv_yellowcoachescouk_quote_purchase', 'yellowcoachescouk_ajax_quote_purchase' );