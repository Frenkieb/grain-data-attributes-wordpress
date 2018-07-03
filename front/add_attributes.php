<?php
/**
 * Prints Google Tag Manager code.
 */
class GD_Attributes_Frontend {
	function __construct() {
		add_action( 'wp_footer', array( $this, 'print_attributes' ) );
	}

	// Adds the variable dataTrackPageVariables to the current page source.
	public function print_attributes() {
		$pageVariableOptions 			= unserialize( GRAIN_DATA_ATTRIBUTES_PAGE_VARIABLES_CONFIG );
		$data_track_page 				= array();
		$gtm_id 						= get_option( 'grain_data_gtm_id', '' );
		$gd_attributes_page_variables 	= get_option( 'grain_data_attributes_page_variables', array() );

		// If the current slug is an excluded one print nothing.
		if ( $this->is_excluded_slug() ) {
			return;
		}

		// If there is no gtm_id then print nothing.
		if ( empty( $gtm_id ) ) {
			return;
		} else {
			$gtmCode = "
				<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
				new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
				j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
				'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
				})(window,document,'script','dataLayer','". $gtm_id ."');</script>
			";
		   echo $gtmCode;
		}

		$current_user = wp_get_current_user();

		foreach ( $pageVariableOptions as $key => $options ) {
			foreach ( $options as $option ) {
				if ( $gd_attributes_page_variables[$key][$option] ) {
					// Only add this when on a single.
					if ( is_single() ) {
						switch( $option ) {
							case 'postPublishDate':
								$data_track_page[$option] = get_the_date('c');
								break;
							case 'postID':
								$data_track_page[$option] = get_the_ID();
								break;
							case 'postAuthorID':
								$data_track_page[$option] = get_the_author_meta( 'ID' );
								break;
							case 'postCategories':
								$categories = get_the_category();
								$category_data = array();
								$category_data_ids = array();
								$category_data_names = array();
								foreach ( $categories as $category ) {
									$category_data[] = array(
										'id' => $category->term_id,
										'name' => $category->name,
									);
									$category_data_ids[] = $category->term_id;
									$category_data_names[] = $category->name;
								}
								$data_track_page[$option] = $category_data;
								$data_track_page['postCategoriesIDs'] = $category_data_ids;
								$data_track_page['postCategoriesNames'] = $category_data_names;
								break;
							case 'postTags':
								$tags = get_the_tags();
								if ( $tags ) {
									$tag_data = array();
									$tag_data_ids = array();
									$tag_data_names = array();
									foreach ( $tags as $tag ) {
										$tag_data[] = array(
											'id' => $tag->term_id,
											'name' => $tag->name,
										);
										$tag_data_ids[] = $tag->term_id;
										$tag_data_names[] = $tag->name;
									}
									$data_track_page[$option] = $tag_data;
									$data_track_page['postTagsIDs'] = $tag_data_ids;
									$data_track_page['postTagsNames'] = $tag_data_names;
								} else {
									$data_track_page[$option] = '';
								}
								break;
						}
					}

					// Always add these.
					switch( $option ) {
						case 'isLoggedIn':
							$data_track_page[$option] = ( is_user_logged_in() ) ? '1' : '0' ;
							break;
						case 'userRole':
							$data_track_page[$option] = ( is_user_logged_in() ) ?  $current_user->roles[0] : '';
							break;
						case 'email':
							$data_track_page[$option] = ( is_user_logged_in() ) ? $current_user->user_email : '';
							break;
						case 'wordPressUserID':
							$data_track_page[$option] = ( is_user_logged_in() ) ? $current_user->ID : '';
						break;
					}
				}
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

	/**
	 * Checks if the current REQUEST_URI is in the option 'grain_data_excluded_paths'.
	 * @return boolean True if REQUEST_URI is in 'grain_data_excluded_paths', false otherwise.
	 */
	function is_excluded_slug() {
		$excluded_paths	= get_option( 'grain_data_excluded_paths', '' );
		if ( empty( trim( $excluded_paths ) ) ) {
			return false;
		}

		$excluded_paths = explode( ',', $excluded_paths );

		// Remove spaces.
		$excluded_paths = array_map( 'trim', $excluded_paths );

		// Make sure all values are lowercase.
		$excluded_paths = array_map( 'strtolower', $excluded_paths );

		// Get the REQUEST URI and only that. We don't want a trailing slash.
		$url_parts = parse_url( $_SERVER['REQUEST_URI'] );
		$slug = strtolower( rtrim( $url_parts['path'], '/' ) );

		// Check if REQUEST URI is in the excludes paths.
		if ( in_array( $slug, $excluded_paths ) ) {
			return true;
		}

		return false;
	}
}

$gd_attributes_frontend = new GD_Attributes_Frontend();
?>
