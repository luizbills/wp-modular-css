<?php

add_filter( WP_Modular_CSS::PREFIX . 'css_module_output_skins', function ( $module_content, $prefix, $module_name ) {

	// rename .inherit to .color-inherit
	$module_content = str_replace( ".${prefix}inherit", ".${prefix}color-inherit", $module_content );
	return $module_content;

}, 10, 3 );

add_filter( WP_Modular_CSS::PREFIX . 'css_module_output_font-family', function ( $module_content, $prefix, $module_name ) {

	// rename .inherit to .color-inherit
	$module_content = str_replace( ".${prefix}code", "code, .${prefix}code", $module_content );
	return $module_content;

}, 10, 3 );