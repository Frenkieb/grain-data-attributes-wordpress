<?php
// Adds the variable dataTrackPageVariables to the current page source.
function gda_add_attributes() {
	$pageVariableOptions = unserialize( GRAIN_DATA_ATTRIBUTES_PAGE_VARIABLES_CONFIG );
	$data_track_page = array();
	$data_track_page_enabled = get_option( 'grain_data_track_page_enabled', 'False' );
	$gtm_id = get_option( 'grain_data_gtm_id', "" );
	$grain_data_attributes_page_variables = get_option( 'grain_data_attributes_page_variables', array() );

	if ( $gtm_id !== '' ) {
		$gtmCode = "
			<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','". $gtm_id ."');</script>
		";
	   echo $gtmCode;
	}

	if ( $data_track_page_enabled === 'False' ) {
		return false;
	}

	$current_user = wp_get_current_user();

	foreach ( $pageVariableOptions as &$key ) {
		if ( $grain_data_attributes_page_variables[$key] === "on" ) {
			switch( $key ) {
				case 'pageTitle':
					$value = get_the_title();
					break;
				case 'pagePublishDate':
					$value = get_the_date('c');
					break;
				case 'pageID':
					$value = get_the_ID();
					break;
				case 'pagePostType':
					$value = get_post_type();
					break;
				case 'pageAuthorID':
					$value = get_the_author_meta( 'ID' );
					break;
				case 'pageCategories':
					$categories = get_the_category();
					$category_data = array();
					foreach ( $categories as $category ) {
						$category_data[] = array(
							'id' => $category->term_id,
							'name' => $category->name,
						);
					}
					$value = $category_data;
					break;
				case 'pageTags':
					$tags = get_the_tags();
					$tag_data = array();
					foreach ( $tags as $tag ) {
						$tag_data[] = array(
							'id' => $tag->term_id,
							'name' => $tag->name,
						);
					}
					$value = $tag_data;
					break;
				case 'isLoggedIn':
					$value = ( is_user_logged_in() ) ? 'True' : 'False' ;
					break;
				case 'userRole':
					$value = ( is_user_logged_in() ) ?  $current_user->roles[0] : '';
					break;
				case 'email':
					$value = ( is_user_logged_in() ) ? $current_user->user_email : '';
					break;
				case 'wordPressUserID':
					$value = ( is_user_logged_in() ) ? $current_user->ID : '';
				break;
			}
			$data_track_page[$key] = isset( $value ) ? $value : "";
		}
	}

	$data_track_page = apply_filters( 'customize_data_track_page', $data_track_page );

	if ( empty( $data_track_page ) ) {
		$data_track_page = "{}";
	} else {
		$data_track_page = json_encode( $data_track_page );
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

add_action( 'wp_footer', 'gda_add_attributes' );
?>
