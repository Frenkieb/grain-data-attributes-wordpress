<?php
/*
Plugin Name: Grain Data Attributes
Description: This plug-in adds the data-attributes for the Grain Data Tracking Framework using Google Tag Manager.
Version: 1.5.0
*/

// Require files
require_once( 'includes/class-gd-attributes.php' );
require_once( 'includes/class-gd-attributes-frontend.php' );
require_once( 'includes/class-gd-attributes-admin.php' );

$gd_attributes_admin = new GD_Attributes();
?>
