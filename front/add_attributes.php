<?php 
//The value of track attribute show on page
function add_attributes() {
	
	$pageVariableOptions = GRAIN_DATA_ATTRIBUTES_PAGE_VARIABLES_CONFIG;
	$data_track_page = array();
	$data_track_page_enabled = get_option('grain_data_track_page_enabled', "False");	
	$gtm_id = get_option('grain_data_gtm_id', "");	
	$grain_data_attributes_page_variables = get_option('grain_data_attributes_page_variables', array());

	if($gtm_id !== "" ) {
		$gtmCode = "
			<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','". $gtm_id ."');</script>	
		";
	   echo $gtmCode; 
	}
   
	if($data_track_page_enabled === "False") {
		return false;
	}
	
	function isLoggedIn() {
		$isLoggedIn = is_user_logged_in();
		if($isLoggedIn) {
			return "True";
		} else {
			return "False";
		}
	}
	
	$current_user = wp_get_current_user();
	foreach ($pageVariableOptions as &$key) {
		if($grain_data_attributes_page_variables[$key] === "on") {
			switch($key) {
				case "pageTitle":
					$value = get_the_title();
					$data_track_page[$key] = isset($value) ? $value : "";
				break;
				case "pagePublishDate":
					$value = get_the_date('c');
					$data_track_page[$key] = isset($value) ? $value : "";
				break;
				case "pageID":
					$value = get_the_ID();
					$data_track_page[$key] = isset($value) ? $value : "";
				break;
				case "pagePostType":
					$value = get_post_type();
					$data_track_page[$key] = isset($value) ? $value : "";
				break;
				case "pageAuthorID":
					$value = get_the_author_meta( 'ID' );
					$data_track_page[$key] = isset($value) ? $value : "";
				break;
				case "pageCategories":
					$value = get_the_category();
					$data_track_page[$key] = isset($value) ? $value : "";
				break;
				case "pageTags":
					$value = get_the_tags();
					$data_track_page[$key] = isset($value) ? $value : "";
				break;
				case "isLoggedIn":
					$value = isLoggedIn();
					$data_track_page[$key] = isset($value) ? $value : "";
				break;
				case "userRole":
					$value = $current_user->roles[0];
					$data_track_page[$key] = isset($value) ? $value : "";
				break;
				case "email":
					$value = $current_user->user_email;
					$data_track_page[$key] = isset($value) ? $value : "";
				break;
				case "wordPressUserID":
					$value = $current_user->ID;
					$data_track_page[$key] = isset($value) ? $value : "";
				break;
			}
			
		}
	}
	
	//Check if the filter is applied
	if(has_filter("customize_data_track_page")) {
		
		//if the filter is used, process it
		$data_track_page = apply_filters("customize_data_track_page", $data_track_page);
	}
	
	if(empty($data_track_page)) {
		$data_track_page = "{}";
	} else {
		$data_track_page = json_encode($data_track_page);
	}
		   
	
	$addDataTrackPage = "
		<script>
			var body = document.querySelector(\"body\");
			var dataTrackPageVariables = ". $data_track_page .";
			body.setAttribute(\"data-track-page\", JSON.stringify(dataTrackPageVariables));
		</script>	
	";
   echo $addDataTrackPage; 
   
}

add_action('wp_footer', 'add_attributes');
?>