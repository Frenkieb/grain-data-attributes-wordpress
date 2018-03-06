<?php

/*
Plugin Name: Grain Data Attributes
*/

// Set some global variables
define ( 'GRAIN_DATA_ATTRIBUTES_PATH', plugin_dir_path( __FILE__ ) );
define ( 'GRAIN_DATA_ATTRIBUTES_URL', plugins_url( '' ,  __FILE__ ) );
define ( 'GRAIN_DATA_ATTRIBUTES_PAGE_VARIABLES_CONFIG',  serialize(
		array(
			'post' =>
				array(
					'pageTitle',
					'pagePublishDate',
					'pageCategories',
					'pageTags',
					'pageAuthorID',
					'pageID',
					'pagePostType',
			),
			'user' =>
				array(
					'userRole',
					'wordPressUserID',
					'email',
					'isLoggedIn',
				)
		)
));

// Require files
require_once( 'admin/save-plugin-options.php' );
require_once( 'front/add_attributes.php' );

// We need Bootstrap for our plugin, so we require it in this function
function bootstrap_jquery_scripts() {
	// wp_enqueue_style( 'grain_data_attributes_bootstrap_css', GRAIN_DATA_ATTRIBUTES_URL . '/admin/assets/vendors/bootstrap/bootstrap.min.css', false );
	// wp_enqueue_script( 'grain_data_attributes_bootstrap_js', GRAIN_DATA_ATTRIBUTES_URL . '/admin/assets/vendors/bootstrap/bootstrap.min.js', false );
}
add_action( 'admin_enqueue_scripts', 'bootstrap_jquery_scripts' );

// We also have additional scripts to use for our own functionality
/*function grain_data_attributes_scripts() {
}
add_action( 'admin_enqueue_scripts', 'grain_data_attributes_scripts' );*/

function add_admin_menu_page() {
	// The menu page will only be showed when the user has the "manage_options" role. The show_grain_data_attributes_page function is called to show the page
	add_menu_page( 'Grain Data Attributes', 'Grain Data Attributes', 'manage_options', 'grain_data_attributes', 'show_grain_data_attributes_page' );
}

// To add an admin page, we should hook into the admin_menu function. Then, this will call our function add_admin_menu_page
add_action( 'admin_menu', 'add_admin_menu_page' );

// This function requires the plugin-page, which is a single page app to manage all possible functionality.
function show_grain_data_attributes_page() {
	require_once( 'admin/plugin-page.php' );
}
?>
