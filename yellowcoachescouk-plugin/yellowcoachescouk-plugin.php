<?php

if ( $_SERVER[ 'REQUEST_METHOD' ] == 'GET' && realpath( __FILE__ ) == realpath( $_SERVER[ 'SCRIPT_FILENAME' ] ) )
{
	header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );
	die( header( 'location: /error.php' ) );
}

/*
Plugin Name: Yellow Coaches Plugin
Plugin URI: yellowcoaches.co.uk
Description: Yellow Coaches WordPress Theme Plugin that modifies WordPress and WooCommerce.
Version: 1.0
Author: David Blakeman & Resh Nawoor
Author URI: tbc
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

/*
PHP 5.4+
jQuery v1.7+
*/

$data = get_file_data( __FILE__, array( 'Version' => 'Version', 'Domain Path' => 'Domain Path' ) );
define( 'YELLOWCOACHESCOUK_VER', $data[ 'Version' ] );
define( 'YELLOWCOACHESCOUK_DOMAIN_PATH', '/' . trim( $data[ 'Domain Path' ], '/' ) . '/' ); // /languages/

define( 'YELLOWCOACHESCOUK_MAIN_FILE',  __FILE__ );
define( 'YELLOWCOACHESCOUK_URL', plugin_dir_url( YELLOWCOACHESCOUK_MAIN_FILE ) );
define( 'YELLOWCOACHESCOUK_PATH', plugin_dir_path( YELLOWCOACHESCOUK_MAIN_FILE ) );

register_activation_hook( YELLOWCOACHESCOUK_MAIN_FILE, 'yellowcoachescouk_activate' );

if ( ! function_exists( 'yellowcoachescouk_initialise_extension' ) ):
	/**
	 * Creates the extension's main class instance.
	 *
	 * @since 1.0.0
	 */
	function yellowcoachescouk_initialise_extension()
	{
		//Plugin starts here...
	}
endif;

function yellowcoachescouk_options_page()
{
	$page_title = 'page title';
	$menu_title = 'Yellow Coaches Settings';
	// $capability = 'edit_theme_options';
	$capability = 'manage_options';
	$menu_slug = 'yellowcoachescouk-settings';
	$function = 'yellowcoachescouk_options_page_html';
    
    add_menu_page(
		$page_title,
		$menu_title, 
		$capability,
		$menu_slug,
        $function,
        '',
        1
    );
}
add_action( 'admin_menu', 'yellowcoachescouk_options_page' );

function yellowcoachescouk_options_page_html()
{
    if ( !current_user_can( 'manage_options' ) )
    {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    
    $YCQ = new YellowcoachescoukQuotes;

    ?>

    <div id="Yellowcoachescouk-admin" class="wrap">

        <h1 class="yellowcoachescouk-title">Yellow Coaches Settings Page</h1>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Locations</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Settings</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">

            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                <div class="row">
                    <div class="col">
                        <h4>Add New Location</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <input id="Yellowcoachescouk-admin-location-input-location" type="text" placeholder="New Location Name Here..." />
                        <button id="Yellowcoachescouk-admin-location-btn" class="yellowcoachescouk-dropbtn btn btn-primary" type="button">Add</button>
                    </div>    
                </div>

                <div class="row">
                    <div class="col">
                        <h4>Modify Existing Location</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <?php echo $YCQ->getAdminLocationSelectHTML(); ?>
                    </div>
                </div>

                <div id="Yellowcoachescouk-admin-location-ajax-edit"></div>

                <div class="row">
                    <div class="col">
                        <button id="Yellowcoachescouk-admin-location-save-btn" class="btn btn-primary" type="button">Save</button>
                    </div>
                </div>

            </div>

            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                <h4>Add New WooCommerce Product</h4>

                <?php echo $YCQ->getAdminProductsHTML(); ?>

            </div>

            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">

                <h4>Settings</h4>

            </div>

        </div>

    </div>

    <?php
}

function yellowcoachescouk_enqueue_admin_script( $hook )
{
	if ( 'toplevel_page_yellowcoachescouk-settings' != $hook )
        return;

	wp_enqueue_script(
		'yellowcoachescouk-plugin-admin-script',
        get_stylesheet_directory_uri() . '/js/admin.js'
	);

    // Setup token security for ajax
    wp_localize_script(
        'yellowcoachescouk-plugin-admin-script',
        'yellowcoachescouk_ajax_object',
        [
          'ajax_url'  => admin_url( 'admin-ajax.php' ),
          'security'  => wp_create_nonce( 'yellowcoachescouk-security-token' ),
        ]
    );

    wp_enqueue_script(
		'yellowcoachescouk-plugin-admin-script-bootstrap',
        get_stylesheet_directory_uri() . '/js/child-theme.min.js'
	);

	// Registers the CSS for the plugin's admin page
	wp_register_style( 
		'yellowcoachescouk-plugin-custom-style',
        get_stylesheet_directory_uri() . '/css/child-theme.min.css',
		array(), 
		'20181120', 
		'all' 
	);
	 // Enqueue the style
    wp_enqueue_style( 'yellowcoachescouk-plugin-custom-style' );
    
	// Registers the CSS for the plugin's admin page
	wp_register_style( 
		'yellowcoachescouk-plugin-custom-style-admin',
        get_stylesheet_directory_uri() . '/css/admin.css',
		array(), 
		'20181120', 
		'all' 
	);
	 // Enqueue the style
	wp_enqueue_style( 'yellowcoachescouk-plugin-custom-style-admin' );
}
add_action( 'admin_enqueue_scripts', 'yellowcoachescouk_enqueue_admin_script' );

add_action( 'wp_ajax_yellowcoachescouk_admin_add_location', 'yellowcoachescouk_admin_add_location' );
add_action( 'wp_ajax_yellowcoachescouk_admin_edit_location', 'yellowcoachescouk_admin_edit_location' );
add_action( 'wp_ajax_yellowcoachescouk_admin_get_location_posts_content_html', 'yellowcoachescouk_admin_get_location_posts_content_html' );
add_action( 'wp_ajax_yellowcoachescouk_admin_get_wcproduct_content', 'yellowcoachescouk_admin_get_wcproduct_content' );