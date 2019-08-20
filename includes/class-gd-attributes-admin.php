<?php
class GD_Attributes_Admin extends GD_Attributes {
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu_page' ) );

		$this->save_options();
	}

	function add_admin_menu_page() {
		// Add menu option.
		add_menu_page( 'Grain Data Attributes', 'Grain Data Attributes', 'manage_options', 'grain_data_attributes', array( $this, 'show_grain_data_attributes_page' ) );
	}

	// This function requires the plugin-page, which is a single page app to manage all possible functionality.
	function show_grain_data_attributes_page() {
		// Display menu page.
		require_once( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/settings.php' );
	}

	function save_options() {
		if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {

			if ( $_POST['save_page_variables'] ) {
				$pageVariableConfiguration = array();
				$page_variables_config = $this->get_page_variables_config();

				foreach( $page_variables_config as $key => $options ) {
					foreach( $options as $value ) {
						$pageVariableConfiguration[$key][$value] = isset( $_POST[$value]) ? true : false;
					}
				}

				update_option( 'grain_data_track_page_enabled', true );
				update_option( 'grain_data_attributes_page_variables', $pageVariableConfiguration );
			}

			if ( isset( $_POST['gtmID'] ) )  {
				update_option( 'grain_data_gtm_id', $_POST['gtmID'] );
			}

			if ( isset( $_POST['gd_excluded_paths'] ) ) {
				update_option( 'grain_data_excluded_paths', $_POST['gd_excluded_paths'] );
			}

			if ( isset( $_POST['gd_excluded_head'] ) ) {
				update_option( 'grain_data_excluded_head', 1 );
			} else {
				update_option( 'grain_data_excluded_head', 0 );
			}

			if ( isset( $_POST['gd_excluded_head_content'] ) ) {
				update_option( 'grain_data_excluded_head_content', $_POST['gd_excluded_head_content'] );
			}
		}

	}
}
