<?php

add_filter( WP_Modular_CSS::PREFIX . 'css_module_output_skins', 'wp_module_css_tachyons_tweaks_skins', 10, 3 );
function wp_module_css_tachyons_tweaks_skins ( $module_content, $prefix, $module_name ) {

	// rename .inherit to .color-inherit
	$module_content = str_replace( ".${prefix}inherit", ".${prefix}color-inherit", $module_content );

	return $module_content;
}