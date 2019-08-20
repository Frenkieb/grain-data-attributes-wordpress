<?php
class GD_Attributes {
	private $page_variables_config = array(
		'post' =>
			array(
				'postPublishDate',
				'postCategories',
				'postTags',
				'postID',
		),
		'user' =>
			array(
				'email',
				'isLoggedIn',
			)
	);

	public function __construct() {
		add_action( 'init', array( $this, 'init' ), 99 );
	}

	/**
	 * Gets all the possible variables that are used in the data tracking.
	 * @return [array]
	 */
	public function get_page_variables_config() {
		return $this->page_variables_config;
	}

	function init() {
		if ( is_admin() ) {
			$gd_attributes_admin = new GD_Attributes_Admin();
		} else {
			$gd_attributes_admin = new GD_Attributes_Frontend();
		}
	}
}
