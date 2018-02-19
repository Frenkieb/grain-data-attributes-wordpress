<?php

	if ($_POST['save_page_variables']) {
				
		$pageVariableOptions = GRAIN_DATA_ATTRIBUTES_PAGE_VARIABLES_CONFIG;
		
		$pageVariableConfiguration = array();
		foreach ($pageVariableOptions as &$value) {
			$pageVariableConfiguration[$value] = isset($_POST[$value]) ? "on" : "off";
		}
		update_option('grain_data_track_page_enabled', 'True');
		update_option('grain_data_attributes_page_variables', $pageVariableConfiguration);
	}
	
	if ($_POST['disable_page_variables']) {
		update_option('grain_data_track_page_enabled', 'False');
	}
	
	if ($_POST['save_gtm_id']) {
		update_option('grain_data_gtm_id', $_POST['gtmID']);
	}
?>