<?php

if ( ! defined( 'WPINC' ) ) die();

add_filter(
	WP_Modular_CSS::PREFIX . 'css_module_output_skins',
	function ( $module_content, $prefix ) {
		// rename .inherit to .color-inherit
		$module_content = str_replace( ".${prefix}inherit", ".${prefix}color-inherit", $module_content );
		return $module_content;
	},
	10,
	2
);

add_filter(
	WP_Modular_CSS::PREFIX . 'css_module_output_font-family',
	function ( $module_content, $prefix ) {
		// add selector code to .code
		$module_content = str_replace( ".${prefix}code", "code, .${prefix}code", $module_content );
		return $module_content;
	},
	10,
	2
);

add_filter(
	WP_Modular_CSS::PREFIX . 'css_module_output_tables',
	function ( $module_content, $prefix ) {
		// change some striped-- to stripe-
		// waiting a awnser in https://github.com/tachyons-css/tachyons/issues/495
		$module_content = str_replace( ".${prefix}striped--dark:nth", ".${prefix}stripe-dark:nth", $module_content );
		$module_content = str_replace( ".${prefix}striped--light:nth", ".${prefix}stripe-light:nth", $module_content );
		return $module_content;
	},
	10,
	2
);