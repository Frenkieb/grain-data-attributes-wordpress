<?php
	/**
	 * Saves the options.
	 */
	if ( isset( $_POST['save_page_variables'] ) ) {
		$pageVariableOptions = unserialize( GRAIN_DATA_ATTRIBUTES_PAGE_VARIABLES_CONFIG );

		$pageVariableConfiguration = array();
		foreach( $pageVariableOptions as $key => $options ) {
			foreach( $options as $value ) {
				$pageVariableConfiguration[$key][$value] = isset( $_POST[$value] ) ? true : false;
			}
		}

		update_option( 'grain_data_track_page_enabled', true );
		update_option( 'grain_data_attributes_page_variables', $pageVariableConfiguration );
	}

	if ( isset( $_POST['disable_page_variables'] ) ) {
		update_option( 'grain_data_track_page_enabled', false );
	}

	if ( isset( $_POST['save_gtm_id'] ) ) {
		update_option( 'grain_data_gtm_id', $_POST['gtmID'] );
	}
?>
