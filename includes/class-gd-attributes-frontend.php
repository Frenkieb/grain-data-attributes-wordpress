<?php
/**
 * Prints Google Tag Manager code.
 */
class GD_Attributes_Frontend extends GD_Attributes {
	function __construct() {
		add_action( 'wp_head', array( $this, 'wp_head' ) );
		add_action( 'wp_footer', array( $this, 'print_attributes' ) );
	}

	/**
	 * wp_head filter
	 */
	public function wp_head() {
		// Print something in the header or not on the excluded paths.
		if ( $this->is_excluded_slug() ) {
			$gd_excluded_head = get_option( 'grain_data_excluded_head', 0 );

			if ( $gd_excluded_head ) {
				$gd_excluded_head_content = get_option( 'grain_data_excluded_head_content', '' );
				if ( $gd_excluded_head_content ) {
					 echo $gd_excluded_head_content . "\r\n";
				}
			}
		}
	}

	/**
	 * Adds the variable dataTrackPageVariables to the current page source.
	 */
	public function print_attributes() {
		$data_track_page 				= array();
		$data_track_content_view		= array();
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

		$page_variables_config = $this->get_page_variables_config();

		foreach ( $page_variables_config as $key => $options ) {
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
							case 'postCategories':
								$categories = get_the_category();
								$category_data = array();

								foreach ( $categories as $category ) {
									$category_data[] = array(
										'type' => 'default',
										'path' => $category->name,
									);
									$category_data_names[] = $category->name;
								}
								$data_track_page['categories'] = $category_data;
								break;
							case 'postTags':
								$tags = get_the_tags();
								if ( $tags ) {
									$tag_data = array();

									foreach ( $tags as $tag ) {
										$tag_data[] = $tag->name;
									}

									$data_track_page['tags'] = $tag_data;
								} else {
									$data_track_page['tags'] = array();
								}
								break;
						}
					}

					// Always add these.
					switch( $option ) {
						case 'isLoggedIn':
							$data_track_page[$option] = ( is_user_logged_in() ) ? '1' : '0' ;
							break;
						case 'email':
							$data_track_page[$option] = ( is_user_logged_in() ) ? $current_user->user_email : '';
							break;
						break;
					}
				}
			}
		}

		$data_track_page = apply_filters( 'customize_data_track_page', $data_track_page );

		if ( empty( $data_track_page ) ) {
			$data_track_page = "{}";
		} else {
			$data_track_page = json_encode( $data_track_page, JSON_UNESCAPED_SLASHES );
		}

		// Data Track Content View
		if ( is_single() ) {
			$data_track_content_view['contentInteraction'] =  'itemDetailView';

			$cv_contents = array();
			// Type
			$cv_contents['type'] = 'post';

			// Categories
			$categories = get_the_category();
			$category_data = array();

			foreach ( $categories as $category ) {
				$category_data[] = array(
					'type' => 'default',
					'path' => $category->name,
				);
				$category_data_names[] = $category->name;
			}
			$cv_contents['categories'] = $category_data;

			// Tags
			$tags = get_the_tags();
			$tag_data = array();
			if ( $tags ) {
				foreach ( $tags as $tag ) {
					$tag_data[] = $tag->name;
				}
			} else {
				$tag_data = array();
			}

			$cv_contents['tags'] = $tag_data;

			// Title
			$cv_contents['name'] = get_the_title();

			// Post ID
			$cv_contents['id'] = get_the_ID();

			// Publish data
			$cv_contents['publishDate'] = get_the_date('c');

			$data_track_content_view['contents'] = array( $cv_contents );
		}

		$data_track_content_view = apply_filters( 'customize_data_track_content_view', $data_track_content_view );

		if ( empty( $data_track_content_view ) ) {
			$data_track_content_view = "{}";
		} else {
			$data_track_content_view = json_encode( $data_track_content_view, JSON_UNESCAPED_SLASHES );
		}

		$addDataTrackPage = "
			<script>
				var body = document.querySelector(\"body\");

				var dataTrackPageVariables = ". $data_track_page .";
				body.setAttribute(\"data-track-page\", JSON.stringify(dataTrackPageVariables));";

		if ( is_single() ) {
			$addDataTrackPage .= "
				var dataTrackContentView = " . $data_track_content_view . ";
				body.setAttribute(\"data-track-contentview\", JSON.stringify(dataTrackContentView));";
		}

		$addDataTrackPage .= "
			</script>" . "\r\n";

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
?>
