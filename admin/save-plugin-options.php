<?php
/**
 * Saves the options.
 */

if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {
	if ( $_POST['save_page_variables'] ) {
		$pageVariableOptions = unserialize( GRAIN_DATA_ATTRIBUTES_PAGE_VARIABLES_CONFIG );

		$pageVariableConfiguration = array();
		foreach( $pageVariableOptions as $key => $options ) {
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
?>
