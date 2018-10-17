<?php
/*
Plugin Name: Grain Data Attributes
Description: This plug-in adds the data-attributes for the Grain Data Tracking Framework using Google Tag Manager.
Version: 1.2.1
*/

// Set some global variables
define ( 'GRAIN_DATA_ATTRIBUTES_PATH', plugin_dir_path( __FILE__ ) );
define ( 'GRAIN_DATA_ATTRIBUTES_URL', plugins_url( '' ,  __FILE__ ) );
define ( 'GRAIN_DATA_ATTRIBUTES_PAGE_VARIABLES_CONFIG',  serialize(
		array(
			'post' =>
				array(
					'postPublishDate',
					'postCategories',
					'postTags',
					'postAuthorID',
					'postID',
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
